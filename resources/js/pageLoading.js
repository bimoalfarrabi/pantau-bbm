import { ref, computed } from 'vue'

export const isPageLoading = ref(false)
export const destinationUrl = ref('')

export const skeletonType = computed(() => {
    if (/\/wilayah\//.test(destinationUrl.value)) return 'region'
    if (/\/about/.test(destinationUrl.value)) return 'about'
    return 'home'
})
