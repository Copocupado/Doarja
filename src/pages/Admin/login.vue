<template>
  <div class="responsive-background pa-5">
    <v-container class="rounded-lg bg-background glowing-border d-flex flex-column align-center ga-5" style="aspect-ratio:16/9; border: 1px solid #0d5c63; padding:50px;">
      <div class="text-h4 text-secondary font-weight-bold">Entrar</div>
      <v-container class="d-flex flex-column justify-space-between flex-grow-1">
        <v-form v-model="isValid" class="d-flex flex-column ga-5">
          <v-text-field
            v-model="email"
            class="text-primary"
            clearable
            color="primary"
            label="Endereço de email"
            placeholder="exemplo@gmail.com"
            :rules="emailRules"
            type="email"
            variant="outlined"
          >
            <template #prepend-inner>
              <v-icon color="primary">mdi-email</v-icon>
            </template>
          </v-text-field>
          <v-text-field
            v-model="password"
            :append-inner-icon="show ? 'mdi-eye' : 'mdi-eye-off'"
            class="text-primary"
            color="primary"
            label="Senha"
            :rules="passwordRules"
            :type="show ? 'text' : 'password'"
            variant="outlined"
            @click:append-inner="show = !show"
          >
            <template #prepend-inner>
              <v-icon color="primary">mdi-lock</v-icon>
            </template>
          </v-text-field>
        </v-form>
        <v-container class="text-secondary text-high-emphasis text-decoration-underline"><span style="cursor: pointer;" @click="()=>{router.push('/')}">Voltar para a seleção de atividades</span></v-container>
        <v-btn
          block
          class="text-background bg-secondary"
          :disabled="!isValid"
          :loading="isLoading"
          rounded="lg"
          size="x-large"
          type="submit"
          @click="onSubmit"
        >
          Entrar
        </v-btn>
        <v-container v-if="shouldShowErrorMessage" class="d-flex justify-center text-error font-weight-bold">{{ errorMessageText }}</v-container>
      </v-container>
    </v-container>
    <Snackbar :snackbar="snackbar" :snackbar-color="snackbarColor" :snackbar-text="snackbarText" @close="snackbar = false" />
  </div>
</template>
<script lang="ts" setup>
  import { onMounted, ref } from 'vue'
  import router from '@/router'
  import { saveSessionData } from '@/models/utility_classes'
  import { adminDAO } from '@/models/Admins/adminDAO'
import { Admin } from '@/models/Admins/admin';

  const snackbar = ref(false)
  let snackbarText = ''
  let snackbarColor = ''

  const show = ref(false)

  const isValid = ref(false)

  const email = ref('')
  const password = ref('')

  const isLoading = ref(false)
  const shouldShowErrorMessage = ref(false)
  let errorMessageText = ''

  const emailRules = [
    (value: string) => !!value || 'Email é obrigatório.',
    (value: string) => {
      if (value === 'root') return true
      const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
      return emailPattern.test(value) || 'Email deve ser válido.'
    },
  ]

  const passwordRules = [
    (value: string) => !!value || 'Senha é obrigatória.',
  ]

  function showSnackbar (response: object) {
    snackbarColor = response.success ? 'success' : 'error'
    snackbarText = response.message
    snackbar.value = true
  }

  onMounted(async () => {
    const response = await adminDAO.isUserAdmin()
    if (!response.success) {
      showSnackbar(response)
      return
    }
    /*if (response.message) {
      router.push('/Admin/dashboard')
    }*/
  })

  async function onSubmit () {
    if (!isValid.value) return

    isLoading.value = true
    try {
      const response = await adminDAO.read({ email: email.value, senha: password.value })
      if (!response.success) {
        errorMessageText = response.message
        shouldShowErrorMessage.value = true
        setTimeout(() => {
          shouldShowErrorMessage.value = false
        }, 3000)
      } else {
        const admin = response.message
        try {
          await saveSessionData({ role: 'admin', ...admin })
        } catch (error) {
          console.error('Error saving session data:', error)
        } finally {
          router.push('/Admin/dashboard')
        }
      }
    } catch (error) {
      console.error('Error fetching data:', error)
    }
    isLoading.value = false
  }
</script>
<style scoped>
  .responsive-background {
    width: 100vw; /* Ensure the div takes full width */
    min-height: 100vh; /* Adjust the height as needed */
    background: url('/src/assets/background-index.png');
    background-size: cover; /* Ensure the background covers the element */
    background-position: center; /* Center the background image */
    display: flex;
    align-items: center;
    justify-content: center;
  }

  @media (max-width: 600px) { /* Adjust max-width for small screens */
    .responsive-background {
      background: linear-gradient(45deg, #5d4a66, #0d5c63);
    }
  }
  /*.glowing-border {
    border: 3px solid #5d4a66;
    box-shadow:
      0 0 10px 3px rgba(93, 74, 102, 0.6),
      0 0 20px 5px rgba(93, 74, 102, 0.3);
  }*/
  .glowing-border {
    border: 3px solid #0d5c63;
    box-shadow: 0 0 10px 3px rgba(13, 92, 99, 0.6), /* outer glow */
                0 0 20px 5px rgba(13, 92, 99, 0.3); /* soft spread glow */
    max-height: 100px;
  }
</style>
