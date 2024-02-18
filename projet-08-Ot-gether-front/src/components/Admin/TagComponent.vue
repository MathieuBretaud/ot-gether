<template>
  <v-card class="mx-auto" max-width="600">
    <v-toolbar color="secondary">
      <h1 class="ml-2">Les tags</h1>
      <v-spacer></v-spacer>
    </v-toolbar>
    <v-list lines="two">
      <v-list-item
        v-for="tag in adminTagStore.tags"
        :key="tag.id"
        :title="`${tag.name} `"
      >
        <template v-slot:prepend>
          <v-avatar color="grey-lighten-1">
            <v-icon color="white">mdi-tag-outline</v-icon>
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
            @click="getIdAndOpenDialog(tag.id)"
          >
          </v-btn>
          <v-dialog v-model="isActive" width="auto">
            <v-card>
              <v-card-text>
                Etes-vous sûr de vouloir supprimer cet événement ?
              </v-card-text>
              <v-card-actions>
                <v-btn
                  :value="tagId"
                  color="primary"
                  @click="confirmDeleteEvent(tagId)"
                  >Oui, je confirme
                </v-btn>
                <v-btn color="primary" @click="isActive = false">Annuler</v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </template>
        <v-divider :thickness="4" inset></v-divider>
      </v-list-item>
    </v-list>
  </v-card>
</template>

<script lang="ts" setup>
import { useAdminTags } from "@/store/admin/adminTagsStore";
import { ref } from "vue";

const adminTagStore = useAdminTags();

const isActive = ref(false);
const tagId = ref(null);

const confirmDeleteEvent = (tagId: any) => {
  isActive.value = false;
  adminTagStore.deleteTag(tagId);
};

const getIdAndOpenDialog = (tag: any) => {
  isActive.value = true;
  tagId.value = tag;
};
</script>

<style></style>
