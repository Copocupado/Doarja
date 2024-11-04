class Admin {
  email: string
  senha: string
  nome: string
  ativo: boolean
  foto_de_perfil: string

  constructor (
    email: string,
    senha: string,
    nome: string,
    ativo: boolean,
    fotoDePerfil: string
  ) {
    this.email = email
    this.senha = senha
    this.nome = nome
    this.ativo = ativo
    this.foto_de_perfil = fotoDePerfil
  }

  toObject (): object {
    return {
      email: this.email,
      senha: this.senha,
      nome: this.nome,
      ativo: this.ativo,
      foto_de_perfil: this.foto_de_perfil,
    }
  }
}

export { Admin }
