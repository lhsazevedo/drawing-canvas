<script setup lang="ts">
import DcButton from '@/components/DcButton.vue'
import DcTextInput from '@/components/DcTextInput.vue'
import { reactive } from 'vue'
import { useForm } from 'vee-validate'
import { useRouter } from 'vue-router'
import { RouterLink } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const form = reactive({
  email: '',
  password: '',
  remember: false,
})

const router = useRouter()
const { isSubmitting, handleSubmit, setErrors, errors } = useForm()
const auth = useAuthStore()

if (auth.isAuthenticated) {
  router.push({ name: 'home' })
}

const onSubmit = handleSubmit(async () => {
  const result = await auth.login(form)
  if (!result.success) {
    setErrors(result.error)
    return
  }
  router.push({ name: 'home' })
})
</script>

<template>
  <div class="max-w-sm mx-auto p-4 my-8 bg-white space-y-8 rounded-xl shadow-md">
    <div class="text-2xl text-center text-slate-700">Login</div>

    <form class="space-y-4" @submit="onSubmit">
      <div>
        <DcTextInput
          id="email"
          type="email"
          class="mt-1 block w-full"
          label="Email"
          v-model="form.email"
          :error="errors.email"
          required
          autofocus
          autocomplete="username"
        />
      </div>

      <div class="space-y-2">
        <DcTextInput
          id="password"
          type="password"
          class="mt-1block w-full"
          label="Password"
          v-model="form.password"
          :error="errors.password"
          required
          autocomplete="current-password"
        />
        <RouterLink to="/password-request" class="text-zinc-500 hover:underline">
          Forgot your password?
        </RouterLink>
      </div>

      <div class="mt-8 space-y-4 flex flex-col items-center">
        <DcButton
          class="w-full block"
          :class="{ 'opacity-25': isSubmitting }"
          :disabled="isSubmitting"
        >
          Log in
        </DcButton>

        <RouterLink to="/signup" class="text-zinc-500 hover:underline">
          Don't have an account?
        </RouterLink>
      </div>
    </form>
  </div>
</template>
