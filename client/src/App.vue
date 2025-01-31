<script setup lang="ts">
import { RouterView } from 'vue-router'
import { useAuthStore } from './stores/auth'
import { onMounted, ref } from 'vue'

const auth = useAuthStore()
const authResolved = ref(false)

onMounted(async () => {
  await auth.check()
  authResolved.value = true
})
</script>

<template>
  <RouterView v-if="authResolved" />
  <div v-else class="w-screen h-screen flex justify-center items-center">
    <div class="flex-shrink">Loading...</div>
  </div>
</template>
