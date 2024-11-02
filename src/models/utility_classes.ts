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

export {
  bootDatabase,
}
