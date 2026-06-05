<script setup>
defineProps({
  activeProductSlug: {
    type: String,
    default: 'all',
  },
  isLoading: {
    type: Boolean,
    default: false,
  },
  products: {
    type: Array,
    default: () => [],
  },
  sortMode: {
    type: String,
    default: 'lowest',
  },
  visibleRegionCount: {
    type: Number,
    default: 0,
  },
  productCount: {
    type: Number,
    default: 0,
  },
})

defineEmits([
  'update:activeProductSlug',
  'update:sortMode',
])
</script>

<template>
  <div class="rounded-[1.5rem] border border-slate-200 bg-white p-4 shadow-sm sm:rounded-[2rem] sm:p-5 md:p-7">
    <div class="flex flex-col gap-4 border-b border-slate-100 pb-6 lg:flex-row lg:items-center lg:justify-between">
      <div>
        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-400">Regional pricing</p>
        <h2 class="mt-2 text-2xl font-bold tracking-tight text-slate-950 sm:text-3xl md:text-4xl">Daftar Harga Regional</h2>
        <p class="mt-3 text-sm text-slate-600">{{ productCount }} produk BBM • {{ visibleRegionCount }} region tampil</p>
      </div>

      <div class="flex w-full flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-center lg:w-auto lg:justify-end">
        <span class="rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-center text-sm font-semibold text-slate-700">{{ visibleRegionCount }} region</span>
        <label class="sr-only" for="sortMode">Urutkan daftar regional</label>
        <span class="relative inline-flex w-full sm:w-auto">
          <select
            id="sortMode"
            :value="sortMode"
            class="w-full min-w-0 appearance-none rounded-full border border-slate-200 bg-white bg-none py-2 pl-4 pr-10 text-sm font-semibold text-slate-700 shadow-sm outline-none transition hover:border-slate-300 focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/15 sm:min-w-[11rem]"
            @change="$emit('update:sortMode', $event.target.value)"
          >
            <option value="name">Urutkan: Nama</option>
            <option value="lowest">Harga terendah</option>
            <option value="highest">Harga tertinggi</option>
          </select>
          <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-slate-400">▾</span>
        </span>
      </div>
    </div>

    <div class="mt-5 flex items-center gap-2 overflow-x-auto pb-1 text-sm sm:gap-3 [-ms-overflow-style:none] [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
      <span class="shrink-0 rounded-full bg-slate-100 px-4 py-2 font-semibold text-slate-500">Produk</span>
      <button
        class="shrink-0 cursor-pointer rounded-full px-4 py-2 font-semibold transition-colors duration-200 sm:px-5"
        :disabled="isLoading"
        :class="activeProductSlug === 'all' ? 'bg-slate-950 text-white shadow-sm' : 'border border-slate-200 bg-white text-slate-700 hover:border-brand-primary hover:bg-blue-50 hover:text-brand-primary'"
        :aria-pressed="activeProductSlug === 'all'"
        @click="$emit('update:activeProductSlug', 'all')"
      >
        Semua
      </button>
      <button
        v-for="product in products"
        :key="product.slug"
        class="shrink-0 cursor-pointer rounded-full px-4 py-2 font-semibold transition-colors duration-200 sm:px-5"
        :disabled="isLoading"
        :class="activeProductSlug === product.slug ? 'bg-slate-950 text-white shadow-sm' : 'border border-slate-200 bg-white text-slate-700 hover:border-brand-primary hover:bg-blue-50 hover:text-brand-primary'"
        :aria-pressed="activeProductSlug === product.slug"
        @click="$emit('update:activeProductSlug', product.slug)"
      >
        {{ product.name }}
      </button>
    </div>
  </div>
</template>
