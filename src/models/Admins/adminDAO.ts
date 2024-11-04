import { BaseDAO } from '../baseDAO'
import { Admin } from './admin'

class AdminDAO extends BaseDAO<Admin> {
  protected endpoint = 'DAOs/adminDAO.php'

  protected createInstance (data: any): Admin {
    return new Admin(data.email, data.senha, data.nome, data.ativo, data.foto_de_perfil)
  }
}

const adminDAO = new AdminDAO()

export { adminDAO }
