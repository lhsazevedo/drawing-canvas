<script setup lang="ts">
import Button from '@/components/DcButton.vue'
import TextInput from '@/components/DcTextInput.vue'
import axios from '@/axios'
import { onMounted, ref } from 'vue'
import { useRouter, RouterLink } from 'vue-router'

const router = useRouter()

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
  <main class="max-w-md mx-auto p-4 space-y-8">
    <h1 class="text-3xl font-bold text-center">Drawing Canvas</h1>

    <Button class="block w-full" @click="() => router.push({ name: 'create' })">
      Create new Canvas
    </Button>

    <div class="space-y-2">
      <div class="text-lg">Got a code? Enter it below to join</div>
      <div class="flex gap-2">
        <TextInput class="flex-grow" placeholder="Enter code here" />
        <Button>Join</Button>
      </div>
    </div>

    <div class="space-y-4">
      <h2 class="text-2xl">Your Canvases</h2>
      <div class="space-y-4">
        <RouterLink
          v-for="session in drawingSessions"
          :to="{ name: 'canvas', params: { id: session.public_id } }"
          :key="session.public_id"
          class="rounded-xl border-2 block border-black p-4"
        >
          <h3 class="text-xl">{{ session.name }}</h3>
          <p>{{ session.description }}</p>
          <p>{{ session.is_public ? 'Public' : 'Private' }}</p>
        </RouterLink>
      </div>
    </div>
  </main>
</template>
