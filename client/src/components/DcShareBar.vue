<script setup lang="ts">
import { ref } from 'vue'
import DcToolBarButton from './DcToolBarButton.vue'

const props = defineProps<{
  sessionID: string
}>()

const copied = ref(false)

function share() {
  navigator.clipboard.writeText(props.sessionID)
  copied.value = true
  setTimeout(() => (copied.value = false), 2000)
}
</script>

<template>
  <div
    class="fixed bottom-2 left-2 py-2 px-4 rounded-full bg-white shadow-md flex space-x-2 justify-center items-center"
  >
    <div>Invite code:</div>
    <div class="flex items-center h-6 text-sm bg-gray-100 px-2 rounded-md">
      {{ copied ? 'Copied!' : sessionID }}
    </div>

    <DcToolBarButton @click="share">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6">
        <title>share</title>
        <path d="M21,12L14,5V9C7,10 4,15 3,20C5.5,16.5 9,14.9 14,14.9V19L21,12Z" />
      </svg>
    </DcToolBarButton>
  </div>
</template>
