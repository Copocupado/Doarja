import axios from 'axios'
import { axiosGetRequest, axiosPostRequest } from './axios'

async function bootDatabase (): Promise<object> {
  try {
    const response = await axios.post('http://localhost/Doarja/src/backend/boot.php')
    return response.data
  } catch (error) {
    console.error('Error fetching data:', error)
    return {
      success: false,
      message: 'Erro ao inicializar o banco de dados: ' + error,
    }
  }
}

async function getSessionData (): Promise<object> {
  try {
    const response = await axios.get('http://localhost/Doarja/src/backend/SessionManager.php', {
      params: {
        action: 'getSessionData',
      },
      withCredentials: true,
    })
    return response.data
  } catch (error) {
    console.error('Error checking user admin status:', error)
    return {
      success: false,
      message: 'Erro ao obter os dados da sessao: ' + error,
    }
  }
}

async function saveSessionData (data: object): Promise<object> {
  try {
    return await axiosPostRequest(data, 'saveSessionData', 'SessionManager.php')
  } catch (error) {
    return {
      success: false,
      message: 'Erro ao salvar os dados na sessao: ' + error,
    }
  }
}

async function logOut (): Promise<object> {
  try {
    const response = await axios.post(
      'http://localhost/Doarja/src/backend/SessionManager.php',
      {
        action: 'destroySession',
      },
      {
        headers: {
          'Content-Type': 'application/json',
        },
        withCredentials: true,
      }
    )
    return response.data
  } catch (error) {
    return {
      success: false,
      message: 'Erro ao usuario sair: ' + error,
    }
  }
}

export {
  bootDatabase,
  getSessionData,
  saveSessionData,
  logOut,
}
