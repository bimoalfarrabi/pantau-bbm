<script setup>
import { onMounted, ref } from 'vue'

const model = defineModel({
  type: String,
  required: true,
})

const input = ref(null)

onMounted(() => {
  if (input.value.hasAttribute('autofocus')) input.value.focus()
})

defineExpose({ focus: () => input.value.focus() })
</script>

<template>
  <input
    ref="input"
    v-model="model"
    class="rounded-xl border-slate-300 text-slate-900 shadow-sm outline-none transition-colors placeholder:text-slate-400 focus:border-slate-950 focus:ring-2 focus:ring-slate-950/10"
  />
</template>
