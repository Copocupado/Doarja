<template>
  <v-container class="d-flex flex-column ga-5">
    <div class="d-flex justify-space-between align-center">
      <div class="d-flex">
        <div class="text-h4 font-weight-medium text-primary">Administradores</div>
        <v-chip class="ma-2" color="primary" label>
          <v-icon icon="mdi-account-hard-hat" start />
          <div v-if="adminList != null">
            {{ adminList.length }} {{ adminList.length > 1 ? 'Administradores' : 'Administrador' }}
          </div>
        </v-chip>
      </div>
      <v-btn color="secondary" prepend-icon="mdi-plus-circle">
        <template #prepend>
          <v-icon class="text-background" />
        </template>
        <div class="text-background">Adicionar Administrador</div>
        <AddAdmin @add-administrador="addAdministrador" />
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
      <template #item.name="{ item }">
        <div class="d-flex ga-3">
          <v-avatar>
            <v-img :src="item.foto_de_perfil" />
          </v-avatar>
          <div class="d-flex align-center">
            {{ item.nome }}
          </div>
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
  import { addAdmin, Admin, deleteAdmin, getAllAdmins, updateAdmin } from '@/models/admin'
  import OptionsAdmins from './optionsAdmins.vue'
  import Snackbar from '../snackbar.vue'

  const props = defineProps<{
    currentlyAuthedAdmin: Admin,
  }>()

  const snackbar = ref(false)
  let snackbarText = 'dasdasdasdasdasdasd'
  let snackbarColor = 'success'

  const adminList = ref<Admin[] | null>(null)
  const itemsPerPage = ref(10)
  const headers = reactive([
    {
      title: 'Administrador',
      align: 'start',
      sortable: false,
      key: 'name',
    },
    { title: 'Email', key: 'email', align: 'end' },
    { title: 'Ativo', key: 'ativo', align: 'end' },
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

  async function getAdmins () {
    return getAllAdmins()
  }

  async function loadItems ({ page, itemsPerPage, sortBy }: { page: number; itemsPerPage: number; sortBy: Array<{ key: string; order: string }> }): Promise<void> {
    loading.value = true
    await populateTable({ page, itemsPerPage, sortBy, search: name.value })
    loading.value = false
  }

  async function populateTable ({ page, itemsPerPage, sortBy, search }: { page: number; itemsPerPage: number; sortBy: Array<{ key: string; order: string }>; search: string }): Promise<void> {
    adminList.value = await getAdmins()

    const start = (page - 1) * itemsPerPage
    const end = start + itemsPerPage

    const items = adminList.value!.slice().map((item: Admin) => {
      const isAtivo = Number(item.ativo)
      delete item.ativo
      return {
        ...item,
        ativo: isAtivo ? 'Ativado' : 'Desativado',
      }
    }).filter((item: Admin) => {
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

  async function addAdministrador (name: string, email: string, password: string, isActive: boolean) {
    const newAdmin = new Admin(email, password, name, isActive, '/src/assets/admin-default-pfp.png')
    const response = await addAdmin(newAdmin)

    await loadItems({ page: 1, itemsPerPage: itemsPerPage.value, sortBy: [] })

    showSnackbar(response)
  }

  async function deleteAdministrator (admin: Admin) {
    const response = await deleteAdmin(admin)

    await loadItems({ page: 1, itemsPerPage: itemsPerPage.value, sortBy: [] })

    showSnackbar(response)
  }
  async function updateAdministrator (admin: Admin, value: boolean) {
    admin.ativo = value
    const response = await updateAdmin(admin)

    await loadItems({ page: 1, itemsPerPage: itemsPerPage.value, sortBy: [] })
    showSnackbar(response)
  }

  function shouldDisplayOptions (admin: Admin) {
    if (admin.email === 'root') {
      return false
    }
    if (props.currentlyAuthedAdmin.email === admin.email) {
      return false
    }
    return true
  }

  function showSnackbar (response: object) {
    snackbarColor = response.success ? 'success' : 'error'
    snackbarText = response.message
    snackbar.value = true
  }
</script>
