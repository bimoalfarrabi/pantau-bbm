<script setup>
import { Head, usePage } from '@inertiajs/vue3'
import BrandMark from '@/Components/BrandMark.vue'

const page = usePage()
const publicShell = page.props.publicShell || {}

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

  <header class="border-b border-slate-200 bg-slate-50/95 backdrop-blur">
    <nav class="mx-auto flex max-w-[1280px] items-center justify-between px-5 py-4">
      <a href="/" class="shrink-0">
        <BrandMark :label="publicShell.brand || 'PantauBBM'" />
      </a>
      <div class="flex items-center gap-6 text-sm font-semibold text-slate-600">
        <a :href="publicShell.links?.about || '/about'" class="rounded-full px-4 py-2 transition hover:bg-white hover:text-slate-950 hover:shadow-sm">About</a>
      </div>
    </nav>
  </header>

  <main>
    <slot />
  </main>

  <footer id="tentang-data" class="border-t border-slate-200 bg-slate-50">
    <div class="mx-auto flex max-w-[1280px] flex-col gap-6 px-5 py-12 text-sm text-slate-700 md:flex-row md:items-center md:justify-between">
      <div class="flex flex-wrap items-center gap-5">
        <BrandMark :label="publicShell.brand || 'PantauBBM'" />
        <p>{{ publicShell.copyright || '© 2026 PantauBBM' }}</p>
      </div>
      <div class="flex flex-wrap gap-3 text-sm font-semibold">
        <a :href="publicShell.links?.about || '/about'" class="rounded-full px-4 py-2 transition hover:bg-white hover:text-slate-950 hover:shadow-sm">About</a>
        <a :href="publicShell.links?.data_source || '/about#data-source'" class="rounded-full px-4 py-2 transition hover:bg-white hover:text-slate-950 hover:shadow-sm">Data Source</a>
        <a :href="`mailto:${publicShell.contact_email || 'hello@pantaubbm.local'}`" class="rounded-full px-4 py-2 transition hover:bg-white hover:text-slate-950 hover:shadow-sm">Contact</a>
      </div>
      <div class="w-full text-xs text-slate-500 md:w-auto md:text-right">
        <p>{{ publicShell.footer_note || 'Data powered by Bensin API.' }}</p>
        <p>{{ publicShell.disclaimer || 'PantauBBM bukan situs resmi Pertamina atau MyPertamina.' }}</p>
      </div>
    </div>
  </footer>
</template>
