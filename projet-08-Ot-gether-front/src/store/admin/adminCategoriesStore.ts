import { deleteCategory, getCategories } from "@/services/categories";
import { defineStore } from "pinia";

export const useAdminCategories = defineStore("adminCategories", {
  state: (): any => ({
    categories: [],
    isLoading: false,
    loaded: false,
  }),
  actions: {
    async fetchCategories() {
      this.isLoading = true;
      const Categories = await getCategories();
      if (Array.isArray(Categories)) {
        this.categories = Categories;
      } else {
        this.categories = [Categories];
      }
      this.isLoading = false;
    },
    async deleteCategory(userId: string) {
      const eventIndex = this.categories.findIndex((p: any) => p.id === userId);
      if (eventIndex !== -1) {
        await deleteCategory(userId);
        this.users.splice(eventIndex, 1);
      }
    },
  },
});

export function initialFetchAdminCategories() {
  const adminUserStore = useAdminCategories();
  if (!adminUserStore.loaded) {
    adminUserStore.fetchCategories();
    adminUserStore.loaded = true;
  }
}
