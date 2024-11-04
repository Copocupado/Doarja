class Item {
  id?: number
  idEntidade?:number
  descricao: string
  quantidade: number
  disponivel: boolean

  constructor (
    descricao: string,
    quantidade: number,
    disponivel: boolean,
    id?: number,
    idEntidade?:number,
  ) {
    this.id = id
    this.idEntidade = idEntidade
    this.descricao = descricao
    this.quantidade = quantidade
    this.disponivel = disponivel
  }
}

export { Item }
