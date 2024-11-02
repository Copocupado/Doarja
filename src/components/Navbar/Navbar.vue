<template>
  <v-navigation-drawer class="bg-secondary" expand-on-hover permanent rail>
    <v-list style="height: 6%;">
      <v-list-item
        :prepend-avatar="userPfp"
        :subtitle="userSecondaryInfo"
        :title="userMainInfo"
      />
    </v-list>

    <v-divider />

    <v-list nav style="display: flex; flex-direction:column; justify-content: space-between; height:93.8%;">
      <div>
        <v-list-item
          :class="{ 'v-list-item--active': selectedSection === 'Duvidas Frequentes' }"
          prepend-icon="mdi-frequently-asked-questions"
          title="Dúvidas frequentes"
          value="myfiles"
          @click="selectSection('Duvidas Frequentes')"
        />
        <v-list-item
          :class="{ 'v-list-item--active': selectedSection === 'noticias' }"
          prepend-icon="mdi-newspaper"
          title="Notícias"
          value="noticias"
          @click="selectSection('noticias')"
        />
        <v-list-item
          :class="{ 'v-list-item--active': selectedSection === 'bazar-solidario' }"
          prepend-icon="mdi-gift"
          title="Bazar solidário"
          value="bazar-solidario"
          @click="selectSection('bazar-solidario')"
        />
        <v-list-item
          :class="{ 'v-list-item--active': selectedSection === 'novidades-da-loja' }"
          prepend-icon="mdi-tshirt-crew"
          title="Novidades da loja"
          value="novidades-da-loja"
          @click="selectSection('novidades-da-loja')"
        />
        <v-list-item
          :class="{ 'v-list-item--active': selectedSection === 'lojas-da-empresa' }"
          prepend-icon="mdi-storefront-edit"
          title="Lojas da Empresa"
          value="lojas-da-empresa"
          @click="selectSection('lojas-da-empresa')"
        />
        <v-list-item
          :class="{ 'v-list-item--active': selectedSection === 'solicitacoes' }"
          prepend-icon="mdi-checkbook"
          title="Solicitações"
          value="solicitacoes"
          @click="selectSection('solicitacoes')"
        />
        <v-list-item
          :class="{ 'v-list-item--active': selectedSection === 'credits' }"
          prepend-icon="mdi-currency-brl"
          title="Reverse Credits"
          value="credits"
          @click="selectSection('credits')"
        />
        <v-list-item
          :class="{ 'v-list-item--active': selectedSection === 'entregas' }"
          prepend-icon="mdi-truck-cargo-container"
          title="Entregas"
          value="entregas"
          @click="selectSection('entregas')"
        />
        <v-list-item
          :class="{ 'v-list-item--active': selectedSection === 'funcionarios' }"
          prepend-icon="mdi-account-hard-hat"
          title="Funcionários"
          value="funcionarios"
          @click="selectSection('funcionarios')"
        />
      </div>
      <v-list-item
        prepend-icon="mdi-logout"
        title="Logout"
        value="Logout"
        @click="logUserOut"
      />
    </v-list>
  </v-navigation-drawer>
</template>

<script lang="ts" setup>
  import { defineEmits, defineProps } from 'vue'
  import { logOut } from '@/models/utility_classes'
  import router from '@/router'

  defineProps<{
    userPfp: string;
    userMainInfo: string;
    userSecondaryInfo: string;

    selectedSection:string;
  }>()

  const emit = defineEmits(['updateSection'])

  // Method to select a section
  const selectSection = (section: string) => {
    emit('updateSection', section)
  }

  const logUserOut = async () => {
    if (await logOut()) {
      console.log('logged user out')
      router.push('/Admin/login')
    } else {
      console.log('error')
    }
  }
</script>
