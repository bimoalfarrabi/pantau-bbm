<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3'
import AuthenticatedLayout from '../Layouts/AuthenticatedLayout.vue'

defineProps({
  laravelVersion: String,
  phpVersion: String,
})

const page = usePage()
</script>

<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <template #header>
      <div>
        <h2 class="text-xl font-semibold leading-tight text-gray-800">Dashboard</h2>
        <p class="mt-1 text-sm text-gray-600">Selamat datang di area pengguna yang sudah masuk.</p>
      </div>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
          <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
            <p class="text-sm text-gray-500">Laravel</p>
            <p class="mt-2 text-2xl font-semibold text-gray-900">{{ laravelVersion }}</p>
          </div>
          <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
            <p class="text-sm text-gray-500">PHP</p>
            <p class="mt-2 text-2xl font-semibold text-gray-900">{{ phpVersion }}</p>
          </div>
          <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
            <p class="text-sm text-gray-500">Akses</p>
            <p class="mt-2 text-2xl font-semibold text-gray-900">{{ page.props.auth.user?.is_admin ? 'Admin' : 'User' }}</p>
          </div>
        </div>

        <div v-if="page.props.auth.user?.is_admin" class="mt-6 rounded-2xl border border-slate-200 bg-slate-50 p-6 shadow-sm">
          <h3 class="text-lg font-semibold text-slate-950">Akses Admin Tersedia</h3>
          <p class="mt-2 text-sm leading-6 text-slate-600">Gunakan halaman admin untuk memantau konfigurasi sinkronisasi data, jadwal otomatis, dan log proses terbaru.</p>
          <Link href="/admin/dashboard" class="mt-4 inline-flex rounded-full bg-slate-950 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800">
            Buka Admin Dashboard
          </Link>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
