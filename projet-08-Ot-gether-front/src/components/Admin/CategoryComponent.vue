<template>
  <v-card class="mx-auto" max-width="600">
    <v-toolbar color="secondary">
      <h1 class="ml-2">Les categories</h1>
      <v-spacer></v-spacer>
    </v-toolbar>
    <v-list lines="two">
      <v-list-item
        v-for="category in adminCategoryStore.categories"
        :key="category.id"
        :title="`${category.name} `"
      >
        <template v-slot:prepend>
          <v-avatar color="grey-lighten-1">
            <v-icon color="white">mdi-format-list-bulleted</v-icon>
          </v-avatar>
        </template>

        <template v-slot:append>
          <v-btn
            color="grey-lighten-1"
            icon="mdi-pencil"
            variant="text"
          ></v-btn>
          <v-btn
            color="red"
            icon="mdi-close"
            variant="text"
            @click="getIdAndOpenDialog(category.id)"
          >
          </v-btn>
        </template>
        <v-divider :thickness="4" inset></v-divider>
      </v-list-item>
    </v-list>
  </v-card>
  <v-dialog v-model="isActive" width="auto">
    <v-card>
      <v-card-text>
        Etes-vous sûr de vouloir supprimer cet événement ?
      </v-card-text>
      <v-card-actions>
        <v-btn
          :value="categoryId"
          color="primary"
          @click="confirmDeleteEvent(categoryId)"
          >Oui, je confirme</v-btn
        >
        <v-btn color="primary" @click="isActive = false">Annuler</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script lang="ts" setup>
import { useAdminCategories } from "@/store/admin/adminCategoriesStore";
import { ref } from "vue";
const adminCategoryStore = useAdminCategories();

const isActive = ref(false);
const categoryId = ref(null);

const confirmDeleteEvent = (categoryId: any) => {
  isActive.value = false;
  adminCategoryStore.deleteCategory(categoryId);
};

const getIdAndOpenDialog = (event: any) => {
  isActive.value = true;
  categoryId.value = event;
};
</script>

<style></style>
