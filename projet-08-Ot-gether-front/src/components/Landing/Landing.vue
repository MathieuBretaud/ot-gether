<template>
  <section
    class="mt-16 md:pb-24 relative overflow-hidden container flex flex-col md:flex-row"
  >
    <!-- Hero Text -->
    <div class="md:max-w-[600px] lg:max-w-[657px] mb-11">
      <h1 class="text-l lg:text-xl text-navyBlue mb-6">
        Des évenements pour partager de bons moments.
      </h1>
      <p class="text-grey text-body-s mb-6 md:mb-10">
        Quels que soient tes centres d'intérêts,<br />
        crée ou rejoins un événement sur Ot'gether. <br />
        Viens découvrir les événements proches de chez toi. C'est gratuit !
      </p>
      <router-link
        class="btn bg-gradientOrangePink hover:bg-gradientOrangePinkHover focus:bg-gradientOrangePinkHover"
        :to="{ name: 'Register' }"
        >Je m'inscris</router-link
      >
    </div>
    <!-- Hero Images -->
    <div class="">
      <img
        src="@/assets/pictures/image-hero.jpg"
        alt="Description de l'image"
      />
    </div>
  </section>
  <section class="bg-gradientBg mt-20 pb-20">
    <div
      class="container flex flex-col gap-12 md:grid md:grid-cols-2 md:gap-x-2.5 lg:grid-cols-3 lg:gap-x-8"
    >
      <router-link
        :to="{ name: 'HomePage' }"
        class="cursor-pointer card-accent md:card-accent bg-gradientOrangePink hover:bg-gradientOrangePinkHover text-white"
      >
        <h4 class="text-[1.5rem] lg:text-[2rem] font-bold">
          Découvrez toutes les catégories d'évènements !
        </h4>
      </router-link>

      <div class="card relative flex flex-col justify-between">
        <!-- <img
          class="absolute inset-0 -top-6 left-6"
          src="#"
          aria-label="hidden"
        /> -->
        <h3 class="text-s mb-4">Jeux-vidéos</h3>
        <div class="text-body-m text-grey mb-6">
          Plongez dans l'univers captivant des jeux-vidéos. Découvrez des mondes
          virtuels étonnants et affûtez vos compétences en affrontant des
          joueurs du monde entier.
        </div>
      </div>

      <div class="card relative flex flex-col justify-between">
        <h3 class="text-s mb-4">Jeux de société</h3>
        <div class="text-body-m text-grey mb-6">
          Rejoignez l'aventure des jeux de société classiques et modernes.
          Stimulez votre esprit stratégique et partagez des moments inoubliables
          avec vos amis et votre famille.
        </div>
      </div>
      <div class="card relative flex flex-col justify-between">
        <h3 class="text-s mb-4">Danse</h3>
        <div class="text-body-m text-grey mb-6">
          Exprimez-vous à travers l'art de la danse. Apprenez de nouveaux
          styles, maîtrisez les pas et laissez la musique guider vos mouvements.
        </div>
      </div>

      <div class="card relative flex flex-col justify-between">
        <h3 class="text-s mb-4">Apéro Party</h3>
        <div class="text-body-m text-grey mb-6">
          Découvrez l'art de l'apéritif. Savourez des cocktails exquis et des
          amuse-bouches délicieux dans une ambiance conviviale et festive.
        </div>
      </div>

      <div class="card relative flex flex-col justify-between">
        <h3 class="text-s mb-4">Restaurant</h3>
        <div class="text-body-m text-grey mb-6">
          Explorez le monde culinaire à travers une sélection de restaurants
          exceptionnels. Goûtez à des plats raffinés et vivez une expérience
          gastronomique inoubliable.
        </div>
      </div>
    </div>
  </section>
</template>

<script lang="ts"></script>

<!-- <v-container class="fill-height">
  <v-responsive class="align-center text-center fill-height">
    <LandingCard description="Test" image="src/assets/pictures/gaming.jpg" />
    <LandingCard description="Bouh" image="src/assets/pictures/gaming.jpg" />
    <LandingCard description="Card" image="src/assets/pictures/voyages.jpg" />
  </v-responsive>
</v-container> -->


