import { axiosGetRequest, axiosPostRequest } from './axios'

abstract class BaseDAO<T> {
  protected abstract endpoint: string;

  async fetchAll (item?: T): Promise<T[] | object> {
    try {
      const response = await axiosGetRequest(item, 'fetchAll', this.endpoint)
      return response.message.map((item: any) => this.createInstance(item))
    } catch (error) {
      console.error('Error fetching all items:', error)
      return {
        success: false,
        message: 'Error fetching all items: ' + error,
      }
    }
  }

  async create (item: T): Promise<object> {
    try {
      return await axiosPostRequest(item, 'create', this.endpoint)
    } catch (error) {
      console.error('Error creating item:', error)
      return {
        success: false,
        message: 'Error creating item: ' + error,
      }
    }
  }

  async read (item: object): Promise<T[] | T | object> {
    try {
      const response = await axiosGetRequest(item, 'read', this.endpoint)

      if (Array.isArray(response.message)) {
        return response.message.map((item: any) => this.createInstance(item))
      } else {
        return response
      }
    } catch (error) {
      console.error('Error reading item:', error)
      return {
        success: false,
        message: 'Error reading item: ' + error,
      }
    }
  }

  async update (item: T): Promise<object> {
    try {
      return await axiosPostRequest(item, 'update', this.endpoint)
    } catch (error) {
      console.error('Error updating item:', error)
      return {
        success: false,
        message: 'Error updating item: ' + error,
      }
    }
  }

  async delete (item: T): Promise<object> {
    try {
      return await axiosPostRequest(item, 'delete', this.endpoint)
    } catch (error) {
      console.error('Error deleting item:', error)
      return {
        success: false,
        message: 'Error deleting item: ' + error,
      }
    }
  }

  async isItem (): Promise<object> {
    try {
      return await axiosGetRequest({}, 'isItem', this.endpoint)
    } catch (error) {
      console.error('Error checking item status:', error)
      return {
        success: false,
        message: 'Erro ao realizar a chamada get: ' + error,
      }
    }
  }

  protected abstract createInstance(data: any): T;
}

export { BaseDAO }
