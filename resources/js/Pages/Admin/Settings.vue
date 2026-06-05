<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue'

const props = defineProps({ settings: Object })
const form = useForm(props.settings)

const sections = [
  {
    title: 'API Bensin',
    description: 'Kontrol endpoint, timeout, dan retry.',
    fields: [
      { key: 'FUEL_API_BASE_URL', label: 'API Base URL', type: 'url' },
      { key: 'FUEL_API_TIMEOUT', label: 'Timeout', type: 'number' },
      { key: 'FUEL_API_RETRY_ATTEMPTS', label: 'Retry Attempts', type: 'number' },
      { key: 'FUEL_API_RETRY_SLEEP_MS', label: 'Retry Sleep MS', type: 'number' },
      { key: 'FUEL_API_USER_AGENT', label: 'User Agent', type: 'text', full: true },
    ],
  },
  {
    title: 'Sync Runtime',
    description: 'Kontrol jadwal dan penyimpanan lock/cache.',
    fields: [
      { key: 'FUEL_SYNC_SCHEDULE_CRON', label: 'Schedule Cron', type: 'text' },
      { key: 'FUEL_SYNC_LOCK_STORE', label: 'Lock Store', type: 'text' },
      { key: 'FUEL_SYNC_LOCK_SECONDS', label: 'Lock Seconds', type: 'number' },
      { key: 'FUEL_SYNC_CACHE_STORE', label: 'Cache Store', type: 'text' },
    ],
  },
]
</script>

<template>
  <Head title="Admin Settings" />
  <AuthenticatedLayout>
    <template #header>
      <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
          <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Admin area</p>
          <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-950">Settings</h1>
          <p class="mt-3 text-sm leading-6 text-slate-600">Simpan konfigurasi runtime tanpa edit `.env` langsung.</p>
        </div>
        <Link href="/admin/dashboard" class="rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:text-slate-950">Kembali</Link>
      </div>
    </template>

    <div class="mx-auto max-w-[1280px] px-5 py-10">
      <div v-if="$page.props.flash.status" class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
        {{ $page.props.flash.status }}
      </div>

      <form class="space-y-6" @submit.prevent="form.put(route('admin.settings.update'))">
        <section v-for="section in sections" :key="section.title" class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
          <div>
            <h3 class="text-lg font-semibold text-slate-950">{{ section.title }}</h3>
            <p class="mt-1 text-sm text-slate-600">{{ section.description }}</p>
          </div>
          <div class="mt-6 grid gap-4 md:grid-cols-2">
            <label v-for="field in section.fields" :key="field.key" class="block" :class="field.full ? 'md:col-span-2' : ''">
              <span class="text-sm font-medium text-slate-700">{{ field.label }}</span>
              <input v-model="form[field.key]" class="mt-1 w-full rounded-xl border-slate-300" :type="field.type">
            </label>
          </div>
        </section>

        <div class="flex flex-wrap items-center gap-3">
          <button class="rounded-full bg-slate-950 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800" :disabled="form.processing">
            {{ form.processing ? 'Menyimpan...' : 'Simpan' }}
          </button>
          <Link href="/admin/dashboard" class="rounded-full border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:text-slate-950">Kembali</Link>
        </div>
      </form>
    </div>
  </AuthenticatedLayout>
</template>
