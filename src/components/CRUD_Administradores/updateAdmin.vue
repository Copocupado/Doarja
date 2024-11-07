<template>
  <v-dialog :model-value="modelValue" @click:outside="emit('close')">
    <v-card
      class="bg-background text-secondary text-center"
      title="Atualizar administrador"
    >
      <v-card-text>
        <v-form
          v-model="form"
          class="d-flex flex-column ga-3"
        >
          <TextFieldComponent
            v-model="name"
            icon="mdi-account-tie"
            label="Nome"
            placeholder="Fulano"
            :rules="rules.nameRules"
            @update-model-value="(newValue: string) => name = newValue"
          />
          <TextFieldComponent
            v-model="password"
            icon="mdi-lock"
            hint="Deixe vazio para manter a mesma senha já cadastrada"
            :isPassword="true"
            label="Nova Senha"
            @update-model-value="(newValue: string) => password = newValue"
          />
          <v-checkbox
            v-model="active"
            color="primary"
            class="text-primary"
            label="Ativar administrador"
          />
        </v-form>
      </v-card-text>
      <template #actions>
        <div class="d-flex" style="justify-content: end; width:100%">
          <v-btn
            class="px-5"
            color="success"
            text="Adicionar"
            variant="tonal"
            @click="() => { if(!form) return; emit('update', new Admin(props.adminToUpdate.email, password, name, active, props.adminToUpdate.foto_de_perfil));}"
          />
        </div>
      </template>
    </v-card>
  </v-dialog>
</template>
<script lang="ts" setup>
  import { Admin } from '@/models/Admins/admin';
  import { ValidationRules } from '@/rules';
  import { ref, watch, computed } from 'vue'

  // eslint-disable-next-line func-call-spacing
  const emit = defineEmits<{
    (e: 'update', item: Admin): void
    (e: 'close'): void
  }>()

  const props = defineProps<{
    adminToUpdate: Admin,
    modelValue: boolean,
  }>()

  const rulesUpdate = computed(() => {
    return new ValidationRules(password.value)
  })
  const rules = computed(() => rulesUpdate.value)

  const form = ref(false)
  const name = ref(props.adminToUpdate.nome)
  const password = ref('')
  const active = ref(props.adminToUpdate.ativo)

  watch(() => props.adminToUpdate, newItem => {
    if (newItem) {
      name.value = newItem.nome
      active.value = newItem.ativo == 'Ativado' ? true : false
    } else {
      name.value = ''
      password.value = ''
      active.value = false
    }
  }, { immediate: true })
</script>
