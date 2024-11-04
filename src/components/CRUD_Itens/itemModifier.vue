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
            <v-text-field v-model="name" label="Nome" :rules="[rules.required]" variant="outlined" />
            <v-text-field v-model="email" label="Email" :rules="[rules.required, rules.validEmail]" variant="outlined" />
            <v-text-field
              v-model="password"
              :append-inner-icon="show ? 'mdi-eye' : 'mdi-eye-off'"
              label="Senha"
              :rules="[rules.required]"
              :type="show ? 'text' : 'password'"
              variant="outlined"
              @click:append-inner="show = !show"
            />
            <v-checkbox
              v-model="active"
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
  import { ref } from 'vue'

  // eslint-disable-next-line func-call-spacing
  const emit = defineEmits<{
    (e: 'add-administrador', name: string, email: string, password: string, isActive: boolean): void
  }>()

  const form = ref(false)
  const name = ref('')
  const email = ref('')
  const password = ref('')
  const show = ref(false)
  const active = ref(false)

  const rules = {
    required: (value: string) => value.length > 0 || 'Campo necessário',
    validEmail: (value: string) => {
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
      return emailRegex.test(value) || 'Insira um e-mail válido'
    },
  }
</script>
