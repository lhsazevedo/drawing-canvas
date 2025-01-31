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
    <h2 class="text-xl">Your Canvases</h2>
    <div class="space-y-4">
      <RouterLink
        v-for="session in drawingSessions"
        :to="{ name: 'canvas', params: { id: session.public_id } }"
        :key="session.public_id"
        class="block p-4 bg-white rounded-xl shadow-md space-y-1"
      >
        <div class="flex justify-between">
          <h3 class="text-lg">{{ session.name }}</h3>
          <div
            v-if="!session.is_public"
            class="px-1 h-6 text-xs text-slate-500 border-slate-500 rounded-full border flex items-center justify-center"
          >
            Private
          </div>
        </div>
        <p class="text-slate-500 text-sm">{{ session.description }}</p>
      </RouterLink>
    </div>
  </div>
</template>
