<script setup>
import { Link } from '@inertiajs/vue3'
</script>

<template>
  <div class="flex min-h-screen flex-col bg-slate-50 text-slate-900">
    <header class="border-b border-slate-200 bg-slate-50/95 backdrop-blur">
      <nav class="mx-auto flex max-w-[1280px] items-center justify-between px-5 py-4">
        <Link :href="route('home')" class="flex items-center gap-2 text-xl font-bold tracking-tight text-slate-950">
          <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-300 text-slate-600">▣</span>
          PantauBBM
        </Link>
      </nav>
    </header>

    <main class="flex flex-1 items-center justify-center px-5 py-12">
      <div class="w-full max-w-md rounded-[2rem] border border-slate-200 bg-white p-6 shadow-[0_16px_50px_rgba(15,23,42,0.08)] sm:p-8">
        <slot />
      </div>
    </main>
  </div>
</template>
