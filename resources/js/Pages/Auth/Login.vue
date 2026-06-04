<script setup>
import Checkbox from '@/Components/Checkbox.vue'
import GuestLayout from '@/Layouts/GuestLayout.vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'

defineProps({
  canResetPassword: Boolean,
  status: String,
})

const form = useForm({
  email: '',
  password: '',
  remember: false,
})

const submit = () => {
  form.post(route('login'), {
    onFinish: () => form.reset('password'),
  })
}
</script>

<template>
  <GuestLayout>
    <Head title="Masuk" />

    <div class="mb-8">
      <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-400">Akses akun</p>
      <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-950">Masuk ke PantauBBM</h1>
      <p class="mt-3 text-sm leading-6 text-slate-600">Kelola data dan pantau pembaruan harga BBM dari satu dashboard.</p>
    </div>

    <div v-if="status" class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
      {{ status }}
    </div>

    <form @submit.prevent="submit">
      <div>
        <InputLabel for="email" value="Email" />
        <TextInput id="email" v-model="form.email" type="email" class="mt-1 block w-full" required autofocus autocomplete="username" />
        <InputError class="mt-2" :message="form.errors.email" />
      </div>

      <div class="mt-4">
        <InputLabel for="password" value="Kata sandi" />
        <TextInput id="password" v-model="form.password" type="password" class="mt-1 block w-full" required autocomplete="current-password" />
        <InputError class="mt-2" :message="form.errors.password" />
      </div>

      <div class="mt-4 flex items-center justify-between gap-4">
        <label class="flex items-center">
          <Checkbox name="remember" v-model:checked="form.remember" />
          <span class="ms-2 text-sm text-slate-600">Ingat saya</span>
        </label>

        <Link v-if="canResetPassword" :href="route('password.request')" class="text-sm font-semibold text-slate-600 transition-colors hover:text-slate-950">
          Lupa kata sandi?
        </Link>
      </div>

      <div class="mt-6 flex items-center justify-end">
        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
          Masuk
        </PrimaryButton>
      </div>
    </form>
  </GuestLayout>
</template>
