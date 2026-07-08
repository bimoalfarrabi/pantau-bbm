<script setup>
import SkeletonCard from './SkeletonCard.vue'
import SkeletonLine from './SkeletonLine.vue'

defineProps({
  cards: {
    type: Number,
    default: 6,
  },
})
</script>

<template>
  <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
    <SkeletonCard v-for="index in cards" :key="index">
      <!-- Header: "Wilayah" label + region name + badge -->
      <div class="mb-5 flex items-start justify-between gap-3">
        <div>
          <SkeletonLine class="h-3 w-16" />
          <SkeletonLine class="mt-2 h-8 w-36" />
        </div>
        <SkeletonLine class="h-6 w-16 shrink-0" />
      </div>

      <!-- Price table rows -->
      <div class="overflow-hidden rounded-2xl border border-slate-200 bg-slate-50/70">
        <div v-for="row in 3" :key="row" class="grid grid-cols-[minmax(0,1fr)_auto] items-center gap-3 px-4 py-3">
          <SkeletonLine class="h-4 w-3/4" />
          <SkeletonLine class="h-7 w-28 shrink-0" />
        </div>
      </div>

      <!-- Footer: "Lihat detail wilayah →" -->
      <SkeletonLine class="mt-4 h-4 w-32" />
    </SkeletonCard>
  </div>
</template>
