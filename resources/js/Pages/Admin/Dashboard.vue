<script setup>
import { Head } from '@inertiajs/vue3'
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue'

defineProps({
  sync: Object,
  logs: Array,
})
</script>

<template>
  <Head title="Admin Dashboard" />

  <AuthenticatedLayout>
    <template #header>
      <div>
        <h2 class="text-xl font-semibold leading-tight text-gray-800">Admin Dashboard</h2>
        <p class="mt-1 text-sm text-gray-600">Kelola status sinkronisasi, konfigurasi API, dan riwayat proses data BBM.</p>
      </div>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
        <div class="grid gap-6 lg:grid-cols-2 xl:grid-cols-4">
          <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
            <p class="text-sm font-medium text-gray-500">Endpoint API</p>
            <p class="mt-2 break-all text-sm font-semibold text-gray-900">{{ sync.apiBaseUrl }}</p>
          </div>
          <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
            <p class="text-sm font-medium text-gray-500">Timeout</p>
            <p class="mt-2 text-2xl font-semibold text-gray-900">{{ sync.timeout }} detik</p>
          </div>
          <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
            <p class="text-sm font-medium text-gray-500">Jadwal Sync</p>
            <p class="mt-2 text-2xl font-semibold text-gray-900">{{ sync.scheduleCron }}</p>
          </div>
          <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
            <p class="text-sm font-medium text-gray-500">Lock Store</p>
            <p class="mt-2 text-2xl font-semibold text-gray-900">{{ sync.lockStore }}</p>
          </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-2">
          <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Konfigurasi Sinkronisasi</h3>
            <dl class="mt-4 grid gap-4 sm:grid-cols-2">
              <div>
                <dt class="text-sm text-gray-500">Retry Attempts</dt>
                <dd class="mt-1 text-base font-semibold text-gray-900">{{ sync.retryAttempts }}</dd>
              </div>
              <div>
                <dt class="text-sm text-gray-500">Retry Sleep</dt>
                <dd class="mt-1 text-base font-semibold text-gray-900">{{ sync.retrySleepMs }} ms</dd>
              </div>
              <div>
                <dt class="text-sm text-gray-500">User Agent</dt>
                <dd class="mt-1 text-base font-semibold text-gray-900">{{ sync.userAgent }}</dd>
              </div>
              <div>
                <dt class="text-sm text-gray-500">Lock Duration</dt>
                <dd class="mt-1 text-base font-semibold text-gray-900">{{ sync.lockSeconds }} detik</dd>
              </div>
              <div>
                <dt class="text-sm text-gray-500">Cache Store</dt>
                <dd class="mt-1 text-base font-semibold text-gray-900">{{ sync.cacheStore }}</dd>
              </div>
            </dl>
          </div>

          <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Riwayat Sinkronisasi Terakhir</h3>
            <div v-if="logs.length > 0" class="mt-4 space-y-4">
              <article v-for="log in logs" :key="log.id" class="rounded-xl border border-gray-200 p-4">
                <div class="flex items-center justify-between gap-4">
                  <p class="text-sm font-semibold text-gray-900">{{ log.source }}</p>
                  <span class="rounded-full px-3 py-1 text-xs font-semibold" :class="log.status === 'success' ? 'bg-emerald-100 text-emerald-700' : log.status === 'failed' ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700'">
                    {{ log.status }}
                  </span>
                </div>
                <p class="mt-2 text-sm text-gray-600">{{ log.message || '-' }}</p>
                <p class="mt-2 text-xs text-gray-500">Mulai: {{ log.startedAt || '-' }} · Selesai: {{ log.finishedAt || '-' }}</p>
              </article>
            </div>
            <p v-else class="mt-4 text-sm text-gray-500">Belum ada log sinkronisasi.</p>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
