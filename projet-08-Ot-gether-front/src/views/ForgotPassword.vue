<template>
  <v-responsive class="mx-auto" max-width="344">
    <div class="ma-4 text-center font-medium text-red-500 text-2xl py-6">
      Mot de passe oublié
    </div>

    <form
      @submit.prevent="mySubmit"
      class="flex flex-col justify-center space-y-4"
    >
      <v-text-field
        v-model="emailValue"
        class="mb-5"
        hide-details="auto"
        label="Adresse Email"
        placeholder="johndoe@gmail.com"
        type="email"
        :error-messages="emailError"
      ></v-text-field>
      <div v-if="fail" class="text-red-500 text-center text-sm">
        {{ fail }}
      </div>
      <div v-if="success" class="text-green-500 text-center text-sm">
        {{ success }}
      </div>
      <div class="d-flex justify-center">
        <v-btn type="submit" :disabled="isSubmitting" class="ma-4"
          >Envoyer un mail de réinitialisation</v-btn
        >
      </div>
    </form>
  </v-responsive>
</template>

<script lang="ts" setup>
import { useForm, useField } from "vee-validate";
import { useRouter } from "vue-router";
import { z } from "zod";
import { toTypedSchema } from "@vee-validate/zod";
import { ref, onMounted } from "vue";
import { clientAxios, loginProps } from "@/services";

const fail = ref<null | string>(null);
const success = ref<null | string>(null);
const router = useRouter();
const validationSchema = z.object({
  email: z
    .string({ required_error: "Adresse email requis." })
    .email("Email non valide"),
});

const { handleSubmit, isSubmitting } = useForm<Partial<loginProps>>({
  validationSchema: toTypedSchema(validationSchema),
});

const mySubmit = handleSubmit(async (values) => {
  try {
    const res = await clientAxios.post(
      "/api/forgot-password",
      values
    );
    if (res.status === 200) {
      success.value = res.data.status;
      setTimeout(() => {
        success.value = null;
        router.push({ name: "Landing" });
      }, 3000);
    }
  } catch (err: any) {
    console.log("forgotpassword submit catch err", err);
    if (err?.response?.status === 422) {
      fail.value = err.response.data.message;
      setTimeout(() => {
        fail.value = null;
      }, 3000);
    }
  }
});

onMounted(async () => {
  document.title = 'Mot de passe oublié';
});

const { value: emailValue, errorMessage: emailError } = useField("email");
</script>
