<template>
  <div class="event-chat">
    <v-list class="messages" style="border-radius: 5px" dense>
      <v-list-item-group>
        <v-list-item v-for="message in messages" :key="message.id">
          <v-list-item-content>
            <v-list-item-title>
              <strong>{{ message.sender.pseudo }}</strong
              >: {{ message.content }}
            </v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <span ref="backScrollTarget"></span>
      </v-list-item-group>
    </v-list>
    <div class="form-container">
      <form @submit.prevent="sendMessage" class="message-form">
        <v-row align="center" no-gutters>
          <v-col cols="10">
            <v-textarea
              v-model="newMessage"
              placeholder="Écrire un message ..."
              auto-grow
              rows="1"
              no-resize
              outlined
              @keydown.enter.prevent="sendMessage"
              style="border-radius: 5px"
            ></v-textarea>
          </v-col>
          <v-col cols="2">
            <v-btn class="ml-2" type="submit" color="primary">Envoyer</v-btn>
          </v-col>
        </v-row>
      </form>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from "vue";
import { clientAxios } from "@/services/index";
import echo from "@/main.ts";

export default {
  props: {
    eventId: {
      type: String,
      required: true,
    },
  },
  setup(props) {
    const messages = ref([]);
    const newMessage = ref("");
    const backScrollTarget = ref(null);

    const sendMessage = async () => {
      await clientAxios
        .post(`/api/v1/events/${props.eventId}/messages`, {
          content: newMessage.value,
        })
        .then(() => {
          newMessage.value = "";
          if (backScrollTarget.value) {
            backScrollTarget.value.scrollIntoView({ behavior: "smooth" });
          }
        })
        .catch((error) => {
          console.error("Erreur lors de l'envoi du message :", error);
        });
    };

    onMounted(async () => {
      try {
        const response = await clientAxios.get(
          `/api/v1/events/${props.eventId}/messages`
        );
        messages.value = response.data;
        // Souscription au canal de Pusher
        const channel = echo.channel(`events.${props.eventId}.messages`);
        channel.listen(".message.posted", (data) => {
          messages.value.push(data.message);
        });
      } catch (error) {
        console.error("Erreur lors de la récupération des messages :", error);
      }
    });

    return { messages, sendMessage, newMessage, backScrollTarget };
  },
};
</script>

<style>
.event-chat {
  display: flex;
  flex-direction: column;
  height: 60vh;
}
.messages {
  margin-bottom: 1rem;
  flex: 1;
  overflow-y: auto;
}

.form-container {
  flex-shrink: 0;
}

.message-form {
  width: 100%;
  margin-bottom: 50px;
}
</style>
