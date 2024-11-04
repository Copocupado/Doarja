<template>
  <div class="responsive-background pa-5">
    <v-container class="bg-background rounded-lg d-flex flex-column justify-space-between ga-5 pa-5">
      <v-row class="d-flex justify-space-between flex-grow-0">
        <v-col class="order-1 order-sm-2 d-flex align-center justify-center" cols="12" sm="2">
          <v-img :aspect-ratio="1/1" max-width="300px" src="/src/assets/logo.png" />
        </v-col>

        <v-col class="text-h2 text-primary font-weight-bold d-flex align-center justify-center justify-sm-start order-2 order-sm-1" cols="12" sm="10" style="text-align: center;">
          Bem Vindo a Doarja!
        </v-col>
      </v-row>
      <v-col class="text-center text-h5 text-secondary font-weight-medium flex-grow-0">Escolha o que melhor te descreve</v-col>
      <v-row>
        <v-col cols="12" sm="4"><HoverBoxComponent :click="()=>goTo('administrador')" /></v-col>
        <v-col cols="12" sm="4"><HoverBoxComponent :click="()=>goTo('entidade')" icon="mdi-hand-heart-outline" text="Sou entidade" /></v-col>
        <v-col cols="12" sm="4"><HoverBoxComponent :click="()=>goTo('search')" icon="mdi-account-search" text="Procuro doações" /></v-col>
      </v-row>
    </v-container>
    <Snackbar :snackbar="snackbar" :snackbar-color="snackbarColor" :snackbar-text="snackbarText" @close="snackbar = false" />
  </div>
</template>
<script lang="ts" setup>
  import HoverBoxComponent from '@/components/Hover-box/hoverBoxComponent.vue'
  import router from '@/router'
  import { onMounted } from 'vue'
  import { bootDatabase } from '@/models/utility_classes'

  const snackbar = ref(false)
  let snackbarText = ''
  let snackbarColor = ''

  function goTo (value: String) {
    switch (value) {
      case 'administrador':
        router.push('/Admin/login')
        break
      case 'entidade':
        router.push('/Entidade/login')
        break
      default:
        router.push('/search')
    }
  }

  function showSnackbar (response: object) {
    snackbarColor = response.success ? 'success' : 'error'
    snackbarText = response.message
    snackbar.value = true
  }

  onMounted(async () => {
    const response = await bootDatabase()
    showSnackbar(response)
  })

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
