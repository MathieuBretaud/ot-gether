<template>
  <v-card min-width="300" max-width="300">
    <div
      v-if="isSubscribed"
      style="
        float: left;
        top: 1em;
        left: -3em;
        position: absolute; /* or fixed if you want it to always be visible */
        transform: rotate(-45deg);
        background: red;
        color: white;
        font-weight: bold;
        padding-left: 3.5em;
        padding-right: 3em;
        padding-top: 0.5em;
        padding-bottom: 0.5em;
        border: 0;
        margin: 0;
        height: auto;
        width: auto;
        z-index: 1;
      "
    >
      Inscrit
    </div>
    <v-card-title class="text-wrap">
      <v-img class="align-end text-white" height="200" :src="image" cover>
        <p class="text-center">{{ title }}</p>
      </v-img>
    </v-card-title>

    <h3 class="text-center">{{ category }}</h3>
    <v-card-text>
      <p>
        Nombre de participants : {{ participantNumber }} / {{ maxParticipant }}
      </p>
      <p>Début de l'événement : {{ formatDate(startEvent) }}</p>
      <p v-if="!dateIsPassed">
        Fin d'inscription : {{ formatDate(endInscription) }}
      </p>
      <p v-else>Inscription terminée</p>
    </v-card-text>
    <v-card-actions class="justify-center">
      <v-btn color="primary">
        <router-link :to="{ name: 'EventDetails', params: { id: id } }">
          Details
        </router-link>
      </v-btn>
      <v-btn
        v-if="currentRouteName && isParticipate"
        color="primary"
        :to="`/events/${id}/discussion`"
      >
        Ouvrir la discussion</v-btn
      >
    </v-card-actions>
    <v-container class="justify-center" v-if="currentRouteName && isCreator">
      <v-row justify="center">
        <v-btn class="mb-3" color="primary">
          <router-link :to="{ name: 'UpdateEvent', params: { id: id } }">
            Modifier mon événement
          </router-link>
        </v-btn>
        <v-btn class="mb-3" color="error" onclick="onClickDelete">
          Supprimer mon événement</v-btn
        >
      </v-row>
    </v-container>
  </v-card>
</template>

<script lang="ts" setup>
import { onMounted, ref, computed } from "vue";
import { datePassed } from "@/helpers";
import { useRoute } from "vue-router";
import { formatDate } from "@/helpers";

const route = useRoute();
const currentRouteName = computed(() => route.name === "myEvents");
const dateIsPassed = ref(false);

const props = defineProps({
  title: String,
  participantNumber: Number,
  maxParticipant: Number,
  startEvent: String,
  endInscription: String,
  category: String,
  image: String,
  isSubscribed: Boolean,
  id: Number,
  isCreator: Boolean,
  isParticipate: Boolean,
});

onMounted(() => datePassed({ eventDate: props.endInscription, dateIsPassed }));
</script>
