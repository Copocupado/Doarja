<template>
  <v-container class="d-flex flex-column ga-5">
    <div class="d-flex justify-space-between align-center">
      <div class="d-flex">
        <div class="text-h4 font-weight-medium text-primary">Itens</div>
        <v-chip class="ma-2" color="primary" label>
          <v-icon icon="mdi-package" start />
          <div v-if="itensList != null">
            {{ itensList.length }} {{ itensList.length > 1 ? 'Itens' : 'Item' }}
          </div>
        </v-chip>
      </div>
      <v-btn color="secondary" prepend-icon="mdi-plus-circle">
        <template #prepend>
          <v-icon class="text-background" />
        </template>
        <div class="text-background">Adicionar Item</div>
        <AddAdmin @add-administrador="addItem" />
      </v-btn>
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
      <template #item.descricao="{ item }">
        <div
          class="text-center"
          style="
            max-width: 800px;
            max-height: 300px;
            overflow-y: auto;
            word-wrap: break-word;
            white-space: pre-wrap;
          "
        >
          {{ item.descricao }}
        </div>
      </template>
      <template #item.opcoes="{ item }">
        <OptionsAdmins
          v-if="shouldDisplayOptions(item)"
          :admin="item"
          @delete-admin="deleteAdministrator"
          @update-admin="updateAdministrator"
        />
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
</template>

<script lang="ts" setup>
  import { reactive, ref, watch } from 'vue'
  import Snackbar from '../snackbar.vue'
  import { Item } from '@/models/Itens/itens'
  import { itemDAO } from '@/models/Itens/itensDAO'
  import { Entidade } from '@/models/Entidade/entidade'

  const props = defineProps<{
    currentlyAuthedEntidade: Entidade,
  }>()

  const snackbar = ref(false)
  let snackbarText = ''
  let snackbarColor = ''

  const itensList = ref<Item[] | null>(null)
  const itemsPerPage = ref(10)
  const headers = reactive([
    {
      title: 'Item',
      align: 'start',
      sortable: true,
      key: 'id',
    },
    { title: 'Descrição', key: 'descricao', align: 'center' },
    { title: 'Disponível', key: 'disponivel', align: 'end' },
    {
      title: 'Opções',
      align: 'end',
      key: 'opcoes',
      sortable: false,
    },
  ])

  const serverItems = ref([])
  const loading = ref(true)
  const totalItems = ref(0)
  const name = ref('')

  watch(name, () => {
    loadItems({ page: 1, itemsPerPage: itemsPerPage.value, sortBy: [] })
  })

  async function getItems () {
    const response = await itemDAO.read({ idEntidade: props.currentlyAuthedEntidade.id })
    console.log(response)
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
    itensList.value = await getItems()

    const start = (page - 1) * itemsPerPage
    const end = start + itemsPerPage

    const items = itensList.value!.slice().map((item: Item) => {
      const isAtivo = Number(item.disponivel)
      delete item.disponivel
      return {
        ...item,
        disponivel: isAtivo ? 'Disponivel' : 'Nao disponivel',
      }
    }).filter((item: Item) => {
      return search ? item.descricao.toLowerCase().includes(search.toLowerCase()) : true
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

  async function addItem (name: string, email: string, password: string, isActive: boolean) {
    const newAdmin = new Admin(email, password, name, isActive, '/src/assets/admin-default-pfp.png')
    const response = await adminDAO.create(newAdmin)

    await loadItems({ page: 1, itemsPerPage: itemsPerPage.value, sortBy: [] })

    showSnackbar(response)
  }

  async function deleteAdministrator (admin: Admin) {
    const response = await adminDAO.delete(admin)

    await loadItems({ page: 1, itemsPerPage: itemsPerPage.value, sortBy: [] })

    showSnackbar(response)
  }
  async function updateAdministrator (admin: Admin, value: boolean) {
    const newAdmin = new Admin(admin.email, admin.senha, admin.nome, value, admin.foto_de_perfil)
    const response = await adminDAO.update(newAdmin)

    await loadItems({ page: 1, itemsPerPage: itemsPerPage.value, sortBy: [] })
    showSnackbar(response)
  }

  function shouldDisplayOptions (admin: Item) {
    return true
  }

  function showSnackbar (response: object) {
    snackbarColor = response.success ? 'success' : 'error'
    snackbarText = response.message
    snackbar.value = true
  }
</script>
