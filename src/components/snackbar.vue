<template>
  <v-snackbar
    class="d-flex justify-center align-start"
    :color="snackbarColor"
    :model-value="snackbar"
    multi-line
    rounded="pill"
    :timeout="timeout"
    variant="tonal"
  >
    <v-container class="d-flex justify-center">
      {{ snackbarText }}
    </v-container>
  </v-snackbar>
</template>

<script lang="ts" setup>
  import { defineEmits, defineProps, watch } from 'vue'

  const props = defineProps<{
    snackbar: boolean
    snackbarColor: string
    snackbarText: string
    timeout?: number
  }>()

  // eslint-disable-next-line func-call-spacing
  const emit = defineEmits<{
    (e: 'close'): void
  }>()

  const timeout = props.timeout || 5000

  watch(() => props.snackbar, newVal => {
    if (newVal) {
      setTimeout(() => {
        emit('close')
      }, timeout)
    }
  })
</script>
