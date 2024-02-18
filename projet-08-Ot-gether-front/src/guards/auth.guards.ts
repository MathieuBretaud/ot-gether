import { useUserStore } from "@/store/user";

export function isAuthenticatedGuard() {
  const userStore = useUserStore();
  if (!userStore.isAuthenticated) {
    return "/login";
  }
}

export function isNotAuthenticatedGuard() {
  const userStore = useUserStore();
  if (userStore.isAuthenticated) {
    return "/";
  }
}

export function isAdminGuard() {
  const userStore = useUserStore();
  if (!userStore.getIsAdmin) {
    return "/";
  }
}
