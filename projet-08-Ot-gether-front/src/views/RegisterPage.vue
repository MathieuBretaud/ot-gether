<template>
  <v-responsive class="mx-auto" max-width="344">
    <div class="ma-4" style="color: #ef5350; font-weight: bold">
      Inscription
    </div>

    <form @submit.prevent="mySubmit">
      <div
        class="pa-5"
        style="display: flex; flex-direction: column; border-radius: 10px"
      >
        <v-text-field
          v-model="lastnameValue"
          class="mb-5"
          hide-details="auto"
          label="Nom"
          placeholder="Doe"
          type="input"
          :error-messages="lastnameError"
        ></v-text-field>

        <v-text-field
          v-model="firstnameValue"
          class="mb-5"
          hide-details="auto"
          label="Prénom"
          placeholder="John"
          type="input"
          :error-messages="firstnameError"
        ></v-text-field>

        <v-text-field
          v-model="cityValue"
          class="mb-5"
          hide-details="auto"
          label="Ville"
          placeholder="Paris"
          type="input"
          :error-messages="cityError"
        ></v-text-field>

        <v-text-field
          v-model="pseudoValue"
          class="mb-5"
          hide-details="auto"
          label="Pseudo"
          placeholder="Dodoe"
          type="input"
          :error-messages="pseudoError"
        ></v-text-field>

        <v-text-field
          v-model="emailValue"
          class="mb-5"
          hide-details="auto"
          label="Email"
          placeholder="johndoe@gmail.com"
          type="email"
          :error-messages="emailError"
        ></v-text-field>

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
      </div>
      <div v-if="data">
        <v-alert :text="data" type="error" variant="tonal"></v-alert>
      </div>
      <div class="d-flex justify-center">
        <div v-if="isSubmitting">
          En cours
          <v-progress-circular indeterminate></v-progress-circular>
        </div>
        <v-btn type="submit" :disabled="isSubmitting" class="ma-4"
          >S'inscrire</v-btn
        >
      </div>
    </form>
  </v-responsive>
</template>

<script lang="ts" setup>
import { signin, signinProps } from "@/services/index";
import { useForm, useField } from "vee-validate";
import { z } from "zod";
import { toTypedSchema } from "@vee-validate/zod";
import { ref, onMounted } from "vue";
const data = ref<string | null>(null);
import { isEmailUsed } from "@/services/index";

const validationSchema = z.object({
  email: z
    .string()
    .email("Email non valide")
    .refine(async (email) => !(await isEmailUsed(email)), {
      message: "Cet email est déjà utilisé",
    }),
  city: z.string().min(3, "Ville requise"),
  firstname: z.string().min(3, "Prénom requis"),
  lastname: z.string().min(3, "Nom requis"),
  pseudo: z.string().min(4, "Pseudo requis"),
});

const passwordSchema = z
  .object({
    password: z.string().min(8, "Minimum 8 caractères pour le mot de passe"),
    confirmPassword: z.string(),
  })
  .refine((data: any) => data.password === data.confirmPassword, {
    message: "Les mots de passe ne correspondent pas",
    path: ["confirmPassword"],
  });

const allSchema = validationSchema.and(passwordSchema);
const { handleSubmit, isSubmitting } = useForm<signinProps>({
  validationSchema: toTypedSchema(allSchema),
});

const mySubmit = handleSubmit(async (values) => {
  const registered = await signin(values);
  if (typeof registered === "string") {
    data.value = registered;
    setTimeout(() => {
      data.value = null;
    }, 3000);
  }
});

onMounted(() => {
  document.title = "S'inscrire";
})

const { value: emailValue, errorMessage: emailError } = useField("email");
const { value: lastnameValue, errorMessage: lastnameError } =
  useField("lastname");
const { value: firstnameValue, errorMessage: firstnameError } =
  useField("firstname");
const { value: cityValue, errorMessage: cityError } = useField("city");
const { value: pseudoValue, errorMessage: pseudoError } = useField("pseudo");
const { value: passwordValue, errorMessage: passwordError } =
  useField("password");
const { value: confirmPasswordValue, errorMessage: confirmPasswordError } =
  useField("confirmPassword");
</script>
