<template>
  <div class="responsive-background pa-5">
    <v-container class="rounded-lg bg-background glowing-border d-flex flex-column align-center ga-5" style="border: 1px solid #0d5c63; padding:50px;">
      <div class="text-h4 text-secondary font-weight-bold">Entrar</div>
      <v-container class="d-flex flex-column justify-space-between flex-grow-1">
        <v-form v-model="isValid" class="d-flex flex-column ga-5">
          <TextFieldComponent
            v-model="email"
            icon="mdi-account-hard-hat"
            label="Email"
            placeholder="exemplo@gmail.com"
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
        </v-form>
        <v-container class="text-secondary text-high-emphasis text-decoration-underline"><span style="cursor: pointer;" @click="()=>{router.push('/')}">Voltar para a seleção de atividades</span></v-container>
        <BtnComponent :disabled="!isValid" :loading="isLoading" text="Entrar" @button-clicked="onSubmit" />
        <v-container v-if="shouldShowErrorMessage" class="d-flex justify-center text-error font-weight-bold">{{ errorMessageText }}</v-container>
      </v-container>
    </v-container>
  </div>
</template>
<script lang="ts" setup>
  import { ref } from 'vue'
  import router from '@/router'
  import { saveSessionData } from '@/models/utility_classes'
  import { adminDAO } from '@/models/Admins/adminDAO'
  import { ValidationRules } from '@/rules'


  const isValid = ref(false)

  const email = ref('')
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
  }
</style>
