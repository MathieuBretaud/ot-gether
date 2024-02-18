<template>
  <v-card class="mx-auto" max-width="400" style="margin: 2rem 0">
    <v-img class="align-end text-white" height="200" :src="image" cover>
    </v-img>
    <v-card-text>
      {{ description }}
    </v-card-text>
  </v-card>
</template>

<script lang="ts" setup>
defineProps({
  description: String,
  image: String,
});
</script>
