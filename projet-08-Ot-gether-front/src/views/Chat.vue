<template>
  <v-container class="bg" fluid>
    <v-btn class="m-2" @click="$router.go(-1)">Retour</v-btn>
    <v-btn>
      <router-link :to="{ name: 'EventDetails', params: { eventId } }">
        Details de l'événement
      </router-link>
    </v-btn>
    <EventChat :eventId="eventId"></EventChat>
  </v-container>
</template>

<script lang="ts">
import EventChat from "@/components/EventChat.vue";
import { useRoute } from "vue-router";
import { onMounted } from "vue";

export default {
  components: {
    EventChat,
  },
  setup() {
    const route = useRoute();
    const eventId = route.params.id;

    onMounted(() => {
      document.title = 'Discussions';
    });

    return {
      eventId,
    };
  },
};
</script>

<style>
.bg {
  background-color: #ebe1e1c6;
}
</style>
