<template>
  <v-dialog
    :model-value="modelValue"
    max-width="500px"
    @click:outside="emit('close')"
  >
    <v-container
      class="fill-height d-flex flex-column justify-start bg-background"
    >
      <v-container class="d-flex ga-3 align-center">
        <div class="text-h4 font-weight-medium text-primary no-padding">Informações do pedido</div>
        <v-icon color="primary" class="text-h3">mdi-hand-coin</v-icon>
      </v-container>
      <v-container class="flex-grow-1">
        <v-form v-model="isValid" class="d-flex flex-column justify-space-between ga-5 fill-height">
          <v-select
            :items="Object.keys(itemsDict)"
            label="Item selecionado"
            required
            variant="outlined"
            color="primary"
            class="text-primary"
            :rules="rules.nameRules"
            @update:modelValue="onItemSelected"
          />

          <TextFieldComponent
            v-model="quantidade"
            icon="mdi-pound"
            label="Quantidade"
            :placeholder="quantidadePlaceholder ?? ''"
            :rules="maximumQuantityRules"
            :disabled="quantidadePlaceholder == null"
            type="number"
            :hint="Number(quantidade) == selectedItemMaxQuantity ? 'Essa quantidade desativará o item' : ''"
            @update-model-value="(newValue: string) => quantidade = newValue"
          />
          <TextFieldComponent
            v-model="nomeRecebedor"
            icon="mdi-account-heart"
            label="Nome do recebedor"
            placeholder="Fulano"
            :rules="rules.nameRules"
            @update-model-value="(newValue: string) => nomeRecebedor = newValue"
          />
          <TextFieldComponent
            v-model="telefoneRecebedor"
            icon="mdi-phone-in-talk"
            label="Telefone do recebedor"
            placeholder="54123456789"
            prefix="+55"
            :rules="rules.phoneRules"
            @update-model-value="(newValue: string) => telefoneRecebedor = newValue"
          />
          <BtnComponent color="secondary" :disabled="!isValid" :loading="isLoading" text="Confirmar" @button-clicked="onSubmit"/>
        </v-form>
      </v-container>
    </v-container>
  </v-dialog>
</template>
<script lang="ts" setup>
/*
  quantidade: number
  nomeRecebedor: string
  telefoneRecebedor: string
  data: Date
*/
import { Entidade } from '@/models/Entidade/entidade';
import { Item } from '@/models/Itens/itens';
import { itemDAO } from '@/models/Itens/itensDAO';
import { Pedido } from '@/models/Pedidos/Pedido';
import { pedidoDAO } from '@/models/Pedidos/PedidosDAO';
import { Pessoa } from '@/models/Pessoas/Pessoa';
import { pessoaDAO } from '@/models/Pessoas/PessoaDAO';
import { ValidationRules } from '@/rules';
import { ref, computed, onMounted } from 'vue';

const props = defineProps<{
  modelValue: boolean,

  currentlyAuthedEntidade:Entidade
  itemsList: Item[]
}>()

const emit = defineEmits<{
  (e: 'added', response: object): void
  (e: 'update-item', descricao: string, quantidade: number, disponivel: boolean): void
  (e: 'close'): void
}>()

const isValid = ref(false)
const quantidade = ref('')
const nomeRecebedor = ref('')
const telefoneRecebedor = ref('')

const itemsDict = ref<Record<string, number>>({});
const quantidadePlaceholder = ref<string | null>(null)
let selectedItemMaxQuantity = <number | null>(null);
let selectedItemID = <number | null>(null)

const isLoading = ref(false)

const rulesUpdate = computed(() => {
  return new ValidationRules('')
})
const rules = computed(() => rulesUpdate.value)

const maximumQuantityRules = computed(() => [
  value => {
    if(value == null || value == ''){
      return 'A quantidade é obrigatória'
    }
    if(selectedItemMaxQuantity! < Number(quantidade.value)) {
      return 'Limite excedido!'
    }

    const numericPattern = /^[0-9]*$/
    return numericPattern.test(value) || 'Insira apenas números!'
  }
]);

onMounted(async () => {
  const stringsDict: Record<string, number> = {}

    props.itemsList.forEach(item => {
      stringsDict[item.toString()] = item.quantidade
    });

  itemsDict.value = stringsDict
})

function onItemSelected(selectedItem: string){
  selectedItemID = extractID(selectedItem)
  selectedItemMaxQuantity = itemsDict.value[selectedItem]
  quantidadePlaceholder.value = `Quantidade máxima: ${selectedItemMaxQuantity}`
}

async function onSubmit() {
  isLoading.value = true
  let answear = await pessoaDAO.read({nome: nomeRecebedor.value, telefone: telefoneRecebedor.value}) as Array<Pessoa>

  if(answear.length == 0) {

    const newPessoa = new Pessoa(nomeRecebedor.value, telefoneRecebedor.value)
    await pessoaDAO.create(newPessoa)
  }

  let response = await pessoaDAO.read({nome: nomeRecebedor.value, telefone: telefoneRecebedor.value})as Array<Pessoa>
  const pessoaCadastrada = response[0]

  response = await pedidoDAO.create(
    new Pedido(
      props.currentlyAuthedEntidade.id!,
      selectedItemID!,
      pessoaCadastrada.id!,
      Number(quantidade.value),
    )
  )

  let itemToUpdate: Item | null = null;

  props.itemsList.forEach(item => {
    if(item.id == selectedItemID){
      itemToUpdate = new Item(
        item.idEntidade,
        item.descricao,
        Number(item.quantidade)-Number(quantidade.value),
        !(Number(item.quantidade) == Number(quantidade.value)),
        item.id
      )
    }
  });

  response = await itemDAO.update(itemToUpdate!)

  isLoading.value = false
  emit('added', response)
}

function extractID(input: string) {
  const match = input.match(/ID:\s*(\d+)/);
  return match ? parseInt(match[1], 10) : null;
}

</script>
