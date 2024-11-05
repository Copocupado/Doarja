class Item {
  id?: number
  idEntidade:number
  descricao: string
  quantidade: number
  disponivel: boolean

  constructor (
    idEntidade:number,
    descricao: string,
    quantidade: number,
    disponivel: boolean,
    id?: number,
  ) {
    this.id = id
    this.idEntidade = idEntidade
    this.descricao = descricao
    this.quantidade = quantidade
    this.disponivel = disponivel
  }
}

export { Item }
