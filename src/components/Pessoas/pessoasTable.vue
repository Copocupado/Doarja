<template>
  <v-container class="d-flex flex-column ga-5">
    <div class="d-flex justify-space-between align-center">
      <div class="d-flex">
        <div class="text-h4 font-weight-medium text-primary">Pessoas</div>
        <v-chip class="ma-2" color="primary" label>
          <v-icon icon="mdi-account-circle" start />
          <div v-if="pessoasList != null">
            {{ pessoasList.length }} {{ pessoasList.length > 1 ? 'Pessoas' : 'Pessoa' }}
          </div>
        </v-chip>
      </div>
    </div>
    <v-data-table-server
      v-model:items-per-page="itemsPerPage"
      class="bg-secondary"
      :headers="headers"
      item-value="name"
      :items="serverItems"
      :items-length="totalItems"
      :loading="loading"
      loading-text="Carregando... Aguarde alguns instantes"
      no-data-text="Desculpe, nada encontrado"
      :search="name"
      style="border-radius: 12px;"
      @update:options="loadItems"
    >
      <template
        #item="{ item }"
      >
        <tr class="hoverable-row" style="cursor: pointer;" @click="()=>selectedItem(item)">
          <td>{{ item.id }}</td>
          <td class="text-end">{{ item.nome }}</td>
          <td class="text-end">{{ item.telefone }}</td>
        </tr>
      </template>
      <template #tfoot>
        <tr>
          <td>
            <v-text-field
              v-model="name"
              class="ma-2"
              density="compact"
              hide-details
              placeholder="Procure por um nome..."
            />
          </td>
        </tr>
      </template>
    </v-data-table-server>
  </v-container>
  <Snackbar :snackbar="snackbar" :snackbar-color="snackbarColor" :snackbar-text="snackbarText" @close="snackbar = false" />
  <v-dialog
    v-model="showPedidos"
    width="fill-width"
  >
    <v-container class="bg-background rounded-lg">
      <ShowPedidos :currently-authed-pessoa="selectedPessoa"></ShowPedidos>
    </v-container>
  </v-dialog>
</template>

<script lang="ts" setup>
  import { reactive, ref, watch } from 'vue'
  import Snackbar from '../snackbar.vue'
  import { Pessoa } from '@/models/Pessoas/Pessoa'
  import { pessoaDAO } from '@/models/Pessoas/PessoaDAO'

  const showPedidos = ref(false)
  let selectedPessoa = null

  const snackbar = ref(false)
  let snackbarText = ''
  let snackbarColor = ''

  const pessoasList = ref<Pessoa[] | null>(null)
  const itemsPerPage = ref(10)
  const headers = reactive([
    {
      title: 'ID',
      align: 'start',
      sortable: false,
      key: 'id',
    },
    { title: 'Nome', key: 'nome', align: 'end' },
    { title: 'Telefone', key: 'telefone', align: 'end' },
  ])

  const serverItems = ref([])
  const loading = ref(true)
  const totalItems = ref(0)
  const name = ref('')

  watch(name, () => {
    loadItems({ page: 1, itemsPerPage: itemsPerPage.value, sortBy: [] })
  })

  async function getPessoas () {
    const response = await pessoaDAO.fetchAll({})
    if (response.success != undefined && response.success === false) {
      showSnackbar(response)
      return
    }
    return response
  }

  async function loadItems ({ page, itemsPerPage, sortBy }: { page: number; itemsPerPage: number; sortBy: Array<{ key: string; order: string }> }): Promise<void> {
    loading.value = true
    await populateTable({ page, itemsPerPage, sortBy, search: name.value })
    loading.value = false
  }

  async function populateTable ({ page, itemsPerPage, sortBy, search }: { page: number; itemsPerPage: number; sortBy: Array<{ key: string; order: string }>; search: string }): Promise<void> {
    pessoasList.value = await getPessoas()

    const start = (page - 1) * itemsPerPage
    const end = start + itemsPerPage

    const items = pessoasList.value!.slice().filter((item: Pessoa) => {
      return search ? item.nome.toLowerCase().includes(search.toLowerCase()) : true
    })

    if (sortBy.length) {
      const sortKey = sortBy[0].key
      const sortOrder = sortBy[0].order
      items.sort((a: Record<string, any>, b: Record<string, any>) => {
        const aValue = a[sortKey]
        const bValue = b[sortKey]
        if (typeof aValue === 'string' && typeof bValue === 'string') {
          return sortOrder === 'desc' ? bValue.localeCompare(aValue) : aValue.localeCompare(bValue)
        }
        return sortOrder === 'desc' ? (bValue as number) - (aValue as number) : (aValue as number) - (bValue as number)
      })
    }

    serverItems.value = items.slice(start, end)
    totalItems.value = items.length
  }

  function selectedItem(item: Pessoa){
    selectedPessoa = item
    showPedidos.value = true
  }

  function showSnackbar (response: object) {
    snackbarColor = response.success ? 'success' : 'error'
    snackbarText = response.message
    snackbar.value = true
  }
</script>
<style scoped>
.hoverable-row {
  transition: background-color 0.3s ease;
}
.hoverable-row:hover {
  background-color: #0d5c63;
}
</style>
