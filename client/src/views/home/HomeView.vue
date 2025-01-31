<script setup lang="ts">
import UserGreeting from './UserGreeting.vue'
import CreateCanvasButton from './CreateCanvasButton.vue'
import JoinSessionForm from './JoinSessionForm.vue'
import SessionList from './SessionList.vue'
</script>

<template>
  <main class="max-w-md mx-auto p-4 space-y-8">
    <h1 class="text-3xl text-center text-slate-800">Drawing Canvas</h1>

    <UserGreeting />
    <CreateCanvasButton />
    <JoinSessionForm />
    <SessionList />
  </main>
</template>
