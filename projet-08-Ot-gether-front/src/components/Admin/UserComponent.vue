<template>
  <v-card class="mx-auto" max-width="600">
    <v-toolbar color="secondary">
      <h1 class="ml-2">Les Utilisateurs</h1>
      <v-spacer></v-spacer>
    </v-toolbar>
    <v-list lines="two">
      <v-list-item
        v-for="user in adminUserStore.users"
        :key="user.id"
        :title="`${user.firstname}`"
      >
        <template v-slot:prepend>
          <v-avatar color="grey-lighten-1">
            <v-icon color="white">mdi-account</v-icon>
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
            @click="getIdAndOpenDialog(user.id)"
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
          :value="userId"
          color="primary"
          @click="confirmDeleteUser(userId)"
          >Oui, je confirme</v-btn
        >
        <v-btn color="primary" @click="isActive = false">Annuler</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
  <v-pagination
    v-model="adminUserStore.currentPage"
    :length="adminUserStore.totalPage"
    @update:model-value="adminUserStore.fetchUsers"
  />
</template>

<script lang="ts" setup>
import { useAdminUsers } from "@/store/admin/adminUsersStore";
import { ref } from "vue";
const isActive = ref(false);
const userId = ref(null);
const adminUserStore = useAdminUsers();

const confirmDeleteUser = (userId: any) => {
  isActive.value = false;
  adminUserStore.deleteUser(userId);
};

const getIdAndOpenDialog = (user: any) => {
  isActive.value = true;
  userId.value = user;
};
// defineProps<{ users: any }>();
</script>

<style></style>
