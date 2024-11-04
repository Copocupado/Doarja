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

/*async function isUserAdmin (): Promise<object> {
  try {
    return await axiosGetRequest({}, 'isUserAdmin', 'DAOs/adminDAO.php')
  } catch (error) {
    console.error('Error checking user admin status:', error)
    return {
      success: false,
      message: 'Erro ao realizar a chamada get: ' + error,
    }
  }
}

async function getAdmin (email: string, password: string): Promise<object> {
  try {
    return await axiosGetRequest(
      { action: 'getAdmin', email, password },
      'getAdmin',
      'DAOs/adminDAO.php'
    )
  } catch (error) {
    console.error('Error fetching admin data:', error)
    return {
      success: false,
      message: 'Nenhum administrador encontrado com essas credenciais',
    }
  }
}

async function getAllAdmins (): Promise<Array<Admin> | object> {
  try {
    const response = await axiosGetRequest(
      { action: 'getAllAdmins' },
      'getAllAdmins',
      'DAOs/adminDAO.php'
    )
    return {
      success: true,
      message: response.message.map(
        (item: any) => new Admin(item.email, item.senha, item.nome, item.ativo, item.foto_de_perfil)
      ),
    }
  } catch (error) {
    console.error('Error fetching all admin data:', error)
    return {
      success: false,
      message: 'Erro ao buscar todos os admins: ' + error,
    }
  }
}

async function addAdmin (admin: Admin): Promise<object> {
  try {
    return await axiosPostRequest(admin, 'addAdmin', 'DAOs/adminDAO.php')
  } catch (error) {
    console.error('Error adding admin:', error)
    return {
      success: false,
      message: 'Erro ao adicionar o administrador: ' + error,
    }
  }
}

async function deleteAdmin (admin: Admin): Promise<object> {
  try {
    return await axiosPostRequest(
      admin,
      'deleteAdmin',
      'DAOs/adminDAO.php'
    )
  } catch (error) {
    console.error('Error deleting admin:', error)
    return {
      success: false,
      message: 'Erro ao deletar o administrador: ' + error,
    }
  }
}

async function updateAdmin (admin: Admin): Promise<object> {
  try {
    return await axiosPostRequest(
      admin,
      'updateAdmin',
      'DAOs/adminDAO.php'
    )
  } catch (error) {
    console.error('Error updating admin:', error)
    return {
      success: false,
      message: 'Erro ao atualizar o administrador: ' + error,
    }
  }
}*/

export { Admin, isUserAdmin, getAdmin, getAllAdmins, addAdmin, deleteAdmin, updateAdmin }
