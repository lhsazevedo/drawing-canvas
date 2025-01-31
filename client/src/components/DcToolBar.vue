<script setup lang="ts">
import type { DrawingTool } from '@/types'
import DcToolBarButton from './DcToolBarButton.vue'

const toolModel = defineModel<DrawingTool>('tool', { required: true })
const colorModel = defineModel<string>('color', { required: true })
const sizeModel = defineModel<number>('size', { required: true })
const props = defineProps<{
  colors: string[]
  sizes: number[]
}>()
</script>

<template>
  <div class="fixed top-2 left-2 p-2 rounded-full bg-white shadow-md flex space-x-2 justify-center">
    <DcToolBarButton @click="toolModel = 'pen'" :class="{ 'bg-slate-100': toolModel === 'pen' }">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6">
        <path
          d="M16.84,2.73C16.45,2.73 16.07,2.88 15.77,3.17L13.65,5.29L18.95,10.6L21.07,8.5C21.67,7.89 21.67,6.94 21.07,6.36L17.9,3.17C17.6,2.88 17.22,2.73 16.84,2.73M12.94,6L4.84,14.11L7.4,14.39L7.58,16.68L9.86,16.85L10.15,19.41L18.25,11.3M4.25,15.04L2.5,21.73L9.2,19.94L8.96,17.78L6.65,17.61L6.47,15.29"
        />
      </svg>
    </DcToolBarButton>
    <DcToolBarButton
      @click="toolModel = 'eraser'"
      :class="{ 'bg-slate-100': toolModel === 'eraser' }"
    >
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6">
        <path
          d="M16.24,3.56L21.19,8.5C21.97,9.29 21.97,10.55 21.19,11.34L12,20.53C10.44,22.09 7.91,22.09 6.34,20.53L2.81,17C2.03,16.21 2.03,14.95 2.81,14.16L13.41,3.56C14.2,2.78 15.46,2.78 16.24,3.56M4.22,15.58L7.76,19.11C8.54,19.9 9.8,19.9 10.59,19.11L14.12,15.58L9.17,10.63L4.22,15.58Z"
        />
      </svg>
    </DcToolBarButton>
    <template v-if="toolModel === 'pen'">
      <div class="w-px self-stretch bg-slate-200"></div>
      <DcToolBarButton
        v-for="color in props.colors"
        :key="color"
        @click="colorModel = color"
        :class="{ 'bg-slate-100': colorModel === color }"
      >
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6">
          <circle cx="12" cy="12" r="8" :fill="color" />
        </svg>
      </DcToolBarButton>
      <div class="w-px self-stretch bg-slate-200"></div>
      <DcToolBarButton
        v-for="size in sizes"
        :key="size"
        @click="sizeModel = size"
        :class="{ 'bg-slate-100': sizeModel === size }"
      >
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6">
          <circle cx="12" cy="12" :r="size" :fill="color" />
        </svg>
      </DcToolBarButton>
    </template>
  </div>
</template>
