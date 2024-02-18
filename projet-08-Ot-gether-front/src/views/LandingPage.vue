<template>
  <Landing />
</template>

<script lang="ts" setup>
import Landing from "@/components/Landing/Landing.vue";
import { onMounted } from 'vue';

onMounted(() => {
  document.title = "Landing";
});
</script>
