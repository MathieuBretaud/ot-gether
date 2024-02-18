<template>
  <v-text-field
    :v-model="value"
    class="mb-5"
    hide-details="auto"
    label="Email address"
    placeholder="johndoe@gmail.com"
    type="email"
  ></v-text-field>
</template>

<script lang="ts" setup>
defineProps({
  value: String,
});
</script>
