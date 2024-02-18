import { clientAxios, getCSRF } from ".";
import { useAdminEvents } from "@/store/admin/adminEventsStore";
import { useAdminReportsStore } from "@/store/admin/adminReportsStore";

export interface eventProps {
  event_category_id: number;
  creator_id: number;
  title: string;
  description: string;
  address: string | null;
  city: string | null;
  region: string | null;
  is_IRL: boolean;
  participant_min: number;
  participant_max: number;
  start_date: string;
  end_date: string;
}

export async function postEvents(values: eventProps): Promise<any> {
  try {
    await getCSRF();
    const res = await clientAxios.post("/api/v1/events", values);
    return res;
  } catch (err: any) {
    console.log("postEvents catch err", err);
    return err.response.data.message;
  }
}

export async function fetchEventsAdmin() {
  const adminEventStore = useAdminEvents();
  try {
    const response = await clientAxios.get(
      `/api/admin/admin-events?perpage=${adminEventStore.perPage}&page=${adminEventStore.currentPage}`
    );

    if (response.data) {
      adminEventStore.events = response.data.data;
      adminEventStore.totalPage = response.data.last_page;
      return response.data.data;
    }
  } catch (error) {
    console.error("Erreur lors de la récupération des événements :", error);
  }
}

export async function fetchEventsReportsAdmin() {
  const adminEventsReports = useAdminReportsStore();
  try {
    const response = await clientAxios.get(
      `/api/admin/admin-events?perpage=${adminEventsReports.perPage}&page=${adminEventsReports.currentPage}`
    );

    if (response.data) {
      adminEventsReports.events = response.data.data;
      adminEventsReports.totalPage = response.data.last_page;
      return response.data.data;
    }
  } catch (error) {
    console.error("Erreur lors de la récupération des événements :", error);
  }
}

export async function deleteEvent(id: string) {
  try {
    const response = await clientAxios.delete(`/api/v1/events/${id}`);

    if (response.data) {
      return response.data;
    }
  } catch (error) {
    console.error("Erreur lors de la suppression de l'événement :", error);
  }
}
export async function updateEvent(
  values: eventProps,
  id: number
): Promise<any> {
  try {
    await getCSRF();
    const res = await clientAxios.put(`/api/v1/events/${id}`, values);
    return res;
  } catch (err: any) {
    console.log("updateEvent catch err", err);
    return err.response.data.message;
  }
}

export const getEvents = async ({
  perPage,
  currentPage,
  events,
  totalPage,
}: any) => {
  try {
    const response = await clientAxios.get(
      `/api/v1/events?perpage=${perPage.value}&page=${currentPage.value}`
    );

    if (response.data) {
      events.value = response.data.data;
      totalPage.value = response.data.last_page;
    }
  } catch (error) {
    console.error("Erreur lors de la récupération des événements :", error);
  }
};
