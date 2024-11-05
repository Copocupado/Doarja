<template>
  <div class="responsive-background pa-5">
    <v-container class="rounded-lg bg-background glowing-border d-flex flex-column align-center ga-5" style="border: 1px solid #0d5c63; padding:50px;">
      <div class="text-h4 text-secondary font-weight-bold">Entrar</div>
      <v-container class="d-flex flex-column justify-space-between flex-grow-1">
        <v-form v-model="isValid" class="d-flex flex-column ga-5">
          <TextFieldComponent
            v-model="name"
            icon="mdi-hand-heart"
            label="Nome da entidade"
            placeholder="Reggla"
            :rules="rules.nameRules"
            @update-model-value="(newValue: string) => name = newValue"
          />
          <TextFieldComponent
            v-model="password"
            icon="mdi-lock"
            :isPassword="true"
            label="Senha"
            :rules="rules.passwordRules"
            @update-model-value="(newValue: string) => password = newValue"
          />
        </v-form>
        <v-container class="d-flex ga-3">
          <v-container class="text-secondary text-high-emphasis text-decoration-underline text-left pa-0"><span style="cursor: pointer;" @click="()=>{router.push('/')}">Voltar para a seleção de atividades</span></v-container>
          <v-container class="text-primary text-high-emphasis text-decoration-underline text-right pa-0"><span style="cursor: pointer;" @click="()=>{router.push('/Entidade/signup')}">Cadastrar sua entidade</span></v-container>
        </v-container>
        <BtnComponent :disabled="!isValid" :loading="isLoading" text="Entrar" @button-clicked="onSubmit" />
        <v-container v-if="shouldShowErrorMessage" class="d-flex justify-center text-error font-weight-bold">{{ errorMessageText }}</v-container>
      </v-container>
    </v-container>
    <Snackbar :snackbar="snackbar" :snackbar-color="snackbarColor" :snackbar-text="snackbarText" @close="snackbar = false" />
  </div>
</template>
<script lang="ts" setup>
  import { ref } from 'vue'
  import router from '@/router'
  import { ValidationRules } from '@/rules'
  import { entidadeDAO } from '@/models/Entidade/entidadeDAO'
  import { saveSessionData } from '@/models/utility_classes'

  const isValid = ref(false)

  const name = ref('')
  const password = ref('')

  const rulesUpdate = computed(() => {
    return new ValidationRules(password.value)
  })
  const rules = computed(() => rulesUpdate.value)

  const isLoading = ref(false)
  const shouldShowErrorMessage = ref(false)
  let errorMessageText = ''

  async function onSubmit () {
    if (!isValid.value) return

    isLoading.value = true
    try {
      const response = await entidadeDAO.read({nome: name.value, senha: password.value})
      if (!(response.success)) {
        errorMessageText = response.message
        shouldShowErrorMessage.value = true
        setTimeout(() => {
          shouldShowErrorMessage.value = false
        }, 3000)
      } else {
        const entidade = response.message
        try {
          if (await saveSessionData({ role: 'entidade', ...entidade })) {
            console.log('Users credentilas saved successfully')
          } else {
            console.log('Failed to save user credentials')
          }
        } catch (error) {
          console.error('Error saving session data:', error)
        } finally {
          router.push('/Entidade/dashboard')
        }
      }
    } catch (error) {
      console.error('Error fetching data:', error)
    }
    isLoading.value = false
  }

  onMounted(async () => {
    const response = await entidadeDAO.isItem()
    if (!response.success) {
      showSnackbar(response)
      return
    }
    router.push('/Entidade/dashboard')
  })

  const snackbar = ref(false)
  let snackbarText = ''
  let snackbarColor = ''

  function showSnackbar (response: object) {
    snackbarColor = response.success ? 'success' : 'error'
    snackbarText = response.message
    snackbar.value = true
  }
</script>
<style scoped>
  .responsive-background {
    width: 100vw; /* Ensure the div takes full width */
    min-height: 100vh; /* Adjust the height as needed */
    background: url('/src/assets/backgorunds/entidade.jpeg');
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
  }
</style>
