import { clientAxios, getCSRF } from ".";

export interface tagsProps {
  id: number;
  name: string;
}

export async function getTags(): Promise<tagsProps | undefined> {
  try {
    const response = await clientAxios.get(`/api/admin/admin-tags`);

    if (response.data) {
      return response.data;
    }
  } catch (error) {
    console.error("Erreur lors de la récupération des tags :", error);
  }
}
// export async function postCategory(
//     values: categoryProps
// ): Promise<any> {
//   try {
//     const res = await clientAxios.post("/api/v1/events", values);
//     return res;
//   } catch (err: any) {
//     console.log("postEvents catch err", err);
//     return err.response.data.message;
//   }
// }
//
export async function deleteTag(id: string) {
  try {
    const response = await clientAxios.delete(`/api/v1/event-tags/${id}`);

    if (response.data) {
      return response.data;
    }
  } catch (error) {
    console.error("Erreur lors de la suppression de l'événement :", error);
  }
}
//
// export const getEvents = async ({
//   perPage,
//   currentPage,
//   events,
//   totalPage,
// }: any) => {
//   try {
//     const response = await clientAxios.get(
//       `/api/v1/events?perpage=${perPage.value}&page=${currentPage.value}`
//     );
//
//     if (response.data) {
//       events.value = response.data.data;
//       totalPage.value = response.data.last_page;
//     }
//   } catch (error) {
//     console.error("Erreur lors de la récupération des événements :", error);
//   }
// };
