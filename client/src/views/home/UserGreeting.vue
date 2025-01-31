<script setup lang="ts">
import { useAuthStore } from '@/stores/auth'
import { useRouter, RouterLink } from 'vue-router'

const auth = useAuthStore()
const router = useRouter()

async function logout() {
  await auth.logout()
  router.push({ name: 'login' })
}
</script>

<template>
  <div class="text-center" v-if="auth.isAuthenticated">
    Hi, {{ auth.name }}!
    <span class="text-zinc-500 hover:underline cursor-pointer" @click="logout">Not you?</span>
  </div>
  <RouterLink
    :to="{ name: 'signup' }"
    v-else
    class="block text-center text-slate-700 hover:underline mb-6"
  >
    Create an account to save your drawings!
  </RouterLink>
</template>
