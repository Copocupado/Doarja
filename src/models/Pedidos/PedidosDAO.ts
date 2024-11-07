import { BaseDAO } from '../baseDAO'
import { Pedido } from './Pedido'

class PedidoDAO extends BaseDAO<Pedido> {
  protected endpoint = 'DAOs/pedidoDAO.php'

  protected createInstance (data: any): Pedido {
    return new Pedido(data.idEntidade, data.idItem, data.idPessoa, data.quantidade, data.data, data.id)
  }
}

const pedidoDAO = new PedidoDAO()

export { pedidoDAO }
