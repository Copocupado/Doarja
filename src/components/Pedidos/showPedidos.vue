<template>
  <div class="d-flex justify-space-between align-center">
    <div class="d-flex">
      <div class="text-h4 font-weight-medium text-primary">Pedidos</div>
      <v-chip class="ma-2" color="primary" label>
        <v-icon icon="mdi-hand-coin" start />
        <div v-if="pedidosList != null">
          {{ pedidosList.length }} {{ pedidosList.length > 1 ? 'Pedidos' : 'Pedido' }}
        </div>
      </v-chip>
    </div>
    <v-btn color="secondary" prepend-icon="mdi-plus-circle">
      <template #prepend>
        <v-icon class="text-background" />
      </template>
      <div class="text-background">Adicionar Pedido</div>
    </v-btn>
  </div>
</template>
<script lang="ts" setup>
import { Entidade } from '@/models/Entidade/entidade';
import { Pedido } from '@/models/Pedidos/Pedido';
import { pedidoDAO } from '@/models/Pedidos/PedidosDAO';
import { ref } from 'vue';

const props = defineProps<{
  currentlyAuthedEntidade: Entidade,
}>()


const pedidosList = ref<Pedido[] | null>(null)

async function fetchPedido(){
  const response = await pedidoDAO.read({idEntidade: props.currentlyAuthedEntidade})
  console.log(response)
}
fetchPedido()
</script>
