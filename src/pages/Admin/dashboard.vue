<template>
  <v-app v-if="admin != null" class="bg-background">
    <v-row no-gutters>
      <v-col cols="2">
        <Navbar
          :menu-items="[
            { title: 'Administradores', icon: 'mdi-account-hard-hat', value: 'administradores' },
          ]"
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
  import AdminTable from '@/components/CRUD_Administradores/tableAdmins.vue'

  import { onMounted, ref } from 'vue'
  import router from '@/router'
  import { getSessionData } from '@/models/utility_classes'
  import { Admin } from '@/models/Admins/admin'

  const admin = ref(null)

  const selectedSection = ref('administradores')

  function updateSection (newSection: string) {
    selectedSection.value = newSection
  }

  const currentComponent = computed(() => {
    switch (selectedSection.value) {
      case 'administradores':
        return AdminTable
      default:
        return ''
    }
  })

  onMounted(async () => {
    const response = await getSessionData()
    if (!response.success) {
      console.log(response.message)
      router.push('/Admin/login')
      return
    }
    admin.value = new Admin(response.message.email, response.message.senha, response.message.nome, response.message.ativo, response.message.foto_de_perfil)
  })

</script>
