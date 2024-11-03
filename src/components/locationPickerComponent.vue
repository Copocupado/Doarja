<template>
  <v-text-field
    ref="autocompleteInput"
    class="text-primary"
    clearable
    color="primary"
    label="Digite um endereço"
    :model-value="address"
    :rules="addressRules"
    variant="outlined"
  >
    <template #prepend-inner>
      <v-icon color="primary">mdi-map-marker</v-icon>
    </template>
  </v-text-field>
</template>

<script lang="ts" setup>
  import { onMounted, ref } from 'vue'

  const autocompleteInput = ref(null)

  onMounted(() => {
    const autocomplete = new google.maps.places.Autocomplete(autocompleteInput.value, {
      types: ['address'],
      componentRestrictions: { country: 'BR' }, // Since you're using pt-BR
    })

    // Listen for place selection
    autocomplete.addListener('place_changed', () => {
      const place = autocomplete.getPlace()
      emit('place-changed', place.formatted_address)
    })
  })

  // eslint-disable-next-line func-call-spacing
  const emit = defineEmits<{
    (e: 'place-changed', place: string): void
  }>()

  defineProps<{
    address: string,
  }>()

  const addressRules = [
    (value: string) => !!value || 'O endereço é obrigatório.', // Required
    (value: string) => (value.length >= 5) || 'O endereço deve ter pelo menos 5 caracteres.', // Minimum length
    (value: string) => (value.length <= 100) || 'O endereço deve ter no máximo 100 caracteres.', // Maximum length
  ]
</script>

<style>
</style>
