<script setup>
import { Head, Link, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue'

const props = defineProps({
  logs: Object,
  filters: Object,
  statusOptions: Array,
})

function statusClass(status) {
  if (status === 'success') return 'bg-emerald-50 text-emerald-700 ring-emerald-200'
  if (status === 'failed') return 'bg-rose-50 text-rose-700 ring-rose-200'
  if (status === 'running') return 'bg-amber-50 text-amber-700 ring-amber-200'
  return 'bg-slate-50 text-slate-700 ring-slate-200'
}

function applyStatus(status) {
  router.get(route('admin.logs.index'), status === 'all' ? {} : { status }, { preserveState: true, preserveScroll: true })
}
</script>

<template>
  <Head title="Admin Logs" />
  <AuthenticatedLayout>
    <template #header>
      <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
          <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Admin area</p>
          <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-950">Sync Logs</h1>
          <p class="mt-3 text-sm leading-6 text-slate-600">Daftar proses sinkronisasi terakhir.</p>
        </div>
        <Link href="/admin/dashboard" class="rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:text-slate-950">Kembali</Link>
      </div>
    </template>

    <div class="mx-auto max-w-[1280px] px-5 py-10">
      <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-wrap gap-2">
          <button
            v-for="status in statusOptions"
            :key="status"
            type="button"
            class="rounded-full border px-4 py-2 text-sm font-semibold transition"
            :class="filters.status === status || (!filters.status && status === 'all') ? 'border-slate-950 bg-slate-950 text-white' : 'border-slate-200 text-slate-700 hover:bg-slate-50'"
            @click="applyStatus(status)"
          >
            {{ status === 'all' ? 'Semua' : status }}
          </button>
        </div>

        <div v-if="logs.data.length" class="mt-6 space-y-4">
          <article v-for="log in logs.data" :key="log.id" class="rounded-2xl border border-slate-200 bg-slate-50/60 p-4">
            <div class="flex flex-wrap items-start justify-between gap-3">
              <div class="space-y-2">
                <div class="flex items-center gap-3">
                  <span class="rounded-full px-3 py-1 text-xs font-semibold ring-1" :class="statusClass(log.status)">{{ log.status }}</span>
                  <div class="font-semibold text-slate-950">{{ log.source }}</div>
                </div>
                <p class="text-sm text-slate-600">{{ log.message || '-' }}</p>
              </div>
              <div class="text-right text-xs text-slate-500">
                <div>Mulai: {{ log.startedAt }}</div>
                <div>Selesai: {{ log.finishedAt || '-' }}</div>
              </div>
            </div>
          </article>
        </div>
        <div v-else class="mt-6 rounded-2xl border border-dashed border-slate-300 p-8 text-center text-sm text-slate-500">
          Log kosong untuk filter ini.
        </div>

        <div v-if="logs.links?.length > 3" class="mt-6 flex flex-wrap gap-2">
          <Link
            v-for="link in logs.links"
            :key="link.label"
            :href="link.url || ''"
            preserve-scroll
            class="rounded-full border px-4 py-2 text-sm transition"
            :class="[
              link.active ? 'border-slate-950 bg-slate-950 text-white' : 'border-slate-200 text-slate-700 hover:bg-slate-50',
              !link.url ? 'cursor-not-allowed opacity-50' : '',
            ]"
            v-html="link.label"
          />
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
