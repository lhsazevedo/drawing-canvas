<script setup lang="ts">
import CheckBox from '@/components/CheckBox.vue'
import TextInput from '@/components/TextInput.vue'
import Button from '@/components/Button.vue'
import { reactive } from 'vue'
import axios from '@/axios'
import { AxiosError } from 'axios'
import { useForm } from 'vee-validate'
import { useRouter } from 'vue-router'

const router = useRouter()

const form = reactive({
  name: '',
  description: '',
  is_public: false,
})

const { handleSubmit, setErrors, errors } = useForm()

const onSubmit = handleSubmit(async () => {
  try {
    await axios.post('/drawing-sessions', form)
    router.push({ name: 'home' })
  } catch (e) {
    if (!(e instanceof AxiosError)) {
      console.error(e)
      return
    }

    if (e.response?.data?.errors) {
      setErrors(e.response.data.errors)
      return
    }
  }
})
</script>

<template>
  <main class="max-w-md mx-auto p-4 space-y-8">
    <h1 class="text-3xl font-bold text-center">Drawing Canvas</h1>

    <div class="">
      <form class="space-y-4" @submit="onSubmit">
        <TextInput
          label="Canvas name"
          placeholder="e.g. My first canvas"
          v-model="form.name"
          :error="errors.name"
        />
        <TextInput
          label="Description (optional)"
          placeholder="e.g. My first canvas"
          v-model="form.description"
          :error="errors.description"
        />
        <CheckBox label="Public" v-model="form.is_public" :error="errors.is_public" />
        <Button class="w-full block" type="submit"> Create! </Button>
      </form>
    </div>
  </main>
</template>
