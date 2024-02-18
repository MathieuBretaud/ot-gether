/**
 * main.ts
 *
 * Bootstraps Vuetify and other plugins then mounts the App`
 */

// Plugins
import { registerPlugins } from "@/plugins";
//import { VueQueryPlugin } from "vue-query";

// Components
import App from "./App.vue";

// Composables
import { createApp } from "vue";

//css styles
import "@/styles/style.css";

import Echo from "laravel-echo";
import Pusher from "pusher-js";

Pusher.logToConsole = false;

const echo = new Echo({
  broadcaster: "pusher",
  key: "5f4abdbf01571adcb016", // Mettre en variable publique Pusher
  cluster: "eu", // Mettre en variable cluster Pusher
  encrypted: true,
});

export default echo;

const app = createApp(App);

registerPlugins(app);
//app.use(VueQueryPlugin);

app.mount("#app");
