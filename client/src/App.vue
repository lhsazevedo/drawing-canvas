<script setup lang="ts">
import { RouterView } from 'vue-router'
import { useAuthStore } from './stores/auth'
import { onMounted, ref } from 'vue'
import DcLoading from '@/components/DcLoading.vue'

const auth = useAuthStore()
const authResolved = ref(false)

onMounted(async () => {
  await auth.check()
  authResolved.value = true
})
</script>

<template>
  <RouterView v-if="authResolved" />
  <DcLoading v-else />
</template>
