import { ref } from 'vue'
import { defineStore } from 'pinia'
import axios from '@/axios'

export const useAuthStore = defineStore('auth', () => {
  const isAuthenticated = ref(false)

  async function login() {
    await axios.get('/auth/me')
    isAuthenticated.value = true
  }

  return { login, isAuthenticated }
})
