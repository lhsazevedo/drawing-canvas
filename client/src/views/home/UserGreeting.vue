<script setup lang="ts">
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router'

const auth = useAuthStore()
const router = useRouter()

async function logout() {
  await auth.logout()
  router.push({ name: 'login' })
}
</script>

<template>
  <div v-if="auth.isAuthenticated">
    Hi, {{ auth.name }}!
    <span class="text-zinc-500 hover:underline cursor-pointer" @click="logout">Not you?</span>
  </div>
  <div v-else>Create an account to save your progress!</div>
</template>
