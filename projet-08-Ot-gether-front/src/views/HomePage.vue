<template>
  <v-container>
    <v-container v-if="!isCharge">
      <v-row>
        <v-col
          style="display: flex; justify-content: center; align-items: center"
          v-for="(event, i) in events"
          class="my-4"
          :key="i"
          cols="12"
          md="6"
          lg="4"
          xl="4"
        >
          <EventCard
            :key="event.id"
            :title="event.title"
            :category="`${getNameCategories(event.event_category_id)}`"
            :image="event.picture"
            :id="event.id"
            :end-inscription="event.end_date"
            :start-event="event.start_date"
            :max-participant="event.participant_max"
            :participant-number="event.participants.length"
            :is-subscribed="
              isPseudoExist({
                participants: event.participants,
                isParticipate,
              })
            "
          />
        </v-col>
      </v-row>
    </v-container>
    <v-container v-else>
      <v-row justify="center">
        <v-progress-circular
          indeterminate
          color="primary"
        ></v-progress-circular>
      </v-row>
    </v-container>
    <v-pagination
      v-model="currentPage"
      :length="totalPage"
      @update:model-value="next"
    />
  </v-container>
</template>

<script lang="ts" setup>
import EventCard from "@/components/EventCard.vue";
import { getEvents } from "@/services/events";
import { onMounted, ref } from "vue";
import { isPseudoExist } from "@/helpers";
import { clientAxios } from "@/services";

const events = ref([] as any[]);
const currentPage = ref(1);
const perPage = ref(6);
const totalPage = ref(0);
const isParticipate = ref([]);
const categories: any = ref([]);
const isCharge = ref(true);

const next = async () => {
  isCharge.value = true;
  await getEvents({ perPage, currentPage, events, totalPage });
  isCharge.value = false;
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
  document.title = "Accueil";
  await getEvents({ perPage, currentPage, events, totalPage });
  const response = await clientAxios.get("/api/v1/event-categories");
  categories.value = response.data;
  isCharge.value = false;
});
</script>
