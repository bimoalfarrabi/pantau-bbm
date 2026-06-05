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
  <div :class="centered ? 'mx-auto max-w-3xl text-center min-w-0' : 'max-w-3xl min-w-0'">
    <p v-if="eyebrow" class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">{{ eyebrow }}</p>
    <h1 v-if="size === 'hero'" class="mt-2 text-4xl font-bold tracking-tight text-slate-950 md:text-6xl">
      {{ title }}
    </h1>
    <h1 v-else-if="size === 'display'" class="mt-2 text-4xl font-semibold tracking-tight text-slate-950 sm:text-5xl lg:text-7xl break-words">
      {{ title }}
    </h1>
    <h2 v-else class="mt-2 text-2xl font-semibold tracking-tight text-slate-950 sm:text-3xl break-words">
      {{ title }}
    </h2>
    <p v-if="description" class="mt-5 text-base leading-7 text-slate-600 sm:text-lg sm:leading-8 break-words" :class="centered ? 'mx-auto max-w-2xl' : ''">
      {{ description }}
    </p>
  </div>
</template>
