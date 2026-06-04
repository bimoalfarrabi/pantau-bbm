<script setup>
import { computed } from 'vue'
import GuestLayout from '@/Layouts/GuestLayout.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'

const props = defineProps({
  status: String,
})

const form = useForm({})

const submit = () => {
  form.post(route('verification.send'))
}

const verificationLinkSent = computed(() => props.status === 'verification-link-sent')
</script>

<template>
  <GuestLayout>
    <Head title="Verifikasi Email" />

    <div class="mb-8">
      <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-400">Verifikasi akun</p>
      <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-950">Cek email kamu</h1>
      <p class="mt-3 text-sm leading-6 text-slate-600">Kami sudah mengirim tautan verifikasi. Klik tautan itu untuk mengaktifkan akun.</p>
    </div>

    <div v-if="verificationLinkSent" class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
      Tautan verifikasi baru sudah dikirim ke email kamu.
    </div>

    <form @submit.prevent="submit">
      <div class="mt-6 flex items-center justify-between gap-4">
        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
          Kirim ulang verifikasi
        </PrimaryButton>

        <Link
          :href="route('logout')"
          method="post"
          as="button"
          class="text-sm font-semibold text-slate-600 transition-colors hover:text-slate-950"
        >
          Keluar
        </Link>
      </div>
    </form>
  </GuestLayout>
</template>
