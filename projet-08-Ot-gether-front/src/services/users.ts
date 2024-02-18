import { useAdminUsers } from "@/store/admin/adminUsersStore";
import { clientAxios, getCSRF } from ".";
import { signinProps } from "@/services/index";

export interface userProps {
  id: number;
  isAdmin: boolean;
  lastname: string;
  firstname: string;
  city: string;
  pseudo: string;
  email: string;
  email_verified_at: string;
  picture: string;
  created_at: string;
  updated_at: string;
  deleted_at: string;
}

export type UpdatedFields = {
  lastname?: string;
  firstname?: string;
  is_admin?: boolean;
  city?: string;
  pseudo?: string;
  email?: string;
  password?: string;
  confirmPassword?: string;
};

export async function getUsers(): Promise<userProps[] | undefined> {
  const adminUserStore = useAdminUsers();
  try {
    const response = await clientAxios.get(
      `/api/admin/admin-users?perpage=${adminUserStore.perPage}&page=${adminUserStore.currentPage}`
    );

    if (response.data) {
      adminUserStore.users = response.data.data;
      adminUserStore.totalPage = response.data.last_page;
      return response.data.data;
    }
  } catch (error) {
    console.error("Erreur lors de la récupération des utilisateurs :", error);

  }
}

export async function deleteUser(id: string) {
  try {
    const response = await clientAxios.delete(`/api/v1/users/${id}`);

    if (response.data) {
      return response.data;
    }
  } catch (error) {
    console.error("Erreur lors de la suppression d'un utilisateur :", error);
  }
}

export async function updateUser(userInfo: signinProps, id: number): Promise<any> {
  try {
    await getCSRF();
    const res = await clientAxios.put(`/api/v1/users/${id}`, userInfo);
    return res
  } catch (err) {
    console.error("Erreur lors de l'update d'un utilisateur :", err)
  }
}

