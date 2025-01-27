<script setup lang="ts">
defineProps<{
  label?: string,
  error?: string,
}>()

const modelValue = defineModel<boolean>()
</script>

<template>
  <div>
    <input
      type="checkbox"
      class="inline-block mr-2"
      :class="{ 'border-red-500': error }"
      v-model="modelValue"
    />
    <label
      v-if="label"
      class="text-lg"
      :class="{ 'text-red-500': error }"
    >
      {{ label }}
    </label>
    <div class="text-red-500">{{ error }}</div>
  </div>
</template>
