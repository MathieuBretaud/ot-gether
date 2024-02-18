<template>
  <v-footer
    class="bg-red-lighten-1 text-center d-flex flex-column"
    :class="$vuetify.display.mdAndUp ? 'max-h-56' : 'h-30'"
  >
    <div class="pt-0">
      Ot'gether est un projet de groupe. Ce projet, nous permet de créer des
      événements autours de plusieurs thèmes, comme les jeux-vidéos ou encore
      des événements IRL (In Real Life) autour des sujets de votre choix. Le
      principe est de mettre en avant vos événements afin d'attirer des
      participants et ainsi rencontrer des personnes avec les mêmes centres
      d'intérêts.
    </div>

    <div
      class="d-flex w-50 my-4 justify-space-evenly"
      :class="$vuetify.display.smAndDown ? 'flex flex-col' : ''"
    >
      <Button link="/" label="A propos" />
      <Button link="/cgu" label="CGU" />
      <Button link="/legal-notice" label="Mentions légales" />
    </div>
    <div>{{ new Date().getFullYear() }} — <strong>Ot'gether</strong></div>
  </v-footer>
</template>

<script lang="ts" setup>
import Button from "@/components/Generic/Button.vue";
</script>
