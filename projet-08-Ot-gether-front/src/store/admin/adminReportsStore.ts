import { defineStore } from "pinia";
import { fetchEventsReportsAdmin } from "@/services/events";

export const useAdminReportsStore = defineStore("adminEventsReports", {
  state: (): any => ({
    eventsReports: [],
    isLoading: false,
    loaded: false,
    needRefresh: false,
    currentPage: 1,
    perPage: 1000,
    totalPage: 0,
  }),
  actions: {
    async fetchEvents() {
      this.isLoading = true;
      const events = await fetchEventsReportsAdmin();
      if (Array.isArray(events)) {
        this.eventsReports = events;
      } else {
        this.eventsReports = [events];
      }
      this.isLoading = false;
    },
  },
});

export function initialFetchAdminEvents() {
  const adminEventStore = useAdminReportsStore();
  if (!adminEventStore.loaded) {
    adminEventStore.fetchEvents();
    adminEventStore.loaded = true;
  }
}
