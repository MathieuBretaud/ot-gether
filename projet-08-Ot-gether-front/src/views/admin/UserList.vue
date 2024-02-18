<template>
  <UserComponent />
</template>

<script lang="ts" setup>
import UserComponent from "@/components/Admin/UserComponent.vue";
import { onMounted } from "vue";

onMounted(() => {
  document.title = "Liste des utilisateurs";
});
</script>
