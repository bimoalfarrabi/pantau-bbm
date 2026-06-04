<script setup>
import { Head, useForm } from '@inertiajs/vue3'
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue'

defineProps({
  metrics: Object,
  sync: Object,
  logs: Array,
})

const form = useForm({ force: false })
const quickLinks = [
  { href: '/admin/settings', label: 'Settings' },
  { href: '/admin/logs', label: 'Logs' },
  { href: '/admin/users', label: 'Users' },
]

function runSync() {
  form.post(route('admin.sync'), {
    preserveScroll: true,
  })
}

function statusClass(status) {
  if (status === 'success') return 'bg-emerald-50 text-emerald-700 ring-emerald-200'
  if (status === 'failed') return 'bg-rose-50 text-rose-700 ring-rose-200'
  if (status === 'running') return 'bg-amber-50 text-amber-700 ring-amber-200'
  return 'bg-slate-50 text-slate-700 ring-slate-200'
}

function formatDate(value) {
  if (!value) return '-'
  return new Intl.DateTimeFormat('id-ID', {
    dateStyle: 'medium',
    timeStyle: 'short',
  }).format(new Date(value.replace(' ', 'T')))
}
</script>

<template>
  <Head title="Admin Operasional" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
          <h2 class="text-xl font-semibold leading-tight text-gray-800">Admin Operasional</h2>
          <p class="mt-1 text-sm text-gray-600">Pantau data, kontrol sinkronisasi, dan cek jejak proses terbaru.</p>
        </div>
        <div class="flex flex-col gap-3 sm:items-end">
          <label class="flex items-center gap-2 text-sm text-gray-600">
            <input v-model="form.force" type="checkbox" class="rounded border-gray-300 text-slate-950 focus:ring-slate-950">
            Force release lock
          </label>
          <button
            type="button"
            class="inline-flex items-center justify-center rounded-full bg-slate-950 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800 disabled:cursor-not-allowed disabled:opacity-60"
            :disabled="form.processing"
            @click="runSync"
          >
            {{ form.processing ? 'Menjalankan sync...' : 'Jalankan Sync Sekarang' }}
          </button>
        </div>
      </div>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
        <div v-if="$page.props.flash.sync_message" class="rounded-2xl border px-4 py-3 text-sm" :class="$page.props.flash.sync_status === 'success' ? 'border-emerald-200 bg-emerald-50 text-emerald-700' : 'border-rose-200 bg-rose-50 text-rose-700'">
          <div class="font-semibold">{{ $page.props.flash.sync_status === 'success' ? 'Sync selesai' : 'Sync gagal' }}</div>
          <div class="mt-1 whitespace-pre-wrap">{{ $page.props.flash.sync_message }}</div>
          <div v-if="$page.props.flash.sync_finished_at" class="mt-1 text-xs opacity-80">Selesai {{ formatDate($page.props.flash.sync_finished_at) }}</div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
          <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
            <p class="text-sm font-medium text-gray-500">Wilayah</p>
            <p class="mt-2 text-3xl font-semibold text-gray-900">{{ metrics.regions }}</p>
          </div>
          <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
            <p class="text-sm font-medium text-gray-500">Produk BBM</p>
            <p class="mt-2 text-3xl font-semibold text-gray-900">{{ metrics.products }}</p>
          </div>
          <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
            <p class="text-sm font-medium text-gray-500">Harga Aktif</p>
            <p class="mt-2 text-3xl font-semibold text-gray-900">{{ metrics.prices }}</p>
          </div>
          <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
            <p class="text-sm font-medium text-gray-500">Riwayat Perubahan</p>
            <p class="mt-2 text-3xl font-semibold text-gray-900">{{ metrics.histories }}</p>
          </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.2fr_0.8fr]">
          <div class="space-y-6">
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
              <div class="flex items-start justify-between gap-4">
                <div>
                  <h3 class="text-lg font-semibold text-gray-900">Status Sync</h3>
                  <p class="mt-1 text-sm text-gray-500">Konfigurasi runtime sinkronisasi BBM.</p>
                </div>
                <span class="rounded-full px-3 py-1 text-xs font-semibold ring-1" :class="statusClass(sync.latestRun?.status)">
                  {{ sync.latestRun?.status ?? 'belum ada data' }}
                </span>
              </div>

              <dl class="mt-6 grid gap-4 sm:grid-cols-2">
                <div>
                  <dt class="text-sm text-gray-500">Endpoint API</dt>
                  <dd class="mt-1 break-all font-semibold text-gray-900">{{ sync.apiBaseUrl }}</dd>
                </div>
                <div>
                  <dt class="text-sm text-gray-500">Jadwal Cron</dt>
                  <dd class="mt-1 font-semibold text-gray-900">{{ sync.scheduleCron }}</dd>
                </div>
                <div>
                  <dt class="text-sm text-gray-500">Timeout</dt>
                  <dd class="mt-1 font-semibold text-gray-900">{{ sync.timeout }} detik</dd>
                </div>
                <div>
                  <dt class="text-sm text-gray-500">Retry</dt>
                  <dd class="mt-1 font-semibold text-gray-900">{{ sync.retryAttempts }}x / {{ sync.retrySleepMs }} ms</dd>
                </div>
                <div>
                  <dt class="text-sm text-gray-500">Lock Store</dt>
                  <dd class="mt-1 font-semibold text-gray-900">{{ sync.lockStore }}</dd>
                </div>
                <div>
                  <dt class="text-sm text-gray-500">Cache Store</dt>
                  <dd class="mt-1 font-semibold text-gray-900">{{ sync.cacheStore }}</dd>
                </div>
              </dl>

              <div class="mt-6 rounded-2xl bg-slate-50 p-4 text-sm text-slate-600">
                <div class="font-semibold text-slate-900">Run terakhir</div>
                <div class="mt-1">{{ sync.latestRun?.message ?? 'Belum ada proses sinkronisasi tercatat.' }}</div>
                <div class="mt-2 text-xs text-slate-500">
                  <div>Mulai: {{ formatDate(sync.latestRun?.startedAt) }}</div>
                  <div>Selesai: {{ formatDate(sync.latestRun?.finishedAt) }}</div>
                </div>
              </div>
            </div>

            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
              <div class="flex items-center justify-between gap-4">
                <div>
                  <h3 class="text-lg font-semibold text-gray-900">Riwayat Sinkronisasi</h3>
                  <p class="mt-1 text-sm text-gray-500">Urutan terbaru di atas.</p>
                </div>
              </div>

              <div v-if="logs.length" class="mt-5 space-y-4">
                <article v-for="log in logs" :key="log.id" class="rounded-2xl border border-gray-200 p-4">
                  <div class="flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                      <span class="rounded-full px-3 py-1 text-xs font-semibold ring-1" :class="statusClass(log.status)">{{ log.status }}</span>
                      <span class="text-sm font-medium text-gray-900">{{ log.source }}</span>
                    </div>
                    <span class="text-xs text-gray-500">{{ formatDate(log.startedAt) }}</span>
                  </div>
                  <p class="mt-3 text-sm text-gray-700">{{ log.message || '-' }}</p>
                  <p class="mt-2 text-xs text-gray-500">Selesai: {{ formatDate(log.finishedAt) }}</p>
                </article>
              </div>
              <div v-else class="mt-5 rounded-2xl border border-dashed border-gray-300 p-8 text-center text-sm text-gray-500">
                Belum ada log sinkronisasi.
              </div>
            </div>
          </div>

          <aside class="space-y-6">
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
              <h3 class="text-lg font-semibold text-gray-900">Panduan Operasional</h3>
              <ul class="mt-4 space-y-3 text-sm leading-6 text-gray-600">
                <li>• Jalankan sync manual saat data sumber berubah atau ingin verifikasi cepat.</li>
                <li>• Cek status terakhir sebelum memicu sync ulang agar tidak tumpang tindih.</li>
                <li>• Gunakan log untuk menelusuri hasil proses terbaru dan pesan error.</li>
              </ul>
            </div>

            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
              <h3 class="text-lg font-semibold text-gray-900">Akses Cepat</h3>
              <div class="mt-4 grid gap-3">
                <a href="/" class="rounded-full border border-gray-200 px-4 py-2 text-center text-sm font-semibold text-gray-700 transition hover:bg-gray-50">Buka Homepage</a>
                <a href="/riwayat" class="rounded-full border border-gray-200 px-4 py-2 text-center text-sm font-semibold text-gray-700 transition hover:bg-gray-50">Lihat Riwayat Publik</a>
                <a v-for="link in quickLinks" :key="link.href" :href="link.href" class="rounded-full border border-gray-200 px-4 py-2 text-center text-sm font-semibold text-gray-700 transition hover:bg-gray-50">{{ link.label }}</a>
              </div>
            </div>

            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
              <h3 class="text-lg font-semibold text-gray-900">Status Singkat</h3>
              <div class="mt-4 rounded-2xl bg-slate-50 p-4 text-sm text-slate-700">
                <div class="font-semibold text-slate-900">Sync terakhir</div>
                <div class="mt-1">{{ formatDate(sync.latestRun?.finishedAt) }}</div>
                <div class="mt-3 text-xs text-slate-500">Lock: {{ sync.lockStore }} · Cache: {{ sync.cacheStore }}</div>
              </div>
            </div>
          </aside>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
