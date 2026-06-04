<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import AuthenticatedLayout from '../../../Layouts/AuthenticatedLayout.vue'

const props = defineProps({ mode: String, user: Object })

const form = useForm({
  name: props.user?.name ?? '',
  email: props.user?.email ?? '',
  password: '',
  password_confirmation: '',
  is_admin: props.user?.isAdmin ?? false,
})

const isEdit = props.mode === 'edit'

function submit() {
  if (isEdit) {
    form.put(route('admin.users.update', props.user.id))
    return
  }

  form.post(route('admin.users.store'))
}

function destroyUser() {
  if (!isEdit) return

  form.delete(route('admin.users.destroy', props.user.id), {
    preserveScroll: true,
  })
}
</script>

<template>
  <Head :title="isEdit ? 'Edit User' : 'Tambah User'" />
  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-end justify-between gap-4">
        <div>
          <h2 class="text-xl font-semibold leading-tight text-gray-800">{{ isEdit ? 'Edit User' : 'Tambah User' }}</h2>
          <p class="mt-1 text-sm text-gray-600">Kelola nama, email, password, dan status admin.</p>
        </div>
        <Link href="/admin/users" class="rounded-full border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-700">Kembali</Link>
      </div>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
        <form class="space-y-6 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200" @submit.prevent="submit">
          <div v-if="form.errors.delete" class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
            {{ form.errors.delete }}
          </div>
          <div v-if="form.errors.role" class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
            {{ form.errors.role }}
          </div>
          <div class="grid gap-4 md:grid-cols-2">
            <label class="block md:col-span-2"><span class="text-sm font-medium text-gray-700">Nama</span><input v-model="form.name" type="text" class="mt-1 w-full rounded-lg border-gray-300"></label>
            <label class="block md:col-span-2"><span class="text-sm font-medium text-gray-700">Email</span><input v-model="form.email" type="email" class="mt-1 w-full rounded-lg border-gray-300"></label>
            <label class="block"><span class="text-sm font-medium text-gray-700">Password</span><input v-model="form.password" type="password" class="mt-1 w-full rounded-lg border-gray-300"></label>
            <label class="block"><span class="text-sm font-medium text-gray-700">Konfirmasi Password</span><input v-model="form.password_confirmation" type="password" class="mt-1 w-full rounded-lg border-gray-300"></label>
          </div>
          <label class="flex items-center gap-2 text-sm text-gray-700"><input v-model="form.is_admin" type="checkbox" class="rounded border-gray-300 text-slate-950"> Admin</label>
          <div class="flex items-center gap-3">
            <button class="rounded-full bg-slate-950 px-5 py-2.5 text-sm font-semibold text-white" :disabled="form.processing">{{ form.processing ? 'Menyimpan...' : 'Simpan' }}</button>
            <Link href="/admin/users" class="rounded-full border border-gray-200 px-5 py-2.5 text-sm font-semibold text-gray-700">Batal</Link>
            <button v-if="isEdit" type="button" class="rounded-full border border-rose-200 px-5 py-2.5 text-sm font-semibold text-rose-700" :disabled="form.processing" @click="destroyUser">Hapus</button>
          </div>
        </form>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
