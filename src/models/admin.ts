import axios from 'axios'

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

async function isUserAdmin (): Promise<boolean> {
  try {
    const response = await axios.get('http://localhost/Doarja/src/backend/DAOs/adminDAO.php', {
      params: {
        action: 'isUserAdmin',
      },
      withCredentials: true,
    })
    return response.data
  } catch (error) {
    console.error('Error checking user admin status:', error)
    return false
  }
}

async function saveSessionData (admin: object): Promise<boolean> {
  try {
    await axios.post(
      'http://localhost/Doarja/src/backend/SessionManager.php',
      {
        action: 'saveSessionData',
        data: { role: 'admin', ...admin },
      },
      {
        headers: {
          'Content-Type': 'application/json',
        },
        withCredentials: true,
      }
    )
    return true
  } catch (error) {
    return false
  }
}

async function getAdmin (
  email: string,
  password: string
): Promise<Object | null> {
  try {
    const response = await axios.get(
      'http://localhost/Doarja/src/backend/DAOs/adminDAO.php',
      {
        params: {
          action: 'getAdmin',
          email,
          password,
        },
      }
    )
    return response.data
  } catch (error) {
    console.error('Error fetching admin data:', error)
    return null
  }
}

export { isUserAdmin, saveSessionData, getAdmin, Admin }
