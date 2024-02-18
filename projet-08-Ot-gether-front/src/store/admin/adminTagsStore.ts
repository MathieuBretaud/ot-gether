import { deleteTag, getTags } from "@/services/tags";
import { defineStore } from "pinia";

export const useAdminTags = defineStore("adminTags", {
  state: (): any => ({
    tags: [],
    isLoading: false,
    loaded: false,
  }),
  actions: {
    async fetchTags() {
      this.isLoading = true;
      const Tags = await getTags();
      if (Array.isArray(Tags)) {
        this.tags = Tags;
      } else {
        this.tags = [Tags];
      }
      this.isLoading = false;
    },
    async deleteTag(tagId: string) {
      const tagIndex = this.tags.findIndex((p: any) => p.id === tagId);
      if (tagIndex !== -1) {
        await deleteTag(tagId);
        this.tags.splice(tagIndex, 1);
      }
    },
  },
});

export function initialFetchAdminTags() {
  const adminTagStore = useAdminTags();
  if (!adminTagStore.loaded) {
    adminTagStore.fetchTags();
    adminTagStore.loaded = true;
  }
}
