import { axiosGetRequest } from '../axios'
import { BaseDAO } from '../baseDAO'
import { Item } from './itens'

class ItemDAO extends BaseDAO<Item> {
  protected endpoint = 'DAOs/itemDAO.php'

  protected createInstance (data: any): Item {
    return new Item( data.idEntidade, data.descricao, data.quantidade, data.disponivel, data.id)
  }

  public async searchLike(inputSearch: string) {
    try {
      return await axiosGetRequest({inputSearch: inputSearch}, 'searchLike', this.endpoint)
    } catch (error) {
      console.error('Error checking item status:', error)
      return {
        success: false,
        message: 'Erro ao realizar a chamada get: ' + error,
      }
    }
  }
}

const itemDAO = new ItemDAO()

export { itemDAO }
