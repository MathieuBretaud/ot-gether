<template>
  <v-container>
    <v-row>
      <MenuList />
      <v-col cols="9" md="9">
        <router-view name="dashboard" v-slot="{ Component, route }">
          <Component :is="Component" :key="route.fullPath" />
        </router-view>
      </v-col>
    </v-row>
  </v-container>
</template>

<script lang="ts" setup>
import MenuList from "@/components/Admin/MenuList.vue";
import { onMounted } from "vue";

onMounted(() => {
  document.title = "Vue des administrateurs";
});
</script>
