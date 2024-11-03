<template>
  <v-app v-if="admin != null" class="bg-background">
    <v-row no-gutters>
      <v-col cols="2">
        <Navbar
          :selected-section="selectedSection"
          :user-main-info="admin.nome"
          :user-pfp="admin.foto_de_perfil"
          :user-secondary-info="admin.email"
          @update-section="updateSection"
        />
      </v-col>

      <v-col class="py-12 px-12" cols="10">
        <v-container class="d-flex flex-column no-padding" style="height: 100%; max-height: 100%;">
          <div style="height: 100%; max-height: 100%;">
            <component :is="currentComponent" :currentlyAuthedAdmin="admin" />
          </div>
        </v-container>
      </v-col>

    </v-row>
  </v-app>
</template>

<script lang="ts" setup>
  import Navbar from '@/components/Navbar/Navbar.vue'
  import AdminTable from '@/components/CRUD_Administradores/table.vue'

  import { onMounted, ref } from 'vue'
  import router from '@/router'
  import { getSessionData } from '@/models/utility_classes'
  import { Admin } from '@/models/admin'

  const admin = ref(null)

  const selectedSection = ref('administradores')

  function updateSection (newSection: string) {
    selectedSection.value = newSection
  }

  const currentComponent = computed(() => {
    console.log('called')
    switch (selectedSection.value) {
      case 'administradores':
        return AdminTable
      default:
        return ''
    }
  })

  onMounted(async () => {
    const response = await getSessionData()
    if (response == null) {
      console.log('user cannot be null, redirecting to login...')
      router.push('/Admin/login')
      return
    }
    admin.value = new Admin(response.email, response.senha, response.nome, response.ativo, response.foto_de_perfil)
  })

</script>

<style scoped>
/* Add your styles here */
.loadingContainer{
  height: 100%;
  border-radius: 4px;
  background-color: rgba(0, 0, 0, 0.308);
}
.no-padding{
  margin: 0;
  padding: 0;
}
</style>
