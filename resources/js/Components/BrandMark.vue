<script setup>
defineProps({
  label: {
    type: String,
    default: 'PantauBBM',
  },
  suffix: {
    type: String,
    default: '',
  },
})
</script>

<template>
  <span class="flex items-center gap-2 text-xl font-bold tracking-tight text-slate-950">
    <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-300 text-slate-600">▣</span>
    <span>{{ label }}</span>
    <span v-if="suffix" class="rounded-full bg-slate-900 px-2.5 py-1 text-xs font-semibold text-white">{{ suffix }}</span>
  </span>
</template>
