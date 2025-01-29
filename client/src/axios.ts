import axios from 'axios'

const instance = axios.create({
  baseURL: import.meta.env.VITE_API_URL,
})

instance.defaults.withCredentials = true
instance.defaults.withXSRFToken = true

export default instance
