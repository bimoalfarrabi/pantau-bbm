<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import PublicSkeletonGrid from '../PublicSkeletonGrid.vue'

const props = defineProps({
  isFiltering: {
    type: Boolean,
    default: false,
  },
  visibleRegionalPrices: {
    type: Array,
    default: () => [],
  },
  activeProductLabel: {
    type: String,
    default: 'Semua',
  },
  currentPage: {
    type: Number,
    default: 1,
  },
  totalPages: {
    type: Number,
    default: 1,
  },
  paginationStart: {
    type: Number,
    default: 0,
  },
  paginationEnd: {
    type: Number,
    default: 0,
  },
  totalItems: {
    type: Number,
    default: 0,
  },
  perPage: {
    type: Number,
    default: 6,
  },
})

defineEmits([
  'reset-filter',
  'go-to-page',
  'update:per-page',
])

const pageNumbers = computed(() => {
  const pages = []
  const firstPage = Math.max(1, props.currentPage - 2)
  const lastPage = Math.min(props.totalPages, props.currentPage + 2)

  for (let page = firstPage; page <= lastPage; page += 1) pages.push(page)

  return pages
})

const showFirstGap = computed(() => pageNumbers.value.length > 0 && pageNumbers.value[0] > 1)
const showLastGap = computed(() => pageNumbers.value.length > 0 && pageNumbers.value[pageNumbers.value.length - 1] < props.totalPages)

function formatPrice(price) {
  return price === null ? 'Tidak tersedia' : `Rp ${new Intl.NumberFormat('id-ID').format(price)}`
}

function isUnavailable(price) {
  return price === null
}

function availablePrices(region) {
  return region.prices.map((price) => price.price).filter((price) => price !== null)
}

function isLowestPrice(region, price) {
  const prices = availablePrices(region)

  return price !== null && prices.length > 1 && price === Math.min(...prices)
}

function isHighestPrice(region, price) {
  const prices = availablePrices(region)

  return price !== null && prices.length > 1 && price === Math.max(...prices)
}

function priceRowLabel(region, price) {
  if (isLowestPrice(region, price)) return 'Termurah'
  if (isHighestPrice(region, price)) return 'Termahal'

  return null
}
</script>

<template>
  <div>
    <Transition name="fade-slide" mode="out-in">
      <div v-if="isFiltering" key="skeleton" class="mt-8">
        <PublicSkeletonGrid :cards="6" />
      </div>

      <div v-else-if="visibleRegionalPrices.length === 0" key="empty" class="mt-8 rounded-[2rem] border border-slate-200 bg-white p-6 text-center shadow-sm md:p-10">
        <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full border border-slate-200 bg-slate-50 text-sm font-bold text-slate-400">0</div>
        <p class="text-lg font-semibold text-slate-950">Belum ada data untuk {{ activeProductLabel }}.</p>
        <p class="mx-auto mt-2 max-w-sm text-sm leading-6 text-slate-600">Coba pilih filter lain atau jalankan sinkronisasi data terbaru untuk memperbarui daftar harga.</p>
      <button class="mt-5 rounded-full bg-slate-950 px-5 py-2 text-sm font-semibold text-white" @click="$emit('reset-filter')">Tampilkan semua</button>
      </div>

      <div v-else key="grid" class="mt-8 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
        <Link
          v-for="region in visibleRegionalPrices"
          :key="region.slug"
          :href="`/wilayah/${region.slug}`"
          class="group block rounded-[1.5rem] border border-slate-200 bg-white p-6 shadow-sm transition-colors duration-200 hover:border-brand-primary/30 hover:shadow-md focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-primary/40"
        >
          <div class="mb-5 flex items-start justify-between gap-3">
            <div>
              <div class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-400">Region</div>
              <div class="mt-2 text-2xl font-bold tracking-tight text-slate-950 transition group-hover:text-brand-primary md:text-[1.75rem]">{{ region.name }}</div>
            </div>
            <span class="shrink-0 rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-semibold text-slate-600">{{ region.prices.length }} produk</span>
          </div>
          <div class="overflow-hidden rounded-2xl border border-slate-200 bg-slate-50/70">
            <div
              v-for="price in region.prices"
              :key="price.slug"
              class="grid grid-cols-[minmax(0,1fr)_auto] items-center gap-3 px-4 py-3"
              :class="{
                'bg-emerald-50/70': isLowestPrice(region, price.price),
                'bg-amber-50/70': isHighestPrice(region, price.price),
              }"
            >
              <div class="min-w-0">
                <span class="block truncate text-sm font-medium leading-6 text-slate-700 md:text-[15px]" :title="price.name">{{ price.name }}</span>
                <span v-if="priceRowLabel(region, price.price)" class="mt-1 inline-flex rounded-full px-2 py-0.5 text-[11px] font-semibold" :class="isLowestPrice(region, price.price) ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700'">
                  {{ priceRowLabel(region, price.price) }}
                </span>
              </div>
              <span
                class="inline-flex min-w-[7.5rem] shrink-0 items-center justify-center rounded-full px-3 py-1 text-center font-mono text-sm font-semibold tabular-nums md:text-[15px]"
                :class="isUnavailable(price.price) ? 'bg-slate-100 text-slate-400' : 'bg-white text-slate-950 shadow-sm ring-1 ring-slate-200'"
              >
                {{ formatPrice(price.price) }}
              </span>
            </div>
          </div>
          <div class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-slate-500 transition-colors group-hover:text-slate-950">
            <span>Lihat detail wilayah</span>
            <span aria-hidden="true">→</span>
          </div>
        </Link>
      </div>
    </Transition>

    <div v-if="!isFiltering && visibleRegionalPrices.length > 0" class="mt-8 rounded-[1.5rem] border border-slate-200 bg-white px-4 py-4 shadow-sm sm:px-5">
      <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
          <p class="text-sm text-slate-600">
            Menampilkan <span class="font-semibold text-slate-950">{{ paginationStart }}</span>–<span class="font-semibold text-slate-950">{{ paginationEnd }}</span>
            dari <span class="font-semibold text-slate-950">{{ totalItems }}</span> region
          </p>
          <label class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600">
            Per halaman
            <span class="relative inline-flex w-full sm:w-auto">
              <select
                :value="perPage"
                class="w-full min-w-[4.5rem] appearance-none rounded-full border border-slate-200 bg-white bg-none py-1.5 pl-3 pr-9 text-sm font-semibold text-slate-700 shadow-sm outline-none transition-colors hover:border-slate-300 focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/15 sm:w-auto"
                @change="$emit('update:per-page', $event.target.value)"
              >
                <option value="6">6</option>
                <option value="9">9</option>
                <option value="12">12</option>
              </select>
              <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-slate-400" aria-hidden="true">▾</span>
            </span>
          </label>
        </div>

        <div class="flex items-center gap-1 overflow-x-auto pb-1 whitespace-nowrap [-ms-overflow-style:none] [scrollbar-width:none] [&::-webkit-scrollbar]:hidden sm:gap-2 sm:flex-wrap sm:justify-end">
          <button
            type="button"
            class="shrink-0 rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition-colors hover:border-brand-primary hover:text-brand-primary disabled:cursor-not-allowed disabled:opacity-40"
            :disabled="currentPage <= 1 || isFiltering"
            aria-label="Halaman sebelumnya"
            @click="$emit('go-to-page', currentPage - 1)"
          >
            <span class="text-base leading-none sm:hidden" aria-hidden="true">←</span>
            <span class="hidden sm:inline">Sebelumnya</span>
          </button>
          <button
            v-if="showFirstGap"
            type="button"
            class="shrink-0 rounded-full border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 transition-colors hover:border-brand-primary hover:text-brand-primary"
            @click="$emit('go-to-page', 1)"
          >
            1
          </button>
          <span v-if="showFirstGap" class="px-1 text-sm font-semibold text-slate-400">…</span>
          <button
            v-for="page in pageNumbers"
            :key="page"
            type="button"
            class="h-10 min-w-10 shrink-0 rounded-full px-3 text-sm font-semibold transition-colors duration-200"
            :class="page === currentPage ? 'bg-gradient-to-r from-slate-950 to-slate-800 text-white shadow-lg shadow-slate-950/20 ring-1 ring-slate-900/10' : 'border border-slate-200 bg-white text-slate-700 hover:-translate-y-0.5 hover:border-brand-primary hover:text-brand-primary'"
            :aria-current="page === currentPage ? 'page' : null"
            @click="$emit('go-to-page', page)"
          >
            {{ page }}
          </button>
          <span v-if="showLastGap" class="px-1 text-sm font-semibold text-slate-400">…</span>
          <button
            v-if="showLastGap"
            type="button"
            class="shrink-0 rounded-full border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 transition-colors hover:border-brand-primary hover:text-brand-primary"
            @click="$emit('go-to-page', totalPages)"
          >
            {{ totalPages }}
          </button>
          <button
            type="button"
            class="shrink-0 rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition-colors hover:border-brand-primary hover:text-brand-primary disabled:cursor-not-allowed disabled:opacity-40"
            :disabled="currentPage >= totalPages || isFiltering"
            aria-label="Halaman berikutnya"
            @click="$emit('go-to-page', currentPage + 1)"
          >
            <span class="text-base leading-none sm:hidden" aria-hidden="true">→</span>
            <span class="hidden sm:inline">Berikutnya</span>
          </button>
        </div>
      </div>
    </div>
  </div>
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
