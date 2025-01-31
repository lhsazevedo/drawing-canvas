<script setup lang="ts">
import DcButton from '@/components/DcButton.vue'
import DcTextInput from '@/components/DcTextInput.vue'
import { reactive } from 'vue'
import { useForm } from 'vee-validate'
import axios from '@/axios'
import { useRouter } from 'vue-router'
import { AxiosError } from 'axios'
import { RouterLink } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const form = reactive({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
})

const router = useRouter()
const { isSubmitting, handleSubmit, setErrors, errors } = useForm()
const auth = useAuthStore()

const onSubmit = handleSubmit(async () => {
  try {
    await axios.post('/register', form)
    auth.isAuthenticated = true
    auth.name = form.name
    router.push({ name: 'home' })
  } catch (e) {
    if (e instanceof AxiosError && e.response?.data?.errors) {
      setErrors(e.response.data.errors)
      return
    }
  }
})
</script>

<template>
  <div class="max-w-sm mx-auto p-4 my-8 space-y-8 bg-white rounded-xl shadow-md">
    <div class="text-2xl text-center text-slate-700">Sign Up</div>

    <form class="space-y-4" @submit="onSubmit">
      <DcTextInput
        id="name"
        type="text"
        label="Name"
        v-model="form.name"
        :error="errors.name"
        required
        autofocus
        autocomplete="name"
      />

      <DcTextInput
        id="email"
        type="email"
        label="Email"
        v-model="form.email"
        :error="errors.email"
        required
        autocomplete="username"
      />

      <DcTextInput
        id="password"
        type="password"
        label="Password"
        v-model="form.password"
        :error="errors.password"
        required
        autocomplete="new-password"
      />

      <DcTextInput
        id="password_confirmation"
        type="password"
        label="Confirm Password"
        v-model="form.password_confirmation"
        :error="errors.password_confirmation"
        required
        autocomplete="new-password"
      />

      <div class="mt-8 space-y-4 flex flex-col items-center">
        <DcButton
          class="w-full block"
          :class="{ 'opacity-25': isSubmitting }"
          :disabled="isSubmitting"
        >
          Create Account
        </DcButton>

        <RouterLink to="/login" class="text-zinc-500 hover:underline">
          Already have an account?
        </RouterLink>
      </div>
    </form>
  </div>
</template>
