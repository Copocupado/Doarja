
class Pessoa {
  id?: number
  nome: string
  telefone: string

  constructor (
    nome: string,
    telefone: string,
    id?: number,
  ) {
    this.id = id
    this.nome = nome,
    this.telefone = telefone
  }
}

export { Pessoa }
