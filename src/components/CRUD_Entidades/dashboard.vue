<template>
  <v-container>
    <v-row no-gutters>
      <v-col cols="2">
        <Navbar
          :menu-items="[
            { title: 'Informações', icon: 'mdi-information', value: 'informacoes' },
            { title: 'Itens', icon: 'mdi-package', value: 'itens' },
            { title: 'Pedidos', icon: 'mdi-hand-coin', value: 'pedidos' },
          ]"
          :selected-section="selectedSection"
          :user-main-info="entidade.nome"
          :user-pfp="`/src/assets/entidades/download (${randomNumber}).jpeg`"
          :user-secondary-info="entidade.endereco"
          @update-section="updateSection"
        />
      </v-col>

      <v-col class="py-12 px-12" cols="10">
        <v-container class="d-flex flex-column no-padding" style="height: 100%; max-height: 100%;">
          <div style="height: 100%; max-height: 100%;">
            <component :is="currentComponent" :currentlyAuthedEntidade="entidade" @update-entidade="updateEntidade"/>
          </div>
        </v-container>
      </v-col>
    </v-row>
    <Snackbar :snackbar="snackbar" :snackbar-color="snackbarColor" :snackbar-text="snackbarText" @close="snackbar = false" />
  </v-container>
</template>

<script lang="ts" setup>
  import Navbar from '@/components/Navbar/Navbar.vue'

  import { ref, computed } from 'vue'
  import { Entidade } from '@/models/Entidade/entidade'
  import tableItens from '@/components/CRUD_Itens/tableItens.vue'
  import infoEntidade from '@/components/CRUD_Entidades/infoEntidade.vue'
  import { getSessionData, saveSessionData } from '@/models/utility_classes'
  import showPedidos from '@/components/Pedidos/showPedidos.vue'

  const snackbar = ref(false)
  let snackbarText = ''
  let snackbarColor = ''


  const props = defineProps<{
    currentlyAuthedEntidade: Entidade,
  }>()

  const entidade = props.currentlyAuthedEntidade

  const selectedSection = ref('itens')
  const randomNumber = Math.floor(Math.random() * 5)

  function updateSection (newSection: string) {
    selectedSection.value = newSection
  }

  const currentComponent = computed(() => {
    switch (selectedSection.value) {
      case 'informacoes':
        return infoEntidade
      case 'itens':
        return tableItens
      case 'pedidos':
        return showPedidos
      default:
        return ''
    }
  })

  async function updateEntidade(data: object) {
    data = await saveSessionData({role: 'entidade', ...data})
    data = await getSessionData()

    if(data.success != undefined && !data.success){
      showSnackbar(data)
    }
    else {
      refresh(data.message)

      snackbarColor = 'success'
      snackbarText = 'Dados Atualizados!'
      snackbar.value = true
    }
  }

  function showSnackbar (response: object) {
    snackbarColor = response.success ? 'success' : 'error'
    snackbarText = response.message
    snackbar.value = true
  }


  function refresh (message: object) {
    entidade.value = new Entidade(message.nome, message.senha, message.endereco, message.telefone, message.id)
  }

</script>
