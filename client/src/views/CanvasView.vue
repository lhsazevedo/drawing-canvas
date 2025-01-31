<script setup lang="ts">
import DcCanvas from '@/components/DcCanvas.vue'
import DcLoading from '@/components/DcLoading.vue'
import axios from '@/axios'
import { useRoute, useRouter } from 'vue-router'
import { onMounted, ref } from 'vue'
import { AxiosError } from 'axios'

const route = useRoute()
const router = useRouter()
const sessionId = route.params.id as string
const loading = ref(true)

onMounted(async () => {
  try {
    await axios.get(`/drawing-sessions/${sessionId}`)
    loading.value = false
  } catch (e) {
    loading.value = false
    if (!(e instanceof AxiosError)) {
      throw e
    }

    router.push({ name: 'home' })
  }
})
</script>

<template>
  <DcLoading v-if="loading"></DcLoading>
  <DcCanvas v-else />
</template>
