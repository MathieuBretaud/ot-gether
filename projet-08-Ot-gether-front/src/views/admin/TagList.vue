<template>
  <TagComponent />
</template>

<script lang="ts" setup>
import TagComponent from "@/components/Admin/TagComponent.vue";
import { onMounted } from "vue";

onMounted(async () => {
  document.title = "Liste des tags";
});
</script>
