<script setup>
import { Head, Link, useForm, usePage } from '@inertiajs/vue3'
import { computed, ref, watch } from 'vue'
import Modal from '../../Components/Modal.vue'
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue'

defineProps({
  sync: Object,
  logs: Array,
  quickLinks: Array,
})

const page = usePage()
const flash = computed(() => page.props.flash || {})
const confirmingSync = ref(false)
const showSyncToast = ref(false)
const syncForm = useForm({ force: false })

watch(
  () => flash.value.sync_message,
  (message) => {
    if (!message) return

    showSyncToast.value = true
    setTimeout(() => {
      showSyncToast.value = false
    }, 6000)
  },
  { immediate: true },
)

function confirmSync() {
  syncForm.force = false
  confirmingSync.value = true
}

function closeSyncModal() {
  if (!syncForm.processing) {
    confirmingSync.value = false
  }
}

function runSync() {
  syncForm.post(route('admin.sync'), {
    preserveScroll: true,
    onSuccess: () => {
      confirmingSync.value = false
    },
  })
}

function formatDate(value) {
  if (!value) return '-'
  return new Date(value).toLocaleString('id-ID', {
    dateStyle: 'medium',
    timeStyle: 'short',
  })
}

function statusClass(status) {
  if (status === 'success') return 'bg-emerald-50 text-emerald-700 ring-emerald-200'
  if (status === 'failed') return 'bg-rose-50 text-rose-700 ring-rose-200'
  if (status === 'running') return 'bg-amber-50 text-amber-700 ring-amber-200'
  return 'bg-slate-50 text-slate-700 ring-slate-200'
}
</script>

<template>
  <Head title="Admin Dashboard" />
  <AuthenticatedLayout>
    <Transition
      enter-active-class="transition ease-out duration-200"
      enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
      enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
      leave-active-class="transition ease-in duration-150"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div v-if="showSyncToast && flash.sync_message" class="fixed right-4 top-4 z-50 w-[calc(100%-2rem)] max-w-md rounded-2xl border bg-white p-4 shadow-xl sm:right-6 sm:top-6" :class="flash.sync_status === 'success' ? 'border-emerald-200' : 'border-rose-200'">
        <div class="flex gap-3">
          <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full" :class="flash.sync_status === 'success' ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700'">
            <svg v-if="flash.sync_status === 'success'" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M20 6 9 17l-5-5"></path>
            </svg>
            <svg v-else class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M18 6 6 18"></path>
              <path d="m6 6 12 12"></path>
            </svg>
          </div>
          <div class="min-w-0 flex-1">
            <div class="text-sm font-semibold text-slate-950">{{ flash.sync_status === 'success' ? 'Sync berhasil' : 'Sync gagal' }}</div>
            <p class="mt-1 text-sm leading-5 text-slate-600">{{ flash.sync_message }}</p>
            <p v-if="flash.sync_finished_at" class="mt-2 text-xs text-slate-500">Selesai: {{ formatDate(flash.sync_finished_at) }}</p>
          </div>
          <button type="button" class="rounded-full p-1 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600" @click="showSyncToast = false">
            <span class="sr-only">Tutup notifikasi</span>
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M18 6 6 18"></path>
              <path d="m6 6 12 12"></path>
            </svg>
          </button>
        </div>
      </div>
    </Transition>

    <template #header>
      <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div class="max-w-3xl">
          <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Admin area</p>
          <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-950">Dashboard</h1>
          <p class="mt-3 text-sm leading-6 text-slate-600">Ringkasan sinkronisasi, status runtime, dan pintasan admin dalam gaya yang sama dengan halaman publik.</p>
        </div>
        <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap">
          <button
            type="button"
            class="w-full rounded-full bg-emerald-600 px-4 py-2 text-center text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-500 disabled:cursor-not-allowed disabled:opacity-60 sm:w-auto"
            :disabled="syncForm.processing"
            @click="confirmSync"
          >
            {{ syncForm.processing ? 'Menjalankan Sync...' : 'Sync Sekarang' }}
          </button>
          <Link href="/" class="w-full rounded-full border border-slate-200 bg-white px-4 py-2 text-center text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:text-slate-950 sm:w-auto">Buka Publik</Link>
          <Link href="/admin/logs" class="w-full rounded-full bg-slate-950 px-4 py-2 text-center text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800 sm:w-auto">Lihat Logs</Link>
        </div>
      </div>
    </template>

    <div class="mx-auto max-w-[1280px] px-5 py-10">
      <div class="grid gap-6 xl:grid-cols-[minmax(0,1.6fr)_minmax(320px,0.9fr)]">
        <section class="space-y-6">
          <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
              <div class="text-sm text-slate-500">Status sync</div>
              <div class="mt-2 text-2xl font-bold text-slate-950">{{ sync.status }}</div>
              <div class="mt-2 text-sm text-slate-600">{{ sync.latestRun?.message ?? 'Belum ada proses sinkronisasi tercatat.' }}</div>
            </article>
            <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
              <div class="text-sm text-slate-500">Jadwal</div>
              <div class="mt-2 text-2xl font-bold text-slate-950">{{ sync.scheduleCron }}</div>
              <div class="mt-2 text-sm text-slate-600">Timeout {{ sync.timeout }} detik</div>
            </article>
            <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
              <div class="text-sm text-slate-500">Lock store</div>
              <div class="mt-2 text-2xl font-bold text-slate-950">{{ sync.lockStore }}</div>
              <div class="mt-2 text-sm text-slate-600">Cache {{ sync.cacheStore }}</div>
            </article>
            <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
              <div class="text-sm text-slate-500">Run terakhir</div>
              <div class="mt-2 text-2xl font-bold text-slate-950">{{ formatDate(sync.latestRun?.finishedAt) }}</div>
              <div class="mt-2 text-sm text-slate-600">Selesai proses terakhir</div>
            </article>
          </div>

          <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-6">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between sm:gap-4">
              <div>
                <h2 class="text-lg font-semibold text-slate-950">Riwayat Sinkronisasi</h2>
                <p class="mt-1 text-sm text-slate-600">Urutan terbaru di atas.</p>
              </div>
            </div>

            <div v-if="logs.length" class="mt-5 space-y-4">
              <article v-for="log in logs" :key="log.id" class="rounded-2xl border border-slate-200 bg-slate-50/60 p-4">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                  <div class="flex items-center gap-3">
                    <span class="rounded-full px-3 py-1 text-xs font-semibold ring-1" :class="statusClass(log.status)">{{ log.status }}</span>
                    <span class="text-sm font-medium text-slate-900">{{ log.source }}</span>
                  </div>
                  <span class="text-xs text-slate-500 sm:text-right">{{ formatDate(log.startedAt) }}</span>
                </div>
                <p class="mt-3 text-sm text-slate-700">{{ log.message || '-' }}</p>
                <p class="mt-2 text-xs text-slate-500">Selesai: {{ formatDate(log.finishedAt) }}</p>
              </article>
            </div>
            <div v-else class="mt-5 rounded-2xl border border-dashed border-slate-300 p-8 text-center text-sm text-slate-500">
              Belum ada log sinkronisasi.
            </div>
          </div>
        </section>

        <aside class="space-y-6">
          <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-6">
            <h3 class="text-lg font-semibold text-slate-950">Panduan Operasional</h3>
            <ul class="mt-4 space-y-3 text-sm leading-6 text-slate-600">
              <li>• Jalankan sync manual saat data sumber berubah.</li>
              <li>• Cek status terakhir sebelum memicu sync ulang.</li>
              <li>• Gunakan log untuk telusuri hasil proses terbaru.</li>
            </ul>
            <button
              type="button"
              class="mt-5 w-full rounded-full bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-500 disabled:cursor-not-allowed disabled:opacity-60"
              :disabled="syncForm.processing"
              @click="confirmSync"
            >
              {{ syncForm.processing ? 'Sedang sync...' : 'Jalankan Sync Manual' }}
            </button>
          </div>

          <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-6">
            <h3 class="text-lg font-semibold text-slate-950">Akses Cepat</h3>
            <div class="mt-4 grid gap-3">
              <a href="/admin/logs" class="rounded-full border border-slate-200 px-4 py-2 text-center text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Sync Logs</a>
              <a href="/admin/users" class="rounded-full border border-slate-200 px-4 py-2 text-center text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Users</a>
              <a v-for="link in quickLinks" :key="link.href" :href="link.href" class="rounded-full border border-slate-200 px-4 py-2 text-center text-sm font-semibold text-slate-700 transition hover:bg-slate-50">{{ link.label }}</a>
            </div>
          </div>
        </aside>
      </div>
    </div>

    <Modal :show="confirmingSync" max-width="md" :closeable="!syncForm.processing" @close="closeSyncModal">
      <div class="p-6">
        <div class="flex items-start gap-4">
          <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-emerald-50 text-emerald-700">
            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <path d="M21 12a9 9 0 0 1-9 9 9.8 9.8 0 0 1-6.9-2.9L3 16"></path>
              <path d="M3 21v-5h5"></path>
              <path d="M3 12a9 9 0 0 1 9-9 9.8 9.8 0 0 1 6.9 2.9L21 8"></path>
              <path d="M16 8h5V3"></path>
            </svg>
          </div>
          <div>
            <h2 class="text-lg font-semibold text-slate-950">Jalankan sync manual?</h2>
            <p class="mt-2 text-sm leading-6 text-slate-600">
              Sistem akan mengambil data terbaru dari sumber API dan memperbarui harga BBM. Proses bisa memakan waktu beberapa saat.
            </p>
          </div>
        </div>

        <label class="mt-5 flex gap-3 rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900">
          <input
            v-model="syncForm.force"
            type="checkbox"
            class="mt-1 rounded border-amber-300 text-amber-600 focus:ring-amber-500"
            :disabled="syncForm.processing"
          >
          <span>
            <span class="font-semibold">Force sync</span>
            <span class="mt-1 block leading-6">Pakai ini hanya kalau sync sebelumnya macet. Opsi ini melepas lock proses sync lama sebelum menjalankan proses baru.</span>
          </span>
        </label>

        <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
          <button
            type="button"
            class="rounded-full border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:text-slate-950 disabled:cursor-not-allowed disabled:opacity-60"
            :disabled="syncForm.processing"
            @click="closeSyncModal"
          >
            Batal
          </button>
          <button
            type="button"
            class="rounded-full bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-500 disabled:cursor-not-allowed disabled:opacity-60"
            :disabled="syncForm.processing"
            @click="runSync"
          >
            {{ syncForm.processing ? 'Menjalankan...' : 'Ya, jalankan sync' }}
          </button>
        </div>
      </div>
    </Modal>
  </AuthenticatedLayout>
</template>
