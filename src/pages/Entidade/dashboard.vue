<template>
  <v-app v-if="entidade != null" class="bg-background">
    <v-row no-gutters>
      <v-col cols="2">
        <Navbar
          :menu-items="[
            { title: 'Itens', icon: 'mdi-package', value: 'itens' },
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
            <component :is="currentComponent" :currentlyAuthedEntidade="entidade" />
          </div>
        </v-container>
      </v-col>

    </v-row>
  </v-app>
</template>

<script lang="ts" setup>
  import Navbar from '@/components/Navbar/Navbar.vue'

  import { onMounted, ref } from 'vue'
  import router from '@/router'
  import { getSessionData } from '@/models/utility_classes'
  import { Entidade } from '@/models/Entidade/entidade'
  import table from '@/components/CRUD_Itens/table.vue'

  const entidade = ref<Entidade | null>(null)

  const selectedSection = ref('itens')
  const randomNumber = Math.floor(Math.random() * 5)

  function updateSection (newSection: string) {
    selectedSection.value = newSection
  }

  const currentComponent = computed(() => {
    switch (selectedSection.value) {
      case 'itens':
        return table
      default:
        return ''
    }
  })

  onMounted(async () => {
    const response = await getSessionData()
    if (!response.success) {
      router.push('/Entidade/login')
      return
    }
    const message = response.message
    entidade.value = new Entidade(message.nome, message.senha, message.endereco, message.telefone, message.id)
  })

</script>
