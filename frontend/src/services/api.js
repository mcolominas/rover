import axios from 'axios'

const API_URL = 'http://localhost:8000/api'

function getErrorMessage(error) {
  if (error.response && error.response.data) {
    return error.response.data
  }

  if (error.request) {
    return { message: "Unable to connect to the planetary server. Please try again later." }
  }

  return { message: "An unexpected error occurred. Please try again." }
}

export const createPlanet = async () => {
  try {
    const response = await axios.post(`${API_URL}/planet`)
    return response.data
  } catch (error) {
    throw getErrorMessage(error)
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
    throw getErrorMessage(error)
  }
}

export const executeCommands = async (roverId, commands) => {
  try {
    const response = await axios.post(`${API_URL}/rovers/${roverId}/commands`, { commands })
    return response.data
  } catch (error) {
    throw getErrorMessage(error)
  }
}
