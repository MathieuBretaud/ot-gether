import { deleteUser, getUsers } from "@/services/users";
import { defineStore } from "pinia";

export const useAdminUsers = defineStore("adminUsers", {
  state: (): any => ({
    users: [],
    isLoading: false,
    loaded: false,
    currentPage: 1,
    perPage: 10,
    totalPage: 0,
  }),
  actions: {
    async fetchUsers() {
      this.isLoading = true;
      const users = await getUsers();
      if (Array.isArray(users)) {
        this.users = users;
      } else {
        this.users = [users];
      }
      this.isLoading = false;
    },
    async deleteUser(userId: string) {
      const eventIndex = this.users.findIndex((p: any) => p.id === userId);
      if (eventIndex !== -1) {
        await deleteUser(userId);
        this.users.splice(eventIndex, 1);
      }
    },
  },
});

export function initialFetchAdminUsers() {
  const adminUserStore = useAdminUsers();
  if (!adminUserStore.loaded) {
    adminUserStore.fetchUsers();
    adminUserStore.loaded = true;
  }
}
