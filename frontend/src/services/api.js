import axios from 'axios'

const API_URL = 'http://localhost:8000/api'

export const createPlanet = async () => {
  try {
    const response = await axios.post(`${API_URL}/planet`)
    return response.data
  } catch (error) {
    throw error.response.data
  }
}

export const launchRover = async (planet_id, x, y, direction) => {
  try {
    const response = await axios.post(`${API_URL}/rovers/launch`, {
      planet_id,
      x,
      y,
      direction,
    })
    return response.data
  } catch (error) {
    throw error.response.data
  }
}

export const executeCommands = async (roverId, commands) => {
  try {
    const response = await axios.post(`${API_URL}/rovers/${roverId}/commands`, { commands })
    return response.data
  } catch (error) {
    throw error.response.data
  }
}
