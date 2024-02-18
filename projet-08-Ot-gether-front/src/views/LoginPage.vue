<template>
  <v-responsive class="mx-auto" max-width="344">
    <h1 class="ma-4 text-center font-medium text-red-500 text-2xl py-6">
      Connexion
    </h1>

    <form @submit.prevent="mySubmit" class="flex flex-col justify-center space-y-4">
      <v-text-field v-model="emailValue" class="mb-5" hide-details="auto" label="Adresse Email"
        placeholder="johndoe@gmail.com" type="email" :error-messages="emailError"></v-text-field>

      <v-text-field v-model="passwordValue" label="Password" type="password"
        hint="Enter your password to access this website" :error-messages="passwordError"></v-text-field>
      <div v-if="data">
        <v-alert :text="data" type="error" variant="tonal"></v-alert>
      </div>
      <div v-if="isSubmitting" class="text-center">En cours
        <v-progress-circular indeterminate></v-progress-circular>
      </div>
      <v-btn type="submit" :disabled="isSubmitting" class="mt-4"> Se connecter </v-btn>
    </form>
  </v-responsive>
</template>

<script lang="ts" setup>
import { useForm, useField } from "vee-validate";
import { login, loginProps } from "@/services/index";
import { string, z } from "zod";
import { toTypedSchema } from "@vee-validate/zod";
import { ref, onMounted } from "vue";

const data = ref<string | null>(null);

const validationSchema = z.object({
  email: z
    .string({ required_error: "Email requis" })
    .email({ message: "Email non valide" }),
  password: string({ required_error: "Mot de passe requis" }).min(1, {
    message: "Mot de passe requis",
  }),
});

const { handleSubmit, isSubmitting } = useForm<loginProps>({
  validationSchema: toTypedSchema(validationSchema),
});

const mySubmit = handleSubmit(async (values) => {
  const loged = await login(values);
  if (typeof loged === "string") {
    data.value = loged;
    setTimeout(() => {
      data.value = null;
    }, 3000);
  }
});

onMounted(() => {
  document.title = "Se connecter"
})

const { value: emailValue, errorMessage: emailError } = useField("email");
const { value: passwordValue, errorMessage: passwordError } =
  useField("password");
</script>
