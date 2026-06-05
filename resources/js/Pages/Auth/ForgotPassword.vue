<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import { Head, useForm } from '@inertiajs/vue3'

defineProps({
  status: String,
})

const form = useForm({
  email: '',
})

const submit = () => {
  form.post(route('password.email'))
}
</script>

<template>
  <GuestLayout>
    <Head title="Lupa Kata Sandi" />

    <div class="mb-8">
      <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-400">Pemulihan akun</p>
      <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-950">Lupa kata sandi?</h1>
      <p class="mt-3 text-sm leading-6 text-slate-600">Masukkan email akunmu. Kami akan kirim tautan reset kata sandi.</p>
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

      <div class="mt-6 flex justify-end">
        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
          Kirim tautan reset
        </PrimaryButton>
      </div>
    </form>
  </GuestLayout>
</template>
