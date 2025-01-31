<script setup lang="ts">
import { ref } from 'vue'
import axios from '@/axios'
import { useRouter } from 'vue-router'
import { AxiosError } from 'axios'
import DcButton from '@/components/DcButton.vue'
import DcTextInput from '@/components/DcTextInput.vue'

const router = useRouter()
const code = ref('')
const codeError = ref<string | undefined>(undefined)

async function join() {
  try {
    await axios.get(`/drawing-sessions/${code.value}`)
    router.push({ name: 'canvas', params: { id: code.value } })
  } catch (e) {
    if (e instanceof AxiosError && e.response?.status === 404) {
      codeError.value = "We couldn't find that session. Please check the code and try again."
    }
    throw e
  }
}
</script>

<template>
  <div class="space-y-2 bg-white p-4 rounded-xl shadow-md">
    <div>Got a code? Enter it below to join</div>
    <div class="flex gap-2 items-start">
      <DcTextInput
        v-model="code"
        :error="codeError"
        class="flex-grow"
        placeholder="Enter code here"
      />
      <DcButton @click="join">Join</DcButton>
    </div>
  </div>
</template>
