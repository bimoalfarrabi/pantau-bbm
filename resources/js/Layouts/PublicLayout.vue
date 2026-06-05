<script setup>
import { computed, onBeforeUnmount, onMounted, ref } from 'vue'
import { Head, Link, usePage } from '@inertiajs/vue3'
import BrandMark from '@/Components/BrandMark.vue'
import SkeletonCard from '@/Components/SkeletonCard.vue'
import SkeletonLine from '@/Components/SkeletonLine.vue'

const page = usePage()
const publicShell = page.props.publicShell || {}
const isPageLoading = ref(false)
const skeletonVariant = computed(() => {
  if (page.component === 'Home') return 'home'
  if (page.component === 'About') return 'about'
  if (page.component === 'Regions/Show') return 'region'

  return 'default'
})

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
    <meta property="og:image" :content="'/favicon.ico'" />
  </Head>

  <header class="border-b border-slate-200 bg-slate-50/95 backdrop-blur">
    <nav class="mx-auto flex max-w-[1280px] items-center justify-between px-5 py-4">
      <Link href="/" class="shrink-0">
        <BrandMark :label="publicShell.brand || 'PantauBBM'" />
      </Link>
      <div class="flex items-center gap-6 text-sm font-semibold text-slate-600">
        <Link :href="publicShell.links?.about || '/about'" class="rounded-full px-4 py-2 transition hover:bg-white hover:text-slate-950 hover:shadow-sm">About</Link>
      </div>
    </nav>
  </header>

  <main>
    <Transition name="fade-slide" mode="out-in">
      <div v-if="isPageLoading" class="border-b border-slate-200 bg-slate-50" aria-hidden="true">
        <div class="mx-auto max-w-[1280px] px-5 py-8">
          <div v-if="skeletonVariant === 'home'" class="space-y-6">
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
              <div class="mt-6 flex gap-3 overflow-hidden">
                <SkeletonLine v-for="index in 5" :key="index" class="h-10 w-28 shrink-0" />
              </div>
            </SkeletonCard>
            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
              <SkeletonCard v-for="index in 6" :key="index">
                <div class="space-y-4">
                  <div class="flex items-start justify-between gap-3">
                    <div class="space-y-3">
                      <SkeletonLine class="h-4 w-20" />
                      <SkeletonLine class="h-8 w-44" />
                    </div>
                    <SkeletonLine class="h-7 w-20" />
                  </div>
                  <div class="space-y-3 rounded-2xl border border-slate-200 bg-slate-50/70 p-4">
                    <SkeletonLine class="h-5 w-full" />
                    <SkeletonLine class="h-5 w-5/6" />
                    <SkeletonLine class="h-5 w-4/6" />
                  </div>
                  <SkeletonLine class="h-11 w-full" />
                </div>
              </SkeletonCard>
            </div>
          </div>

          <div v-else-if="skeletonVariant === 'about'" class="grid gap-6 lg:grid-cols-[1.5fr_0.75fr]">
            <SkeletonCard>
              <div class="space-y-5">
                <SkeletonLine class="h-10 w-1/2" />
                <SkeletonLine class="h-5 w-full" />
                <SkeletonLine class="h-5 w-11/12" />
                <SkeletonLine class="h-5 w-4/5" />
              </div>
            </SkeletonCard>
            <div class="space-y-6">
              <SkeletonCard>
                <div class="flex items-center gap-5">
                  <SkeletonLine class="h-16 w-16 shrink-0" />
                  <div class="w-full space-y-3">
                    <SkeletonLine class="h-9 w-24" />
                    <SkeletonLine class="h-5 w-36" />
                  </div>
                </div>
              </SkeletonCard>
              <SkeletonCard>
                <div class="flex items-center gap-5">
                  <SkeletonLine class="h-16 w-16 shrink-0" />
                  <div class="w-full space-y-3">
                    <SkeletonLine class="h-8 w-44" />
                    <SkeletonLine class="h-5 w-28" />
                  </div>
                </div>
              </SkeletonCard>
            </div>
            <div class="lg:col-span-2 grid gap-8 lg:grid-cols-2">
              <div class="space-y-4">
                <SkeletonLine class="h-8 w-36" />
                <SkeletonCard>
                  <div class="flex flex-col gap-6 sm:flex-row sm:items-start">
                    <SkeletonLine class="h-28 w-28 shrink-0" />
                    <div class="w-full space-y-4">
                      <SkeletonLine class="h-8 w-56" />
                      <SkeletonLine class="h-4 w-40" />
                      <SkeletonLine class="h-5 w-full" />
                      <SkeletonLine class="h-5 w-4/5" />
                    </div>
                  </div>
                </SkeletonCard>
              </div>
              <div class="space-y-4">
                <SkeletonLine class="h-8 w-56" />
                <SkeletonCard>
                  <div class="space-y-7">
                    <div v-for="index in 3" :key="index" class="flex gap-4">
                      <SkeletonLine class="h-6 w-6 shrink-0" />
                      <div class="w-full space-y-3">
                        <SkeletonLine class="h-7 w-48" />
                        <SkeletonLine class="h-5 w-full" />
                        <SkeletonLine class="h-5 w-5/6" />
                      </div>
                    </div>
                  </div>
                </SkeletonCard>
              </div>
            </div>
          </div>

          <div v-else-if="skeletonVariant === 'region'" class="space-y-6">
            <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
              <div class="w-full max-w-2xl space-y-4">
                <SkeletonLine class="h-12 w-2/3" />
                <SkeletonLine class="h-5 w-1/2" />
              </div>
              <SkeletonLine class="h-12 w-40" />
            </div>
            <div class="grid gap-6 lg:grid-cols-[0.95fr_1.95fr]">
              <SkeletonCard>
                <div class="space-y-4">
                  <SkeletonLine class="h-7 w-1/2" />
                  <div v-for="index in 4" :key="index" class="flex items-start justify-between gap-4 border-t border-slate-100 pt-4 first:border-t-0 first:pt-0">
                    <div class="space-y-3">
                      <SkeletonLine class="h-6 w-24" />
                      <SkeletonLine class="h-5 w-36" />
                      <SkeletonLine class="h-4 w-28" />
                    </div>
                    <SkeletonLine class="h-8 w-28" />
                  </div>
                </div>
              </SkeletonCard>
              <div class="space-y-6">
                <SkeletonCard>
                  <div class="space-y-5">
                    <div class="flex items-center justify-between gap-4">
                      <div class="space-y-3">
                        <SkeletonLine class="h-7 w-36" />
                        <SkeletonLine class="h-4 w-64" />
                      </div>
                      <SkeletonLine class="h-11 w-36" />
                    </div>
                    <div class="flex h-64 items-end gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-5">
                      <SkeletonLine v-for="index in 8" :key="index" class="w-full rounded-t-xl" :class="index % 3 === 0 ? 'h-28' : index % 2 === 0 ? 'h-40' : 'h-20'" />
                    </div>
                  </div>
                </SkeletonCard>
                <SkeletonCard>
                  <div class="space-y-5">
                    <SkeletonLine class="h-7 w-64" />
                    <div class="grid gap-4 md:grid-cols-2">
                      <div v-for="index in 4" :key="index" class="space-y-3 rounded-2xl border border-slate-200 p-4">
                        <SkeletonLine class="h-5 w-2/3" />
                        <SkeletonLine class="h-4 w-full" />
                        <SkeletonLine class="h-4 w-20" />
                      </div>
                    </div>
                  </div>
                </SkeletonCard>
              </div>
            </div>
            <SkeletonCard>
              <div class="space-y-5">
                <SkeletonLine class="h-7 w-44" />
                <div class="grid gap-4 md:grid-cols-2">
                  <div v-for="index in 6" :key="index" class="space-y-3 rounded-2xl border border-slate-200 p-4">
                    <SkeletonLine class="h-5 w-1/2" />
                    <SkeletonLine class="h-4 w-full" />
                    <SkeletonLine class="h-4 w-20" />
                  </div>
                </div>
              </div>
            </SkeletonCard>
          </div>

          <div v-else class="grid gap-6 md:grid-cols-3">
            <SkeletonCard v-for="index in 3" :key="index">
              <div class="space-y-4">
                <SkeletonLine class="h-8 w-2/3" />
                <SkeletonLine class="h-4 w-full" />
                <SkeletonLine class="h-4 w-5/6" />
              </div>
            </SkeletonCard>
          </div>
        </div>
      </div>
    </Transition>
    <slot />
  </main>

  <footer id="tentang-data" class="border-t border-slate-200 bg-slate-50">
    <div class="mx-auto flex max-w-[1280px] flex-col gap-6 px-5 py-12 text-sm text-slate-700 md:flex-row md:items-center md:justify-between">
      <div class="flex flex-wrap items-center gap-5">
        <BrandMark :label="publicShell.brand || 'PantauBBM'" />
        <p>{{ publicShell.copyright || '© 2026 PantauBBM' }}</p>
      </div>
      <div class="flex flex-wrap gap-3 text-sm font-semibold">
        <Link :href="publicShell.links?.about || '/about'" class="rounded-full px-4 py-2 transition hover:bg-white hover:text-slate-950 hover:shadow-sm">About</Link>
        <Link :href="publicShell.links?.data_source || '/about#data-source'" class="rounded-full px-4 py-2 transition hover:bg-white hover:text-slate-950 hover:shadow-sm">Data Source</Link>
        <a :href="`mailto:${publicShell.contact_email || 'hello@pantaubbm.local'}`" class="rounded-full px-4 py-2 transition hover:bg-white hover:text-slate-950 hover:shadow-sm">Contact</a>
      </div>
      <div class="w-full text-xs text-slate-500 md:w-auto md:text-right">
        <p>{{ publicShell.footer_note || 'Data powered by Bensin API.' }}</p>
        <p>{{ publicShell.disclaimer || 'PantauBBM bukan situs resmi Pertamina atau MyPertamina.' }}</p>
      </div>
    </div>
  </footer>
</template>
