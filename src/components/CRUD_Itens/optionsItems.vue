<template>
  <v-menu>
    <template #activator="{ props }">
      <v-btn icon="mdi-dots-vertical" variant="text" v-bind="props" />
    </template>

    <v-list>
      <v-list-item @click="() => { confirmationDialog = true; methodToExecute = 'Excluir'; }">
        <template #prepend>
          <v-icon color="error">mdi-delete</v-icon>
        </template>
        <template #title>
          <span class="text-error">Excluir</span>
        </template>
      </v-list-item>
      <v-list-item @click="() => { methodToExecute = 'Editar'; getConfirmationResponse(true) }">
        <template #prepend>
          <v-icon color="info">mdi-pencil</v-icon>
        </template>
        <template #title>
          <span class="text-info">Editar</span>
        </template>
      </v-list-item>
    </v-list>
  </v-menu>
  <ConfirmationComponent :dialog="confirmationDialog" @send-confirmation-response="getConfirmationResponse" />
</template>

<script lang="ts" setup>
  import { ref } from 'vue'
  import ConfirmationComponent from '../confirmationComponent.vue'
  import { Item } from '@/models/Itens/itens'

  // Define emitted events
  const emit = defineEmits(['delete', 'update'])

  // Define props
  const props = defineProps<{
    item: Item;
  }>()

  // Reactive state
  const confirmationDialog = ref(false)
  const methodToExecute = ref<string | null>(null)

  // Methods
  function getConfirmationResponse (response: boolean) {
    confirmationDialog.value = false
    if (!response) return

    switch (methodToExecute.value) {
      case 'Excluir':
        emit('delete', props.item)
        break
      case 'Editar':
        emit('update', props.item)
        break
    }
  }
</script>
