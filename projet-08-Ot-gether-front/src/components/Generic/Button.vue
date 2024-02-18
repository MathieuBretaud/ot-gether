<template>
  <v-btn
    variant="text"
    :href="link"
    style="border-bottom: 1px #fff solid; border-radius: 0"
    >{{ label }}</v-btn
  >
</template>

<script lang="ts" setup>
defineProps({
  link: String,
  label: String,
});
</script>
