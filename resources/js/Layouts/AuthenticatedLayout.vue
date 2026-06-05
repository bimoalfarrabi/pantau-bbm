<script setup>
import { ref } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import BrandMark from '@/Components/BrandMark.vue'
import Dropdown from '@/Components/Dropdown.vue'
import DropdownLink from '@/Components/DropdownLink.vue'
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue'

const showingNavigationDropdown = ref(false)
const page = usePage()
const publicShell = page.props.publicShell || {}

const adminLinks = [
  { label: 'Dashboard', routeName: 'admin.dashboard', pattern: 'admin.dashboard' },
  { label: 'Sync Logs', routeName: 'admin.logs.index', pattern: 'admin.logs.*' },
  { label: 'Users', routeName: 'admin.users.index', pattern: 'admin.users.*' },
  { label: 'Audit', routeName: 'admin.audit-logs.index', pattern: 'admin.audit-logs.*' },
  { label: 'Settings', routeName: 'admin.settings.index', pattern: 'admin.settings.*' },
]
</script>

<template>
  <div class="min-h-screen bg-slate-50 text-slate-900">
    <nav class="border-b border-slate-200 bg-slate-50/95 backdrop-blur">
      <div class="mx-auto max-w-[1280px] px-5 py-4">
        <div class="flex flex-wrap items-center justify-between gap-4 lg:flex-nowrap">
          <div class="flex min-w-0 flex-1 items-center gap-4 sm:gap-8">
            <Link :href="route('admin.dashboard')" class="shrink-0">
              <BrandMark :label="publicShell.brand || 'PantauBBM'" suffix="Admin" />
            </Link>

            <div class="hidden items-center gap-1 lg:flex">
              <Link
                v-for="item in adminLinks"
                :key="item.routeName"
                :href="route(item.routeName)"
                class="rounded-full px-4 py-2 text-sm font-semibold transition"
                :class="route().current(item.pattern) ? 'bg-slate-950 text-white shadow-sm' : 'text-slate-600 hover:bg-white hover:text-slate-950 hover:shadow-sm'"
              >
                {{ item.label }}
              </Link>
            </div>
          </div>

          <div class="hidden items-center gap-3 sm:flex">
            <Link href="/" class="rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:text-slate-950">
              Lihat Publik
            </Link>
            <Dropdown align="right" width="48">
              <template #trigger>
                <button type="button" class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:text-slate-950">
                  {{ $page.props.auth.user.name }}
                  <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                  </svg>
                </button>
              </template>

              <template #content>
                <DropdownLink :href="route('profile.edit')">Profile</DropdownLink>
                <DropdownLink :href="route('logout')" method="post" as="button">Log Out</DropdownLink>
              </template>
            </Dropdown>
          </div>

          <button type="button" class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white p-2 text-slate-500 shadow-sm transition hover:text-slate-700 lg:hidden" @click="showingNavigationDropdown = !showingNavigationDropdown">
            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
              <path :class="{ hidden: showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
              <path :class="{ hidden: !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }" class="mt-4 lg:hidden">
          <div class="space-y-1 rounded-2xl border border-slate-200 bg-white p-2 shadow-sm">
            <ResponsiveNavLink
              v-for="item in adminLinks"
              :key="item.routeName"
              :href="route(item.routeName)"
              :active="route().current(item.pattern)"
            >
              {{ item.label }}
            </ResponsiveNavLink>
            <ResponsiveNavLink href="/">Lihat Publik</ResponsiveNavLink>
            <ResponsiveNavLink :href="route('profile.edit')">Profile</ResponsiveNavLink>
            <ResponsiveNavLink :href="route('logout')" method="post" as="button">Log Out</ResponsiveNavLink>
          </div>
        </div>
      </div>
    </nav>

    <header v-if="$slots.header" class="border-b border-slate-200 bg-white">
      <div class="mx-auto max-w-[1280px] px-5 py-6 sm:py-8">
        <slot name="header" />
      </div>
    </header>

    <main>
      <slot />
    </main>
  </div>
</template>
