<template>
  <v-responsive class="mx-auto" max-width="344">
    <div class="ma-4" style="color: #ef5350; font-weight: bold">
      Modifier mes informations :
    </div>

    <form @submit.prevent="mySubmit">
      <div class="pa-5" style="display: flex; flex-direction: column; border-radius: 10px">
        <v-text-field v-model="lastnameValue" class="mb-5" hide-details="auto" label="Nom" placeholder="Doe" type="input"
          :error-messages="lastnameError"></v-text-field>
        <v-text-field v-model="firstnameValue" class="mb-5" hide-details="auto" label="Prénom" placeholder="John" type="input"
          :error-messages="firstnameError"></v-text-field>

        <v-text-field v-model="cityValue" class="mb-5" hide-details="auto" label="Ville" placeholder="Paris" type="input"
          :error-messages="cityError"></v-text-field>

        <v-text-field v-model="pseudoValue" class="mb-5" hide-details="auto" label="Pseudo" placeholder="Dodoe" type="input"
          :error-messages="pseudoError"></v-text-field>

        <v-text-field v-model="emailValue" class="mb-5" hide-details="auto" label="Email" placeholder="johndoe@gmail.com"
          type="email" :error-messages="emailError"></v-text-field>

        <v-text-field v-model="passwordValue" label="Mot de passe" type="password"
          :error-messages="passwordError"></v-text-field>

        <v-text-field v-model="confirmPasswordValue" label="Confirmation mot de passe" type="password"
          :error-messages="confirmPasswordError"></v-text-field>
      </div>
      <div class="flex flex-col justify-center">
        <div v-if="isSubmitting">En cours
          <v-progress-circular indeterminate></v-progress-circular>
        </div>
        <div v-if="error" class="text-red-500">
          {{ error }}
        </div>
        <v-btn type="submit" :disabled="isSubmitting" class="ma-4 w-1/2 mx-auto">Modifier</v-btn>
      </div>
    </form>
  </v-responsive>
</template>

<script lang="ts" setup>
import { signinProps } from "@/services/index";
import { useForm, useField } from "vee-validate";
import { z } from "zod";
import { toTypedSchema } from "@vee-validate/zod";
import { ref } from "vue";
import { isEmailUsed } from "@/services/index";
import { updateUser, UpdatedFields } from "@/services/users";
import { onMounted } from "vue";

const data = defineProps({
  userInfo: {
    type: Object,
    required: true,
  },
});

onMounted(() => {
  emailValue.value = data.userInfo.email;
  lastnameValue.value = data.userInfo.lastname;
  firstnameValue.value = data.userInfo.firstname;
  cityValue.value = data.userInfo.city;
  pseudoValue.value = data.userInfo.pseudo;
})

const error = ref();

const validationSchema = z.object({
  email: z
    .string()
    .email("Email non valide")
    .refine(async (email) => {
      // On check si le mail a été modifié
      if (email !== data.userInfo.email) {
        return !(await isEmailUsed(email));
      }
      return true;
    }, {
      message: "Cet email est déjà utilisé",
    }),
  city: z.string().min(3, "Ville requise"),
  firstname: z.string().min(3, "Prénom requis"),
  lastname: z.string().min(3, "Nom requis"),
  pseudo: z.string().min(4, "Pseudo requis"),
});

const passwordSchema = z
  .object({
    password: z.string().min(8, "Minimum 8 caractères pour le mot de passe").optional(),
    confirmPassword: z.string().optional(),
  })
  .refine((data: any) => data.password === data.confirmPassword, {
    message: "Les mots de passe ne correspondent pas",
    path: ["confirmPassword"],
  });

const allSchema = validationSchema.and(passwordSchema);

const { handleSubmit, isSubmitting } = useForm<signinProps>({
  validationSchema: toTypedSchema(allSchema),
  initialValues: {
    email: data.userInfo.email,
    firstname: data.userInfo.firstname,
    lastname: data.userInfo.lastname,
    city: data.userInfo.city,
    pseudo: data.userInfo.pseudo,
  },
});

const mySubmit = handleSubmit(async (values) => {
  
  const updatedFields: UpdatedFields = {};

  if (values.firstname !== data.userInfo.firstname) {
    updatedFields.firstname = values.firstname;
  }

  if (values.lastname !== data.userInfo.lastname) {
    updatedFields.lastname = values.lastname;
  }

  if (values.city !== data.userInfo.city) {
    
    updatedFields.city = values.city;
  }

  if (values.pseudo !== data.userInfo.pseudo) {
    updatedFields.pseudo = values.pseudo;
  }

  if (values.email !== data.userInfo.email) {
    updatedFields.email = values.email;
  }

  if (Object.keys(updatedFields).length === 0) {    
    error.value = "Aucun changement detecté";
    setTimeout(() => {
      error.value = "";
    }, 3000)   
    return;
  }

  const res = await updateUser(updatedFields as signinProps, data.userInfo.id);
  if (res.status !== 200) {
    error.value = "Une erreur est survenue. Veuillez rééssayer plus tard.";
    return;
  }

  location.reload();
  
});

const { value: emailValue, errorMessage: emailError } = useField("email");
const { value: lastnameValue, errorMessage: lastnameError } = useField("lastname");
const { value: firstnameValue, errorMessage: firstnameError } = useField("firstname");
const { value: cityValue, errorMessage: cityError } = useField("city");
const { value: pseudoValue, errorMessage: pseudoError } = useField("pseudo");
const { value: passwordValue, errorMessage: passwordError } = useField("password");
const { value: confirmPasswordValue, errorMessage: confirmPasswordError } = useField("confirmPassword");

</script>