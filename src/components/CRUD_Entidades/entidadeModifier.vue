<template>
  <v-dialog
    :model-value="modelValue"
    max-width="500px"
    @click:outside="onDismiss"
  >
    <InfoEntidade @update-entidade="onSubmit" :currently-authed-entidade="item" />
  </v-dialog>
</template>

  <script lang="ts" setup>
    import { Entidade } from '@/models/Entidade/entidade'

    const props = defineProps<{
      modelValue: boolean
      item: Entidade
    }>()

    const emit = defineEmits<{
      (e: 'updated', item: object): void
      (e: 'dismissed'): void
    }>()

    function onSubmit(item: object) {
      emit('updated', item)
    }

    function onDismiss() {
      console.log('Dialog dismissed')
      emit('dismissed')
    }
  </script>
