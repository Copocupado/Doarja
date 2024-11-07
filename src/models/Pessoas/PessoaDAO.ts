import { BaseDAO } from '../baseDAO'
import { Pessoa } from './Pessoa'

class PessoaDAO extends BaseDAO<Pessoa> {
  protected endpoint = 'DAOs/pessoaDAO.php'

  protected createInstance (data: any): Pessoa {
    return new Pessoa(data.nome, data.telefone, data.id)
  }
}

const pessoaDAO = new PessoaDAO()

export { pessoaDAO }
