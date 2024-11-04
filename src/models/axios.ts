import axios from 'axios'

const urlPath = 'http://localhost/Doarja/src/backend/'

async function axiosGetRequest (data: object, funcToExecute: string, path: string): Promise<object> {
  const requestUrl = urlPath + path
  try {
    const response = await axios.get(
      requestUrl,
      {
        params: {
          action: funcToExecute,
          data,
        },
        withCredentials: true,
      }
    )
    return response.data
  } catch (error) {
    console.error('Error on get request:', error)
    return {
      success: false,
      message: 'Erro ao realizar a chamada get: ' + error,
    }
  }
}

async function axiosPostRequest (data: object, funcToExecute: string, path: string): Promise<object> {
  const requestUrl = urlPath + path
  try {
    const response = await axios.post(
      requestUrl,
      {
        action: funcToExecute,
        data,
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
    console.error('Error on post request:', error)
    return {
      success: false,
      message: 'Erro ao realizar a chamada post: ' + error,
    }
  }
}

export {
  axiosGetRequest,
  axiosPostRequest,
}
