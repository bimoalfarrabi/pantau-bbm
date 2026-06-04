<script setup>
import { Head, Link, router } from '@inertiajs/vue3'
import { reactive, ref, watch } from 'vue'
import AuthenticatedLayout from '../../../Layouts/AuthenticatedLayout.vue'

const props = defineProps({ users: Object, filters: Object })

const form = reactive({
  search: props.filters?.search ?? '',
  role: props.filters?.role ?? 'all',
  status: props.filters?.status ?? 'active',
})
const searchTimer = ref(null)

watch(
  () => ({ ...form }),
  () => {
    clearTimeout(searchTimer.value)
    searchTimer.value = setTimeout(() => {
      router.get(route('admin.users.index'), {
        search: form.search || undefined,
        role: form.role === 'all' ? undefined : form.role,
        status: form.status === 'active' ? undefined : form.status,
      }, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
      })
    }, 250)
  },
  { deep: true },
)

function toggleAdmin(user) {
  if (user.isMe || user.deletedAt) return

  router.patch(route('admin.users.toggle-admin', user.id), {}, { preserveScroll: true })
}

function restoreUser(user) {
  router.patch(route('admin.users.restore', user.id), {}, { preserveScroll: true })
}
</script>

<template>
  <Head title="Manajemen User" />
  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-end justify-between gap-4">
        <div>
          <h2 class="text-xl font-semibold leading-tight text-gray-800">Manajemen User</h2>
          <p class="mt-1 text-sm text-gray-600">Kelola akun admin dan user biasa.</p>
        </div>
        <Link href="/admin/users/create" class="rounded-full bg-slate-950 px-4 py-2 text-sm font-semibold text-white">Tambah User</Link>
      </div>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div v-if="$page.props.flash.status" class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
          {{ $page.props.flash.status }}
        </div>
        <div v-if="$page.props.errors.role" class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
          {{ $page.props.errors.role }}
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
          <div class="flex flex-wrap gap-2">
            <button type="button" class="rounded-full border px-4 py-2 text-sm font-semibold transition" :class="form.status === 'active' ? 'border-slate-950 bg-slate-950 text-white' : 'border-gray-200 text-gray-700 hover:bg-gray-50'" @click="form.status = 'active'">Aktif</button>
            <button type="button" class="rounded-full border px-4 py-2 text-sm font-semibold transition" :class="form.status === 'deleted' ? 'border-slate-950 bg-slate-950 text-white' : 'border-gray-200 text-gray-700 hover:bg-gray-50'" @click="form.status = 'deleted'">Dihapus</button>
          </div>

          <div class="mt-4 grid gap-3 md:grid-cols-[1fr_220px]">
            <label class="block">
              <span class="text-sm font-medium text-gray-700">Search</span>
              <input v-model="form.search" type="search" placeholder="Cari nama atau email" class="mt-1 w-full rounded-lg border-gray-300">
            </label>
            <label class="block">
              <span class="text-sm font-medium text-gray-700">Role</span>
              <select v-model="form.role" class="mt-1 w-full rounded-lg border-gray-300">
                <option value="all">Semua</option>
                <option value="admin">Admin</option>
                <option value="user">User</option>
              </select>
            </label>
          </div>

          <div class="mt-6 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead>
                <tr class="text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                  <th class="py-3 pr-4">Nama</th>
                  <th class="py-3 pr-4">Email</th>
                  <th class="py-3 pr-4">Role</th>
                  <th class="py-3 pr-4">Dibuat</th>
                  <th class="py-3 pr-4">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200">
                <tr v-for="user in users.data" :key="user.id" :class="user.deletedAt ? 'opacity-60' : ''">
                  <td class="py-4 pr-4 font-medium text-gray-900">{{ user.name }}</td>
                  <td class="py-4 pr-4 text-gray-600">
                    {{ user.email }}
                    <span v-if="user.deletedAt" class="ml-2 rounded-full bg-rose-50 px-2 py-1 text-[10px] font-semibold text-rose-700">Deleted</span>
                    <span v-if="user.isMe" class="ml-2 rounded-full bg-slate-50 px-2 py-1 text-[10px] font-semibold text-slate-700">You</span>
                  </td>
                  <td class="py-4 pr-4">
                    <span class="rounded-full px-3 py-1 text-xs font-semibold" :class="user.isAdmin ? 'bg-amber-50 text-amber-700' : 'bg-slate-50 text-slate-700'">
                      {{ user.isAdmin ? 'Admin' : 'User' }}
                    </span>
                  </td>
                  <td class="py-4 pr-4 text-sm text-gray-500">{{ user.createdAt }}</td>
                  <td class="py-4 pr-4">
                    <div class="flex flex-wrap gap-2">
                      <Link v-if="!user.deletedAt" :href="`/admin/users/${user.id}/edit`" class="rounded-full border border-gray-200 px-3 py-1.5 text-xs font-semibold text-gray-700">Edit</Link>
                      <button type="button" class="rounded-full border border-gray-200 px-3 py-1.5 text-xs font-semibold text-gray-700 disabled:cursor-not-allowed disabled:opacity-40" :disabled="user.isMe || user.deletedAt" @click="toggleAdmin(user)">
                        {{ user.isAdmin ? 'Turunkan Admin' : 'Jadikan Admin' }}
                      </button>
                      <button v-if="user.deletedAt" type="button" class="rounded-full border border-emerald-200 px-3 py-1.5 text-xs font-semibold text-emerald-700" @click="restoreUser(user)">Pulihkan</button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <div v-if="users.data.length === 0" class="mt-6 rounded-2xl border border-dashed border-gray-300 p-8 text-center text-sm text-gray-500">
            User tidak ditemukan.
          </div>

          <div v-if="users.links?.length > 3" class="mt-6 flex flex-wrap gap-2">
            <Link
              v-for="link in users.links"
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
