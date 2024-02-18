import { defineStore } from "pinia";

export const useAppStore = defineStore("app", {
  state: () => ({
    user: null,
  }),
  actions: {
    setUser(userData: any) {
      this.user = userData;
    },
  },
  getters: {
    getUser(state: any) {
      return state.user;
    },
  },
});
