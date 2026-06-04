<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import { Head, useForm } from '@inertiajs/vue3'

const form = useForm({
  password: '',
})

const submit = () => {
  form.post(route('password.confirm'), {
    onFinish: () => form.reset(),
  })
}
</script>

<template>
  <GuestLayout>
    <Head title="Konfirmasi Kata Sandi" />

    <div class="mb-8">
      <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-400">Keamanan akun</p>
      <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-950">Konfirmasi kata sandi</h1>
      <p class="mt-3 text-sm leading-6 text-slate-600">Masukkan kata sandi untuk melanjutkan ke area aman.</p>
    </div>

    <form @submit.prevent="submit">
      <div>
        <InputLabel for="password" value="Kata sandi" />
        <TextInput id="password" v-model="form.password" type="password" class="mt-1 block w-full" required autocomplete="current-password" autofocus />
        <InputError class="mt-2" :message="form.errors.password" />
      </div>

      <div class="mt-6 flex justify-end">
        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
          Konfirmasi
        </PrimaryButton>
      </div>
    </form>
  </GuestLayout>
</template>
