<template>
  <v-dialog max-width="400px" :model-value="dialog">
    <v-card class="bg-background">
      <v-card-title class="headline">
        Confirmação
      </v-card-title>

      <v-card-text>
        Tem certeza que deseja continuar?
      </v-card-text>

      <v-card-actions class="d-flex justify-space-between">
        <v-btn text @click="sendConfirmationResponse(false)">Cancelar</v-btn>
        <v-spacer />
        <v-btn color="error" text @click="sendConfirmationResponse(true)">Confirmar</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script lang="ts" setup>
  import { defineProps, defineEmits } from 'vue'

  // Define props
  const props = defineProps<{
    dialog: boolean
  }>()

  // Define emits
  // eslint-disable-next-line func-call-spacing
  const emit = defineEmits<{
    (event: 'send-confirmation-response', confirmation: boolean): void
  }>()

  // Method to send confirmation response
  function sendConfirmationResponse (confirmation: boolean) {
    emit('send-confirmation-response', confirmation)
  }
</script>
