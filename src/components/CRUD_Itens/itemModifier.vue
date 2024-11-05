<template>
  <v-dialog max-width="400px" :model-value="shouldDisplay" persistent>
    <v-card
      class="bg-background text-secondary text-center"
      :title="item == null ? 'Adicionar novo item' : 'Item para editar'"
    >
      <v-card-text>
        <v-form
          v-model="form"
          class="d-flex flex-column ga-3"
        >
          <TextFieldComponent
            v-model="descricao"
            icon="mdi-pencil"
            label="Descrição"
            placeholder="etc..."
            :rules="rules.descriptionRules"
            @update-model-value="(newValue: string) => descricao = newValue"
          />
          <TextFieldComponent
            v-model="quantidade"
            icon="mdi-pound"
            label="Quantidade"
            placeholder="Apenas números"
            :rules="rules.numericRules"
            type="number"
            @update-model-value="(newValue: string) => quantidade = newValue"
          />
          <v-checkbox
            v-model="disponivel"
            class="text-primary"
            color="primary"
            label="Disponível"
          />
        </v-form>
      </v-card-text>
      <template #actions>
        <div class="d-flex" style="justify-content: start; width:50%">
          <v-btn
            class="px-5"
            color="error"
            text="Cancelar"
            variant="tonal"
            @click="emit('close')"
          />
        </div>
        <div class="d-flex" style="justify-content: end; width:50%">
          <v-btn
            class="px-5"
            color="success"
            text="Concluir"
            variant="tonal"
            @click="onSubmit"
          />
        </div>
      </template>
    </v-card>
  </v-dialog>
</template>

<script lang="ts" setup>
  import { ref, watch } from 'vue'
  import { ValidationRules } from '@/rules'
  import { Item } from '@/models/Itens/itens'

  const rules = new ValidationRules('')

  const props = defineProps<{
    shouldDisplay: boolean,
    shouldUpdate: boolean,
    item?: Item,
  }>()

  // eslint-disable-next-line func-call-spacing
  const emit = defineEmits<{
    (e: 'add-item', descricao: string, quantidade: number, disponivel: boolean): void
    (e: 'update-item', descricao: string, quantidade: number, disponivel: boolean): void
    (e: 'close'): void
  }>()

  const form = ref(false)
  const descricao = ref('')
  const quantidade = ref('')
  const disponivel = ref(false)

  watch(() => props.item, newItem => {
    if (newItem) {
      descricao.value = newItem.descricao
      quantidade.value = newItem.quantidade.toString()
      disponivel.value = newItem.disponivel === 'Disponível'
    } else {
      descricao.value = ''
      quantidade.value = ''
      disponivel.value = false
    }
  }, { immediate: true })

  // Handle form submission
  function onSubmit () {
    if (!form.value) return
    if (props.shouldUpdate) {
      emit('update-item', descricao.value, Number(quantidade.value), disponivel.value)
      return
    }
    emit('add-item', descricao.value, Number(quantidade.value), disponivel.value)
  }
</script>
