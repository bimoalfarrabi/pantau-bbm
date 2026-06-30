<script setup>
import { onBeforeUnmount, onMounted, ref } from 'vue'
import { Head, Link, usePage } from '@inertiajs/vue3'
import BrandMark from '@/Components/BrandMark.vue'

const page = usePage()
const publicShell = page.props.publicShell || {}
const isPageLoading = ref(false)

function showPageSkeleton() {
  isPageLoading.value = true
}

function hidePageSkeleton() {
  isPageLoading.value = false
}

onMounted(() => {
  window.addEventListener('pantau:loading-start', showPageSkeleton)
  window.addEventListener('pantau:loading-finish', hidePageSkeleton)
})

onBeforeUnmount(() => {
  window.removeEventListener('pantau:loading-start', showPageSkeleton)
  window.removeEventListener('pantau:loading-finish', hidePageSkeleton)
})

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
    <meta property="og:image" :content="seo?.image || '/og-image.png'" />
    <link rel="icon" type="image/svg+xml" href="/pantaubbm.svg" />
  </Head>

  <header class="border-b border-slate-200 bg-slate-50/95 backdrop-blur">
    <nav class="mx-auto flex max-w-[1280px] items-center justify-between gap-3 px-4 py-4 sm:px-5">
      <Link href="/" class="shrink-0">
        <BrandMark :label="publicShell.brand || 'PantauBBM'" />
      </Link>
      <div class="flex min-w-0 items-center justify-end gap-3 text-sm font-semibold text-slate-600 sm:gap-6">
        <Link :href="publicShell.links?.about || '/about'" class="rounded-full px-4 py-2 transition hover:bg-white hover:text-slate-950 hover:shadow-sm">Tentang</Link>
      </div>
    </nav>
  </header>

  <main>
    <Transition name="fade-slide" mode="out-in">
      <div v-if="isPageLoading" class="border-b border-slate-200 bg-slate-50" aria-hidden="true">
        <div class="mx-auto max-w-[1280px] px-5 py-8">
          <slot name="skeleton" />
        </div>
      </div>
    </Transition>
    <slot />
  </main>

  <footer id="tentang-data" class="border-t border-slate-200 bg-slate-50">
    <div class="mx-auto flex max-w-[1280px] flex-col items-center gap-6 px-5 py-12 text-center text-sm text-slate-700 md:flex-row md:items-center md:justify-between md:text-left">
      <div class="flex flex-wrap items-center justify-center gap-5 md:justify-start">
        <BrandMark :label="publicShell.brand || 'PantauBBM'" />
        <p>{{ publicShell.copyright || '© 2026 PantauBBM' }}</p>
      </div>
      <div class="flex flex-wrap justify-center gap-3 text-sm font-semibold md:justify-start">
        <Link :href="publicShell.links?.about || '/about'" class="rounded-full px-4 py-2 transition hover:bg-white hover:text-slate-950 hover:shadow-sm">Tentang</Link>
        <Link :href="publicShell.links?.data_source || '/about#data-source'" class="rounded-full px-4 py-2 transition hover:bg-white hover:text-slate-950 hover:shadow-sm">Sumber Data</Link>
        <a :href="`mailto:${publicShell.contact_email || 'hello@pantaubbm.local'}`" class="rounded-full px-4 py-2 transition hover:bg-white hover:text-slate-950 hover:shadow-sm">Kontak</a>
      </div>
      <div class="w-full text-xs text-slate-500 md:w-auto md:text-right">
        <p>{{ publicShell.footer_note || 'Data powered by Bensin API.' }}</p>
        <p>{{ publicShell.disclaimer || 'PantauBBM bukan situs resmi Pertamina atau MyPertamina.' }}</p>
      </div>
    </div>
  </footer>
</template>

<style scoped>
.fade-slide-enter-active,
.fade-slide-leave-active {
  transition: opacity 0.22s ease, transform 0.22s ease;
}

.fade-slide-enter-from,
.fade-slide-leave-to {
  opacity: 0;
  transform: translateY(8px);
}
</style>
