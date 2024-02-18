<template>
  <div class="py-16 text-center flex flex-col w-1/2 mx-auto">
    <div v-if="!form" class="pb-6 flex flex-col items-center justify-center">
      <h1 class="text-4xl font-bold pb-6 underline">Mes informations :</h1>
      <div v-if="userInfo" class="space-y-4 text-left text-lg">
        <p><span class="font-semibold">Nom : </span>{{ userInfo.lastname }}</p>
        <p>
          <span class="font-semibold">Prénom : </span>{{ userInfo.firstname }}
        </p>
        <p><span class="font-semibold">Pseudo : </span>{{ userInfo.pseudo }}</p>
        <p><span class="font-semibold">Ville : </span>{{ userInfo.city }}</p>
        <p><span class="font-semibold">Email : </span>{{ userInfo.email }}</p>
      </div>
      <div v-else class="text-center text-lg">
        Chargement
        <v-progress-circular indeterminate></v-progress-circular>
      </div>
    </div>
    <div v-if="form">
      <FormInfo :userInfo="userInfo" />
    </div>
    <button
      :class="{
        'text-black bg-gray-300 hover:bg-gray-500': form,
        'text-white bg-blue-500 hover:bg-blue-700': !form,
      }"
      class="font-bold py-2 px-4 rounded mx-auto"
      :disabled="!userInfo"
      :onclick="toggleForm"
    >
      {{ form ? "Retourner en arrière" : "Modifier mes informations" }}
    </button>
    <router-link :to="{ name: 'myEvents' }" custom v-slot="{ navigate }">
      <button
        @click="navigate"
        role="link"
        class="text-white bg-blue-500 hover:bg-blue-700 font-bold py-2 my-6 px-4 rounded mx-auto"
      >
        Voir mes événements
      </button>
    </router-link>
  </div>
</template>

<script lang="ts" setup>
import { onMounted, ref } from "vue";
import { clientAxios } from "@/services";
import { useRouter } from "vue-router";
import FormInfo from "@/components/Profile/FormInfo.vue";

const router = useRouter();
const form = ref<Boolean>(false);
const userInfo = ref<any>();

onMounted(async () => {
  document.title = "Profil";
  const res = await clientAxios("/api/v1/me");
  if (res.status !== 200) {
    return router.push({ name: "Login" });
  }

  userInfo.value = res.data;
});

const toggleForm = () => {
  form.value = !form.value;
};
</script>
