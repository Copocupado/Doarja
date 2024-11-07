<template>
  <v-card
    class="d-flex bg-secondary"
  >
    <v-container class="d-flex flex-column justify-between overflow-y-auto">
      <v-card-text>
        <div>{{ entidade.nome }}</div>

        <p class="text-h4 font-weight-black">{{ item.descricao }}</p>

        <p>{{ pedido.quantidade }} Unidades</p>
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

    <v-expand-transition class="overflow-y-auto" v-if="pessoa != null">
      <v-card
        v-if="reveal"
        class="position-absolute w-100 bg-primary d-flex flex-column justify-between"
        height="100%"
        style="bottom: 0;"
      >
        <v-container class="d-flex flex-column justify-between">
          <v-card-text>
            <div>{{ formatDate(pedido.data!) }}</div>

            <p class="text-h4 font-weight-black">{{ pessoa.nome }}</p>

            <p>+55 {{ pessoa.telefone }}</p>
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
</template>
<script lang="ts" setup>

  import { Entidade } from '@/models/Entidade/entidade';
  import { Item } from '@/models/Itens/itens';
  import { Pedido } from '@/models/Pedidos/Pedido';
  import { Pessoa } from '@/models/Pessoas/Pessoa';
  import { pessoaDAO } from '@/models/Pessoas/PessoaDAO';
  import { ref, onMounted } from 'vue';

  const props = defineProps<{
    entidade: Entidade
    pedido: Pedido
    item: Item
  }>()

  const reveal= ref(false)
  const pessoa = ref<Pessoa | null>(null)

  function formatDate(timestamp) {
    if (!timestamp) return '';

    const date = new Date(timestamp);
    return new Intl.DateTimeFormat('pt-BR', {
      day: '2-digit',
      month: '2-digit',
      year: '2-digit',
      hour: '2-digit',
      minute: '2-digit',
    }).format(date);
  }

  onMounted(async () => {
    const response = await pessoaDAO.read({id: props.pedido.idPessoa}) as Array<Pessoa>
    pessoa.value = response[0]
  })
</script>
