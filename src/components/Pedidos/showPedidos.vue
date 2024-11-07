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
      <v-btn color="secondary" v-if="props.currentlyAuthedEntidade != undefined" prepend-icon="mdi-plus-circle" @click="shouldDisplayModifierDialog = true">
        <template #prepend>
          <v-icon class="text-background" />
        </template>
        <div class="text-background">Adicionar Pedido</div>
      </v-btn>
    </v-container>
    <v-container class="flex-grow-1">
      <v-row class="fill-height fill-width">
        <v-col
          v-if="pedidosList != null && !(isBooting)"
          v-for="item in pedidosList"
          :key="item.id"
          :cols="(pedidosList[pedidosList.length - 1] == item && pedidosList.length % 2 != 0) ? '12' : '6'"
        >
          <PedidoCard
            :entidade="props.currentlyAuthedEntidade ?? pedidosToEntidadeDict[item.id!]"
            :pedido="item"
            :item="getItem(item)!"
          />
        </v-col>
        <v-col
          v-for="item in [1,2,3,4,5,6]"
          v-else
          :key="item"
          cols="6"
        >
          <v-skeleton-loader class="bg-background" type="card"/>
        </v-col>
      </v-row>
    </v-container>
  </v-container>
  <PedidoModifier
    v-if="itemsList != null && props.currentlyAuthedEntidade != undefined"
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
  import { entidadeDAO } from '@/models/Entidade/entidadeDAO';
  import { Item } from '@/models/Itens/itens';
  import { itemDAO } from '@/models/Itens/itensDAO';
  import { Pedido } from '@/models/Pedidos/Pedido';
  import { pedidoDAO } from '@/models/Pedidos/PedidosDAO';
  import { Pessoa } from '@/models/Pessoas/Pessoa';
  import { ref, onMounted, computed } from 'vue';

  const props = defineProps<{
    currentlyAuthedEntidade?: Entidade,
    currentlyAuthedPessoa?: Pessoa,
  }>()

  const shouldDisplayModifierDialog = ref(false)
  const isBooting = ref(true)

  const pedidosList = ref<Pedido[] | null>(null)
  const itemsList = ref<Item[] | null>(null)
  const pedidosToEntidadeDict = ref<Record<number, Entidade>>({})
  const pedidosToItemDict = ref<Record<number, Item>>({})

  const snackbar = ref(false)
  let snackbarText = ''
  let snackbarColor = ''

  async function fetchPedido(){
    if(props.currentlyAuthedEntidade != undefined){
      pedidosList.value = await pedidoDAO.read({idEntidade: props.currentlyAuthedEntidade.id})
    } else {
      pedidosList.value = await pedidoDAO.read({idPessoa: props.currentlyAuthedPessoa!.id})
      if(pedidosList.value != null) {
        for (const element of pedidosList.value!) {
          var response = await entidadeDAO.read({ id: element.idEntidade })
          const newEntidade = response.message as Entidade

          response = await itemDAO.read({ id: element.idItem }) as Array<Item>
          const newItem = response[0] as Item

          pedidosToEntidadeDict.value[element.id!] = newEntidade
          pedidosToItemDict.value[element.id!] = newItem
        }
      }
    }
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
      return pedidosToItemDict.value[pedido.id!]
    }
    return itemsList.value.find(element => element.id === pedido.idItem) || null;
  }


  onMounted(async () => {
    isBooting.value = true
    await fetchPedido()
    if(props.currentlyAuthedEntidade != undefined){
      await fetchItems()
    }
    isBooting.value = false
  })
</script>
