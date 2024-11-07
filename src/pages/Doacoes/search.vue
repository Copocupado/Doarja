<template>
  <v-app class="d-flex flex-column ga-5">
    <v-container class="d-flex justify-between align-center" style="height: 200px">
      <v-col cols="10">
        <div class="text-subtitle-1 font-weight-bold text-primary"><span @click="router.push('/')" style="cursor: pointer;">Voltar</span></div>
        <div class="text-h4 font-weight-black text-primary">O que você precisa?</div>
      </v-col>
      <v-col cols="2">
        <v-img src="/src/assets/logo.png" :aspect-ratio="1/1"/>
      </v-col>
    </v-container>
    <SearchComponent @search-result="updatePedidoDisplay" />
    <v-container class="flex-grow-1 d-flex flex-column">
      <v-row v-if="searchResult.length != 0 || firstSearch" class="fill-height fill-width">
        <v-col
          v-for="item in searchResult"
          :key="item.id"
          :cols="(searchResult[searchResult.length - 1] == item && searchResult.length % 2 != 0) ? '12' : '6'"
        >
          <ItemCard :item="item" />
        </v-col>
      </v-row>
      <v-container v-else class="fill-height fill-width d-flex justify-center flex-column align-center flex-grow-1">
        <v-icon class="text-h1 text-error">mdi-emoticon-dead</v-icon>
        <v-container class="text-error text-center text-h3 font-weight-medium">
          Desculpe, nada encontrado!
        </v-container>
      </v-container>
    </v-container>
  </v-app>
</template>
<script lang="ts" setup>
  import { ref } from 'vue';
  import SearchComponent from '@/components/SearchSystem/SearchComponent.vue';
  import { Pedido } from '@/models/Pedidos/Pedido';
  import router from '@/router';

  const searchResult = ref<Array<Pedido>>([])
  const firstSearch = ref(true)

  function updatePedidoDisplay(pedidosList: Array<Pedido>){
    firstSearch.value = false
    searchResult.value = pedidosList
    console.log(pedidosList)
  }
</script>
