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
      <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
          <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Admin area</p>
          <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-950">{{ isEdit ? 'Edit User' : 'Tambah User' }}</h1>
          <p class="mt-3 text-sm leading-6 text-slate-600">Kelola nama, email, password, dan status admin.</p>
        </div>
        <Link href="/admin/users" class="rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:text-slate-950">Kembali</Link>
      </div>
    </template>

    <div class="mx-auto max-w-3xl px-5 py-10">
      <form class="space-y-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm" @submit.prevent="submit">
        <div v-if="form.errors.delete" class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
          {{ form.errors.delete }}
        </div>
        <div v-if="form.errors.role" class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
          {{ form.errors.role }}
        </div>
        <div class="grid gap-4 md:grid-cols-2">
          <label class="block md:col-span-2"><span class="text-sm font-medium text-slate-700">Nama</span><input v-model="form.name" type="text" class="mt-1 w-full rounded-xl border-slate-300"></label>
          <label class="block md:col-span-2"><span class="text-sm font-medium text-slate-700">Email</span><input v-model="form.email" type="email" class="mt-1 w-full rounded-xl border-slate-300"></label>
          <label class="block"><span class="text-sm font-medium text-slate-700">Password</span><input v-model="form.password" type="password" class="mt-1 w-full rounded-xl border-slate-300"></label>
          <label class="block"><span class="text-sm font-medium text-slate-700">Konfirmasi Password</span><input v-model="form.password_confirmation" type="password" class="mt-1 w-full rounded-xl border-slate-300"></label>
        </div>
        <label class="flex items-center gap-2 text-sm text-slate-700"><input v-model="form.is_admin" type="checkbox" class="rounded border-slate-300 text-slate-950"> Admin</label>
        <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-center">
          <button class="w-full rounded-full bg-slate-950 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800 sm:w-auto" :disabled="form.processing">{{ form.processing ? 'Menyimpan...' : 'Simpan' }}</button>
          <Link href="/admin/users" class="w-full rounded-full border border-slate-200 bg-white px-5 py-2.5 text-center text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:text-slate-950 sm:w-auto">Batal</Link>
          <button v-if="isEdit" type="button" class="w-full rounded-full border border-rose-200 px-5 py-2.5 text-sm font-semibold text-rose-700 sm:w-auto" :disabled="form.processing" @click="destroyUser">Hapus</button>
        </div>
      </form>
    </div>
  </AuthenticatedLayout>
</template>
