<template>
  <v-container>
    <v-text-field
      v-model="inputSearch"
      :loading="loading"
      color="secondary"
      class="text-secondary"
      append-inner-icon="mdi-magnify"
      variant="outlined"
      hide-details
      single-line
      @click:append-inner="searchItems"
      @keyup.enter="searchItems"
    />
  </v-container>
</template>

<script lang="ts" setup>
import { itemDAO } from '@/models/Itens/itensDAO';
import { ref, watch } from 'vue';

const loading = ref(false);
const inputSearch = ref('');

async function searchItems() {
  loading.value = true
  const response = await itemDAO.searchLike(inputSearch.value)
  console.log(response)
  loading.value = false
}

watch(inputSearch, (newValue) => {
  searchItems()
});
</script>

