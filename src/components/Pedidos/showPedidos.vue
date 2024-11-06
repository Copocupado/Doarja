<template>
  <v-container class="d-flex flex-column ga-5 fill-height">
    <v-container class="d-flex justify-space-between align-center">
      <div class="d-flex">
        <div class="text-h4 font-weight-medium text-primary">Pedidos</div>
        <v-chip class="ma-2" color="primary" label>
          <v-icon icon="mdi-hand-coin" start />
          <div v-if="pedidosList != null">
            {{ pedidosList.length }} {{ pedidosList.length > 1 ? 'Pedidos' : 'Pedido' }}
          </div>
        </v-chip>
      </div>
      <v-btn color="secondary" prepend-icon="mdi-plus-circle" @click="shouldDisplayModifierDialog = true">
        <template #prepend>
          <v-icon class="text-background" />
        </template>
        <div class="text-background">Adicionar Pedido</div>
      </v-btn>
    </v-container>
    <v-container class="flex-grow-1" v-if="pedidosList != null && itemsList != null">
      <v-row class="fill-height fill-width">
        <v-col
          v-for="item in pedidosList"
          :key="item.id"
          :cols="(pedidosList[pedidosList.length - 1] == item && pedidosList.length % 2 != 0) ? '12' : '6'"
        >
          <PedidoCard
            :entidade="props.currentlyAuthedEntidade"
            :pedido="item"
            :item="getItem(item)!"
          />
        </v-col>
      </v-row>
    </v-container>
  </v-container>
  <PedidoModifier
    v-if="itemsList != null"
    :model-value="shouldDisplayModifierDialog"
    :currently-authed-entidade="props.currentlyAuthedEntidade"
    :items-list="itemsList"
    @close="shouldDisplayModifierDialog = false"
    @added="handleAdded"
  />
  <Snackbar :snackbar="snackbar" :snackbar-color="snackbarColor" :snackbar-text="snackbarText" @close="snackbar = false" />
</template>
<script lang="ts" setup>
  import { Entidade } from '@/models/Entidade/entidade';
  import { Item } from '@/models/Itens/itens';
  import { itemDAO } from '@/models/Itens/itensDAO';
  import { Pedido } from '@/models/Pedidos/Pedido';
  import { pedidoDAO } from '@/models/Pedidos/PedidosDAO';
  import { ref, onMounted } from 'vue';

  const props = defineProps<{
    currentlyAuthedEntidade: Entidade,
  }>()

  const shouldDisplayModifierDialog = ref(false)
  const pedidosList = ref<Pedido[] | null>(null)
  const itemsList = ref<Item[] | null>(null)

  const snackbar = ref(false)
  let snackbarText = ''
  let snackbarColor = ''

  async function fetchPedido(){
    pedidosList.value = await pedidoDAO.read({idEntidade: props.currentlyAuthedEntidade.id})
    console.log(pedidosList.value)
  }

  async function fetchItems(){
    itemsList.value = await itemDAO.read({ idEntidade: props.currentlyAuthedEntidade.id, disponivel: 1 })
  }

  function showSnackbar (response: object) {
    snackbarColor = response.success ? 'success' : 'error'
    snackbarText = response.message
    snackbar.value = true
  }

  async function handleAdded(response: object){
    showSnackbar(response)
    shouldDisplayModifierDialog.value = false

    await fetchPedido()
    await fetchItems()
  }

  function getItem(pedido: Pedido) {
    if (!itemsList.value) {
      return null;
    }
    return itemsList.value.find(element => element.id === pedido.idItem) || null;
  }

  onMounted(async () => {
    await fetchPedido()
    await fetchItems()
  })
</script>
