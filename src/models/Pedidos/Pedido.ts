
class Pedido {
  id?: number
  idEntidade:number
  idItem: number
  idPessoa: number
  quantidade: number
  data?: Date

  constructor (
    idEntidade: number,
    idItem: number,
    idPessoa: number,
    quantidade: number,
    data?: Date,
    id?: number,
  ) {
    this.id = id
    this.idItem = idItem
    this.idPessoa = idPessoa
    this.quantidade = quantidade
    this.data = data
    this.idEntidade = idEntidade
  }
}

export { Pedido }
