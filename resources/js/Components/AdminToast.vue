<script setup>
import { computed, ref, watch } from 'vue'

const props = defineProps({
  show: {
    type: Boolean,
    default: false,
  },
  variant: {
    type: String,
    default: 'success',
  },
  title: {
    type: String,
    default: '',
  },
  message: {
    type: String,
    default: '',
  },
  details: {
    type: String,
    default: '',
  },
})

const emit = defineEmits(['close'])
const visible = ref(props.show)

watch(
  () => props.show,
  (show) => {
    visible.value = show
  },
)

const variantClasses = computed(() => {
  if (props.variant === 'error') {
    return {
      border: 'border-rose-200',
      icon: 'bg-rose-50 text-rose-700',
    }
  }

  return {
    border: 'border-emerald-200',
    icon: 'bg-emerald-50 text-emerald-700',
  }
})

function closeToast() {
  visible.value = false
  emit('close')
}
</script>

<template>
  <Transition
    enter-active-class="transition ease-out duration-200"
    enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
    enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
    leave-active-class="transition ease-in duration-150"
    leave-from-class="opacity-100"
    leave-to-class="opacity-0"
  >
    <div
      v-if="visible && message"
      class="fixed right-4 top-4 z-50 w-[calc(100%-2rem)] max-w-md rounded-2xl border bg-white p-4 shadow-xl sm:right-6 sm:top-6"
      :class="variantClasses.border"
    >
      <div class="flex gap-3">
        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full" :class="variantClasses.icon">
          <svg v-if="variant !== 'error'" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M20 6 9 17l-5-5"></path>
          </svg>
          <svg v-else class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 6 6 18"></path>
            <path d="m6 6 12 12"></path>
          </svg>
        </div>
        <div class="min-w-0 flex-1">
          <div class="text-sm font-semibold text-slate-950">{{ title }}</div>
          <p class="mt-1 text-sm leading-5 text-slate-600">{{ message }}</p>
          <p v-if="details" class="mt-2 text-xs text-slate-500">{{ details }}</p>
        </div>
        <button type="button" class="rounded-full p-1 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600" @click="closeToast">
          <span class="sr-only">Tutup notifikasi</span>
          <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 6 6 18"></path>
            <path d="m6 6 12 12"></path>
          </svg>
        </button>
      </div>
    </div>
  </Transition>
</template>
