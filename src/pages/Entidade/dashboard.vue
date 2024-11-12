<template>
  <v-app>
    <Dashboard v-if="entidade != null" :currently-authed-entidade="entidade"></Dashboard>
  </v-app>
</template>

<script lang="ts" setup>
  import Dashboard from '@/components/CRUD_Entidades/dashboard.vue'
  import { onMounted, ref } from 'vue'
  import { getSessionData } from '@/models/utility_classes'
  import { Entidade } from '@/models/Entidade/entidade'

  const entidade = ref(null)

  onMounted(async () => {
    const response = await getSessionData()
    console.log(response)
    if (!response.success) {
      router.push('/Entidade/login')
      return
    }
    const message = response.message
    refresh(message)
  })

  function refresh (message: object) {
    entidade.value = new Entidade(message.nome, message.senha, message.endereco, message.telefone, message.id)
  }

</script>
