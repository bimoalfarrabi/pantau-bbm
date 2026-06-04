<script setup>
import { computed } from 'vue'
import PublicLayout from '../../Layouts/PublicLayout.vue'

const props = defineProps({
  histories: Array,
})

const visibleHistories = computed(() => props.histories || [])
</script>

<template>
  <PublicLayout :seo="{ title: 'Riwayat Harga BBM - PantauBBM', description: 'Lihat riwayat perubahan harga BBM Indonesia berdasarkan wilayah dan produk.', canonical: '/riwayat' }">
    <section class="bg-slate-50">
      <div class="mx-auto max-w-[1280px] px-5 py-16 md:py-20 text-center">
        <h1 class="text-4xl font-bold tracking-tight text-slate-950 md:text-6xl">Riwayat Harga BBM</h1>
        <p class="mx-auto mt-5 max-w-2xl text-lg leading-8 text-slate-600">Perubahan dicatat saat harga terbaru berbeda dari harga sebelumnya.</p>
      </div>
    </section>

    <section class="mx-auto max-w-[1280px] px-5 py-16">
      <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div v-if="visibleHistories.length === 0" class="p-10 text-center">
          <p class="text-lg font-semibold text-slate-950">Belum ada riwayat perubahan harga.</p>
          <p class="mt-2 text-slate-600">Riwayat akan muncul setelah sync menemukan harga yang berubah.</p>
        </div>
        <div v-else class="overflow-x-auto">
          <table class="w-full min-w-[760px] text-left text-sm">
            <thead class="border-b border-slate-200 bg-slate-50 text-slate-600">
              <tr><th class="p-5 font-semibold">Tanggal</th><th class="p-5 font-semibold">Wilayah</th><th class="p-5 font-semibold">Produk</th><th class="p-5 font-semibold">Harga Lama</th><th class="p-5 font-semibold">Harga Baru</th></tr>
            </thead>
            <tbody>
              <tr v-for="history in visibleHistories" :key="`${history.regionName}-${history.productName}-${history.changedAt}`" class="border-b border-slate-200 last:border-b-0">
                <td class="p-5 text-slate-600">{{ history.changedAt ? new Date(history.changedAt).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }) : '-' }}</td>
                <td class="p-5 font-semibold text-slate-950">{{ history.regionName }}</td>
                <td class="p-5 text-slate-700">{{ history.productName }}</td>
                <td class="p-5 text-slate-700">{{ history.oldPrice === null ? '-' : `Rp ${new Intl.NumberFormat('id-ID').format(history.oldPrice)}` }}</td>
                <td class="p-5 font-semibold text-slate-950">{{ history.newPrice === null ? '-' : `Rp ${new Intl.NumberFormat('id-ID').format(history.newPrice)}` }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </section>
  </PublicLayout>
</template>
