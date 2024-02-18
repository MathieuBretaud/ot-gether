<template>
  <v-card class="mx-auto" max-width="600">
    <v-toolbar color="secondary">
      <h1 class="ml-2">Les événements</h1>
      <v-spacer></v-spacer>
    </v-toolbar>

    <h3 v-if="adminEventStore.isLoading">Chargement ...</h3>
    <v-list v-else lines="two">
      <v-list-item
        v-for="event in adminEventStore.events"
        :key="event.id"
        :title="`${event.title} ${event.id}`"
      >
        <template v-slot:prepend>
          <v-badge
            color="error"
            :content="event.reports.length"
            inline
            v-if="event.reports.length > 0"
          ></v-badge>
          <v-badge
            v-else
            color="success"
            :content="event.reports.length"
            inline
          ></v-badge>
        </template>

        <template v-slot:append>
          <div v-if="!event.deleted_at">
            <v-btn
              :to="{ name: 'UpdateEvent', params: { id: event.id } }"
              color="grey-lighten-1"
              icon="mdi-pencil"
              variant="text"
            ></v-btn>
            <v-btn
              color="red"
              icon="mdi-close"
              variant="text"
              @click="getIdAndOpenDialog(event.id)"
            >
            </v-btn>
          </div>
          <div v-else>
            <span>Supprimé le {{ formatDate(event.deleted_at) }}</span>
          </div>
        </template>
        <v-divider :thickness="4" inset></v-divider>
      </v-list-item>
    </v-list>
  </v-card>
  <v-dialog v-model="isActive" width="auto">
    <v-card>
      <v-card-text>
        Etes-vous sûr de vouloir supprimer cet événement ?
      </v-card-text>
      <v-card-actions>
        <v-btn
          :value="eventId"
          color="primary"
          @click="confirmDeleteEvent(eventId)"
          >Oui, je confirme
        </v-btn>
        <v-btn color="primary" @click="isActive = false">Annuler</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
  <v-pagination
    v-model="adminEventStore.currentPage"
    :length="adminEventStore.totalPage"
    @update:model-value="adminEventStore.fetchEvents"
  />
</template>

<script lang="ts" setup>
import { ref } from "vue";
import { useAdminEvents } from "@/store/admin/adminEventsStore";
import dayjs from "dayjs";

const adminEventStore = useAdminEvents();
const isActive = ref(false);
const eventId = ref(null);

const formatDate = (deleteEventDate: any) => {
  const dalateDate = dayjs(deleteEventDate).format("DD/MM/YYYY");
  return dalateDate;
};
const confirmDeleteEvent = (eventId: any) => {
  isActive.value = false;
  adminEventStore.deleteEvent(eventId);
};

const getIdAndOpenDialog = (event: any) => {
  isActive.value = true;
  eventId.value = event;
};
</script>
