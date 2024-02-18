<template>
  <Event/>
</template>

<script lang="ts" setup>
import Event from "@/components/Admin/Event.vue";
import { onMounted } from 'vue';

onMounted(() => {
  document.title = "Liste des événements";
});

</script>
