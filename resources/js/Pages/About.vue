<script setup>
import PublicLayout from '../Layouts/PublicLayout.vue'
import SectionShell from '../Components/SectionShell.vue'
import UiCard from '../Components/UiCard.vue'

const props = defineProps({
  provinceCount: Number,
  latestSyncAt: String,
  seo: Object,
})
</script>

<template>
  <PublicLayout :seo="seo">
    <SectionShell>
      <div class="mb-14 border-b border-slate-200 pb-10">
        <h1 class="text-5xl font-semibold tracking-tight text-slate-950 sm:text-6xl lg:text-7xl">About</h1>
        <p class="mt-5 max-w-3xl text-lg leading-8 text-slate-600 sm:text-xl">Understanding the platform, the people behind it, and the data that powers it.</p>
      </div>

      <div class="grid gap-6 lg:grid-cols-[1.5fr_0.75fr]">
        <UiCard>
          <div class="relative overflow-hidden">
            <div class="absolute right-0 top-0 hidden opacity-10 lg:block" aria-hidden="true">
              <svg width="140" height="140" viewBox="0 0 140 140" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M18 106H32V88H18V106ZM42 106H56V72H42V106ZM66 106H80V56H66V106ZM90 106H104V34H90V106ZM114 106H128V18H114V106Z" fill="#94A3B8"/></svg>
            </div>
            <div class="flex items-start gap-3">
              <div class="mt-1 rounded-full border border-slate-300 p-1 text-slate-950">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"></circle><path d="M12 8h.01"></path><path d="M11 12h1v4h1"></path></svg>
              </div>
              <h2 class="text-3xl font-medium tracking-tight text-slate-950">The Mission</h2>
            </div>
            <div class="mt-6 max-w-3xl space-y-5 text-lg leading-9 text-slate-700">
              <p>PantauBBM dibangun dengan fokus tunggal: bikin pemantauan harga BBM di Indonesia terasa jelas, cepat, dan mudah dipakai. Di tengah perubahan harga yang bisa memengaruhi logistik, mobilitas, dan biaya harian, data yang rapi jadi kebutuhan utama.</p>
              <p>Kami memangkas noise. Tanpa gangguan visual yang tidak perlu, tanpa lapisan informasi yang membingungkan. Hanya data yang bersih, tren yang mudah dibaca, dan akses yang cepat supaya keputusan bisa diambil tanpa ribet.</p>
            </div>
          </div>
        </UiCard>

        <div class="space-y-6">
          <UiCard>
            <div class="flex items-center gap-5">
              <div class="flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 text-slate-700">
                <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a10 10 0 1 0 10 10"></path><path d="M12 6v6l4 2"></path></svg>
              </div>
              <div>
                <div class="text-4xl font-medium tracking-tight text-slate-950">{{ provinceCount ?? 0 }}</div>
                <div class="mt-1 text-lg text-slate-600">Provinces Covered</div>
              </div>
            </div>
          </UiCard>
          <UiCard>
            <div class="flex items-center gap-5">
              <div class="flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 text-slate-700">
                <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-9-9"></path><path d="M21 3v6h-6"></path></svg>
              </div>
              <div>
                <div class="text-3xl font-medium tracking-tight text-slate-950">{{ latestSyncAt ? latestSyncAt : '—' }}</div>
                <div class="mt-1 text-lg text-slate-600">Last Sync</div>
              </div>
            </div>
          </UiCard>
        </div>
      </div>

      <div class="mt-16 grid gap-8 lg:grid-cols-2">
        <section>
          <h2 class="text-3xl font-medium tracking-tight text-slate-950">Creator</h2>
          <div class="mt-4 border-t border-slate-200 pt-8">
            <UiCard>
              <div class="flex flex-col gap-6 sm:flex-row sm:items-start">
                <div class="flex h-28 w-28 shrink-0 items-center justify-center rounded-full bg-slate-950 text-white shadow-lg"><span class="text-4xl font-semibold tracking-tight">PB</span></div>
                <div class="min-w-0">
                  <h3 class="text-3xl font-medium tracking-tight text-slate-950">Pantau Dev Team</h3>
                  <p class="mt-1 text-sm uppercase tracking-[0.28em] text-slate-500">Systems Engineering</p>
                  <p class="mt-5 max-w-xl text-lg leading-8 text-slate-700">Tim kecil yang fokus bangun alat data publik yang cepat, sederhana, dan enak dipakai. Kami percaya utility app tetap bisa terasa rapi, ringan, dan punya detail visual yang matang.</p>
                </div>
              </div>
            </UiCard>
          </div>
        </section>

        <section>
          <h2 class="text-3xl font-medium tracking-tight text-slate-950">Sources & Credits</h2>
          <div class="mt-4 border-t border-slate-200 pt-8">
            <UiCard>
              <div class="space-y-7">
                <div class="flex gap-4"><div class="mt-1 text-slate-950"><svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="5" rx="8" ry="3"></ellipse><path d="M4 5v14c0 1.7 3.6 3 8 3s8-1.3 8-3V5"></path><path d="M4 12c0 1.7 3.6 3 8 3s8-1.3 8-3"></path></svg></div><div><h3 class="text-2xl font-medium tracking-tight text-slate-950">Bensin API</h3><p class="mt-2 text-lg leading-8 text-slate-700">Sumber utama harga BBM yang dipakai untuk sinkronisasi data dan pembaruan tampilan.</p></div></div>
                <div class="flex gap-4"><div class="mt-1 text-slate-950"><svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h16"></path><path d="M4 12h16"></path><path d="M4 17h16"></path><path d="m8 4 4 4-4 4"></path></svg></div><div><h3 class="text-2xl font-medium tracking-tight text-slate-950">Open Source Stack</h3><p class="mt-2 text-lg leading-8 text-slate-700">Dibangun dengan Laravel, Tailwind CSS, dan komponen frontend yang ringan supaya pengalaman tetap konsisten.</p></div></div>
                <div class="flex gap-4"><div class="mt-1 text-slate-950"><svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18"></path><path d="M7 21V8"></path><path d="M12 21V3"></path><path d="M17 21v-8"></path></svg></div><div><h3 class="text-2xl font-medium tracking-tight text-slate-950">Disclaimer</h3><p class="mt-2 text-lg leading-8 text-slate-700">PantauBBM adalah platform independen dan bukan situs resmi Pertamina atau pemerintah Indonesia.</p></div></div>
              </div>
            </UiCard>
          </div>
        </section>
      </div>
    </SectionShell>
  </PublicLayout>
</template>
