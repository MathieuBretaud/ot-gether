<template>
  <v-responsive class="mx-auto" max-width="344">
    <div class="ma-4" style="color: #ef5350; font-weight: bold">
      Créer un événement
    </div>

    <form @submit.prevent="mySubmit">
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
          @blur="handleChangeCategoryTitle"
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
          @blur="handleChangeDescription"
          class="mb-5"
          label="Description"
          :error-messages="descriptionError"
        ></v-textarea>

        <v-text-field
          v-if="isIRLValue"
          @blur="handleChangeCity"
          v-model="cityValue"
          label="Ville"
          placeholder="Paris"
          type="input"
          :error-messages="cityError"
        ></v-text-field>

        <v-text-field
          v-if="isIRLValue"
          @blur="handleChangeAddress"
          v-model="addressValue"
          label="Adresse"
          placeholder="Rue du Paradis"
          type="input"
          :error-messages="addressError"
        ></v-text-field>

        <v-text-field
          v-if="isIRLValue"
          @blur="handleChangeRegion"
          v-model="regionValue"
          label="Région"
          placeholder="Ile-de-France"
          type="input"
          :error-messages="regionError"
        ></v-text-field>

        <v-text-field
          v-model.number="minParticipantValue"
          label="Participant minimum"
          type="number"
          :error-messages="minParticipantError"
        ></v-text-field>

        <v-text-field
          v-model.number="maxParticipantValue"
          @blur="handleChangeMaxParticipant"
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
      <div class="ml-4">
        <v-switch
          v-model="participate"
          color="success"
          v-if="participate"
          :label="`Je participe`"
        ></v-switch>
        <v-switch
          v-model="participate"
          v-else
          :label="`Je ne participe pas`"
        ></v-switch>
      </div>

      <div class="d-flex justify-center">
        <v-btn
          v-if="!isSubmitting"
          type="submit"
          :disabled="isSubmitting"
          class="ma-4"
          >Créer un événement</v-btn
        >
        <v-btn v-else>
          En cours
          <v-progress-circular indeterminate></v-progress-circular>
        </v-btn>
      </div>
    </form>
  </v-responsive>
</template>

<script lang="ts" setup>
import { useForm, useField } from "vee-validate";
import { z } from "zod";
import { toTypedSchema } from "@vee-validate/zod";
import { ref } from "vue";
import { clientAxios } from "@/services/index";
import { onMounted } from "vue";
import { postEvents, eventProps } from "@/services/events";
import router from "@/router";

const participate = ref(true);

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
    const res = await postEvents(values);
    if (res.status === 201) {
      if (participate.value === true) {
        await clientAxios.post(`/api/v1/events/${res.data.id}/participate`);
      }
      router.push({ name: "EventDetails", params: { id: res.data.id } });
    }
  } catch (error) {
    console.error("Erreur lors de la création de l'événement :", error);
  }
});

const getCategories = async () => {
  const response = await clientAxios.get("/api/v1/event-categories");
  categories.value = response.data;
};

onMounted(() => {
  getCategories();
  document.title = "Création d'un événement";
});

const { value: categoryValue, errorMessage: categoryError } =
  useField("event_category_id");
const {
  value: titleValue,
  errorMessage: titleError,
  handleChange: handleChangeCategoryTitle,
} = useField("title", undefined, {
  validateOnValueUpdate: false,
});
const {
  value: descriptionValue,
  errorMessage: descriptionError,
  handleChange: handleChangeDescription,
} = useField("description", undefined, {
  validateOnValueUpdate: false,
});
const { value: minParticipantValue, errorMessage: minParticipantError } =
  useField("participant_min");
const {
  value: maxParticipantValue,
  errorMessage: maxParticipantError,
  handleChange: handleChangeMaxParticipant,
} = useField("participant_max", undefined, {
  validateOnValueUpdate: false,
});
const { value: startDateValue, errorMessage: startDateError } =
  useField("start_date");
const { value: endDateValue, errorMessage: endDateError } =
  useField("end_date");
const { value: isIRLValue, errorMessage: isIRLError } = useField(
  "is_IRL",
  undefined,
  {
    initialValue: false,
  }
);
const {
  value: cityValue,
  errorMessage: cityError,
  handleChange: handleChangeCity,
} = useField("city", undefined, {
  validateOnValueUpdate: false,
});
const {
  value: regionValue,
  errorMessage: regionError,
  handleChange: handleChangeRegion,
} = useField("region", undefined, {
  validateOnValueUpdate: false,
});
const {
  value: addressValue,
  errorMessage: addressError,
  handleChange: handleChangeAddress,
} = useField("address", undefined, {
  validateOnValueUpdate: false,
});
</script>
