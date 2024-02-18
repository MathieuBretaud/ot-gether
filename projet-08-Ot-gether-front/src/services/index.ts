import router from "@/router";
import axios from "axios";
import { useUserStore } from "@/store/user";
import { useAppStore } from "@/store/app";
// import { useLocalStorage, useStorage } from "@vueuse/core";

export interface loginProps {
  email: string;
  password: string;
}

export interface signinProps {
  lastname: string;
  firstname: string;
  is_admin: boolean;
  city: string;
  pseudo: string;
  email: string;
  picture: string;
  password: string;
}

export const clientAxios = axios.create({
  baseURL: "http://localhost",
  withCredentials: true,
  withXSRFToken: true,
});

export async function login({
  email,
  password,
}: loginProps): Promise<string | void> {
  try {
    await getCSRF();

    await clientAxios.post("/api/login", {
      email: email,
      password: password,
    });

    const response = await clientAxios.get<Promise<Partial<signinProps>>>(
      "/api/user"
    );

    if (response) {
      const appStore = useAppStore();
      await useUserStore().storeInfo();

      appStore.setUser(response.data);
      router.push({ name: "HomePage" });
    }
  } catch (err: any) {
    console.log("login catch err", err);
    return err.response.data.message;
  }
}

export async function signin({
  firstname,
  lastname,
  city,
  pseudo,
  email,
  picture,
  password,
}: signinProps): Promise<void | string> {
  try {
    await getCSRF();
    await clientAxios.post("/api/register", {
      firstname,
      lastname,
      city,
      pseudo,
      email,
      picture,
      password,
    });

    await clientAxios.post("/api/login", {
      email,
      password,
    });

    const response = await clientAxios.get<Promise<Partial<signinProps>>>(
      "/api/user"
    );

    if (response) {
      const appStore = useAppStore();
      await useUserStore().storeInfo();

      appStore.setUser(response.data);
      router.push({ name: "HomePage" });
    }
  } catch (err: any) {
    console.log("registered catch err", err);
    return err.response.data.message;
  }
}

export async function logout(): Promise<void | string> {
  try {
    const response = await clientAxios.post("/api/logout");

    if (response.status === 204) {
      useUserStore().delete();
      router.push({ name: "Login" });
    }
  } catch (err: any) {
    console.log("Logout catch err", err);
    return err.response.data.message;
  }
}

export async function getCSRF(): Promise<void> {
  await clientAxios.get("/sanctum/csrf-cookie", {});
}
// feature check email already exist for register Form
export async function isEmailUsed(email: string): Promise<boolean> {
  try {
    const response = await clientAxios.get(`/api/check-email?email=${email}`);
    return response.data.isUsed;
  } catch (error) {
    console.error("Erreur lors de la vérification de l'email", error);
    return false;
  }
}

export async function fetchCurrentUser(): Promise<signinProps | null> {
  try {
    const response = await clientAxios.get<Promise<signinProps | null>>(
      "/api/v1/me"
    );
    return response.data;
  } catch (e) {
    return null;
  }
}
