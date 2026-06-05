<script setup>
defineProps({
  type: {
    type: String,
    default: 'submit',
  },
})
</script>

<template>
  <button
    :type="type"
    class="inline-flex items-center justify-center rounded-full border border-transparent bg-slate-950 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-950/20 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
  >
    <slot />
  </button>
</template>
