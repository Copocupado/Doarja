import { BaseDAO } from '../baseDAO'
import { Item } from './itens'

class ItemDAO extends BaseDAO<Item> {
  protected endpoint = 'DAOs/itemDAO.php'

  protected createInstance (data: any): Item {
    return new Item(data.descricao, data.quantidade, data.disponivel, data.id, data.idEntidade)
  }
}

const itemDAO = new ItemDAO()

export { itemDAO }
