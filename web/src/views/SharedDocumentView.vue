<template>
  <DefaultLayout>
    <div class="min-h-[calc(100vh-16rem)] flex items-center justify-center py-12">
      <div class="w-full max-w-md">
        <!-- Loading -->
        <div v-if="loading" class="text-center">
          <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-slate-900 mx-auto mb-4"></div>
          <p class="text-[13px] text-slate-500">Chargement du document…</p>
        </div>

        <!-- Expired -->
        <div v-else-if="expired" class="text-center">
          <h1 class="text-2xl font-semibold text-slate-900 mb-2">Lien expiré</h1>
          <p class="text-[13px] text-slate-500">Ce lien de partage n'est plus valide. Demandez un nouveau lien à votre contact.</p>
        </div>

        <!-- Not found -->
        <div v-else-if="notFound" class="text-center">
          <h1 class="text-2xl font-semibold text-slate-900 mb-2">Lien introuvable</h1>
          <p class="text-[13px] text-slate-500">Ce lien de partage n'existe pas ou a été révoqué.</p>
        </div>

        <!-- Document -->
        <div v-else-if="metadata">
          <!-- Emitter identity -->
          <div class="text-center mb-8">
            <div v-if="metadata.emitter_logo" class="mb-3 flex justify-center">
              <img
                :src="metadata.emitter_logo"
                alt="Logo"
                class="h-12 max-w-[160px] object-contain"
                @error="metadata.emitter_logo = null"
              />
            </div>
            <p v-if="metadata.emitter_company" class="text-[13px] font-medium text-slate-700 mb-0.5">
              {{ metadata.emitter_company }}
            </p>
            <p v-if="metadata.emitter" class="text-[13px] text-slate-500">
              <span v-if="!metadata.emitter_company">Partagé par </span>{{ metadata.emitter }}
            </p>
          </div>

          <!-- Document info -->
          <div class="text-center mb-6">
            <span class="inline-block text-[11px] font-medium text-slate-500 bg-slate-100 px-2.5 py-1 rounded-md mb-3">
              {{ metadata.template_name }}
            </span>
            <h1 class="text-2xl font-semibold text-slate-900">{{ metadata.document_name }}</h1>
          </div>

          <!-- Expiry -->
          <div class="border border-slate-200 rounded-lg p-4 mb-6">
            <div class="flex items-center justify-between text-[13px]">
              <span class="text-slate-500">Expire le</span>
              <span v-if="metadata.expires_at" class="font-medium text-slate-900">{{ formatDate(metadata.expires_at) }}</span>
              <span v-else class="font-medium text-slate-900">Jamais</span>
            </div>
          </div>

          <a
            :href="downloadUrl"
            target="_blank"
            rel="noopener noreferrer"
            class="inline-flex items-center justify-center gap-2 w-full h-9 px-4 text-[13px] font-medium bg-slate-900 text-white rounded-lg hover:bg-slate-800 transition-colors"
          >
            <Download class="w-3.5 h-3.5" />
            Télécharger le PDF
          </a>
        </div>
      </div>
    </div>
  </DefaultLayout>
</template>

<script setup>
import { Download } from "lucide-vue-next";
import { computed, onMounted, ref } from "vue";
import { useRoute } from "vue-router";
import DefaultLayout from "@/layouts/DefaultLayout.vue";
import { api } from "@/services/api";

const route = useRoute();
const token = route.params.token;

const loading = ref(true);
const expired = ref(false);
const notFound = ref(false);
const metadata = ref(null);
const downloadUrl = computed(() => `${import.meta.env.VITE_BACKEND_URL}/api/share/${token}`);

function formatDate(iso) {
  return new Date(iso).toLocaleDateString("fr-FR", {
    day: "numeric",
    month: "long",
    year: "numeric",
  });
}

onMounted(async () => {
  try {
    metadata.value = await api.getShareInfo(token);
  } catch (err) {
    if (err.status === 410) expired.value = true;
    else notFound.value = true;
  } finally {
    loading.value = false;
  }
});
</script>
