<script setup lang="ts">
import DcCanvas from '@/components/DcCanvas.vue'
import DcLoading from '@/components/DcLoading.vue'
import axios from '@/axios'
import { useRoute, useRouter } from 'vue-router'
import { onMounted, ref } from 'vue'
import { AxiosError } from 'axios'
import type DrawingSessionApiResource from '~/types.ts'

const route = useRoute()
const router = useRouter()
const sessionId = route.params.id as string
const loading = ref(true)

const drawingSession = ref<DrawingSessionApiResource | null>(null)

onMounted(async () => {
  try {
    const response = await axios.get(`/drawing-sessions/${sessionId}`)
    drawingSession.value = response.data.data
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
  <DcCanvas :share-bar="drawingSession.is_public" v-else />
</template>
