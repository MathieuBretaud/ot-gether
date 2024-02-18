<template>
  <CategoryComponent />
</template>

<script lang="ts" setup>
import CategoryComponent from "@/components/Admin/CategoryComponent.vue";
import { onMounted } from "vue";

onMounted(async () => {
  document.title = "Liste des catégories";
});
</script>
