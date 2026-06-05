<script setup>
import { Head, Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue'

defineProps({ logs: Object })
</script>

<template>
  <Head title="Audit Log Admin" />
  <AuthenticatedLayout>
    <template #header>
      <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
          <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Admin area</p>
          <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-950">Audit Log</h1>
          <p class="mt-3 text-sm leading-6 text-slate-600">Jejak aksi admin pada manajemen user.</p>
        </div>
        <Link href="/admin/users" class="rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:text-slate-950">Users</Link>
      </div>
    </template>

    <div class="mx-auto max-w-[1280px] px-5 py-10">
      <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-6">
        <div v-if="logs.data.length" class="space-y-4">
          <article v-for="log in logs.data" :key="log.id" class="rounded-2xl border border-slate-200 bg-slate-50/60 p-4">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
              <div>
                <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ log.action }}</div>
                <div class="mt-1 font-medium text-slate-950">{{ log.message }}</div>
                <div class="mt-2 text-sm text-slate-600">Actor: {{ log.actor?.name }} · {{ log.actor?.email }}</div>
              </div>
              <div class="text-xs text-slate-500 sm:text-right">{{ log.createdAt }}</div>
            </div>
          </article>
        </div>
        <div v-else class="rounded-2xl border border-dashed border-slate-300 p-8 text-center text-sm text-slate-500">
          Audit log belum ada.
        </div>

        <div v-if="logs.links?.length > 3" class="mt-6 flex flex-wrap gap-2 overflow-x-auto pb-1 [-ms-overflow-style:none] [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
          <Link
            v-for="link in logs.links"
            :key="link.label"
            :href="link.url || ''"
            class="rounded-full border px-4 py-2 text-sm transition"
            :class="link.active ? 'border-slate-950 bg-slate-950 text-white' : 'border-slate-200 text-slate-700 hover:bg-slate-50'"
            v-html="link.label"
          />
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
