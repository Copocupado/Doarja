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

      <v-list-item v-if="!(admin.ativo === 'Ativado')" @click="() => { confirmationDialog = true; methodToExecute = 'Ativar'; }">
        <template #prepend>
          <v-icon color="success">mdi-account-check</v-icon>
        </template>
        <template #title>
          <span class="text-success">Ativar</span>
        </template>
      </v-list-item>

      <v-list-item v-if="admin.ativo === 'Ativado'" @click="() => { confirmationDialog = true; methodToExecute = 'Desativar'; }">
        <template #prepend>
          <v-icon color="info">mdi-account-cancel</v-icon>
        </template>
        <template #title>
          <span class="text-info">Desativar</span>
        </template>
      </v-list-item>
    </v-list>
  </v-menu>
  <ConfirmationComponent :dialog="confirmationDialog" @send-confirmation-response="getConfirmationResponse" />
</template>

<script lang="ts" setup>
  import { ref } from 'vue'
  import ConfirmationComponent from '../confirmationComponent.vue'

  // Define emitted events
  const emit = defineEmits(['delete-admin', 'update-admin'])

  // Define props
  const props = defineProps<{
    admin: object;
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
        emit('delete-admin', props.admin)
        break
      case 'Ativar':
        emit('update-admin', props.admin, true)
        break
      case 'Desativar':
        emit('update-admin', props.admin, false)
        break
    }
  }
</script>
