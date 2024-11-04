<template>
  <div class="responsive-background pa-5">
    <v-container class="rounded-lg bg-background glowing-border d-flex flex-column align-center ga-5" style="aspect-ratio:16/9; border: 1px solid #0d5c63; padding:50px;">
      <div class="text-h4 text-secondary font-weight-bold">Cadastar-se</div>
      <v-container class="d-flex flex-column justify-space-between flex-grow-1">
        <v-form v-model="isValid" class="d-flex flex-column ga-5">
          <v-text-field
            v-model="name"
            class="text-primary"
            clearable
            color="primary"
            label="Nome da entidade"
            placeholder="exemplo@gmail.com"
            :rules="nameRules"
            type="name"
            variant="outlined"
          >
            <template #prepend-inner>
              <v-icon color="primary">mdi-hand-heart</v-icon>
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
          <v-text-field
            v-model="confirmPassword"
            :append-inner-icon="confirmShow ? 'mdi-eye' : 'mdi-eye-off'"
            class="text-primary"
            color="primary"
            label="Confirmar senha"
            :rules="confirmPasswordRules"
            :type="confirmShow ? 'text' : 'password'"
            variant="outlined"
            @click:append-inner="confirmShow = !confirmShow"
          >
            <template #prepend-inner>
              <v-icon color="primary">mdi-shield-check</v-icon>
            </template>
          </v-text-field>
          <LocationPickerComponent :address="address" @place-changed="placeChanged" />
        </v-form>
        <v-container class="d-flex ga-3">
          <v-container class="text-secondary text-high-emphasis text-decoration-underline text-left pa-0"><span style="cursor: pointer;" @click="()=>{router.push('/')}">Voltar para a seleção de atividades</span></v-container>
          <v-container class="text-primary text-high-emphasis text-decoration-underline text-right pa-0"><span style="cursor: pointer;" @click="()=>{router.push('/Entidade/login')}">Entrar com a sua entidade</span></v-container>
        </v-container>
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
          Cadastrar-se
        </v-btn>
        <v-container v-if="shouldShowErrorMessage" class="d-flex justify-center text-error font-weight-bold">{{ errorMessageText }}</v-container>
      </v-container>
    </v-container>
  </div>
</template>
<script lang="ts" setup>
  import { onMounted, ref } from 'vue'
  import router from '@/router'
  import { getAdmin, isUserAdmin, saveSessionData } from '@/models/Admins/admin'
  import LocationPickerComponent from '@/components/locationPickerComponent.vue'

  const show = ref(false)
  const confirmShow = ref(false)

  const isValid = ref(false)

  const name = ref('')
  const password = ref('')
  const confirmPassword = ref('')
  const address = ref('')

  const isLoading = ref(false)
  const shouldShowErrorMessage = ref(false)
  let errorMessageText = ''

  const nameRules = [
    (value: string) => !!value || 'O nome é obrigatório.',
    (value: string) => {
      return value.length > 2
    },
  ]

  const passwordRules = [
    (value: string) => !!value || 'Senha é obrigatória.',
  ]

  const confirmPasswordRules = [
    (value: string) => !!value || 'Senha é obrigatória.',
    (value: string) => (value === password.value && value === confirmPassword.value) || 'Senhas não coincidem',
  ]

  onMounted(async () => {
    if (await isUserAdmin()) {
      console.log('user already logged in as admin, redirecting...')
    } else {
      console.log('user not logged in, taking no further action')
    }
  })

  async function onSubmit () {
    if (!isValid.value) return

    isLoading.value = true
    try {
      const response = await getAdmin(name.value, password.value)
      if (!(response.success)) {
        errorMessageText = response.message
        shouldShowErrorMessage.value = true
        setTimeout(() => {
          shouldShowErrorMessage.value = false
        }, 3000)
      } else {
        const admin = response.message
        try {
          if (await saveSessionData(admin)) {
            console.log('Users credentilas saved successfully')
          } else {
            console.log('Failed to save user credentials')
          }
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

  function placeChanged (newPlace: string) {
    address.value = newPlace
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
