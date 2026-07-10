<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3'
import BrandMark from '@/Components/BrandMark.vue'
import SkeletonCard from '@/Components/SkeletonCard.vue'
import SkeletonLine from '@/Components/SkeletonLine.vue'
import PublicSkeletonGrid from '@/Components/PublicSkeletonGrid.vue'
import { isPageLoading, skeletonType } from '@/pageLoading.js'

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
      <div v-if="isPageLoading" key="skeleton" class="border-b border-slate-200 bg-slate-50" aria-hidden="true">
        <div class="mx-auto max-w-[1280px] px-5 py-8">
          <!-- Skeleton: halaman wilayah/show -->
          <template v-if="skeletonType === 'region'">
            <!-- Breadcrumb -->
            <div class="mb-6 flex items-center gap-2 text-sm text-slate-400">
              <SkeletonLine class="h-4 w-16" />
              <span>›</span>
              <SkeletonLine class="h-4 w-16" />
              <span>›</span>
              <SkeletonLine class="h-4 w-32" />
            </div>
            <!-- Header: title + status badge -->
            <div class="mb-8 flex items-end justify-between gap-4">
              <div class="space-y-3">
                <SkeletonLine class="h-10 w-64" />
                <SkeletonLine class="h-4 w-48" />
              </div>
              <SkeletonLine class="h-10 w-36 rounded-xl" />
            </div>
            <!-- Main grid: harga saat ini + tren/perubahan -->
            <div class="grid gap-6 lg:grid-cols-[0.95fr_1.95fr]">
              <!-- Left: Harga Saat Ini -->
              <SkeletonCard>
                <div class="space-y-4">
                  <SkeletonLine class="h-7 w-36" />
                  <div class="space-y-4">
                    <div class="flex items-start justify-between gap-3">
                      <div class="space-y-2">
                        <SkeletonLine class="h-6 w-20 rounded-md" />
                        <SkeletonLine class="h-5 w-32" />
                        <SkeletonLine class="h-5 w-24" />
                      </div>
                      <SkeletonLine class="h-10 w-24 rounded-full" />
                    </div>
                    <div class="flex items-start justify-between gap-3">
                      <div class="space-y-2">
                        <SkeletonLine class="h-6 w-20 rounded-md" />
                        <SkeletonLine class="h-5 w-32" />
                        <SkeletonLine class="h-5 w-24" />
                      </div>
                      <SkeletonLine class="h-10 w-24 rounded-full" />
                    </div>
                    <div class="flex items-start justify-between gap-3">
                      <div class="space-y-2">
                        <SkeletonLine class="h-6 w-20 rounded-md" />
                        <SkeletonLine class="h-5 w-32" />
                        <SkeletonLine class="h-5 w-24" />
                      </div>
                      <SkeletonLine class="h-10 w-24 rounded-full" />
                    </div>
                  </div>
                </div>
              </SkeletonCard>
              <!-- Right: Tren Harga + Perubahan Terakhir -->
              <div class="space-y-6">
                <SkeletonCard>
                  <div class="space-y-4">
                    <div class="flex items-end justify-between">
                      <div class="space-y-2">
                        <SkeletonLine class="h-7 w-28" />
                        <SkeletonLine class="h-4 w-40" />
                      </div>
                      <SkeletonLine class="h-10 w-36 rounded-xl" />
                    </div>
                    <div class="mt-6 grid grid-cols-8 gap-2">
                      <SkeletonLine v-for="i in 8" :key="i" class="h-32" />
                    </div>
                  </div>
                </SkeletonCard>
                <SkeletonCard>
                  <div class="space-y-4">
                    <SkeletonLine class="h-7 w-40" />
                    <div class="grid gap-3 sm:grid-cols-2">
                      <SkeletonLine v-for="i in 4" :key="i" class="h-20" />
                    </div>
                  </div>
                </SkeletonCard>
              </div>
            </div>
            <!-- Bottom: Histori Lengkap -->
            <div class="mt-6">
              <SkeletonCard>
                <div class="space-y-4">
                  <SkeletonLine class="h-7 w-32" />
                  <div class="grid gap-3 sm:grid-cols-2">
                    <SkeletonLine v-for="i in 6" :key="i" class="h-16" />
                  </div>
                </div>
              </SkeletonCard>
            </div>
          </template>

          <!-- Skeleton: halaman about -->
          <template v-else-if="skeletonType === 'about'">
            <div class="grid gap-6 lg:grid-cols-[1.5fr_0.75fr]">
              <SkeletonCard>
                <div class="space-y-5">
                  <SkeletonLine class="h-10 w-1/2" />
                  <SkeletonLine class="h-5 w-full" />
                  <SkeletonLine class="h-5 w-5/6" />
                  <SkeletonLine class="h-5 w-4/5" />
                </div>
              </SkeletonCard>
              <div class="space-y-6">
                <SkeletonCard>
                  <div class="space-y-4">
                    <SkeletonLine class="h-8 w-1/3" />
                    <SkeletonLine class="h-5 w-full" />
                    <SkeletonLine class="h-5 w-2/3" />
                  </div>
                </SkeletonCard>
                <SkeletonCard>
                  <div class="space-y-4">
                    <SkeletonLine class="h-8 w-1/3" />
                    <SkeletonLine class="h-5 w-full" />
                    <SkeletonLine class="h-5 w-2/3" />
                  </div>
                </SkeletonCard>
              </div>
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
      <div v-else key="content">
        <slot />
      </div>
    </Transition>
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
