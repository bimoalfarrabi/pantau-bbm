<script setup>
import { Head, Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue'

defineProps({ logs: Object })
</script>

<template>
  <Head title="Audit Log Admin" />
  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-end justify-between gap-4">
        <div>
          <h2 class="text-xl font-semibold leading-tight text-gray-800">Audit Log Admin</h2>
          <p class="mt-1 text-sm text-gray-600">Jejak aksi admin pada manajemen user.</p>
        </div>
        <Link href="/admin/users" class="rounded-full border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">Users</Link>
      </div>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
          <div v-if="logs.data.length" class="space-y-4">
            <article v-for="log in logs.data" :key="log.id" class="rounded-2xl border border-gray-200 p-4">
              <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                  <div class="text-xs font-semibold uppercase tracking-wide text-gray-500">{{ log.action }}</div>
                  <div class="mt-1 font-medium text-gray-900">{{ log.message }}</div>
                  <div class="mt-2 text-sm text-gray-600">Actor: {{ log.actor?.name }} · {{ log.actor?.email }}</div>
                </div>
                <div class="text-xs text-gray-500">{{ log.createdAt }}</div>
              </div>
            </article>
          </div>
          <div v-else class="rounded-2xl border border-dashed border-gray-300 p-8 text-center text-sm text-gray-500">
            Audit log belum ada.
          </div>

          <div v-if="logs.links?.length > 3" class="mt-6 flex flex-wrap gap-2">
            <Link
              v-for="link in logs.links"
              :key="link.label"
              :href="link.url || ''"
              class="rounded-full border px-4 py-2 text-sm transition"
              :class="link.active ? 'border-slate-950 bg-slate-950 text-white' : 'border-gray-200 text-gray-700 hover:bg-gray-50'"
              v-html="link.label"
            />
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
