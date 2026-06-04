<script setup>
import { Head } from '@inertiajs/vue3'

defineProps({
  seo: Object,
})
</script>

<template>
  <Head :title="seo?.title || 'PantauBBM - Pantau Harga BBM Indonesia'">
    <meta name="description" :content="seo?.description || 'Pantau harga BBM berdasarkan daerah di Indonesia.'" />
    <link rel="canonical" :href="seo?.canonical || ''" />
    <meta property="og:title" :content="seo?.title || 'PantauBBM - Pantau Harga BBM Indonesia'" />
    <meta property="og:description" :content="seo?.description || 'Pantau harga BBM berdasarkan daerah di Indonesia.'" />
    <meta property="og:type" content="website" />
    <meta property="og:url" :content="seo?.canonical || ''" />
    <meta property="og:image" :content="'/favicon.ico'" />
  </Head>
  <header class="border-b border-slate-200 bg-slate-50">
    <nav class="mx-auto flex max-w-[1280px] items-center justify-between px-5 py-4">
      <a href="/" class="flex items-center gap-2 text-xl font-bold tracking-tight text-slate-950">
        <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-300 text-slate-600">▣</span>
        PantauBBM
      </a>
      <div class="flex items-center gap-6 text-sm text-slate-700">
        <a href="/" class="hover:text-slate-950">Map View</a>
        <a href="/about" class="hover:text-slate-950">About</a>
        <a href="/login" class="rounded-full border border-slate-200 px-4 py-2 font-semibold text-slate-700 transition hover:border-slate-300 hover:text-slate-950">Masuk</a>
        <a href="/#daftar-regional" class="rounded-full bg-slate-950 px-5 py-2.5 font-semibold text-white">Location</a>
      </div>
    </nav>
  </header>

  <main>
    <slot />
  </main>

  <footer id="tentang-data" class="border-t border-slate-200 bg-slate-50">
    <div class="mx-auto flex max-w-[1280px] flex-col gap-6 px-5 py-12 text-sm text-slate-700 md:flex-row md:items-center md:justify-between">
      <div class="flex flex-wrap items-center gap-5">
        <p class="text-xl font-bold text-slate-950">PantauBBM</p>
        <p>© 2026 PantauBBM Data</p>
      </div>
      <div class="flex flex-wrap gap-8 text-base">
        <a href="/about" class="hover:text-slate-950">About</a>
        <a href="/about#data-source" class="hover:text-slate-950">Data Source</a>
        <a href="/login" class="hover:text-slate-950">Masuk</a>
        <a href="mailto:hello@pantaubbm.local" class="hover:text-slate-950">Contact</a>
      </div>
      <div class="w-full text-xs text-slate-500 md:w-auto md:text-right">
        <p>Data powered by Bensin API.</p>
        <p>PantauBBM bukan situs resmi Pertamina atau MyPertamina.</p>
      </div>
    </div>
  </footer>
</template>
