<script setup lang="ts">

</script>

<template>
  <button
    class="text-center text-xl text-black hover:bg-black hover:text-white cursor-pointer font-bold py-2 px-4 rounded-xl border-2 border-black"
    @click="$emit('click', $event)"
  >
    <slot></slot>
  </button>
</template>
