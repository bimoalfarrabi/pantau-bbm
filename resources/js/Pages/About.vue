<script setup>
import PublicLayout from '../Layouts/PublicLayout.vue'
import SectionShell from '../Components/SectionShell.vue'
import UiCard from '../Components/UiCard.vue'
import SkeletonCard from '../Components/SkeletonCard.vue'
import SkeletonLine from '../Components/SkeletonLine.vue'

const props = defineProps({
  provinceCount: Number,
  latestSyncAt: String,
  aboutContent: Object,
  seo: Object,
})

const content = props.aboutContent || {}

const hasUrl = (url) => typeof url === 'string' && url.trim().length > 0
</script>

<template>
  <PublicLayout :seo="seo">
    <SectionShell>
      <div v-if="provinceCount === undefined || latestSyncAt === undefined" class="mb-10 grid gap-6 lg:grid-cols-2">
        <SkeletonCard>
          <div class="space-y-4">
            <SkeletonLine class="h-10 w-1/2" />
            <SkeletonLine class="h-5 w-full" />
            <SkeletonLine class="h-5 w-5/6" />
          </div>
        </SkeletonCard>
        <SkeletonCard>
          <div class="space-y-4">
            <SkeletonLine class="h-10 w-1/3" />
            <SkeletonLine class="h-5 w-full" />
            <SkeletonLine class="h-5 w-2/3" />
          </div>
        </SkeletonCard>
      </div>
      <div class="mb-14 border-b border-slate-200 pb-10">
        <h1 class="text-5xl font-semibold tracking-tight text-slate-950 sm:text-6xl lg:text-7xl">Tentang</h1>
        <p class="mt-5 max-w-3xl text-lg leading-8 text-slate-600 sm:text-xl">Mengenal platform ini, orang di baliknya, dan data yang menggerakkannya.</p>
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
              <h2 class="text-3xl font-medium tracking-tight text-slate-950">{{ content.missionTitle || 'The Mission' }}</h2>
            </div>
            <div class="mt-6 max-w-3xl space-y-5 text-lg leading-9 text-slate-700">
              <p v-for="paragraph in (content.missionBody || '').split('\n').filter(Boolean)" :key="paragraph">{{ paragraph }}</p>
            </div>
          </div>
        </UiCard>

        <div class="space-y-6">
          <UiCard>
            <div class="flex items-center gap-5">
              <div class="flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 text-slate-700">
                <font-awesome-icon :icon="['far', 'map']" class="h-7 w-7" />
              </div>
              <div>
                <div class="text-4xl font-medium tracking-tight text-slate-950">{{ provinceCount ?? 0 }}</div>
                <div class="mt-1 text-lg text-slate-600">Provinsi tercakup</div>
              </div>
            </div>
          </UiCard>
          <UiCard>
            <div class="flex items-center gap-5">
              <div class="flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 text-slate-700">
                <font-awesome-icon :icon="['far', 'clock']" class="h-7 w-7" />
              </div>
              <div>
                <div class="text-3xl font-medium tracking-tight text-slate-950">{{ latestSyncAt ? latestSyncAt : '—' }}</div>
                <div class="mt-1 text-lg text-slate-600">Sinkron terakhir</div>
              </div>
            </div>
          </UiCard>
        </div>
      </div>

      <div class="mt-16 grid gap-8 lg:grid-cols-2">
        <section>
          <h2 class="text-2xl font-medium tracking-tight text-slate-950 sm:text-3xl">Pembuat</h2>
          <div class="mt-4 border-t border-slate-200 pt-8">
            <UiCard>
              <div class="flex flex-col gap-6 sm:flex-row sm:items-start">
                <img v-if="content.creatorPhotoUrl" :src="content.creatorPhotoUrl" :alt="content.creatorName || 'Pembuat'" class="h-28 w-28 shrink-0 rounded-full object-cover shadow-lg">
                <div v-else class="flex h-28 w-28 shrink-0 items-center justify-center rounded-full bg-slate-950 text-white shadow-lg"><span class="text-4xl font-semibold tracking-tight">{{ (content.creatorName || 'Pantau Dev Team').split(' ').map((word) => word[0]).join('').slice(0, 2) }}</span></div>
                <div class="min-w-0">
                  <h3 class="text-3xl font-medium tracking-tight text-slate-950">{{ content.creatorName || 'Pantau Dev Team' }}</h3>
                  <p class="mt-1 text-sm uppercase tracking-[0.28em] text-slate-500">{{ content.creatorSubtitle || 'Systems Engineering' }}</p>
                  <p class="mt-5 max-w-xl text-lg leading-8 text-slate-700">{{ content.creatorDescription || 'Tim kecil yang fokus bangun alat data publik yang cepat, sederhana, dan enak dipakai. Kami percaya utility app tetap bisa terasa rapi, ringan, dan punya detail visual yang matang.' }}</p>
                </div>
              </div>
            </UiCard>
          </div>
        </section>

        <section>
          <h2 class="text-2xl font-medium tracking-tight text-slate-950 sm:text-3xl">{{ content.sourcesTitle || 'Sumber & Kredit' }}</h2>
          <div class="mt-4 border-t border-slate-200 pt-8">
            <UiCard>
              <div class="space-y-7">
                <div class="flex gap-4">
                  <div class="mt-1 text-slate-950">
                    <font-awesome-icon :icon="['far', 'hard-drive']" class="h-6 w-6" />
                  </div>
                  <div>
                    <h3 class="text-2xl font-medium tracking-tight text-slate-950">{{ content.sourceOneTitle || 'Bensin API' }}</h3>
                    <p class="mt-2 text-lg leading-8 text-slate-700">{{ content.sourceOneDescription || 'Sumber utama harga BBM yang dipakai untuk sinkronisasi data dan pembaruan tampilan.' }}</p>
                    <a v-if="hasUrl(content.sourceOneUrl)" :href="content.sourceOneUrl" target="_blank" rel="noreferrer" class="mt-2 inline-flex text-sm font-semibold text-slate-950 underline decoration-slate-300 underline-offset-4 transition hover:decoration-slate-950">{{ content.sourceOneLinkLabel || 'Buka link' }}</a>
                  </div>
                </div>
                <div class="flex gap-4">
                  <div class="mt-1 text-slate-950">
                    <font-awesome-icon :icon="['far', 'object-group']" class="h-6 w-6" />
                  </div>
                  <div>
                    <h3 class="text-2xl font-medium tracking-tight text-slate-950">{{ content.sourceTwoTitle || 'Tumpukan Open Source' }}</h3>
                    <p class="mt-2 text-lg leading-8 text-slate-700">{{ content.sourceTwoDescription || 'Dibangun dengan Laravel, Tailwind CSS, dan komponen frontend ringan supaya pengalaman tetap konsisten.' }}</p>
                    <a v-if="hasUrl(content.sourceTwoUrl)" :href="content.sourceTwoUrl" target="_blank" rel="noreferrer" class="mt-2 inline-flex text-sm font-semibold text-slate-950 underline decoration-slate-300 underline-offset-4 transition hover:decoration-slate-950">{{ content.sourceTwoLinkLabel || 'Buka tautan' }}</a>
                  </div>
                </div>
                <div class="flex gap-4">
                  <div class="mt-1 text-slate-950">
                    <font-awesome-icon :icon="['far', 'circle-question']" class="h-6 w-6" />
                  </div>
                  <div>
                    <h3 class="text-2xl font-medium tracking-tight text-slate-950">{{ content.sourceThreeTitle || 'Penafian' }}</h3>
                    <p class="mt-2 text-lg leading-8 text-slate-700">{{ content.sourceThreeDescription || 'PantauBBM adalah platform independen dan bukan situs resmi Pertamina atau pemerintah Indonesia.' }}</p>
                    <a v-if="hasUrl(content.sourceThreeUrl)" :href="content.sourceThreeUrl" target="_blank" rel="noreferrer" class="mt-2 inline-flex text-sm font-semibold text-slate-950 underline decoration-slate-300 underline-offset-4 transition hover:decoration-slate-950">{{ content.sourceThreeLinkLabel || 'Buka tautan' }}</a>
                  </div>
                </div>
              </div>
            </UiCard>
          </div>
        </section>
      </div>
    </SectionShell>
  </PublicLayout>
</template>
