<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import { Head, useForm } from '@inertiajs/vue3'

const props = defineProps({
  email: {
    type: String,
    required: true,
  },
  token: {
    type: String,
    required: true,
  },
})

const form = useForm({
  token: props.token,
  email: props.email,
  password: '',
  password_confirmation: '',
})

const submit = () => {
  form.post(route('password.store'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  })
}
</script>

<template>
  <GuestLayout>
    <Head title="Atur Ulang Kata Sandi" />

    <div class="mb-8">
      <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-400">Pemulihan akun</p>
      <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-950">Atur ulang kata sandi</h1>
      <p class="mt-3 text-sm leading-6 text-slate-600">Buat kata sandi baru untuk akunmu.</p>
    </div>

    <form @submit.prevent="submit">
      <div>
        <InputLabel for="email" value="Email" />
        <TextInput id="email" v-model="form.email" type="email" class="mt-1 block w-full" required autofocus autocomplete="username" />
        <InputError class="mt-2" :message="form.errors.email" />
      </div>

      <div class="mt-4">
        <InputLabel for="password" value="Kata sandi baru" />
        <TextInput id="password" v-model="form.password" type="password" class="mt-1 block w-full" required autocomplete="new-password" />
        <InputError class="mt-2" :message="form.errors.password" />
      </div>

      <div class="mt-4">
        <InputLabel for="password_confirmation" value="Konfirmasi kata sandi" />
        <TextInput id="password_confirmation" v-model="form.password_confirmation" type="password" class="mt-1 block w-full" required autocomplete="new-password" />
        <InputError class="mt-2" :message="form.errors.password_confirmation" />
      </div>

      <div class="mt-6 flex justify-end">
        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
          Simpan kata sandi baru
        </PrimaryButton>
      </div>
    </form>
  </GuestLayout>
</template>
