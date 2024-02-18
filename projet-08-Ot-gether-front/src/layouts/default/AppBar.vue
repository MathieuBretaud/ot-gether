<template>
  <v-app-bar flat color="red-lighten-1">
    <v-app-bar-title>
      <div class="d-flex justify-space-between">
        <router-link :to="{ name: 'Landing' }">
          <img
            src="/public/logo-otgether.png"
            alt="logo otgether"
            class="h-12 mr-2 rounded"
          />
        </router-link>
        <div v-if="$vuetify.display.mdAndDown"></div>
        <div v-else>
          <div v-if="userStore.isAuthenticated === false">
            <v-btn :to="{ name: 'Login' }">Connexion</v-btn>
            <v-btn :to="{ name: 'Register' }">Inscription</v-btn>
          </div>
          <div v-if="userStore.isAuthenticated">
            <v-btn :to="{ name: 'HomePage' }">Accueil</v-btn>
            <v-btn :to="{ name: 'myEvents' }">Mes événements</v-btn>
            <v-btn :to="{ name: 'CreateEvent' }">Créer un événement</v-btn>
            <v-btn :to="{ name: 'Profile' }">{{
              user.pseudo.toUpperCase()
            }}</v-btn>
            <v-btn v-if="isAdmin" :to="{ name: 'AdminAdministration' }"
              >Administration</v-btn
            >
            <v-btn :onclick="disconnected">Déconnexion</v-btn>
          </div>
        </div>
      </div>
    </v-app-bar-title>
    <div>
      <v-app-bar-nav-icon
        v-if="$vuetify.display.mdAndDown"
        @click.stop="drawer = !drawer"
      ></v-app-bar-nav-icon>
    </div>
  </v-app-bar>

  <v-navigation-drawer v-model="drawer" location="right" temporary>
    <v-list v-if="userStore.isAuthenticated === false">
      <v-list-item
        v-for="item in deconnectionItems"
        :key="item.value"
        link
        :to="item.path"
      >
        <v-list-item-title>{{ item.title }}</v-list-item-title>
      </v-list-item>
    </v-list>
    <v-list v-if="userStore.isAuthenticated">
      <v-list-item
        v-for="item in connectionItems"
        :key="item.value"
        link
        :to="item.path"
      >
        <v-list-item-title>{{ item.title }}</v-list-item-title>
      </v-list-item>
      <v-list-item v-if="isAdmin" :to="{ name: 'AdminAdministration' }">
        Administration
      </v-list-item>
      <v-list-item link :onclick="disconnected">Déconnexion</v-list-item>
    </v-list>
  </v-navigation-drawer>
</template>

<script lang="ts">
import { ref, watch, watchEffect } from "vue";
import { useUserStore } from "@/store/user";
import { logout } from "@/services/index";

const userStore = useUserStore();

export default {
  setup() {
    const user = ref<any>(userStore.getUserInfo);
    const isAdmin = ref();
    const drawer = ref(false);
    const deconnectionItems = [
      {
        title: "Connexion",
        value: "login",
        path: "/login",
      },
      {
        title: "S'inscrire",
        value: "register",
        path: "/register",
      },
    ];

    const connectionItems = [
      {
        title: "Accueil",
        value: "events",
        path: "/home",
      },
      {
        title: "Mes événements",
        value: "myEvents",
        path: "/myEvents",
      },

      {
        title: "Créer un événement",
        value: "createEvent",
        path: "/create-event",
      },
      {
        title: `${user.value.pseudo}`,
        value: "profile",
        path: "/profile",
      },
    ];

    watch(
      () => userStore.getUserInfo,
      (newValue) => {
        user.value = newValue;
        connectionItems[3].title = newValue?.pseudo || "";
      }
    );
    watchEffect(() => {
      isAdmin.value = userStore.getCurrentUser?.is_admin;
    });

    const disconnected = async () => {
      await logout();
    };

    return {
      user,
      userStore,
      isAdmin,
      drawer,
      connectionItems,
      deconnectionItems,
      disconnected,
    };
  },
};
</script>
