<script setup lang="ts">
import axios from '@/axios'
import { onMounted, ref } from 'vue'
import { RouterLink } from 'vue-router'

interface DrawingSession {
  id: number
  public_id: string
  name: string
  description: string
  is_public: boolean
}

const drawingSessions = ref<DrawingSession[]>([])

onMounted(async () => {
  const res = await axios.get<{ data: DrawingSession[] }>('/drawing-sessions')
  drawingSessions.value = res.data.data
})
</script>

<template>
  <div v-if="drawingSessions" class="space-y-4">
    <h2 class="text-2xl">Your Canvases</h2>
    <div class="space-y-4">
      <RouterLink
        v-for="session in drawingSessions"
        :to="{ name: 'canvas', params: { id: session.public_id } }"
        :key="session.public_id"
        class="rounded-xl border-2 block border-black p-4"
      >
        <div class="flex justify-between">
          <h3 class="text-xl">{{ session.name }}</h3>
          <div
            v-if="!session.is_public"
            class="px-2 text-sm rounded-full border flex items-center justify-center"
          >
            Private
          </div>
        </div>
        <p>{{ session.description }}</p>
      </RouterLink>
    </div>
  </div>
</template>
