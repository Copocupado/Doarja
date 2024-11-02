<template>
  <v-app class="bg-background">
    <v-row no-gutters>
      <v-col cols="2">
        <Navbar
          v-if="admin != null"
          selected-section="first"
          :user-main-info="admin.nome"
          :user-pfp="admin.foto_de_perfil"
          :user-secondary-info="admin.email"
        />
      </v-col>

      <v-col class="py-12 px-12" cols="10">
        <v-container class="d-flex flex-column no-padding" style="height: 100%; max-height: 100%;">
          <div class="loadingContainer">
            <v-container class="d-flex justify-center align-center" style="height: 100%;">
              <v-progress-circular
                color="blue-lighten-3"
                indeterminate
                :size="60"
                :width="5"
              />
            </v-container>
          </div>
        </v-container>
      </v-col>

    </v-row>
  </v-app>
</template>

<script setup>
  import Navbar from '@/components/Navbar/Navbar.vue'

  import { onMounted, ref } from 'vue'
  import router from '@/router'
  import { getSessionData } from '@/models/utility_classes'
  import { Admin } from '@/models/admin'

  const admin = ref(null)

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
