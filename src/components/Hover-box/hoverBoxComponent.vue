<template>
  <v-container class="hover-effect-col rounded-lg d-flex flex-column justify-center align-center" style="height: 100%;">
    <v-icon
      class="fill-height"
      :icon="icon"
      style="font-size: min(10vw, 120vh);"
    />
    <div class="text-sm-h5 text-body-1 text-capitalize font-weight-bold text-secondary" style="text-align: center;">{{ text }}</div>
  </v-container>
</template>

<script>
  export default {
    name: 'IconCard',
    props: {
      icon: {
        type: String,
        required: true,
        default: 'mdi-shield-account-outline', // Default icon if none is provided
      },
      text: {
        type: String,
        required: true,
        default: 'Sou administrador', // Default text if none is provided
      },
    },
  }
</script>

<style lang="scss">
@import "style.scss"; /* Adjust the path if needed */
</style>
