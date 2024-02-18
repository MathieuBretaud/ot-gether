<template>
  <div class="w-3/4 mx-auto space-y-4 py-8">
    <h2 class="text-center underline text-2xl">Mentions Légales</h2>
    <p>
      Ot'Gether est un projet d'événements organisés autour de plusieurs thématiques. Ce site est géré par <strong>Mathieu
        Bretaud, Aline Massard, Casanova David et Grégory Baptiste.</strong>
    </p>

    <h3 class="underline font-bold">Responsable éditorial</h3>
    <p>
      Ot'Gether<br>
      otgether@gmail.com<br>
      01 02 03 04 05
    </p>

    <h3 class="underline font-bold">Propriété intellectuelle</h3>
    <p>
      Le contenu du site Ot'Gether, y compris les textes, images, vidéos, et autres éléments multimédias, est la propriété
      intellectuelle de Ot'Gether et est protégé par les lois sur les droits d'auteur.
    </p>
  </div>
</template>

<script lang="ts" setup>
import { onMounted } from 'vue';

onMounted(() => {
  document.title = "Mentions Légales";
});
</script>
