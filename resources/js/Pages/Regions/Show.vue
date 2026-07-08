<script setup>
import { computed, ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import PublicLayout from '../../Layouts/PublicLayout.vue'
import SectionShell from '../../Components/SectionShell.vue'
import UiCard from '../../Components/UiCard.vue'
import SectionHeader from '../../Components/SectionHeader.vue'
import SkeletonCard from '../../Components/SkeletonCard.vue'
import SkeletonLine from '../../Components/SkeletonLine.vue'

const props = defineProps({
  region: Object,
  slug: String,
  historyProducts: Array,
  historyEntries: Object,
  seo: Object,
})

const ronLabels = {
  pertalite: 'RON 90',
  pertamax: 'RON 92',
  'pertamax turbo': 'RON 98',
  dexlite: 'CN 51',
  'pertamina dex': 'CN 53',
}

const selectedProductId = ref('all')
const formatter = new Intl.NumberFormat('id-ID')
const regionName = computed(() => props.region?.name || titleCase(props.slug.replaceAll('-', ' ')))
const prices = computed(() => props.region?.prices || [])
const historyProductNames = computed(() => Object.fromEntries((props.historyProducts || []).map((product) => [String(product.id), product.name])))
const lastSyncedAt = computed(() => prices.value.map((price) => price.last_synced_at).filter(Boolean).sort().at(-1))
const selectedProduct = computed(() => props.historyProducts?.find((product) => String(product.id) === String(selectedProductId.value)) || props.region?.prices?.find((price) => String(price.fuel_product_id) === String(selectedProductId.value))?.product)
const selectedEntries = computed(() => (selectedProductId.value === 'all' ? Object.values(props.historyEntries || {}).flat() : props.historyEntries?.[selectedProductId.value] || []))
const latestChanges = computed(() => Object.values(props.historyEntries || {}).flat().sort((a, b) => new Date(b.changedAt) - new Date(a.changedAt)).slice(0, 24))
const latestChangeByProduct = computed(() => Object.fromEntries(
  Object.entries(props.historyEntries || {}).map(([productId, entries]) => [
    productId,
    [...entries].sort((a, b) => new Date(b.changedAt) - new Date(a.changedAt))[0],
  ]),
))
const trendData = computed(() => {
  if (selectedProductId.value === 'all') {
    return props.historyProducts
      ?.map((product) => {
        const entries = [...(props.historyEntries?.[product.id] || [])].sort((a, b) => new Date(b.changedAt) - new Date(a.changedAt))
        const latestEntry = entries[0]
        const currentPrice = prices.value.find((price) => price.fuel_product_id === product.id)

        return {
          label: product.name,
          price: latestEntry?.newPrice || currentPrice?.price,
        }
      })
      .filter((item) => item.price)
  }

  const entries = [...selectedEntries.value]
    .sort((a, b) => new Date(a.changedAt) - new Date(b.changedAt))
    .map((entry) => ({
      label: formatShortDate(entry.changedAt),
      price: entry.newPrice,
    }))

  if (entries.length > 0) return entries

  const currentPrice = prices.value.find((price) => price.fuel_product_id === selectedProductId.value)
  return currentPrice?.price ? [{ label: 'Saat ini', price: currentPrice.price }] : []
})
const trendSummary = computed(() => {
  if (trendData.value.length === 0) return 'Histori belum tersedia untuk produk ini.'

  if (selectedProductId.value === 'all') return 'Perbandingan harga terbaru untuk semua produk di wilayah ini.'

  const first = Number(trendData.value[0]?.price || 0)
  const last = Number(trendData.value.at(-1)?.price || 0)
  const delta = last - first

  if (delta > 0) return `Naik Rp${formatter.format(delta)} dari awal histori.`
  if (delta < 0) return `Turun Rp${formatter.format(Math.abs(delta))} dari awal histori.`
  return 'Harga stabil sepanjang histori yang tersedia.'
})
const chartRange = computed(() => {
  const values = trendData.value.map((item) => Number(item.price || 0))
  const max = Math.max(...values, 1)
  const min = Math.min(...values, max)
  return { min, range: Math.max(max - min, 1) }
})

function titleCase(value) {
  return value.replace(/\b\w/g, (letter) => letter.toUpperCase())
}

function formatPrice(value) {
  return value === null || value === undefined ? '-' : `Rp ${formatter.format(value)}`
}

function formatDate(value) {
  return value ? new Date(value).toLocaleString('id-ID', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' }) : '-'
}

function formatShortDate(value) {
  return value ? new Date(value).toLocaleDateString('id-ID', { day: '2-digit', month: 'short' }) : 'Saat ini'
}

// Returns { delta, percent } for an entry. Both 0 when prices are null/zero.
function computeDelta(entry) {
  if (entry.oldPrice === null || entry.newPrice === null) return { delta: 0, percent: 0 }
  const delta = entry.newPrice - entry.oldPrice
  const percent = entry.oldPrice === 0 ? 0 : (delta / entry.oldPrice) * 100
  return { delta, percent }
}

function formatPercent(value) {
  return `${new Intl.NumberFormat('id-ID', { maximumFractionDigits: 1 }).format(Math.abs(value))}%`
}

// label and class for history entries (uses +/- prefix)
function deltaLabel(entry) {
  const { delta, percent } = computeDelta(entry)
  if (delta > 0) return `+Rp${formatter.format(delta)} (${formatPercent(percent)})`
  if (delta < 0) return `-Rp${formatter.format(Math.abs(delta))} (${formatPercent(percent)})`
  return 'Tetap'
}

function deltaClass(entry) {
  const { delta } = computeDelta(entry)
  if (delta > 0) return 'text-rose-600'
  if (delta < 0) return 'text-emerald-600'
  return 'text-slate-500'
}

// label and class for current price cards (uses ↗/↘ prefix, looks up entry by product id)
function currentPriceDeltaLabel(price) {
  const entry = latestChangeByProduct.value[price.fuel_product_id]
  if (!entry) return '— Tetap'
  const { delta, percent } = computeDelta(entry)
  if (delta > 0) return `↗ +Rp${formatter.format(delta)} (${formatPercent(percent)})`
  if (delta < 0) return `↘ -Rp${formatter.format(Math.abs(delta))} (${formatPercent(percent)})`
  return '— Tetap'
}

function currentPriceDeltaClass(price) {
  const entry = latestChangeByProduct.value[price.fuel_product_id]
  return entry ? deltaClass(entry) : 'text-slate-500'
}

function badgeClass(label) {
  if (label.includes('92')) return 'bg-blue-100 text-blue-800'
  if (label.includes('98')) return 'bg-rose-100 text-rose-800'
  if (label.includes('CN')) return 'bg-amber-100 text-amber-800'
  return 'bg-emerald-100 text-emerald-800'
}

function barHeight(price) {
  return `${18 + ((Number(price || 0) - chartRange.value.min) / chartRange.value.range) * 62}%`
}
</script>

<template>
  <PublicLayout :seo="seo">
    <SectionShell>
      <div class="flex flex-wrap items-center gap-3 text-sm text-slate-600">
        <Link :href="route('home')" class="hover:text-slate-950">Beranda</Link>
        <span>›</span>
        <Link :href="`${route('home')}#daftar-regional`" class="hover:text-slate-950">Wilayah</Link>
        <span>›</span>
        <span class="text-slate-950">{{ regionName }}</span>
      </div>

      <div class="mt-8 flex flex-col gap-4 md:flex-row md:items-end md:justify-between md:gap-6">
        <SectionHeader :title="regionName" size="display" :description="`Pembaruan terakhir: ${formatDate(lastSyncedAt)} WIB`" />
        <div class="inline-flex w-full items-center justify-center gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-950 shadow-sm sm:w-fit sm:px-5">
          <span class="h-3 w-3 rounded-full bg-emerald-500"></span>
          {{ prices.length > 0 ? 'Data tersedia' : 'Data belum tersedia' }}
        </div>
      </div>
    </SectionShell>

    <section class="bg-slate-50">
      <div class="mx-auto max-w-[1280px] px-4 py-4 sm:px-5 md:py-6">
        <div v-if="!region || prices.length === 0" class="space-y-6">
          <SkeletonCard>
            <div class="space-y-4">
              <SkeletonLine class="h-8 w-2/3" />
              <SkeletonLine class="h-5 w-full" />
              <SkeletonLine class="h-5 w-5/6" />
            </div>
          </SkeletonCard>
          <div class="rounded-3xl border border-slate-200 bg-white p-8 text-center shadow-sm sm:p-10">
            <p class="text-lg font-semibold text-slate-950 sm:text-xl">Data wilayah ini belum tersedia.</p>
            <p class="mt-2 text-sm text-slate-600 sm:text-base">Jalankan sinkronisasi untuk memuat data terbaru.</p>
          </div>
        </div>

        <div v-else class="space-y-6">
          <div class="grid gap-6 lg:grid-cols-[0.95fr_1.95fr]">
            <UiCard>
              <div class="mb-6 flex items-center gap-3 sm:mb-8">
                <h2 class="text-xl font-bold text-slate-950 sm:text-2xl">Harga Saat Ini</h2>
              </div>

              <div class="divide-y divide-slate-200">
                <div v-for="price in prices" :key="price.fuel_product_id" class="py-5 first:pt-0">
                  <div class="flex flex-col gap-4 sm:gap-3">
                    <div class="flex items-start gap-3">
                      <div class="min-w-0 flex-1">
                        <span class="inline-flex rounded-md px-3 py-1 text-sm font-semibold" :class="badgeClass(ronLabels[price.product?.name?.toLowerCase()] || 'BBM')">
                          {{ ronLabels[price.product?.name?.toLowerCase()] || 'BBM' }}
                        </span>
                        <h3 class="mt-3 truncate text-lg font-bold text-slate-950 sm:text-xl">{{ price.product?.name || 'Produk' }}</h3>
                        <p class="mt-1 text-sm text-slate-500">Harga regional {{ regionName }}</p>
                      </div>
                      <div class="shrink-0 rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-left shadow-sm sm:text-right">
                        <p class="text-xl font-bold text-slate-950 sm:text-2xl">{{ formatPrice(price.price) }}</p>
                      </div>
                    </div>
                    <div class="flex items-center justify-between gap-3 sm:justify-end">
                      <p class="text-sm font-semibold" :class="currentPriceDeltaClass(price)">{{ currentPriceDeltaLabel(price) }}</p>
                    </div>
                  </div>
                </div>
              </div>
            </UiCard>

            <div class="space-y-6">
              <UiCard>
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                  <div>
                    <h2 class="text-xl font-bold text-slate-950 sm:text-2xl">Tren Harga</h2>
                    <p class="mt-1 text-sm text-slate-500">{{ trendSummary }}</p>
                  </div>
                  <select v-model="selectedProductId" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-950 outline-none focus:border-slate-950 focus:ring-0 md:w-auto">
                    <option value="all">• Semua Produk</option>
                    <option v-for="product in historyProducts" :key="product.id" :value="product.id">{{ product.name }}</option>
                  </select>
                </div>

                <div v-if="trendData.length === 0" class="mt-6 rounded-2xl border border-dashed border-slate-300 p-6 text-center text-sm text-slate-500 sm:mt-8 sm:p-8">Histori belum tersedia untuk produk ini.</div>
                <div v-else>
                  <div class="mt-6 space-y-3 rounded-2xl border border-slate-200 bg-slate-50 p-4 sm:mt-8 sm:p-5 lg:hidden">
                    <div v-for="(item, index) in trendData.slice(0, 6)" :key="`${item.label}-${index}`" class="rounded-2xl border border-slate-200 bg-white p-3 shadow-sm">
                      <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                          <p class="truncate text-sm font-semibold text-slate-950">{{ item.label }}</p>
                          <p class="text-xs text-slate-500">{{ formatPrice(item.price) }}</p>
                        </div>
                      </div>
                      <div class="mt-3 h-3 overflow-hidden rounded-full bg-slate-200">
                        <div class="h-full rounded-full bg-slate-950 transition-all duration-300" :style="{ width: `${Math.max(12, ((Number(item.price || 0) - chartRange.min) / chartRange.range) * 100)}%` }"></div>
                      </div>
                    </div>
                  </div>

                  <div class="mt-6 hidden rounded-2xl border border-slate-200 bg-slate-50 p-4 sm:mt-8 sm:p-5 lg:block">
                    <div class="grid h-72 grid-rows-[1fr_auto] gap-3">
                      <div class="flex min-h-0 items-end gap-3 overflow-x-auto border-b border-slate-200 pb-0">
                        <div v-for="(item, index) in trendData" :key="`${item.label}-${index}-bar`" class="group flex h-full w-20 shrink-0 items-end px-1">
                          <div class="flex h-full w-full items-end rounded-t-2xl px-1.5 pt-2">
                            <div class="w-full rounded-t-xl bg-gradient-to-t from-slate-950 to-slate-800 transition-all duration-300" :style="{ height: barHeight(item.price) }"></div>
                          </div>
                        </div>
                      </div>

                      <div class="flex gap-3 overflow-x-auto">
                        <div v-for="(item, index) in trendData" :key="`${item.label}-${index}-label`" class="flex w-20 shrink-0 flex-col items-center gap-1 px-1 text-center">
                          <p class="min-h-10 w-full text-xs font-semibold leading-4 text-slate-500 break-words">{{ item.label }}</p>
                          <p class="text-xs font-semibold text-slate-950">{{ formatPrice(item.price) }}</p>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>
              </UiCard>

              <UiCard>
                <h2 class="flex flex-wrap items-center gap-3 text-xl font-bold text-slate-950 sm:text-2xl">
                  <span>Perubahan Terakhir</span>
                  <span v-if="selectedProductId === 'all'" class="inline-flex rounded-full bg-slate-950 px-3 py-1 text-sm font-semibold text-white">Semua Produk</span>
                  <span v-else>{{ selectedProduct?.name || 'Produk' }}</span>
                </h2>
                <div v-if="selectedEntries.length === 0" class="mt-6 rounded-2xl border border-dashed border-slate-300 p-6 text-center text-sm text-slate-500 sm:p-8">Belum ada perubahan harga untuk produk ini.</div>
                <div v-else class="mt-6 grid gap-4 sm:grid-cols-2">
                  <div v-for="entry in selectedEntries.slice(0, 4)" :key="`${entry.productId}-${entry.changedAt}`" class="rounded-2xl border border-slate-200 p-4">
                    <p class="font-semibold text-slate-950">{{ entry.productName || historyProductNames[entry.productId] || selectedProduct?.name || 'Produk' }}</p>
                    <p class="mt-1 text-sm text-slate-500">{{ formatDate(entry.changedAt) }}</p>
                    <p class="mt-2 text-sm text-slate-600">{{ formatPrice(entry.oldPrice) }} → {{ formatPrice(entry.newPrice) }}</p>
                    <p class="mt-2 text-xs font-semibold" :class="deltaClass(entry)">{{ deltaLabel(entry) }}</p>
                  </div>
                </div>
              </UiCard>
            </div>
          </div>

          <div>
            <UiCard>
              <h2 class="text-xl font-bold text-slate-950 sm:text-2xl">Histori Lengkap</h2>
              <div v-if="latestChanges.length === 0" class="mt-6 rounded-2xl border border-dashed border-slate-300 p-6 text-center text-sm text-slate-500 sm:p-8">Belum ada data histori.</div>
              <div v-else class="mt-6 grid gap-4 sm:grid-cols-2">
                <div v-for="entry in latestChanges" :key="`${entry.productId}-${entry.changedAt}`" class="rounded-2xl border border-slate-200 p-4">
                  <p class="font-semibold text-slate-950">{{ entry.productName }}</p>
                  <p class="mt-2 text-sm text-slate-600">{{ formatPrice(entry.oldPrice) }} → {{ formatPrice(entry.newPrice) }}</p>
                  <p class="mt-2 text-xs font-semibold" :class="deltaClass(entry)">{{ deltaLabel(entry) }}</p>
                </div>
              </div>
            </UiCard>
          </div>
        </div>
      </div>
    </section>
  </PublicLayout>
</template>
