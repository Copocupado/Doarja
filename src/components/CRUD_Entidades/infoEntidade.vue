<template>
  <v-container
    class="fill-height d-flex flex-column justify-start bg-background"
  >
    <v-container class="d-flex ga-3 align-center">
      <div class="text-h4 font-weight-medium text-primary no-padding">Informações da entidade</div>
      <v-icon color="primary" class="text-h3">mdi-store-edit</v-icon>
    </v-container>
    <v-container class="flex-grow-1">
      <v-form v-model="isValid" class="d-flex flex-column justify-space-between ga-5 fill-height">
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
          hint="Deixe vazio para manter a mesma senha já cadastrada"
          :isPassword="true"
          label="Nova Senha"
          @update-model-value="(newValue: string) => password = newValue"
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
        <BtnComponent color="secondary" :disabled="!isValid" :loading="isLoading" text="Editar" @button-clicked="update"/>
      </v-form>
      <v-container
        v-if="shouldShowErrorMessage"
        :class="['d-flex', 'justify-center', 'font-weight-bold', messageClass]"
      >
        {{ errorMessageText }}
      </v-container>
    </v-container>
  </v-container>
</template>
<script lang="ts" setup>
  import { ref, computed, watch } from 'vue'
  import LocationPickerComponent from '@/components/locationPickerComponent.vue'
  import { ValidationRules } from '@/rules'
  import { Entidade } from '@/models/Entidade/entidade';
  import { entidadeDAO } from '@/models/Entidade/entidadeDAO';

  const props = defineProps<{
    currentlyAuthedEntidade?: Entidade,
  }>()

  // eslint-disable-next-line func-call-spacing
  const emit = defineEmits<{
    (e: 'update-entidade', data: object): void
  }>()

  async function update() {
    const newEntidade = new Entidade(name.value, password.value, address.value, phone.value, props.currentlyAuthedEntidade?.id)

    isLoading.value = true

    let response = await entidadeDAO.update(newEntidade)

    response = await entidadeDAO.read({id: newEntidade.id})

    isLoading.value = false
    emit('update-entidade', response.message)
  }

  const isValid = ref(false)

  const name = ref('')
  const password = ref('')
  const address = ref('')
  const phone = ref('')
  const isLoading = ref(false)

  watch(() => props.currentlyAuthedEntidade, newItem => {
    if (newItem) {
      name.value = newItem.nome
      address.value = newItem.endereco
      phone.value = newItem.telefone
    } else {
      name.value = ''
      password.value = ''
      address.value = ''
      phone.value = ''
    }
  }, { immediate: true })

  let messageClass = ''

  const rulesUpdate = computed(() => {
    return new ValidationRules(password.value)
  })
  const rules = computed(() => rulesUpdate.value)

  const shouldShowErrorMessage = ref(false)
  let errorMessageText = ''

  function placeChanged (newPlace: string) {
    address.value = newPlace
  }

</script>
<style>
.no-padding{
  padding: 0;
  margin: 0;
}
</style>
