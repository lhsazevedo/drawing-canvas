<script setup lang="ts"></script>

<template>
  <button
    class="text-center text-md bg-blue-600 text-white hover:bg-blue-700 cursor-pointer font-bold py-2 px-4 rounded-xl"
    @click="$emit('click', $event)"
  >
    <slot></slot>
  </button>
</template>
