<script setup>
import { computed, nextTick, ref, watch } from 'vue'
import { router } from '@inertiajs/vue3'
import PublicLayout from '../Layouts/PublicLayout.vue'
import SectionShell from '../Components/SectionShell.vue'
import SearchRegion from '../Components/Home/SearchRegion.vue'
import RegionalFilterHeader from '../Components/Home/RegionalFilterHeader.vue'
import RegionalPriceGrid from '../Components/Home/RegionalPriceGrid.vue'
import SkeletonCard from '../Components/SkeletonCard.vue'
import SkeletonLine from '../Components/SkeletonLine.vue'

const props = defineProps({
  regions: Array,
  filters: Object,
  products: Array,
  regionalPrices: Array,
  regionalMeta: Object,
  seo: Object,
})

const searchQuery = ref(props.filters?.search || '')
const debouncedSearchQuery = ref(searchQuery.value)
const suggestions = ref([])
const isSearching = ref(false)
const activeProductSlug = ref(props.filters?.product || 'all')
const sortMode = ref(props.filters?.sort || 'lowest')
const isFiltering = ref(false)
const currentPage = ref(props.regionalMeta?.currentPage || 1)
const perPage = ref(props.regionalMeta?.perPage || 6)
let filterTimer = null
let debounceTimer = null
let abortController = null
let searchRequestId = 0
let filterFinishedTimer = null
const minimumFilteringMs = 350

watch(searchQuery, (value) => {
  clearTimeout(debounceTimer)
  debounceTimer = setTimeout(() => {
    debouncedSearchQuery.value = value
    fetchSuggestions(value)
    updateRegionalList({ search: value, page: 1 })
  }, 220)
})

watch(
  () => [currentPage.value, perPage.value, activeProductSlug.value, sortMode.value],
  async () => {
    await nextTick()
    document.getElementById('daftar-regional')?.scrollIntoView({ behavior: 'smooth', block: 'start' })
  },
)

const normalizedKeyword = computed(() => debouncedSearchQuery.value.trim().toLowerCase())
const activeProductLabel = computed(() => {
  if (activeProductSlug.value === 'all') return 'Semua'
  return props.products.find((product) => product.slug === activeProductSlug.value)?.name || 'Produk'
})
const visibleRegionalPrices = computed(() => props.regionalPrices || [])
const visibleRegionCount = computed(() => props.regionalMeta?.total || 0)
const productCount = computed(() => props.products.length)
const totalPages = computed(() => props.regionalMeta?.lastPage || 1)
const paginationStart = computed(() => visibleRegionCount.value === 0 ? 0 : ((currentPage.value - 1) * perPage.value) + 1)
const paginationEnd = computed(() => Math.min(currentPage.value * perPage.value, visibleRegionCount.value))

async function fetchSuggestions(value) {
  abortController?.abort()
  const requestId = ++searchRequestId

  if (!value.trim()) {
    suggestions.value = []
    isSearching.value = false
    return
  }

  abortController = new AbortController()
  isSearching.value = true

  try {
    const params = new URLSearchParams()
    if (value.trim()) params.set('q', value.trim())

    const response = await fetch(`/wilayah/autocomplete?${params.toString()}`, {
      headers: { Accept: 'application/json' },
      signal: abortController.signal,
    })

    if (requestId === searchRequestId) {
      suggestions.value = response.ok ? (await response.json()).data || [] : []
    }
  } catch (error) {
    if (error.name !== 'AbortError' && requestId === searchRequestId) suggestions.value = []
  } finally {
    if (requestId === searchRequestId) isSearching.value = false
  }
}

function openSelectedSuggestion() {
  const selectedSuggestion = suggestions.value[0]
  if (selectedSuggestion) router.visit(selectedSuggestion.url || `/wilayah/${selectedSuggestion.slug}`)
}

function resetFilter() {
  updateRegionalList({ product: 'all', page: 1 })
}

function goToPage(page) {
  updateRegionalList({ page: Math.min(Math.max(page, 1), totalPages.value) })
}

function setPerPage(value) {
  updateRegionalList({ perPage: Number(value), page: 1 })
}

function setProduct(product) {
  updateRegionalList({ product, page: 1 })
}

function setSort(sort) {
  updateRegionalList({ sort, page: 1 })
}

function updateRegionalList(overrides = {}) {
  clearTimeout(filterFinishedTimer)

  const query = {
    search: overrides.search ?? searchQuery.value,
    product: overrides.product ?? activeProductSlug.value,
    sort: overrides.sort ?? sortMode.value,
    page: overrides.page ?? currentPage.value,
    perPage: overrides.perPage ?? perPage.value,
  }

  const requestQuery = cleanRegionalQuery(query)

  isFiltering.value = true
  const filteringStartedAt = performance.now()

  router.get('/', requestQuery, {
    preserveState: true,
    preserveScroll: true,
    replace: true,
    only: ['regionalPrices', 'regionalMeta', 'filters'],
    onSuccess: () => {
      searchQuery.value = query.search
      debouncedSearchQuery.value = query.search
      activeProductSlug.value = query.product
      sortMode.value = query.sort
      currentPage.value = query.page
      perPage.value = query.perPage
    },
    onFinish: () => {
      const elapsedMs = performance.now() - filteringStartedAt
      const remainingMs = Math.max(minimumFilteringMs - elapsedMs, 0)

      filterFinishedTimer = setTimeout(() => {
        isFiltering.value = false
      }, remainingMs)
    },
  })
}

function cleanRegionalQuery(query) {
  const cleanQuery = {}

  if (query.search.trim() !== '') cleanQuery.search = query.search.trim()
  if (query.product !== 'all') cleanQuery.product = query.product
  if (query.sort !== 'lowest') cleanQuery.sort = query.sort
  if (Number(query.page) > 1) cleanQuery.page = Number(query.page)
  if (Number(query.perPage) !== 6) cleanQuery.perPage = Number(query.perPage)

  return cleanQuery
}
</script>

<template>
  <PublicLayout :seo="seo">
    <template #skeleton>
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
    </template>

    <SectionShell>
      <div class="text-center">
        <div class="mx-auto max-w-3xl">
          <h1 class="text-4xl font-bold tracking-tight text-slate-950 md:text-6xl">Pantau harga BBM terkini</h1>
          <p class="mx-auto mt-5 max-w-2xl text-lg leading-8 text-slate-600">Cari provinsi, bandingkan daftar regional, dan buka detail wilayah dengan alur yang cepat.</p>
        </div>

        <SearchRegion
          :search-query="searchQuery"
          :debounced-search-query="debouncedSearchQuery"
          :suggestions="suggestions"
          :is-searching="isSearching"
          @update:search-query="searchQuery = $event"
          @open-selected="openSelectedSuggestion"
        />
      </div>
    </SectionShell>

    <SectionShell>
      <div id="daftar-regional">
        <RegionalFilterHeader
          :is-loading="isFiltering"
          :active-product-slug="activeProductSlug"
          :products="products"
          :sort-mode="sortMode"
          :visible-region-count="visibleRegionCount"
          :product-count="productCount"
          @update:active-product-slug="setProduct"
          @update:sort-mode="setSort"
        />

        <RegionalPriceGrid
          :is-filtering="isFiltering"
          :visible-regional-prices="visibleRegionalPrices"
          :active-product-label="activeProductLabel"
          :current-page="currentPage"
          :total-pages="totalPages"
          :pagination-start="paginationStart"
          :pagination-end="paginationEnd"
          :total-items="visibleRegionCount"
          :per-page="perPage"
          @reset-filter="resetFilter"
          @go-to-page="goToPage"
          @update:per-page="setPerPage"
        />
      </div>
    </SectionShell>
  </PublicLayout>
</template>
