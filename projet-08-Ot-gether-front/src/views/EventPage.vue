<template>
  <v-container v-if="!isCharge" fluid>
    <v-row v-if="event" align="center" justify="center">
      <v-img
        :src="event.picture"
        class="white--text align-end"
        cover
        height="400px"
        gradient="to top right, rgba(0,0,0,.1), rgba(0,0,0,.5)"
      >
        <v-row align="end">
          <v-col
            class="ma-6 pa-6 bg-white"
            cols="4"
            style="border-radius: 5px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1)"
          >
            <v-row justify="space-between">
              <h1 class="display-1 font-weight-bold mb-4">
                {{ event.title }}
              </h1>
              <v-btn size="25" color="error" icon @click="dialog = true">
                <v-icon size="15">mdi-flag</v-icon>
              </v-btn>
            </v-row>
            <h3
              class="text-subtitle-1"
              style="color: rgb(0, 0, 90); word-break: break-all"
            >
              {{ event.description }}
            </h3>
            <v-dialog v-model="dialog" width="400px">
              <v-card>
                <v-card-text v-if="!userReporting">
                  <v-select
                    v-if="!isReporting"
                    v-model="messageValue"
                    :items="[
                      'Propos diffamatoires',
                      'Propos à caractère raciste',
                      'Propos à caractère homophobe',
                      'Propos à caractère sexiste',
                      'Propos à caractère violent',
                      'Propos à caractère discriminatoire',
                      'Propos à caractère illégal',
                    ]"
                    label="Message"
                    item-title="message"
                    item-value="message"
                    required
                  ></v-select>
                  <p v-else>Signalement envoyé</p>
                </v-card-text>
                <v-card-text v-else>
                  <p>Vous avez déjà signalé cet événement</p>
                </v-card-text>
                <v-card-actions>
                  <v-btn
                    @click="mySubmit"
                    v-if="!reportIsCharge && !isReporting && !userReporting"
                    color="error"
                    type="submit"
                  >
                    <v-icon>mdi-flag</v-icon>
                    Signaler
                  </v-btn>
                  <v-btn
                    v-if="reportIsCharge && !isReporting && !userReporting"
                  >
                    <v-progress-circular
                      disabled
                      indeterminate
                      color="error"
                    ></v-progress-circular>
                  </v-btn>
                  <v-btn color="primary" block @click="dialog = false"
                    >Fermer</v-btn
                  >
                </v-card-actions>
              </v-card>
            </v-dialog>
          </v-col>
        </v-row>
      </v-img>

      <v-col cols="12" class="my-4">
        <div class="text-center">
          <div>
            <strong
              >Participants {{ event.participants.length }} /
              {{ event.participant_max }} :</strong
            >
          </div>
          <div v-if="participants.length === 0">
            Aucun participant pour le moment...
          </div>
          <v-chip
            v-for="participant in participants"
            :key="participant.id"
            class="ma-1"
          >
            <v-icon
              v-if="creatorParticipate === participant.pseudo"
              icon="mdi-crown"
            ></v-icon>
            {{ participant.pseudo }}
          </v-chip>

          <div
            style="
              background-color: black;
              width: 200px;
              height: 1px;
              margin: 10px auto;
            "
          ></div>
          <!-- <p v-if="event.end_date === dayjs()">
            Fin des inscriptions aujourd'hui
          </p> -->
          <p v-if="dayjs().isBefore(event.end_date)">
            Fin des inscriptions dans
            {{ differenceBetweenDates(event.end_date) }} jours
          </p>
          <p>Début de l'événement : {{ formatDate(event.start_date) }}</p>
          <p>{{ getNameCategories(event.event_category_id) }}</p>
          <div v-if="event.is_IRL">
            <p>Région : {{ event.region }}</p>
            <p>Ville : {{ event.city }}</p>
          </div>
          <p>
            Créateur de l'événement : {{ getNameCreator(event.creator_id) }}
          </p>
        </div>

        <div class="text-center mt-4" v-if="isParticipate">
          <v-btn color="primary" :to="`/events/${event.id}/discussion`">
            Ouvrir la discussion</v-btn
          >
        </div>
      </v-col>
    </v-row>

    <!-- Dialogue du Chat -->
    <v-dialog v-model="showChat" persistent max-width="600px">
      <v-card>
        <template v-slot:append>
          <v-btn icon @click="showChat = false">
            <v-icon>mdi-close</v-icon>
          </v-btn>
        </template>
        <v-card-title> Discussion - {{ event.title }} </v-card-title>
        <v-card-text>
          <EventChat :eventId="id"></EventChat>
        </v-card-text>
      </v-card>
    </v-dialog>

    <v-row justify="center" wrap>
      <v-btn class="mx-2" @click="$router.go(-1)">Retour</v-btn>
      <div v-if="!buttonCharge">
        <v-btn
          class="mx-2"
          v-if="!isParticipate && !isCompleted && !isTerminate"
          @click="participateToEvent"
          >Participer</v-btn
        >
        <v-btn class="mx-2" v-if="isParticipate" @click="unsubscribeToEvent"
          >Se désinscrire</v-btn
        >
      </div>
      <div v-else>
        <v-btn class="mx-4" style="width: 100px"
          ><v-progress-circular disabled indeterminate></v-progress-circular
        ></v-btn>
      </div>

      <v-btn class="mx-2" v-if="isCompleted" disabled>Complet</v-btn>
      <v-btn class="mx-2" v-if="isTerminate" disabled
        >Inscription terminée</v-btn
      >
    </v-row>
  </v-container>
  <v-container v-else>
    <v-row align="center" justify="center">
      <v-progress-circular indeterminate></v-progress-circular>
    </v-row>
  </v-container>
</template>

<script lang="ts" setup>
import { clientAxios } from "@/services/index";
import { onMounted, ref } from "vue";
import { useRoute } from "vue-router";
import { useUserStore } from "@/store/user";
import { isPseudoExist, formatDate } from "@/helpers";
import EventChat from "../components/EventChat.vue";
import dayjs from "dayjs";
import { useForm, useField } from "vee-validate";
import { z } from "zod";
import { toTypedSchema } from "@vee-validate/zod";
import { useAdminEvents } from "@/store/admin/adminEventsStore";

const dialog = ref(false);
const categories: any = ref([]);
const route = useRoute();
const id = route.params.id;
const userStore = useUserStore();
const event: any = ref(null);
const showChat = ref(false);
const isCompleted = ref(false);
const isTerminate = ref(false);
const isCharge = ref(true);
const participants = ref<any[]>([]);
const isParticipate = ref(false);
const buttonCharge = ref(false);
const users: any = ref([]);
const creatorParticipate = ref("");
const isReporting = ref(false);
const reportIsCharge = ref(false);
const userReporting = ref(false);

const differenceBetweenDates = (endEventDate: any) => {
  const date1 = dayjs();
  const date2 = dayjs(endEventDate);
  const diffInDays = date2.diff(date1, "day");
  return diffInDays;
};

const getEvent = async (id: any) => {
  try {
    const response = await clientAxios.get(`/api/v1/events/${id}`);
    if (response.data) {
      participants.value = response.data.participants;
      return response.data;
    }
  } catch (error) {
    console.error("Erreur lors de la récupération de l'événement :", error);
  }
};

const inscriptionIsTerminate = () => {
  const date = new Date();
  const eventDate = new Date(event.value.end_date);
  if (eventDate < date) {
    isTerminate.value = true;
  }
};

const eventIsCompleted = () => {
  const participantsNumber = event.value.participants.length;
  const maxParticipants = event.value.participant_max;
  if (participantsNumber === maxParticipants) {
    isCompleted.value = true;
  } else {
    isCompleted.value = false;
  }
};

const userReportsEvent = async () => {
  if (event.value.reports.length > 0) {
    const reports = event.value.reports;
    const user = reports.find(
      (report: any) => report.user_id === userStore.getUserInfo.id
    );
    if (user) {
      userReporting.value = true;
    }
  }
};

const participateToEvent = async () => {
  try {
    buttonCharge.value = true;
    const response = await clientAxios.post(`/api/v1/events/${id}/participate`);
    if (response.data) {
      participants.value.push({ pseudo: userStore.getUserInfo.pseudo });
      isParticipate.value = true;
      eventIsCompleted();
      buttonCharge.value = false;
      return response.data;
    }
  } catch (error) {
    console.error("Erreur lors de la récupération de l'événement :", error);
  }
};

const validationSchema = z.object({
  message: z
    .string({ required_error: "Message requiss" })
    .min(1, "Message requis"),
});

const { handleSubmit } = useForm<any>({
  validationSchema: toTypedSchema(validationSchema),
});

const mySubmit = handleSubmit(async (values: any) => {
  try {
    reportIsCharge.value = true;
    const res = await reportEvent(values.message);
    if (res.status === 200) {
      isReporting.value = true;
      reportIsCharge.value = false;
    }
  } catch (error) {
    console.error("Erreur lors de l'envoi du signalement :", error);
  }
});

const reportEvent = async (values: any) => {
  try {
    const response = await clientAxios.post(`/api/v1/events/${id}/reports`, {
      message: values,
    });
    if (response.data) {
      const adminEventStore = useAdminEvents();
      adminEventStore.needRefresh = true;
      return response.data;
    }
  } catch (error) {
    console.error("Erreur lors de l'envoi du signalement :", error);
  }
};

const unsubscribeToEvent = async () => {
  try {
    buttonCharge.value = true;
    const response = await clientAxios.post(`/api/v1/events/${id}/unsubscribe`);
    if (response.data) {
      if (participants.value.length > 0) {
        for (let i = 0; i < participants.value.length; i++) {
          if (participants.value[i].pseudo === userStore.getUserInfo.pseudo) {
            participants.value.splice(i, 1);
          }
        }
      }
      isParticipate.value = false;
      eventIsCompleted();
      buttonCharge.value = false;
      return response.data;
    }
  } catch (error) {
    console.error("Erreur lors de la récupération de l'événement :", error);
  }
};

const getNameCategories = (categoryId: Number) => {
  const category = categories.value.find(
    (category: any) => category.id === categoryId
  );
  if (category) {
    return category.name;
  } else {
    return null;
  }
};

const creatorIsParticipate = () => {
  const creatorId = event.value.creator_id;
  const participants = event.value.participants;
  const creator = participants.find((participant: any) => {
    return participant.id === creatorId;
  });
  if (creator) {
    creatorParticipate.value = participants.find(
      (participant: any) => participant.id === creatorId
    ).pseudo;
  }
};

const getNameCreator = (creatorId: Number) => {
  const creator = users.value.find((user: any) => user.id === creatorId);
  if (creator) {
    return creator.pseudo;
  } else {
    return null;
  }
};

onMounted(async () => {
  document.title = "Événement";
  event.value = await getEvent(id);
  if (event.value) {
    const response = await clientAxios.get("/api/v1/event-categories");
    categories.value = response.data;
    const user = await clientAxios.get("/api/v1/users?page=1&perpage=1000");
    users.value = user.data.data;
    isPseudoExist({ participants: event.value.participants, isParticipate });
    creatorIsParticipate();
    eventIsCompleted();
    inscriptionIsTerminate();
    userReportsEvent();
    isCharge.value = false;
  }
});

const { value: messageValue } = useField<string>("message");
</script>
