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
      <div class="flex items-end justify-between gap-4">
        <div>
          <h2 class="text-xl font-semibold leading-tight text-gray-800">Sync Logs</h2>
          <p class="mt-1 text-sm text-gray-600">Daftar proses sinkronisasi terakhir.</p>
        </div>
        <Link href="/admin/dashboard" class="rounded-full border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">Kembali</Link>
      </div>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
          <div class="flex flex-wrap gap-2">
            <button
              v-for="status in statusOptions"
              :key="status"
              type="button"
              class="rounded-full border px-4 py-2 text-sm font-semibold transition"
              :class="filters.status === status || (!filters.status && status === 'all') ? 'border-slate-950 bg-slate-950 text-white' : 'border-gray-200 text-gray-700 hover:bg-gray-50'"
              @click="applyStatus(status)"
            >
              {{ status === 'all' ? 'Semua' : status }}
            </button>
          </div>

          <div v-if="logs.data.length" class="mt-6 space-y-4">
            <article v-for="log in logs.data" :key="log.id" class="rounded-2xl border border-gray-200 p-4">
              <div class="flex flex-wrap items-start justify-between gap-3">
                <div class="space-y-2">
                  <div class="flex items-center gap-3">
                    <span class="rounded-full px-3 py-1 text-xs font-semibold ring-1" :class="statusClass(log.status)">{{ log.status }}</span>
                    <div class="font-semibold text-gray-900">{{ log.source }}</div>
                  </div>
                  <p class="text-sm text-gray-600">{{ log.message || '-' }}</p>
                </div>
                <div class="text-xs text-gray-500 text-right">
                  <div>Mulai: {{ log.startedAt }}</div>
                  <div>Selesai: {{ log.finishedAt || '-' }}</div>
                </div>
              </div>
            </article>
          </div>
          <div v-else class="mt-6 rounded-2xl border border-dashed border-gray-300 p-8 text-center text-sm text-gray-500">
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
                link.active ? 'border-slate-950 bg-slate-950 text-white' : 'border-gray-200 text-gray-700 hover:bg-gray-50',
                !link.url ? 'cursor-not-allowed opacity-50' : '',
              ]"
              v-html="link.label"
            />
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
