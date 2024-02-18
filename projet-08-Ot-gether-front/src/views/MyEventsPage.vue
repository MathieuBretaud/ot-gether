<template>
  <v-container v-if="!isCharge">
    <v-card>
      <v-tabs v-model="tab" color="deep-purple-accent-4" align-tabs="center">
        <v-tab :value="1">Inscrits</v-tab>
        <v-tab :value="2">Crées</v-tab>
      </v-tabs>
      <v-window v-model="tab">
        <v-window-item v-for="n in 3" :key="n" :value="1">
          <v-expansion-panels
            v-model="panel"
            multiple
            class="mt-4"
            variant="accordion"
          >
            <v-expansion-panel>
              <v-expansion-panel-title>
                Mes événements à venir</v-expansion-panel-title
              >
              <v-expansion-panel-text>
                <v-container fluid>
                  <v-row v-if="myFutureEvents.length > 0">
                    <v-col
                      style="
                        display: flex;
                        justify-content: center;
                        align-items: center;
                      "
                      v-for="event in myFutureEvents"
                      :key="event.id"
                    >
                      <EventCard
                        :key="event.id"
                        :title="event.title"
                        :category="`${getNameCategories(
                          event.event_category_id
                        )}`"
                        :image="event.picture"
                        :id="event.id"
                        :end-inscription="event.end_date"
                        :start-event="event.start_date"
                        :max-participant="event.participant_max"
                        :participant-number="event.participants.length"
                        :is-participate="myEventsDiscussion"
                      />
                    </v-col>
                  </v-row>
                  <v-row v-else>
                    <v-col cols="12">
                      <h2 class="text-center">
                        Vous êtes inscrit à aucun événement à venir
                      </h2>
                    </v-col>
                  </v-row>
                </v-container>
              </v-expansion-panel-text>
            </v-expansion-panel>
            <v-expansion-panel>
              <v-expansion-panel-title>
                Mes événements commencés / terminés</v-expansion-panel-title
              >
              <v-expansion-panel-text>
                <v-container fluid>
                  <v-row v-if="myPastEvents.length > 0">
                    <v-col
                      style="display: flex; justify-content: center"
                      v-for="event in myPastEvents"
                      :key="event.id"
                    >
                      <EventCard
                        :key="event.id"
                        :title="event.title"
                        :category="`${getNameCategories(
                          event.event_category_id
                        )}`"
                        :image="event.picture"
                        :id="event.id"
                        :end-inscription="event.end_date"
                        :start-event="event.start_date"
                        :max-participant="event.participant_max"
                        :participant-number="event.participants.length"
                        :is-participate="myEventsDiscussion"
                      />
                    </v-col>
                  </v-row>
                  <v-row v-else>
                    <v-col cols="12">
                      <h2 class="text-center">
                        Vous êtes inscrit à aucun événement commencé / terminé
                      </h2>
                    </v-col>
                  </v-row>
                </v-container>
              </v-expansion-panel-text>
            </v-expansion-panel>
          </v-expansion-panels>
        </v-window-item>
        <v-window-item v-for="n in 3" :key="n" :value="2">
          <v-expansion-panels
            v-model="panel"
            multiple
            class="mt-4"
            variant="accordion"
          >
            <v-expansion-panel>
              <v-expansion-panel-title>
                Mes événements à venir
              </v-expansion-panel-title>
              <v-expansion-panel-text>
                <v-container fluid>
                  <v-row v-if="myFutureCreatedEvents.length > 0">
                    <v-col
                      style="
                        display: flex;
                        justify-content: center;
                        align-items: center;
                      "
                      v-for="event in myFutureCreatedEvents"
                      :key="event.id"
                    >
                      <EventCard
                        :key="event.id"
                        :title="event.title"
                        :category="`${getNameCategories(
                          event.event_category_id
                        )}`"
                        :image="event.picture"
                        :id="event.id"
                        :end-inscription="event.end_date"
                        :start-event="event.start_date"
                        :max-participant="event.participant_max"
                        :participant-number="event.participants.length"
                        :is-creator="true"
                        :is-participate="event.participants.length > 0 ? 
                  event.participants.map((participant: any) => participant.pseudo).includes(userStore.getUserInfo.pseudo) ? true : false : false"
                      />
                    </v-col>
                  </v-row>
                  <v-row v-else>
                    <v-col cols="12">
                      <h2 class="text-center">
                        Vous n'avez pas encore créé d'événement
                      </h2>
                    </v-col>
                  </v-row>
                </v-container>
              </v-expansion-panel-text>
            </v-expansion-panel>
            <v-expansion-panel>
              <v-expansion-panel-title>
                Mes événements commencés / terminés
              </v-expansion-panel-title>
              <v-expansion-panel-text>
                <v-container fluid>
                  <v-row v-if="myPastCreatedEvents.length > 0">
                    <v-col
                      style="
                        display: flex;
                        justify-content: center;
                        align-items: center;
                      "
                      v-for="event in myPastCreatedEvents"
                      :key="event.id"
                    >
                      <EventCard
                        :key="event.id"
                        :title="event.title"
                        :category="`${getNameCategories(
                          event.event_category_id
                        )}`"
                        :image="event.picture"
                        :id="event.id"
                        :end-inscription="event.end_date"
                        :start-event="event.start_date"
                        :max-participant="event.participant_max"
                        :participant-number="event.participants.length"
                        :is-creator="true"
                        :is-participate="event.participants.length > 0 ? 
                  event.participants.map((participant: any) => participant.pseudo).includes(userStore.getUserInfo.pseudo) ? true : false : false"
                      />
                    </v-col>
                  </v-row>
                  <v-row v-else>
                    <v-col cols="12">
                      <h2 class="text-center">
                        Vous n'avez pas encore créé d'événement
                      </h2>
                    </v-col>
                  </v-row>
                </v-container>
              </v-expansion-panel-text>
            </v-expansion-panel>
          </v-expansion-panels>
        </v-window-item>
      </v-window>
    </v-card>
  </v-container>
  <v-container v-else>
    <v-row justify="center">
      <v-progress-circular indeterminate color="primary"></v-progress-circular>
    </v-row>
  </v-container>
</template>

<script lang="ts" setup>
import EventCard from "@/components/EventCard.vue";
import { clientAxios } from "@/services/index";
import { onMounted, ref } from "vue";
import { useUserStore } from "@/store/user";
import dayjs from "dayjs";

const isCharge = ref(true);
const tab = ref(null);
const myEvents = ref([] as any[]);
const myFutureEvents = ref([] as any[]);
const myPastEvents = ref([] as any[]);
const myCreatedEvents = ref([] as any[]);
const myFutureCreatedEvents = ref([] as any[]);
const myPastCreatedEvents = ref([] as any[]);
const userStore = useUserStore();
const categories: any = ref([]);
const myEventsDiscussion = ref(true);
const panel = ref([0]);

const getMyEvents = async () => {
  try {
    const response = await clientAxios.get("/api/v1/user/myevents");

    if (response.data) {
      myEvents.value = response.data[0];
      if (myEvents.value.length > 0) {
        for (let i = 0; i < myEvents.value.length; i++) {
          if (
            dayjs(myEvents.value[i].start_date).isAfter(dayjs()) ||
            dayjs(myEvents.value[i].start_date).isSame(dayjs())
          ) {
            myFutureEvents.value.push(myEvents.value[i]);
            myFutureEvents.value.sort((a: any, b: any) =>
              dayjs(a.start_date).isAfter(dayjs(b.start_date)) ? 1 : -1
            );
          } else {
            myPastEvents.value.push(myEvents.value[i]);
            myPastEvents.value.sort((a: any, b: any) =>
              dayjs(b.start_date).isAfter(dayjs(a.start_date)) ? 1 : -1
            );
          }
        }
      }
      isCharge.value = false;
    }
  } catch (error) {
    console.error("Erreur lors de la récupération de mes événements :", error);
  }
};

const getMyCreatedEvents = async () => {
  try {
    const response = await clientAxios.get(`/api/v1/events?perpage=100&page=1`);

    if (response.data) {
      for (let i = 0; i < response.data.data.length; i++) {
        if (response.data.data[i].creator_id === userStore.getUserInfo.id) {
          myCreatedEvents.value.push(response.data.data[i]);
          if (
            dayjs(response.data.data[i].start_date).isAfter(dayjs()) ||
            dayjs(response.data.data[i].start_date).isSame(dayjs())
          ) {
            myFutureCreatedEvents.value.push(response.data.data[i]);
            myFutureCreatedEvents.value.sort((a: any, b: any) =>
              dayjs(a.start_date).isAfter(dayjs(b.start_date)) ? 1 : -1
            );
          } else {
            myPastCreatedEvents.value.push(response.data.data[i]);
            myPastCreatedEvents.value.sort((a: any, b: any) =>
              dayjs(b.start_date).isAfter(dayjs(a.start_date)) ? 1 : -1
            );
          }
        }
      }
    }
  } catch (error) {
    console.error(
      "Erreur lors de la récupération de mes événements créés :",
      error
    );
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

onMounted(async () => {
  document.title = "Mes événements";
  await getMyEvents();
  await getMyCreatedEvents();
  const response = await clientAxios.get("/api/v1/event-categories");
  categories.value = response.data;
});
</script>
