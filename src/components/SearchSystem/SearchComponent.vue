<template>
  <v-container>
    <v-text-field
      v-model="inputSearch"
      color="background"
      class="text-background bg-primary rounded"
      append-inner-icon="mdi-magnify"
      hide-details
      single-line
      @click:append-inner="searchItems"
      @keyup.enter="searchItems"
    >
      <template #loader>
        <v-progress-linear
          :active="loading"
          color="primary"
          :model-value="0"
          height="7"
          indeterminate
        />
      </template>
    </v-text-field>
  </v-container>
</template>

<script lang="ts" setup>
import { itemDAO } from '@/models/Itens/itensDAO';
import { Pedido } from '@/models/Pedidos/Pedido';
import { ref, watch, onMounted } from 'vue';

const emit = defineEmits<{
  (e: 'search-result', pedidosList: Array<Pedido>): void
}>()

const loading = ref(false);
const inputSearch = ref('');

async function searchItems() {
  loading.value = true
  const response = await itemDAO.searchLike(inputSearch.value)
  emit('search-result', response.message as Array<Pedido>)
  loading.value = false
}

watch(inputSearch, (newValue) => {
  searchItems()
});

onMounted(() => {
  searchItems()
})
</script>

