<template>
  <div>
    <div v-if="!success && !fail">
      <h2 class="text-3xl text-black">Vérification en cours</h2>
    </div>
    <div v-if="success" class="bg-green-500 text-black text-center text-2xl">
      {{ success }}
    </div>
    <div v-if="fail">
      <h2>Votre token est expiré. Veuiller recommencer.</h2>
      <button class="bg-blue-500 text-black text-center" :onclick="resendEmail">
        Renvoyer le mail
      </button>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { ref, onMounted } from "vue";
import { clientAxios } from "@/services";
import { useRoute, useRouter } from "vue-router";

const route = useRoute();
const router = useRouter();
const success = ref<null | string>(null);
const fail = ref<Boolean>(false);

onMounted(async () => {
  document.title = "Vérifier son adresse mail";
  try {
    const query = route.query;
    const params = route.params;

    let res = await clientAxios.get(
      "verify-email/" +
        params.id +
        "/" +
        params.token +
        "?expires=" +
        query.expires +
        "&signature=" +
        query.signature
    );
    if (res.status === 400) {
      success.value = res.data.message;
      setTimeout(() => {
        success.value = null;
        router.push({ name: "HomePage" });
      }, 2000);
    }
    if (res.status === 200) {
      success.value = res.data.message;
      setTimeout(() => {
        success.value = null;
        router.push({ name: "HomePage" });
      }, 3000);
    }
    if (res.data?.message === "Token expired") {
      fail.value = true;
    }
  } catch (err: any) {
    console.log("verifyEmail catch err", err);
    if (err?.response?.status === 403) {
      fail.value = true;
    }
  }
});

const resendEmail = async () => {
  try {
    const res = await clientAxios.post("api/email/verification-notification");
    if (res.status === 200) {
      success.value = res.data.message;
      setTimeout(() => {
        fail.value = false;
      }, 3000);
    }
    if (res.status === 400) {
      success.value = res.data.message;
      setTimeout(() => {
        success.value = null;
        router.push({ name: "HomePage" });
      }, 2000);
    }
  } catch (err: any) {
    console.log("resendEmail catch err", err);
    if (err?.response?.status === 403) {
      fail.value = true;
    }
  }
};
</script>
