<script setup lang="ts">
const modelValue = defineModel()
defineProps<{
  placeholder?: string
  label?: string
  id?: string
  error?: string
}>()
</script>

<template>
  <div>
    <label v-if="label" :for="id || undefined" :class="[error ? 'text-red-500' : 'text-slate-700']">
      {{ label }}
    </label>
    <input
      type="text"
      :id="id || undefined"
      v-model="modelValue"
      v-bind="$attrs"
      :placeholder="placeholder"
      class="w-full px-4 py-2 rounded-lg bg-white border border-slate-300"
      :class="{ 'border-red-500': error }"
    />
    <div v-if="error" class="text-red-500">{{ error }}</div>
  </div>
</template>
