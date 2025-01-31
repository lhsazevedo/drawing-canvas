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
    <!-- TODO: Accessible label -->
    <label
      v-if="label"
      :for="id || undefined"
      class="block text-lg"
      :class="{ 'text-red-500': error }"
    >
      {{ label }}
    </label>
    <input
      type="text"
      :id="id || undefined"
      v-model="modelValue"
      v-bind="$attrs"
      :placeholder="placeholder"
      class="w-full px-4 py-2 rounded-lg border-2 border-black"
      :class="{ 'border-red-500': error }"
    />
    <div class="text-red-500">{{ error }}</div>
  </div>
</template>
