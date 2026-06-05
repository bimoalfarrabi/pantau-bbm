<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
})

const submit = () => {
  form.post(route('register'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  })
}
</script>

<template>
  <GuestLayout>
    <Head title="Daftar" />

    <div class="mb-8">
      <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-400">Buat akun</p>
      <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-950">Daftar ke PantauBBM</h1>
      <p class="mt-3 text-sm leading-6 text-slate-600">Buat akun untuk akses dashboard dan fitur admin.</p>
    </div>

    <form @submit.prevent="submit">
      <div>
        <InputLabel for="name" value="Nama" />
        <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full" required autofocus autocomplete="name" />
        <InputError class="mt-2" :message="form.errors.name" />
      </div>

      <div class="mt-4">
        <InputLabel for="email" value="Email" />
        <TextInput id="email" v-model="form.email" type="email" class="mt-1 block w-full" required autocomplete="username" />
        <InputError class="mt-2" :message="form.errors.email" />
      </div>

      <div class="mt-4">
        <InputLabel for="password" value="Kata sandi" />
        <TextInput id="password" v-model="form.password" type="password" class="mt-1 block w-full" required autocomplete="new-password" />
        <InputError class="mt-2" :message="form.errors.password" />
      </div>

      <div class="mt-4">
        <InputLabel for="password_confirmation" value="Konfirmasi kata sandi" />
        <TextInput id="password_confirmation" v-model="form.password_confirmation" type="password" class="mt-1 block w-full" required autocomplete="new-password" />
        <InputError class="mt-2" :message="form.errors.password_confirmation" />
      </div>

      <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between sm:gap-4">
        <Link :href="route('login')" class="text-sm font-semibold text-slate-600 transition-colors hover:text-slate-950">Sudah punya akun?</Link>
        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
          Daftar
        </PrimaryButton>
      </div>
    </form>
  </GuestLayout>
</template>
