// Composables
import { useUserStore } from "@/store/user";
import { createRouter, createWebHistory } from "vue-router";
import {
  isAdminGuard,
  isAuthenticatedGuard,
  isNotAuthenticatedGuard,
} from "@/guards";
import { initialFetchAdminEvents } from "@/store/admin/adminEventsStore";
import { initialFetchAdminUsers } from "@/store/admin/adminUsersStore";
import { initialFetchAdminCategories } from "@/store/admin/adminCategoriesStore";
import { initialFetchAdminTags } from "@/store/admin/adminTagsStore";

const routes = [
  {
    path: "/",
    component: () => import("@/layouts/default/Default.vue"),
    children: [
      {
        path: "",
        name: "Landing",
        // route level code-splitting
        // this generates a separate chunk (Home-[hash].js) for this route
        // which is lazy-loaded when the route is visited.
        component: () => import("@/views/LandingPage.vue"),
      },
      {
        path: "login",
        name: "Login",
        beforeEnter: [isNotAuthenticatedGuard],
        component: () => import("@/views/LoginPage.vue"),
      },
      {
        path: "register",
        name: "Register",
        beforeEnter: [isNotAuthenticatedGuard],
        component: () => import("@/views/RegisterPage.vue"),
      },
      {
        path: "home",
        name: "HomePage",
        beforeEnter: [isAuthenticatedGuard],
        component: () => import("@/views/HomePage.vue"),
      },
      {
        path: "/profile",
        name: "Profile",
        beforeEnter: [isAuthenticatedGuard],
        component: () => import("@/views/Profile.vue"),
      },
      {
        path: "/events/:id",
        name: "EventDetails",
        beforeEnter: [isAuthenticatedGuard],
        component: () => import("@/views/EventPage.vue"),
      },
      {
        path: "events/update/:id",
        name: "UpdateEvent",
        beforeEnter: [isAuthenticatedGuard],
        component: () => import("@/views/UpdateEvent.vue"),
      },
      {
        path: "create-event",
        name: "CreateEvent",
        beforeEnter: [isAuthenticatedGuard],
        component: () => import("@/views/CreateEventPage.vue"),
      },
      {
        path: "myEvents",
        name: "myEvents",
        beforeEnter: [isAuthenticatedGuard],
        component: () => import("@/views/MyEventsPage.vue"),
      },
      {
        path: "/events/:id/discussion",
        name: "Chat",
        beforeEnter: async (to: any, from: any, next: any) => {
          const userStore = useUserStore();
          await userStore.fetchUserEvents();

          const eventId = parseInt(to.params.id, 10);

          if (userStore.getUserEvents.includes(eventId)) {
            next();
          } else {
            next("/error");
          }
        },
        component: () => import("@/views/Chat.vue"),
      },
      {
        path: "verify-email/:id/:token",
        name: "VerifyEmail",
        beforeEnter: [isAuthenticatedGuard],
        component: () => import("@/views/VerifyEmail.vue"),
      },
      {
        path: "forgot-password",
        name: "ForgotPassword",
        beforeEnter: [isNotAuthenticatedGuard],
        component: () => import("@/views/ForgotPassword.vue"),
      },
      {
        path: "password-reset/:token",
        name: "PasswordReset",
        beforeEnter: [isNotAuthenticatedGuard],
        component: () => import("@/views/PasswordReset.vue"),
      },
      {
        path: "admin/administration",
        name: "AdminAdministration",
        beforeEnter: [
          isAuthenticatedGuard,
          isAdminGuard,
          initialFetchAdminEvents,
          initialFetchAdminUsers,
          initialFetchAdminCategories,
          initialFetchAdminTags,
        ],
        component: () => import("@/views/admin/ViewAdmin.vue"),
        children: [
          {
            path: "/admin/dashboard",
            name: "DashboardAdmin",
            components: {
              dashboard: () => import("@/views/admin/Dashboard.vue"),
            },
          },
          {
            path: "/admin/events",
            name: "EventsAdmin",
            components: {
              dashboard: () => import("@/views/admin/EventList.vue"),
            },
          },
          {
            path: "/admin/users",
            name: "UsersAdmin",
            components: {
              dashboard: () => import("@/views/admin/UserList.vue"),
            },
          },
          {
            path: "/admin/categories",
            name: "CategoriesAdmin",
            components: {
              dashboard: () => import("@/views/admin/CategoryList.vue"),
            },
          },
          {
            path: "/admin/tags",
            name: "tagsAdmin",
            components: {
              dashboard: () => import("@/views/admin/TagList.vue"),
            },
          },
          {
            path: "/admin/reports",
            name: "reportsAdmin",
            components: {
              dashboard: () => import("@/views/admin/ReportList.vue"),
            },
          },
        ],
      },
      {
        path: "/cgu",
        name: "CGU",
        component: () => import("@/views/Cgu.vue"),
      },
      {
        path: "/legal-notice",
        name: "LegalNotice",
        component: () => import("@/views/LegalNotice.vue"),
      },
    ],
  },
  {
    path: "/:notfound(.*)*",
    name: "NotFound",
    component: () => import("@/layouts/default/Custom.vue"),
    // meta: {
    //   layout: import('@/layouts/default/Custom.vue')
    // }
  },
];

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
});

router.beforeEach(async () => {
  const userStore = useUserStore();
  if (!userStore.loaded) {
    await userStore.fetchCurrentUser();
  }
});

export default router;
