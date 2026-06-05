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
      <div class="mb-6 flex items-center justify-between gap-3">
        <SkeletonLine class="h-7 w-2/3" />
        <SkeletonLine class="h-7 w-16" />
      </div>
      <div class="space-y-4">
        <SkeletonLine class="h-5 w-full" />
        <SkeletonLine class="h-5 w-5/6" />
        <SkeletonLine class="h-5 w-2/3" />
      </div>
      <SkeletonLine class="mt-6 h-11 w-full" />
    </SkeletonCard>
  </div>
</template>
