<script setup>
import { computed, onBeforeUnmount, onMounted, ref } from 'vue'
import { Head, Link, usePage } from '@inertiajs/vue3'
import BrandMark from '@/Components/BrandMark.vue'
import SkeletonCard from '@/Components/SkeletonCard.vue'
import SkeletonLine from '@/Components/SkeletonLine.vue'
import PublicSkeletonGrid from '@/Components/PublicSkeletonGrid.vue'

const page = usePage()
const publicShell = page.props.publicShell || {}
const isPageLoading = ref(false)
const destinationUrl = ref('')

function showPageSkeleton(event) {
  destinationUrl.value = event.detail?.url || ''
  isPageLoading.value = true
}

function hidePageSkeleton() {
  isPageLoading.value = false
}

// Detect skeleton type based on destination URL
const skeletonType = computed(() => {
  if (/\/wilayah\//.test(destinationUrl.value)) return 'region'
  return 'home'
})

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
          <!-- Skeleton: halaman wilayah/show -->
          <template v-if="skeletonType === 'region'">
            <div class="grid gap-6 lg:grid-cols-[0.95fr_1.95fr]">
              <SkeletonCard>
                <div class="space-y-4">
                  <SkeletonLine class="h-8 w-1/2" />
                  <SkeletonLine class="h-5 w-full" />
                  <SkeletonLine class="h-5 w-5/6" />
                  <SkeletonLine class="h-5 w-4/5" />
                </div>
              </SkeletonCard>
              <SkeletonCard>
                <div class="space-y-5">
                  <SkeletonLine class="h-8 w-2/5" />
                  <div class="space-y-3">
                    <SkeletonLine class="h-14 w-full" />
                    <SkeletonLine class="h-14 w-full" />
                    <SkeletonLine class="h-14 w-full" />
                  </div>
                </div>
              </SkeletonCard>
            </div>
          </template>

          <!-- Skeleton: halaman home (default) -->
          <template v-else>
            <div class="space-y-6">
              <div class="mx-auto max-w-3xl space-y-4 text-center">
                <SkeletonLine class="mx-auto h-10 w-3/4" />
                <SkeletonLine class="mx-auto h-5 w-2/3" />
              </div>
              <SkeletonCard>
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                  <div class="space-y-3">
                    <SkeletonLine class="h-4 w-40" />
                    <SkeletonLine class="h-9 w-72" />
                    <SkeletonLine class="h-4 w-56" />
                  </div>
                  <div class="flex gap-3">
                    <SkeletonLine class="h-10 w-28" />
                    <SkeletonLine class="h-10 w-44" />
                  </div>
                </div>
              </SkeletonCard>
              <PublicSkeletonGrid :cards="6" />
            </div>
          </template>
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
