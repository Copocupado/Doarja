<template>
  <div class="responsive-background pa-5">
    <v-container class="rounded-lg bg-background glowing-border d-flex flex-column align-center ga-5" style="aspect-ratio:16/9; border: 1px solid #0d5c63; padding:50px;">
      <div class="text-h4 text-secondary font-weight-bold">Cadastar-se</div>
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
          <TextFieldComponent
            v-model="confirmPassword"
            icon="mdi-shield-check"
            :isPassword="true"
            label="Confirmar senha"
            :rules="rules.confirmPasswordRules"
            @update-model-value="(newValue: string) => confirmPassword = newValue"
          />
          <LocationPickerComponent :address="address" @place-changed="placeChanged" />
          <TextFieldComponent
            v-model="phone"
            icon="mdi-phone-in-talk"
            label="Telefone para contato"
            placeholder="54123456789"
            prefix="+55"
            :rules="rules.phoneRules"
            @update-model-value="(newValue: string) => phone = newValue"
          />
        </v-form>
        <v-container class="d-flex ga-3">
          <v-container class="text-secondary text-high-emphasis text-decoration-underline text-left pa-0"><span style="cursor: pointer;" @click="()=>{router.push('/')}">Voltar para a seleção de atividades</span></v-container>
          <v-container class="text-primary text-high-emphasis text-decoration-underline text-right pa-0"><span style="cursor: pointer;" @click="()=>{router.push('/Entidade/login')}">Entrar com a sua entidade</span></v-container>
        </v-container>
        <BtnComponent :disabled="!isValid" :loading="isLoading" text="Cadastrar-se" @button-clicked="onSubmit" />
        <v-container
          v-if="shouldShowErrorMessage"
          :class="['d-flex', 'justify-center', 'font-weight-bold', messageClass]"
        >
          {{ errorMessageText }}
        </v-container>
      </v-container>
    </v-container>
  </div>
</template>
<script lang="ts" setup>
  import { ref } from 'vue'
  import router from '@/router'
  import LocationPickerComponent from '@/components/locationPickerComponent.vue'
  import { ValidationRules } from '@/rules'
  import { entidadeDAO } from '@/models/Entidade/entidadeDAO'
  import { Entidade } from '@/models/Entidade/entidade'
  import { saveSessionData } from '@/models/utility_classes'

  const isValid = ref(false)

  const name = ref('')
  const password = ref('')
  const confirmPassword = ref('')
  const address = ref('')
  const phone = ref('')

  let messageClass = ''

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
      const response = await entidadeDAO.create(new Entidade(name.value, password.value, address.value, phone.value))
      errorMessageText = response.message
      messageClass = response.success ? 'text-success' : 'text-error'
      shouldShowErrorMessage.value = true
      setTimeout(() => {
        if (response.success) {
          router.push('/Entidade/login')
        }
        shouldShowErrorMessage.value = false
      }, 3000)
      if (response.success) {
        try {
          await saveSessionData({ role: 'entidade', ...response.message })
        } catch (error) {
          console.error('Error saving session data:', error)
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
      color: #ccc5b93d;
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
