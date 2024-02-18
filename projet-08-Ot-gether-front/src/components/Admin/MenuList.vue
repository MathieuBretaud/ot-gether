<template>
  <v-col cols="3" md="3">
    <v-card class="mx-auto" max-width="300">
      <v-list>
        <v-list-item
          v-for="menu in menuItems"
          :key="menu.value"
          :to="menu.path"
        >
          <v-list-item-title>{{ menu.title }}</v-list-item-title>
        </v-list-item>
      </v-list>
    </v-card>
  </v-col>
</template>

<script lang="ts" setup>
const menuItems = [
  {
    title: "Accueil",
    value: "DashboardAdmin",
    path: "/admin/dashboard",
  },
  {
    title: "Les utilisateurs",
    value: "UsersAdmin",
    path: "/admin/users",
  },
  {
    title: "Les événements",
    value: "EventsAdmin",
    path: "/admin/events",
  },
  {
    title: "Les catégories",
    value: "CategoriesAdmin",
    path: "/admin/categories",
  },
  {
    title: "Les tags",
    value: "TagsAdmin",
    path: "/admin/tags",
  },
  {
    title: "Les reports",
    value: "ReportsAdmin",
    path: "/admin/reports",
  },
];
</script>
