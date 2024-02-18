import { defineStore } from "pinia";
import { clientAxios } from "@/services";
import { useStorage } from "@vueuse/core";
const STORE_NAME = "USER_INFO";
import { fetchCurrentUser } from "@/services/index";
import type { signinProps } from "@/services/index";

export const useUserStore = defineStore({
  id: "user",

  state: () => ({
    userinfo: useStorage(STORE_NAME, {
      id: null,
      pseudo: null,
      picture: null,
    }),
    currentUser: null as signinProps | null,
    loaded: false,
    userEvents: [] as any,
  }),
  getters: {
    getUserInfo(state) {
      return state.userinfo;
    },
    getUserEvents(state) {
      return state.userEvents;
    },
    getCurrentUser(state) {
      return state.currentUser;
    },
    getIsAdmin(state) {
      return state.currentUser?.is_admin;
    },
    isAuthenticated(state): boolean | null {
      if (state.currentUser) {
        return true;
      } else if (!state.currentUser && state.loaded) {
        return false;
      } else {
        return null;
      }
    },
  },
  actions: {
    updateUser(partialUser: any) {
      this.userinfo = {
        ...this.userinfo,
        ...partialUser,
      };
    },

    delete() {
      localStorage.setItem(
        STORE_NAME,
        JSON.stringify({
          id: null,
          pseudo: null,
          picture: null,
        })
      );

      this.$reset();
    },

    async storeInfo() {
      const { data: userInfo } = await clientAxios.get("/api/user");

      localStorage.setItem(
        "USER_INFO",
        JSON.stringify({
          id: userInfo.id,
          pseudo: userInfo.pseudo,
          picture: userInfo.picture,
        })
      );

      this.$reset();
    },
    async fetchCurrentUser() {
      this.currentUser = await fetchCurrentUser();
      this.loaded = true;
    },
    async fetchUserEvents() {
      const response = await clientAxios.get("/api/v1/user/myevents");
      if (response.data[0] && Array.isArray(response.data[0])) {
        this.userEvents = response.data[0].map((event) => event.id);
      } else {
        console.error("Format de données inattendu:", response.data);
        this.userEvents = [];
      }
    },
  },
});
