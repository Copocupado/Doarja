import axios from 'axios'

async function bootDatabase (): Promise<'success' | 'failure'> {
  try {
    await axios.post('http://localhost/Doarja/src/backend/boot.php')
    return 'success'
  } catch (error) {
    console.error('Error fetching data:', error)
    return 'failure'
  }
}

async function getSessionData (): Promise<object | null> {
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
    return null
  }
}

async function logOut (): Promise<boolean> {
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
    return true
  } catch (error) {
    return false
  }
}

export {
  bootDatabase,
  getSessionData,
  logOut,
}
