
class Pedido {
  id?: number
  idEntidade:number
  idItem: number
  quantidade: number
  nomeRecebedor: string
  telefoneRecebedor: string
  data: Date

  constructor (
    idEntidade: number,
    idItem: number,
    quantidade: number,
    nomeRecebedor: string,
    telefoneRecebedor: string,
    data: Date,
    id?: number,
  ) {
    this.id = id
    this.idItem = idItem
    this.quantidade = quantidade
    this.nomeRecebedor = nomeRecebedor
    this.telefoneRecebedor = telefoneRecebedor
    this.data = data
    this.idEntidade = idEntidade
  }
}

export { Pedido }
