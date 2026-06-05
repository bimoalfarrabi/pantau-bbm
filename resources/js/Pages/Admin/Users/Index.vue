<script setup>
import { Head, Link, router, useForm } from '@inertiajs/vue3'
import { reactive } from 'vue'
import AuthenticatedLayout from '../../../Layouts/AuthenticatedLayout.vue'

const props = defineProps({ users: Object, filters: Object })

const form = reactive({
  search: props.filters.search || '',
  role: props.filters.role || 'all',
  status: props.filters.status || 'active',
})

const actionForm = useForm({})

function submitFilters() {
  router.get(route('admin.users.index'), {
    search: form.search,
    role: form.role === 'all' ? '' : form.role,
    status: form.status,
  }, { preserveState: true, preserveScroll: true })
}

function toggleAdmin(user) {
  actionForm.patch(route('admin.users.toggle-admin', user.id), { preserveScroll: true })
}

function restoreUser(user) {
  actionForm.post(route('admin.users.restore', user.id), { preserveScroll: true })
}
</script>

<template>
  <Head title="Admin Users" />
  <AuthenticatedLayout>
    <template #header>
      <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
          <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Admin area</p>
          <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-950">Users</h1>
          <p class="mt-3 text-sm leading-6 text-slate-600">Kelola user, role, dan status aktif dalam tampilan yang lebih rapi.</p>
        </div>
        <Link href="/admin/users/create" class="rounded-full bg-slate-950 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">Tambah User</Link>
      </div>
    </template>

    <div class="mx-auto max-w-[1280px] px-5 py-10">
      <div v-if="$page.props.errors.role" class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
        {{ $page.props.errors.role }}
      </div>

      <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <form class="grid gap-4 md:grid-cols-[1fr_220px_220px_auto]" @submit.prevent="submitFilters">
          <label class="block md:col-span-1">
            <span class="text-sm font-medium text-slate-700">Search</span>
            <input v-model="form.search" type="search" placeholder="Cari nama atau email" class="mt-1 w-full rounded-xl border-slate-300">
          </label>
          <label class="block">
            <span class="text-sm font-medium text-slate-700">Role</span>
            <select v-model="form.role" class="mt-1 w-full rounded-xl border-slate-300">
              <option value="all">Semua</option>
              <option value="admin">Admin</option>
              <option value="user">User</option>
            </select>
          </label>
          <label class="block">
            <span class="text-sm font-medium text-slate-700">Status</span>
            <select v-model="form.status" class="mt-1 w-full rounded-xl border-slate-300">
              <option value="active">Aktif</option>
              <option value="deleted">Dihapus</option>
            </select>
          </label>
          <button type="submit" class="mt-6 rounded-full bg-slate-950 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">Filter</button>
        </form>

        <div class="mt-6 overflow-x-auto">
          <table class="min-w-full divide-y divide-slate-200">
            <thead>
              <tr class="text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                <th class="py-3 pr-4">Nama</th>
                <th class="py-3 pr-4">Email</th>
                <th class="py-3 pr-4">Role</th>
                <th class="py-3 pr-4">Dibuat</th>
                <th class="py-3 pr-4">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
              <tr v-for="user in users.data" :key="user.id" :class="user.deletedAt ? 'opacity-60' : ''">
                <td class="py-4 pr-4 font-medium text-slate-950">{{ user.name }}</td>
                <td class="py-4 pr-4 text-slate-600">
                  {{ user.email }}
                  <span v-if="user.deletedAt" class="ml-2 rounded-full bg-rose-50 px-2 py-1 text-[10px] font-semibold text-rose-700">Deleted</span>
                  <span v-if="user.isMe" class="ml-2 rounded-full bg-slate-50 px-2 py-1 text-[10px] font-semibold text-slate-700">You</span>
                </td>
                <td class="py-4 pr-4">
                  <span class="rounded-full px-3 py-1 text-xs font-semibold" :class="user.isAdmin ? 'bg-amber-50 text-amber-700' : 'bg-slate-50 text-slate-700'">{{ user.isAdmin ? 'Admin' : 'User' }}</span>
                </td>
                <td class="py-4 pr-4 text-sm text-slate-500">{{ user.createdAt }}</td>
                <td class="py-4 pr-4">
                  <div class="flex flex-wrap gap-2">
                    <Link v-if="!user.deletedAt" :href="`/admin/users/${user.id}/edit`" class="rounded-full border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-50">Edit</Link>
                    <button type="button" class="rounded-full border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-40" :disabled="user.isMe || user.deletedAt || actionForm.processing" @click="toggleAdmin(user)">
                      {{ user.isAdmin ? 'Turunkan Admin' : 'Jadikan Admin' }}
                    </button>
                    <button v-if="user.deletedAt" type="button" class="rounded-full border border-emerald-200 px-3 py-1.5 text-xs font-semibold text-emerald-700 disabled:cursor-not-allowed disabled:opacity-40" :disabled="actionForm.processing" @click="restoreUser(user)">Pulihkan</button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div v-if="users.data.length === 0" class="mt-6 rounded-2xl border border-dashed border-slate-300 p-8 text-center text-sm text-slate-500">
          User tidak ditemukan.
        </div>

        <div v-if="users.links?.length > 3" class="mt-6 flex flex-wrap gap-2">
          <Link
            v-for="link in users.links"
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
