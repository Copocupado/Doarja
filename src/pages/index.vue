<template>
  <div class="responsive-background pa-5">
    <v-container class="bg-background rounded-lg d-flex flex-column justify-space-between ga-5 pa-5">
      <v-row class="d-flex justify-space-between flex-grow-0">
        <!-- Second column (image) first on small screens -->
        <v-col class="order-1 order-sm-2 d-flex align-center justify-center" cols="12" sm="2">
          <v-img :aspect-ratio="1/1" max-width="300px" src="/src/assets/logo.png" />
        </v-col>

        <!-- First column (text) second on small screens -->
        <v-col class="text-h2 text-primary font-weight-bold d-flex align-center justify-center justify-sm-start order-2 order-sm-1" cols="12" sm="10" style="text-align: center;">
          Bem Vindo a Doarja!
        </v-col>
      </v-row>
      <!--<v-col class="d-flex justify-space-between flex-grow-0">
        <div class="text-h2 text-primary font-weight-bold d-flex justify-center align-center">Bem Vindo a Doarja!</div>
        <div style="width:15%">
          <v-img
            :aspect-ratio="1/1"
            src="/src/assets/logo.png"
          />
        </div>
      </v-col>-->
      <v-col class="text-center text-h5 text-secondary font-weight-medium flex-grow-0">Escolha o que melhor te descreve</v-col>
      <v-row>
        <v-col cols="12" sm="4"><HoverBoxComponent /></v-col>
        <v-col cols="12" sm="4"><HoverBoxComponent icon="mdi-hand-heart-outline" text="Sou uma entidade" /></v-col>
        <v-col cols="12" sm="4"><HoverBoxComponent icon="mdi-account-search" text="Procuro doações" /></v-col>
      </v-row>
    </v-container>
  </div>
</template>
<script lang="ts" setup>
  import HoverBoxComponent from '@/components/Hover-box/hoverBoxComponent.vue'

  //
</script>
<style>
.responsive-background {
  width: 100vw; /* Ensure the div takes full width */
  min-height: 100vh; /* Adjust the height as needed */
  background: url('/src/assets/background-index.png');
  background-size: cover; /* Ensure the background covers the element */
  background-position: center; /* Center the background image */
  display: flex;
  align-items: center;
  justify-content: center;
}

@media (max-width: 600px) { /* Adjust max-width for small screens */
  .responsive-background {
    background: linear-gradient(45deg, #5d4a66, #0d5c63);
  }
}
</style>
