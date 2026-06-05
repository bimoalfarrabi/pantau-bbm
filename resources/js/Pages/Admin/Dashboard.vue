<script setup>
import { Head, Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue'

defineProps({
  sync: Object,
  logs: Array,
  quickLinks: Array,
})

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
    <template #header>
      <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div class="max-w-3xl">
          <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Admin area</p>
          <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-950">Dashboard</h1>
          <p class="mt-3 text-sm leading-6 text-slate-600">Ringkasan sinkronisasi, status runtime, dan pintasan admin dalam gaya yang sama dengan halaman publik.</p>
        </div>
        <div class="flex flex-wrap gap-3">
          <Link href="/" class="rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:text-slate-950">Buka Publik</Link>
          <Link href="/admin/logs" class="rounded-full bg-slate-950 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">Lihat Logs</Link>
        </div>
      </div>
    </template>

    <div class="mx-auto max-w-[1280px] px-5 py-10">
      <div class="grid gap-6 xl:grid-cols-[minmax(0,1.6fr)_minmax(320px,0.9fr)]">
        <section class="space-y-6">
          <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
              <div class="text-sm text-slate-500">Status sync</div>
              <div class="mt-2 text-2xl font-bold text-slate-950">{{ sync.status }}</div>
              <div class="mt-2 text-sm text-slate-600">{{ sync.latestRun?.message ?? 'Belum ada proses sinkronisasi tercatat.' }}</div>
            </article>
            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
              <div class="text-sm text-slate-500">Jadwal</div>
              <div class="mt-2 text-2xl font-bold text-slate-950">{{ sync.scheduleCron }}</div>
              <div class="mt-2 text-sm text-slate-600">Timeout {{ sync.timeout }} detik</div>
            </article>
            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
              <div class="text-sm text-slate-500">Lock store</div>
              <div class="mt-2 text-2xl font-bold text-slate-950">{{ sync.lockStore }}</div>
              <div class="mt-2 text-sm text-slate-600">Cache {{ sync.cacheStore }}</div>
            </article>
            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
              <div class="text-sm text-slate-500">Run terakhir</div>
              <div class="mt-2 text-2xl font-bold text-slate-950">{{ formatDate(sync.latestRun?.finishedAt) }}</div>
              <div class="mt-2 text-sm text-slate-600">Selesai proses terakhir</div>
            </article>
          </div>

          <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between gap-4">
              <div>
                <h2 class="text-lg font-semibold text-slate-950">Riwayat Sinkronisasi</h2>
                <p class="mt-1 text-sm text-slate-600">Urutan terbaru di atas.</p>
              </div>
            </div>

            <div v-if="logs.length" class="mt-5 space-y-4">
              <article v-for="log in logs" :key="log.id" class="rounded-2xl border border-slate-200 bg-slate-50/60 p-4">
                <div class="flex flex-wrap items-center justify-between gap-3">
                  <div class="flex items-center gap-3">
                    <span class="rounded-full px-3 py-1 text-xs font-semibold ring-1" :class="statusClass(log.status)">{{ log.status }}</span>
                    <span class="text-sm font-medium text-slate-900">{{ log.source }}</span>
                  </div>
                  <span class="text-xs text-slate-500">{{ formatDate(log.startedAt) }}</span>
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
          <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="text-lg font-semibold text-slate-950">Panduan Operasional</h3>
            <ul class="mt-4 space-y-3 text-sm leading-6 text-slate-600">
              <li>• Jalankan sync manual saat data sumber berubah.</li>
              <li>• Cek status terakhir sebelum memicu sync ulang.</li>
              <li>• Gunakan log untuk telusuri hasil proses terbaru.</li>
            </ul>
          </div>

          <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
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
  </AuthenticatedLayout>
</template>
