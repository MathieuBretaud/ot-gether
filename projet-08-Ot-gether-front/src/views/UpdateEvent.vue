<template>
  <v-responsive class="mx-auto" max-width="344">
    <div class="ma-4" style="color: #ef5350; font-weight: bold">
      Modifier mon événement
    </div>

    <form v-if="!isCharge" @submit.prevent="mySubmit">
      <div
        class="pa-5"
        style="display: flex; flex-direction: column; border-radius: 10px"
      >
        <v-checkbox
          v-model="isIRLValue"
          label="Evénement présentiel"
          :error-messages="isIRLError"
        ></v-checkbox>
        <v-text-field
          v-model="titleValue"
          class="mb-5"
          hide-details="auto"
          label="Titre"
          placeholder="Titre de l'événement"
          type="input"
          :error-messages="titleError"
        ></v-text-field>

        <v-select
          v-model="categoryValue"
          label="Catégorie"
          :items="categories"
          item-title="name"
          item-value="id"
          :error-messages="categoryError"
        ></v-select>

        <v-textarea
          v-model="descriptionValue"
          class="mb-5"
          label="Description"
          :error-messages="descriptionError"
        ></v-textarea>

        <v-text-field
          v-if="isIRLValue"
          v-model="cityValue"
          :class="errorCity ? 'border-b-2 border-red-500' : 'pb-5'"
          hide-details="auto"
          label="Ville"
          placeholder="Paris"
          type="input"
          :error-messages="cityError"
        ></v-text-field>
        <div v-if="errorCity" class="text-sm text-red-500 pl-4 pb-4">
          {{ errorCity }}
        </div>
        <v-text-field
          v-if="isIRLValue"
          v-model="addressValue"
          :class="errorAddress ? 'border-b-2 border-red-500' : 'pb-5'"
          hide-details="auto"
          label="Adresse"
          placeholder="Rue du paradis"
          type="input"
          :error-messages="addressError"
        ></v-text-field>
        <div v-if="errorAddress" class="text-sm text-red-500 pl-4 pb-4">
          {{ errorAddress }}
        </div>
        <v-text-field
          v-if="isIRLValue"
          v-model="regionValue"
          :class="errorRegion ? 'border-b-2 border-red-500' : 'pb-5'"
          hide-details="auto"
          label="Région"
          placeholder="Loiret"
          :error-messages="regionError"
        ></v-text-field>
        <div v-if="errorRegion" class="text-sm text-red-500 pl-4 pb-4">
          {{ errorRegion }}
        </div>
        <v-text-field
          v-model.number="minParticipantValue"
          label="Participant minimum"
          type="number"
          :error-messages="minParticipantError"
        ></v-text-field>

        <v-text-field
          v-model.number="maxParticipantValue"
          label="Participant maximum"
          type="number"
          :error-messages="maxParticipantError"
        ></v-text-field>

        <v-text-field
          v-model="startDateValue"
          label="Début de l'événement"
          type="datetime-local"
          :error-messages="startDateError"
        ></v-text-field>

        <v-text-field
          v-model="endDateValue"
          label="Fin de l'inscription"
          type="datetime-local"
          :error-messages="endDateError"
        ></v-text-field>
      </div>
      <div class="d-flex justify-center">
        <v-btn
          v-if="!isSubmitting"
          type="submit"
          :disabled="isSubmitting"
          class="ma-4"
          >Modifier mon événement</v-btn
        >
        <v-btn v-else>
          En cours
          <v-progress-circular indeterminate></v-progress-circular>
        </v-btn>
      </div>
    </form>
    <v-container v-else>
      <v-row justify="center">
        <v-progress-circular
          indeterminate
          color="primary"
        ></v-progress-circular>
      </v-row>
    </v-container>
  </v-responsive>
</template>

<script lang="ts" setup>
import { useForm, useField } from "vee-validate";
import { z } from "zod";
import { toTypedSchema } from "@vee-validate/zod";
import { ref } from "vue";
import { clientAxios } from "@/services/index";
import { onMounted } from "vue";
import router from "@/router";
import { useRoute } from "vue-router";
import { updateEvent, eventProps } from "@/services/events";

const route = useRoute();
const id = route.params.id;
const errorAddress = ref<null | string>(null);
const errorCity = ref<null | string>(null);
const errorRegion = ref<null | string>(null);
const event: any = ref(null);
const isCharge = ref(true);

const validationSchema = z.object({
  title: z.string({ required_error: "Titre requis" }).min(4, {
    message: "Titre trop court",
  }),
  event_category_id: z
    .number({ required_error: "Catégorie requise" })
    .min(1, "Catégorie requise"),
  description: z
    .string({ required_error: "Description requise" })
    .min(50, "Description trop courte"),
});

const addressSchema = z
  .object({
    is_IRL: z.boolean().optional(),
    address: z.string().min(4, "Adresse trop courte").optional(),
    city: z.string().min(4, "Ville trop courte").optional(),
    region: z.string().min(4, "Région trop courte").optional(),
  })
  .superRefine((values, context) => {
    if (values.is_IRL === true && values.address === undefined) {
      context.addIssue({
        code: "custom",
        path: ["address"],
        message: "Adresse requise",
      });
    }
    if (values.is_IRL === true && values.city === undefined) {
      context.addIssue({
        code: "custom",
        path: ["city"],
        message: "Ville requise",
      });
    }
    if (values.is_IRL === true && values.region === undefined) {
      context.addIssue({
        code: "custom",
        path: ["region"],
        message: "Région requise",
      });
    }
  });

const participantsSchema = z
  .object({
    participant_min: z
      .number({ required_error: "Nombre minimum de participants requis" })
      .nonnegative({ message: "Le nombre doit être supérieur ou égal à 0" }),
    participant_max: z
      .number({ required_error: "Nombre maximum de participants requis" })
      .nonnegative({ message: "Le nombre doit être supérieur ou égal à 0" }),
  })
  .refine((data: any) => data.participant_min <= data.participant_max, {
    message:
      "Le maximum doit être supérieur ou égal au minimum de participants",
    path: ["participant_max"],
  });

const dateSchema = z
  .object({
    start_date: z.coerce.date({ required_error: "Date de début requise" }),
    end_date: z.coerce.date({ required_error: "Date de fin requise" }),
  })
  .refine((data: any) => data.start_date >= data.end_date, {
    message:
      "La date de fin d'inscription doit être inférieur ou égale à la date de début de l'événement",
    path: ["end_date"],
  });

const allSchema = validationSchema
  .and(participantsSchema)
  .and(dateSchema)
  .and(addressSchema);

const { handleSubmit, isSubmitting } = useForm<eventProps>({
  validationSchema: toTypedSchema(allSchema),
});
const categories: any = ref([]);

const mySubmit = handleSubmit(async (values: eventProps) => {
  try {
    if (values.is_IRL === false) {
      values.address = "";
      values.city = "";
      values.region = "";
    }
    const res = await updateEvent(values, event.value.id);

    if (res.status === 200) {
      router.push({ name: "myEvents" });
    }
  } catch (error) {
    console.error("Erreur lors de la modification de l'événement :", error);
  }
});

const getCategories = async () => {
  const response = await clientAxios.get("/api/v1/event-categories");
  categories.value = response.data;
};

const getEvent = async (id: any) => {
  try {
    const response = await clientAxios.get(`/api/v1/events/${id}`);
    if (response.data) {
      return response.data;
    }
  } catch (error) {
    console.error("Erreur lors de la récupération de l'événement :", error);
  }
};

onMounted(async () => {
  document.title = "Modifier un événement";
  await getCategories();
  event.value = await getEvent(id);
  if (event.value) {
    isIRLValue.value = event.value.is_IRL;
    titleValue.value = event.value.title;
    categoryValue.value = event.value.event_category_id;
    if (event.value.is_IRL) {
      addressValue.value = event.value.address;
      cityValue.value = event.value.city;
      regionValue.value = event.value.region;
    }
    descriptionValue.value = event.value.description;
    minParticipantValue.value = event.value.participant_min;
    maxParticipantValue.value = event.value.participant_max;
    const startDate = new Date(event.value.start_date);
    startDateValue.value = startDate.toISOString().slice(0, 16);
    const endDate = new Date(event.value.end_date);
    endDateValue.value = endDate.toISOString().slice(0, 16);
  }
  isCharge.value = false;
});

const { value: categoryValue, errorMessage: categoryError } =
  useField("event_category_id");
const { value: titleValue, errorMessage: titleError } = useField("title");
const { value: descriptionValue, errorMessage: descriptionError } =
  useField("description");
const { value: addressValue, errorMessage: addressError } = useField("address");
const { value: cityValue, errorMessage: cityError } = useField("city");
const { value: regionValue, errorMessage: regionError } = useField("region");
const { value: minParticipantValue, errorMessage: minParticipantError } =
  useField("participant_min");
const { value: maxParticipantValue, errorMessage: maxParticipantError } =
  useField("participant_max");
const { value: startDateValue, errorMessage: startDateError } =
  useField("start_date");
const { value: endDateValue, errorMessage: endDateError } =
  useField("end_date");
const { value: isIRLValue, errorMessage: isIRLError } = useField("is_IRL");
</script>
