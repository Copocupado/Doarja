class Entidade {
  id?: number
  nome: string
  senha: string
  endereco: string
  telefone: string

  constructor (
    nome: string,
    senha: string,
    endereco: string,
    telefone: string,
    id?: number,
  ) {
    this.nome = nome
    this.senha = senha
    this.endereco = endereco
    this.telefone = telefone
    this.id = id
  }

  toObject (): object {
    return {
      nome: this.nome,
      senha: this.senha,
      endereco: this.endereco,
      telefone: this.telefone,
    }
  }
}

export { Entidade }
