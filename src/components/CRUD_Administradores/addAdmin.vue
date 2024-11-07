<template>
  <v-dialog activator="parent" max-width="400px">
    <template #default="{ isActive }">
      <v-card
        class="bg-background text-secondary text-center"
        title="Adicionar novo administrador"
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
              v-model="email"
              icon="mdi-email"
              label="Email"
              placeholder="fulano@gmail.com"
              :rules="rules.emailRules"
              @update-model-value="(newValue: string) => email = newValue"
            />
            <TextFieldComponent
              v-model="password"
              icon="mdi-lock"
              :isPassword="true"
              label="Senha"
              :rules="rules.passwordRules"
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
              @click="() => { if(!form) return; emit('add-administrador', name, email, password, active); isActive.value = false;}"
            />
          </div>
        </template>
      </v-card>
    </template>
  </v-dialog>
</template>
<script lang="ts" setup>
  import { ValidationRules } from '@/rules';
import { ref, computed } from 'vue'

  // eslint-disable-next-line func-call-spacing
  const emit = defineEmits<{
    (e: 'add-administrador', name: string, email: string, password: string, isActive: boolean): void
  }>()

  const rulesUpdate = computed(() => {
    return new ValidationRules(password.value)
  })
  const rules = computed(() => rulesUpdate.value)

  const form = ref(false)
  const name = ref('')
  const email = ref('')
  const password = ref('')
  const active = ref(false)
</script>
