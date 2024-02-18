<template>
  <v-responsive class="mx-auto" max-width="344">
    <div class="ma-4 text-center font-medium text-red-500 text-2xl py-6">
      Réinitialisation du mot de passe
    </div>

    <form
      @submit.prevent="mySubmit"
      class="flex flex-col justify-center space-y-4"
    >
      <v-text-field
        v-model="passwordValue"
        label="Mot de passe"
        type="password"
        :error-messages="passwordError"
      ></v-text-field>

      <v-text-field
        v-model="confirmPasswordValue"
        label="Confirmation mot de passe"
        type="password"
        :error-messages="confirmPasswordError"
      ></v-text-field>
      <div v-if="fail" class="text-red-500 text-center text-sm">
        {{ fail }}
      </div>
      <div v-if="success" class="text-green-500 text-center text-sm">
        {{ success }}
      </div>
      <div class="d-flex justify-center">
        <v-btn type="submit" :disabled="isSubmitting" class="ma-4"
          >Réinitialiser le mot de passe
        </v-btn>
      </div>
    </form>
  </v-responsive>
</template>

<script lang="ts" setup>
import { useForm, useField } from "vee-validate";
import { z } from "zod";
import { toTypedSchema } from "@vee-validate/zod";
import { ref, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import { clientAxios } from "@/services";

export interface passwordProps {
  password: string;
  password_confirmation: string;
}

const route = useRoute();
const router = useRouter();

const email = route.query.email;
const token = route.params.token;

const success = ref<null | string>(null);
const fail = ref<null | string>(null);

const passwordSchema = z
  .object({
    password: z
      .string({ required_error: "Mot de passe requis." })
      .min(8, "Minimum 8 caractères pour le mot de passe"),
    password_confirmation: z.string({
      required_error: "Confirmation du mot de passe requis.",
    }),
  })
  .refine((data: any) => data.password === data.password_confirmation, {
    message: "Les mots de passe ne correspondent pas",
    path: ["password_confirmation"],
  });

const { handleSubmit, isSubmitting } = useForm<Partial<passwordProps>>({
  validationSchema: toTypedSchema(passwordSchema),
});

const mySubmit = handleSubmit(async (values) => {
  try {
    const res = await clientAxios.post("/api/reset-password", {
      password: values.password,
      password_confirmation: values.password_confirmation,
      token,
      email,
    });
    if (res.status === 200) {
      success.value = res.data.status;
      setTimeout(() => {
        success.value = null;
        router.push({ name: "Login" });
      }, 3000);
    }
  } catch (err: any) {
    console.log("catch err", err);
    if (err?.response?.status === 422) {
      fail.value = err.response.data.message;
      setTimeout(() => {
        fail.value = null;
        router.push({ name: "ForgotPassword" });
      }, 3000);
    }
  }
});

onMounted(() => {
  document.title = "Réinitialiser mon mot de passe"
}) 

const { value: passwordValue, errorMessage: passwordError } =
  useField("password");
const { value: confirmPasswordValue, errorMessage: confirmPasswordError } =
  useField("password_confirmation");
</script>
