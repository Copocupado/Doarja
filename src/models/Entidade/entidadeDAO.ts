import { BaseDAO } from '../baseDAO'
import { Entidade } from './entidade'

class EntidadeDAO extends BaseDAO<Entidade> {
  protected endpoint = 'DAOs/entidadeDAO.php'

  protected createInstance (data: any): Entidade {
    return new Entidade(data.nome, data.senha, data.endereco, data.telefone, data.id)
  }
}

const entidadeDAO = new EntidadeDAO()

export { entidadeDAO }
