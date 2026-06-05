<script setup>
defineProps({
  eyebrow: {
    type: String,
    default: '',
  },
  title: {
    type: String,
    required: true,
  },
  description: {
    type: String,
    default: '',
  },
  centered: {
    type: Boolean,
    default: false,
  },
  size: {
    type: String,
    default: 'md',
  },
})
</script>

<template>
  <div :class="centered ? 'mx-auto max-w-3xl text-center' : 'max-w-3xl'">
    <p v-if="eyebrow" class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">{{ eyebrow }}</p>
    <h1 v-if="size === 'hero'" class="mt-2 text-4xl font-bold tracking-tight text-slate-950 md:text-6xl">
      {{ title }}
    </h1>
    <h1 v-else-if="size === 'display'" class="mt-2 text-5xl font-semibold tracking-tight text-slate-950 sm:text-6xl lg:text-7xl">
      {{ title }}
    </h1>
    <h2 v-else class="mt-2 text-3xl font-semibold tracking-tight text-slate-950">
      {{ title }}
    </h2>
    <p v-if="description" class="mt-5 text-lg leading-8 text-slate-600" :class="centered ? 'mx-auto max-w-2xl' : ''">
      {{ description }}
    </p>
  </div>
</template>
