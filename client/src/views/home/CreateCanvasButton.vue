<script setup lang="ts">
import { useRouter } from 'vue-router'
import DcButton from '@/components/DcButton.vue'

const router = useRouter()
</script>

<template>
  <DcButton class="block w-full" @click="() => router.push({ name: 'create' })">
    Create new Canvas
  </DcButton>
</template>
