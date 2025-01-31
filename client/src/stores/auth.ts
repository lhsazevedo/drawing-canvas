import { ref } from 'vue'
import { defineStore } from 'pinia'
import axios from '@/axios'
import { AxiosError } from 'axios'
import type { Result } from '@/types'

type ApiValidationErrors = {
  [key: string]: string
}

export const useAuthStore = defineStore('auth', () => {
  const isAuthenticated = ref(false)
  const name = ref<string | null>(null)

  async function check(): Promise<boolean> {
    const res = await axios.get('/auth/me')
    if (!res?.data?.data?.id) {
      isAuthenticated.value = false
      name.value = null
      return false
    }
    isAuthenticated.value = true
    name.value = res.data.data.name
    return true
  }

  async function login(credentials: {
    email: string
    password: string
  }): Promise<Result<void, ApiValidationErrors>> {
    try {
      await axios.post('/login', credentials)
      const res = await axios.get('/auth/me')
      isAuthenticated.value = true
      name.value = res.data.data.name
      return { success: true, data: undefined }
    } catch (e) {
      if (e instanceof AxiosError && e?.response?.data.errors) {
        return { success: false, error: e.response.data.errors }
      }
      throw e
    }
  }

  async function logout() {
    await axios.post('/logout')
    isAuthenticated.value = false
    name.value = null
  }

  return { isAuthenticated, name, login, check, logout }
})
