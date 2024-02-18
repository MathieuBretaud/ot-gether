<template>
  <v-app>

      <div class="flex flex-col justify-center text-center text-black h-full">
        <h1 class="text-4xl font-medium pb-4">404 : Page introuvable</h1>
        <button :onclick="() => router.push({ name: 'HomePage' })" class="underline">Retour à l'accueil</button>
      </div>


    <default-footer />
  </v-app>
</template>

<script lang="ts" setup>
import { useRouter } from 'vue-router';
import DefaultFooter from "./Footer.vue";

const router = useRouter();

</script>
