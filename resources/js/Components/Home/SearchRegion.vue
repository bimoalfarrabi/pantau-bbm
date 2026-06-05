<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'

defineProps({
  searchQuery: {
    type: String,
    default: '',
  },
  debouncedSearchQuery: {
    type: String,
    default: '',
  },
  suggestions: {
    type: Array,
    default: () => [],
  },
  isSearching: {
    type: Boolean,
    default: false,
  },
})

defineEmits([
  'update:searchQuery',
  'open-selected',
])

const searchInput = ref(null)

function focusSearch() {
  searchInput.value?.focus()
}

function highlightedName(name, keyword) {
  const normalizedKeyword = keyword.trim().toLowerCase()

  if (!normalizedKeyword) return [{ text: name, matches: false }]

  const matchIndex = name.toLowerCase().indexOf(normalizedKeyword)

  if (matchIndex === -1) return [{ text: name, matches: false }]

  return [
    { text: name.slice(0, matchIndex), matches: false },
    { text: name.slice(matchIndex, matchIndex + normalizedKeyword.length), matches: true },
    { text: name.slice(matchIndex + normalizedKeyword.length), matches: false },
  ].filter((part) => part.text !== '')
}
</script>

<template>
  <div class="mx-auto mt-8 max-w-5xl rounded-[1.75rem] border border-slate-200 bg-white/95 px-4 py-4 shadow-[0_10px_30px_rgba(15,23,42,0.06)] backdrop-blur sm:rounded-[2.5rem] sm:px-5 sm:py-5">
    <label for="search" class="sr-only">Cari daerah</label>
    <div class="flex items-center gap-2 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 transition focus-within:border-brand-primary focus-within:bg-white focus-within:shadow-[0_0_0_4px_rgba(59,130,246,0.12)] sm:gap-3 sm:rounded-full sm:px-5">
      <span class="text-xl text-slate-400">⌕</span>
      <input
        ref="searchInput"
        id="search"
        :value="searchQuery"
        type="search"
        placeholder="Cari provinsi atau wilayah..."
        class="min-w-0 flex-1 appearance-none border-0 bg-transparent text-base text-slate-900 outline-none ring-0 placeholder:text-slate-400 focus:outline-none focus:ring-0 md:text-lg"
        @input="$emit('update:searchQuery', $event.target.value)"
        @keydown.enter.prevent="$emit('open-selected')"
      >
      <button
        v-if="searchQuery"
        type="button"
        class="shrink-0 rounded-full px-3 py-1 text-sm font-semibold text-slate-500 transition hover:bg-slate-200 hover:text-slate-900"
        @click="$emit('update:searchQuery', ''); focusSearch()"
      >
        Hapus
      </button>
    </div>

    <div v-if="debouncedSearchQuery || isSearching" class="mt-4 max-h-72 overflow-auto rounded-[1.75rem] border border-slate-200 bg-white p-3 text-left shadow-sm">
      <div class="mb-3 flex items-center justify-between px-2 text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">
        <span>Autocomplete</span>
        <span v-if="suggestions.length">{{ suggestions.length }} hasil</span>
      </div>
      <div class="grid gap-3 sm:grid-cols-2">
        <Link
          v-for="region in suggestions"
          :key="region.slug"
          :href="region.url || `/wilayah/${region.slug}`"
          class="cursor-pointer rounded-2xl border border-slate-200 px-4 py-3 text-sm font-medium text-slate-700 transition duration-200 hover:-translate-y-0.5 hover:border-brand-primary hover:bg-slate-50 hover:text-brand-primary sm:px-5 sm:py-4 sm:text-base"
        >
          <span>
            <template v-for="part in highlightedName(region.name, debouncedSearchQuery)" :key="`${region.slug}-${part.text}-${part.matches}`">
              <mark v-if="part.matches" class="rounded bg-transparent font-bold text-brand-primary">{{ part.text }}</mark>
              <span v-else>{{ part.text }}</span>
            </template>
          </span>
        </Link>
        <p v-if="isSearching" class="px-4 py-3 text-sm text-slate-500">Mencari daerah...</p>
        <p v-if="debouncedSearchQuery && !isSearching && suggestions.length === 0" class="px-4 py-3 text-sm text-slate-500">Daerah tidak ditemukan.</p>
      </div>
    </div>

  </div>
</template>
