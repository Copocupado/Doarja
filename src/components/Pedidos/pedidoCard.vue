<template>
  <v-card
    class="d-flex bg-secondary"
  >
    <v-container class="d-flex flex-column justify-between">
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

    <v-expand-transition>
      <v-card
        v-if="reveal"
        class="position-absolute w-100 bg-primary d-flex flex-column justify-between"
        height="100%"
        style="bottom: 0;"
      >
        <v-card-text class="pb-0">
          <p class="text-h4">{{pedido.nomeRecebedor}}</p>

          <p class="text-medium-emphasis text-background">
            {{pedido.telefoneRecebedor}}
          </p>
        </v-card-text>

        <v-card-actions class="pt-0">
          <v-btn
            color="background"
            text="Fechar"
            variant="text"
            @click="reveal = false"
          />
        </v-card-actions>
      </v-card>
    </v-expand-transition>
  </v-card>
</template>
<script lang="ts" setup>

import { Entidade } from '@/models/Entidade/entidade';
import { Item } from '@/models/Itens/itens';
import { Pedido } from '@/models/Pedidos/Pedido';
import { ref } from 'vue';

const props = defineProps<{
  entidade:Entidade
  pedido:Pedido
  item:Item
}>()

const reveal= ref(false)
</script>
