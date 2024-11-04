import { BaseDAO } from '../baseDAO'
import { Admin } from './admin'
import { axiosGetRequest } from '../axios'

class AdminDAO extends BaseDAO<Admin> {
  protected endpoint = 'DAOs/adminDAO.php'

  protected createInstance (data: any): Admin {
    return new Admin(data.email, data.senha, data.nome, data.ativo, data.foto_de_perfil)
  }

  async isUserAdmin (): Promise<object> {
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
}

const adminDAO = new AdminDAO()

export { adminDAO }
