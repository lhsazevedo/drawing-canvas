<script setup lang="ts"></script>

<template>
  <button
    class="text-center text-xl bg-black text-white hover:bg-white hover:text-black cursor-pointer font-bold py-2 px-4 rounded-xl border-2 border-black"
    @click="$emit('click', $event)"
  >
    <slot></slot>
  </button>
</template>
