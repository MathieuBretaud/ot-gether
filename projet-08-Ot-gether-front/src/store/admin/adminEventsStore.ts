import { defineStore } from "pinia";
import { deleteEvent, fetchEventsAdmin } from "@/services/events";

export const useAdminEvents = defineStore("adminEvent", {
  state: (): any => ({
    events: [],
    isLoading: false,
    loaded: false,
    needRefresh: false,
    currentPage: 1,
    perPage: 10,
    totalPage: 0,
  }),
  actions: {
    async fetchEvents() {
      this.isLoading = true;
      const events = await fetchEventsAdmin();
      if (Array.isArray(events)) {
        this.events = events;
      } else {
        this.events = [events];
      }
      this.isLoading = false;
    },
    async deleteEvent(eventId: string) {
      const eventIndex = this.events.findIndex((p: any) => p.id === eventId);
      if (eventIndex !== -1) {
        await deleteEvent(eventId);
        this.events.splice(eventIndex, 1);
      }
    },
  },
});

export function initialFetchAdminEvents() {
  const adminEventStore = useAdminEvents();
  if (!adminEventStore.loaded || adminEventStore.needRefresh) {
    adminEventStore.fetchEvents();
    adminEventStore.loaded = true;
    if (adminEventStore.needRefresh) {
      adminEventStore.events = [];
      adminEventStore.needRefresh = true;
    }
  }
}
