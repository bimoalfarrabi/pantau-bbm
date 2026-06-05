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
  {
    title: 'About Page',
    description: 'Kontrol konten halaman About dari admin.',
    fields: [
      { key: 'ABOUT_MISSION_TITLE', label: 'Mission Title', type: 'text', full: true },
      { key: 'ABOUT_MISSION_BODY', label: 'Mission Body', type: 'textarea', full: true },
      { key: 'ABOUT_CREATOR_NAME', label: 'Creator Name', type: 'text' },
      { key: 'ABOUT_CREATOR_SUBTITLE', label: 'Creator Subtitle', type: 'text' },
      { key: 'ABOUT_CREATOR_DESCRIPTION', label: 'Creator Description', type: 'textarea', full: true },
      { key: 'ABOUT_CREATOR_PHOTO_URL', label: 'Creator Photo URL', type: 'url', full: true },
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
        <details v-for="section in sections" :key="section.title" class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm" :open="section.title === 'About Page'">
          <summary class="cursor-pointer list-none">
            <div class="flex items-center justify-between gap-4">
              <div>
                <h3 class="text-lg font-semibold text-slate-950">{{ section.title }}</h3>
                <p class="mt-1 text-sm text-slate-600">{{ section.description }}</p>
              </div>
              <span class="rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-semibold text-slate-600 transition group-open:bg-slate-950 group-open:text-white">Toggle</span>
            </div>
          </summary>

          <div class="mt-6 grid gap-4 md:grid-cols-2">
            <label v-for="field in section.fields" :key="field.key" class="block" :class="field.full ? 'md:col-span-2' : ''">
              <span class="text-sm font-medium text-slate-700">{{ field.label }}</span>
              <textarea v-if="field.type === 'textarea'" v-model="form[field.key]" class="mt-1 min-h-32 w-full rounded-xl border-slate-300"></textarea>
              <input v-else v-model="form[field.key]" class="mt-1 w-full rounded-xl border-slate-300" :type="field.type">
              <div v-if="field.key === 'ABOUT_CREATOR_PHOTO_URL'" class="mt-4 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Preview</p>
                <div class="mt-3 flex items-center gap-4">
                  <img v-if="form.ABOUT_CREATOR_PHOTO_URL" :src="form.ABOUT_CREATOR_PHOTO_URL" alt="Creator photo preview" class="h-20 w-20 rounded-full object-cover shadow-sm">
                  <div v-else class="flex h-20 w-20 items-center justify-center rounded-full bg-slate-950 text-xl font-semibold text-white shadow-sm">
                    {{ (form.ABOUT_CREATOR_NAME || 'PB').split(' ').map((word) => word[0]).join('').slice(0, 2) }}
                  </div>
                  <div>
                    <p class="font-semibold text-slate-950">{{ form.ABOUT_CREATOR_NAME || 'Creator' }}</p>
                    <p class="mt-1 text-sm text-slate-600">{{ form.ABOUT_CREATOR_SUBTITLE || 'Subtitle' }}</p>
                  </div>
                </div>
              </div>
            </label>
          </div>
          <div v-if="section.title === 'About Page'" class="mt-8 rounded-2xl border border-slate-200 bg-slate-50 p-5">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">About Preview</p>
            <div class="mt-4 grid gap-5 lg:grid-cols-[1.35fr_0.85fr]">
              <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h4 class="text-2xl font-semibold tracking-tight text-slate-950">{{ form.ABOUT_MISSION_TITLE || 'The Mission' }}</h4>
                <div class="mt-4 space-y-3 text-sm leading-6 text-slate-600">
                  <p v-for="paragraph in (form.ABOUT_MISSION_BODY || '').split('\n').filter(Boolean)" :key="paragraph">{{ paragraph }}</p>
                </div>
              </div>
              <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex gap-4">
                  <img v-if="form.ABOUT_CREATOR_PHOTO_URL" :src="form.ABOUT_CREATOR_PHOTO_URL" alt="Creator preview" class="h-20 w-20 rounded-full object-cover shadow-sm">
                  <div v-else class="flex h-20 w-20 shrink-0 items-center justify-center rounded-full bg-slate-950 text-xl font-semibold text-white shadow-sm">
                    {{ (form.ABOUT_CREATOR_NAME || 'PB').split(' ').map((word) => word[0]).join('').slice(0, 2) }}
                  </div>
                  <div>
                    <h4 class="text-xl font-semibold tracking-tight text-slate-950">{{ form.ABOUT_CREATOR_NAME || 'Pantau Dev Team' }}</h4>
                    <p class="mt-1 text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">{{ form.ABOUT_CREATOR_SUBTITLE || 'Systems Engineering' }}</p>
                    <p class="mt-3 text-sm leading-6 text-slate-600">{{ form.ABOUT_CREATOR_DESCRIPTION || 'Deskripsi creator tampil di sini.' }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </details>

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
