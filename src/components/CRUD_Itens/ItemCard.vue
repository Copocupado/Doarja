<template>
  <v-card
    v-if="!fetchingInfo && entidade != null"
    class="d-flex bg-secondary"
  >
    <v-container class="d-flex flex-column justify-between overflow-y-auto">
      <v-card-text>
        <div :class="item.disponivel == 1 ? 'text-success' : 'text-error'">
          {{ item.disponivel == 1 ? 'Disponível' : 'Indisponível' }}
        </div>

        <p class="text-h4 font-weight-black">{{ item.descricao }}</p>

        <p>{{ item.quantidade }} Unidades disponíveis</p>
      </v-card-text>

      <v-card-actions>
        <v-btn
          color="background"
          text="Mais informações"
          variant="text"
          @click="reveal = true"
        />
      </v-card-actions>
    </v-container>

    <v-expand-transition class="overflow-y-auto">
      <v-card
        v-if="reveal"
        class="position-absolute w-100 bg-primary d-flex flex-column justify-between"
        height="100%"
        style="bottom: 0;"
      >
        <v-container class="d-flex flex-column justify-between">
          <v-card-text>
            <div>{{ entidade.endereco }}</div>

            <p class="text-h4 font-weight-black">Na entidade: {{ entidade.nome }}</p>

            <p>+55 {{ entidade.telefone }}</p>
          </v-card-text>

          <v-card-actions>
            <v-btn
              color="background"
              text="Fechar"
              variant="text"
              @click="reveal = false"
            />
          </v-card-actions>
        </v-container>
      </v-card>
    </v-expand-transition>
  </v-card>
  <v-card v-else>
    <v-skeleton-loader class="bg-background fill-width" type="card"/>
  </v-card>
</template>
<script lang="ts" setup>

  import { Entidade } from '@/models/Entidade/entidade';
  import { entidadeDAO } from '@/models/Entidade/entidadeDAO';
  import { Item } from '@/models/Itens/itens';
  import { ref, onMounted } from 'vue';

  const props = defineProps<{
    item: Item
  }>()

  const fetchingInfo = ref(false)

  const reveal= ref(false)

  const entidade = ref<Entidade | null>(null)

  async function getEntidade() {
    const response = await entidadeDAO.read({id: props.item.idEntidade}) as Array<Entidade>
    entidade.value=response.message
  }

  onMounted(async () => {
    fetchingInfo.value = true
    await getEntidade()
    fetchingInfo.value = false
  })
</script>
