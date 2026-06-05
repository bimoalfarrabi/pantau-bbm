<script setup>
import { ref } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue'

const props = defineProps({ settings: Object })
const form = useForm(props.settings)
const githubFetch = ref({ processing: false, message: '', error: '' })

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
    description: 'Kontrol konten About dan credit publik.',
    fields: [
      { key: 'ABOUT_MISSION_TITLE', label: 'Mission Title', type: 'text', full: true },
      { key: 'ABOUT_MISSION_BODY', label: 'Mission Body', type: 'textarea', full: true },
      { key: 'ABOUT_CREATOR_GITHUB_USERNAME', label: 'GitHub Username (Optional)', type: 'text', full: true },
      { key: 'ABOUT_CREATOR_NAME', label: 'Creator Name', type: 'text' },
      { key: 'ABOUT_CREATOR_SUBTITLE', label: 'Creator Subtitle', type: 'text' },
      { key: 'ABOUT_CREATOR_DESCRIPTION', label: 'Creator Description', type: 'textarea', full: true },
      { key: 'ABOUT_CREATOR_PHOTO_URL', label: 'Creator Photo URL', type: 'url', full: true },
      { key: 'ABOUT_SOURCES_TITLE', label: 'Sources Title', type: 'text', full: true },
      { key: 'ABOUT_SOURCE_ONE_TITLE', label: 'Source One Title', type: 'text' },
      { key: 'ABOUT_SOURCE_ONE_DESCRIPTION', label: 'Source One Description', type: 'textarea', full: true },
      { key: 'ABOUT_SOURCE_ONE_LINK_LABEL', label: 'Source One Link Label', type: 'text' },
      { key: 'ABOUT_SOURCE_ONE_URL', label: 'Source One URL', type: 'url', full: true },
      { key: 'ABOUT_SOURCE_TWO_TITLE', label: 'Source Two Title', type: 'text' },
      { key: 'ABOUT_SOURCE_TWO_DESCRIPTION', label: 'Source Two Description', type: 'textarea', full: true },
      { key: 'ABOUT_SOURCE_TWO_LINK_LABEL', label: 'Source Two Link Label', type: 'text' },
      { key: 'ABOUT_SOURCE_TWO_URL', label: 'Source Two URL', type: 'url', full: true },
      { key: 'ABOUT_SOURCE_THREE_TITLE', label: 'Source Three Title', type: 'text' },
      { key: 'ABOUT_SOURCE_THREE_DESCRIPTION', label: 'Source Three Description', type: 'textarea', full: true },
      { key: 'ABOUT_SOURCE_THREE_LINK_LABEL', label: 'Source Three Link Label', type: 'text' },
      { key: 'ABOUT_SOURCE_THREE_URL', label: 'Source Three URL', type: 'url', full: true },
    ],
  },
]

async function fetchGithubProfile() {
  githubFetch.value.processing = true
  githubFetch.value.message = ''
  githubFetch.value.error = ''

  try {
    const username = String(form.ABOUT_CREATOR_GITHUB_USERNAME || '').trim()

    if (!username) {
      githubFetch.value.error = 'Isi username GitHub dulu.'
      return
    }

    const response = await window.axios.post(route('admin.settings.fetch-github-profile'), { username })
    const data = response.data || {}

    if (data.username) form.ABOUT_CREATOR_GITHUB_USERNAME = data.username
    if (data.name) form.ABOUT_CREATOR_NAME = data.name
    if (data.bio) form.ABOUT_CREATOR_DESCRIPTION = data.bio
    if (data.avatar_url) form.ABOUT_CREATOR_PHOTO_URL = data.avatar_url

    githubFetch.value.message = 'Profil GitHub berhasil diambil. Review lalu simpan kalau sudah oke.'
  } catch (error) {
    githubFetch.value.error = error?.response?.data?.message || 'Gagal mengambil profil GitHub.'
  } finally {
    githubFetch.value.processing = false
  }
}
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
            <template v-for="field in section.fields" :key="field.key">
              <div v-if="field.key === 'ABOUT_SOURCES_TITLE'" class="md:col-span-2 border-t border-slate-200 pt-6">
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Sources & Credits</p>
                <p class="mt-1 text-sm text-slate-600">Edit judul dan isi setiap item sumber/credit.</p>
              </div>
                <label class="block" :class="field.full ? 'md:col-span-2' : ''">
                  <span class="text-sm font-medium text-slate-700">{{ field.label }}</span>
                  <textarea v-if="field.type === 'textarea'" v-model="form[field.key]" class="mt-1 min-h-32 w-full rounded-xl border-slate-300"></textarea>
                  <input v-else v-model="form[field.key]" class="mt-1 w-full rounded-xl border-slate-300" :type="field.type">
                  <p v-if="field.key === 'ABOUT_CREATOR_GITHUB_USERNAME'" class="mt-2 text-xs text-slate-500">Kosongkan kalau tidak ingin mengambil data dari GitHub.</p>
                  <p v-if="field.key.endsWith('_URL')" class="mt-2 text-xs text-slate-500">Isi link publik yang ingin dibuat clickable.</p>
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
            </template>
          </div>
          <div v-if="section.title === 'About Page'" class="mt-6 flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-center">
            <button type="button" class="w-full rounded-full border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:text-slate-950 disabled:cursor-not-allowed disabled:opacity-50 sm:w-auto" :disabled="githubFetch.processing" @click="fetchGithubProfile">
              {{ githubFetch.processing ? 'Mengambil...' : 'Fetch GitHub' }}
            </button>
            <span v-if="githubFetch.message" class="text-sm font-medium text-emerald-700">{{ githubFetch.message }}</span>
            <span v-if="githubFetch.error" class="text-sm font-medium text-rose-700">{{ githubFetch.error }}</span>
          </div>
          <div v-if="section.title === 'About Page'" class="mt-8 rounded-2xl border border-slate-200 bg-slate-50 p-4 sm:p-5">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">About Preview</p>
            <div class="mt-4 grid gap-5 lg:grid-cols-[1.35fr_0.85fr]">
              <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
                <h4 class="text-2xl font-semibold tracking-tight text-slate-950">{{ form.ABOUT_MISSION_TITLE || 'The Mission' }}</h4>
                <div class="mt-4 space-y-3 text-sm leading-6 text-slate-600">
                  <p v-for="paragraph in (form.ABOUT_MISSION_BODY || '').split('\n').filter(Boolean)" :key="paragraph">{{ paragraph }}</p>
                </div>
              </div>
              <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
                <div class="flex flex-col gap-4 sm:flex-row">
                  <img v-if="form.ABOUT_CREATOR_PHOTO_URL" :src="form.ABOUT_CREATOR_PHOTO_URL" alt="Creator preview" class="h-20 w-20 rounded-full object-cover shadow-sm">
                  <div v-else class="flex h-20 w-20 shrink-0 items-center justify-center rounded-full bg-slate-950 text-xl font-semibold text-white shadow-sm">
                    {{ (form.ABOUT_CREATOR_NAME || 'PB').split(' ').map((word) => word[0]).join('').slice(0, 2) }}
                  </div>
                  <div>
                    <h4 class="text-xl font-semibold tracking-tight text-slate-950">{{ form.ABOUT_CREATOR_NAME || 'Pantau Dev Team' }}</h4>
                    <p class="mt-1 text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">{{ form.ABOUT_CREATOR_SUBTITLE || 'Systems Engineering' }}</p>
                    <p class="mt-3 text-sm leading-6 text-slate-600">{{ form.ABOUT_CREATOR_DESCRIPTION || 'Deskripsi creator tampil di sini.' }}</p>
                    <p v-if="form.ABOUT_CREATOR_GITHUB_USERNAME" class="mt-3 text-xs font-medium text-slate-500">Source GitHub: @{{ form.ABOUT_CREATOR_GITHUB_USERNAME }}</p>
                  </div>
                </div>
              </div>
            </div>
            <div class="mt-5 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
              <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">{{ form.ABOUT_SOURCES_TITLE || 'Sources & Credits' }}</p>
              <div class="mt-5 space-y-5">
                <div class="flex flex-col gap-4 sm:flex-row">
                  <div class="mt-1 text-slate-950"><svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="5" rx="8" ry="3"></ellipse><path d="M4 5v14c0 1.7 3.6 3 8 3s8-1.3 8-3V5"></path><path d="M4 12c0 1.7 3.6 3 8 3s8-1.3 8-3"></path></svg></div>
                  <div>
                    <h4 class="text-lg font-semibold text-slate-950">{{ form.ABOUT_SOURCE_ONE_TITLE || 'Bensin API' }}</h4>
                    <p class="mt-1 text-sm leading-6 text-slate-600">{{ form.ABOUT_SOURCE_ONE_DESCRIPTION || 'Sumber utama harga BBM yang dipakai untuk sinkronisasi data dan pembaruan tampilan.' }}</p>
                    <p v-if="form.ABOUT_SOURCE_ONE_URL" class="mt-2 break-all text-sm font-medium text-slate-950">{{ form.ABOUT_SOURCE_ONE_LINK_LABEL || 'Buka link' }}: {{ form.ABOUT_SOURCE_ONE_URL }}</p>
                  </div>
                </div>
                <div class="flex flex-col gap-4 sm:flex-row">
                  <div class="mt-1 text-slate-950"><svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h16"></path><path d="M4 12h16"></path><path d="M4 17h16"></path><path d="m8 4 4 4-4 4"></path></svg></div>
                  <div>
                    <h4 class="text-lg font-semibold text-slate-950">{{ form.ABOUT_SOURCE_TWO_TITLE || 'Open Source Stack' }}</h4>
                    <p class="mt-1 text-sm leading-6 text-slate-600">{{ form.ABOUT_SOURCE_TWO_DESCRIPTION || 'Dibangun dengan Laravel, Tailwind CSS, dan komponen frontend yang ringan supaya pengalaman tetap konsisten.' }}</p>
                    <p v-if="form.ABOUT_SOURCE_TWO_URL" class="mt-2 break-all text-sm font-medium text-slate-950">{{ form.ABOUT_SOURCE_TWO_LINK_LABEL || 'Buka link' }}: {{ form.ABOUT_SOURCE_TWO_URL }}</p>
                  </div>
                </div>
                <div class="flex flex-col gap-4 sm:flex-row">
                  <div class="mt-1 text-slate-950"><svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18"></path><path d="M7 21V8"></path><path d="M12 21V3"></path><path d="M17 21v-8"></path></svg></div>
                  <div>
                    <h4 class="text-lg font-semibold text-slate-950">{{ form.ABOUT_SOURCE_THREE_TITLE || 'Disclaimer' }}</h4>
                    <p class="mt-1 text-sm leading-6 text-slate-600">{{ form.ABOUT_SOURCE_THREE_DESCRIPTION || 'PantauBBM adalah platform independen dan bukan situs resmi Pertamina atau pemerintah Indonesia.' }}</p>
                    <p v-if="form.ABOUT_SOURCE_THREE_URL" class="mt-2 break-all text-sm font-medium text-slate-950">{{ form.ABOUT_SOURCE_THREE_LINK_LABEL || 'Buka link' }}: {{ form.ABOUT_SOURCE_THREE_URL }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </details>

        <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-center">
          <button class="w-full rounded-full bg-slate-950 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800 sm:w-auto" :disabled="form.processing">
            {{ form.processing ? 'Menyimpan...' : 'Simpan' }}
          </button>
          <Link href="/admin/dashboard" class="w-full rounded-full border border-slate-200 bg-white px-5 py-2.5 text-center text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:text-slate-950 sm:w-auto">Kembali</Link>
        </div>
      </form>
    </div>
  </AuthenticatedLayout>
</template>
