<template>
  <v-card class="mx-auto" max-width="600">
    <v-toolbar color="secondary">
      <h1 class="ml-2">Les reports</h1>
      <v-spacer></v-spacer>
    </v-toolbar>
    <v-expansion-panels multiple>
      <v-expansion-panel
        v-for="event in adminEventsReports.eventsReports.filter(
          (event: any) => event.reports.length > 0
        )"
        :key="event.id"
      >
        <v-expansion-panel-title>
          <v-row no-gutters>
            <v-col cols="1" class="d-flex justify-start">
              <v-badge
                color="error"
                :content="event.reports.length"
                inline
                v-if="event.reports.length > 0"
              ></v-badge>
            </v-col>
            <v-col cols="4" class="d-flex justify-start">
              {{ event.title }}
            </v-col>
          </v-row>
        </v-expansion-panel-title>
        <v-expansion-panel-text>
          <v-table density="compact">
            <thead>
              <tr>
                <th class="text-left">User ID</th>
                <th class="text-left">Message</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="report in event.reports" :key="report.index">
                <td>{{ report.user_id }}</td>
                <td>{{ report.message }}</td>
              </tr>
            </tbody>
          </v-table>
          <!-- <v-row justify="space-between" no-gutters>
          <v-col cols="3"> user id: {{ report.user_id }}</v-col>
          <v-col cols="6"> message: {{ report.message }}</v-col>
        </v-row> -->
        </v-expansion-panel-text>
      </v-expansion-panel>
    </v-expansion-panels>
  </v-card>
</template>

<script lang="ts" setup>
import { useAdminReportsStore } from "@/store/admin/adminReportsStore";

const adminEventsReports = useAdminReportsStore();
adminEventsReports.fetchEvents();
</script>
