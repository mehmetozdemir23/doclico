<template>
  <div class="flex items-center justify-between mb-6">
    <div class="text-[13px] text-slate-600">
      {{ documents.length }} document{{ documents.length > 1 ? "s" : "" }}
    </div>
    <BaseButton to="/"> Nouveau document </BaseButton>
  </div>

  <BaseAlert v-if="error" :message="error" type="error" class="mb-6" />

  <div v-if="loading" class="text-center py-20">
    <p class="text-[13px] text-slate-500">Chargement...</p>
  </div>

  <div v-else-if="documents.length === 0" class="text-center py-20">
    <div
      class="inline-flex items-center justify-center w-12 h-12 rounded-lg bg-slate-100 mb-4"
    >
      <FileText class="w-5 h-5 text-slate-400" />
    </div>
    <p class="text-[15px] text-slate-600 mb-4">Aucun document sauvegardé</p>
    <BaseButton to="/"> Créer un document </BaseButton>
  </div>

  <div v-else>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
      <div
        v-for="document in documents"
        :key="document.id"
        class="border border-slate-200 rounded-lg p-4 hover:border-slate-300 transition-colors flex flex-col"
      >
        <div class="mb-3">
          <h3 class="text-[15px] font-medium text-slate-900 mb-1 line-clamp-2">
            {{ document.name }}
          </h3>
          <div class="flex items-center gap-2">
            <FileText class="w-3.5 h-3.5 text-slate-400 flex-shrink-0" />
            <p class="text-[13px] text-slate-500 truncate">
              {{ document.template.name }}
            </p>
          </div>
        </div>
        <p class="text-[12px] text-slate-400 mb-4">
          Créé le {{ formatLongDate(document.createdAt) }}
        </p>

        <div class="flex items-center gap-2 mt-auto">
          <button
            class="inline-flex items-center justify-center h-8 w-8 text-slate-700 hover:text-slate-900 hover:bg-slate-100 rounded-lg transition-colors"
            title="Partager"
            @click="openShareModal(document)"
          >
            <Share2 class="w-3.5 h-3.5" />
          </button>
          <button
            class="inline-flex items-center justify-center h-8 w-8 text-slate-700 hover:text-slate-900 hover:bg-slate-100 rounded-lg transition-colors"
            title="Voir le document"
            @click="handleOpen(document.id)"
          >
            <Eye class="w-3.5 h-3.5" />
          </button>
          <button
            class="inline-flex items-center justify-center w-8 h-8 text-slate-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors ml-auto"
            title="Supprimer"
            @click="handleDelete(document.id)"
          >
            <Trash2 class="w-3.5 h-3.5" />
          </button>
        </div>
      </div>
    </div>
  </div>

  <div
    v-if="shareModalOpen"
    class="fixed inset-0 bg-black/50 flex items-end sm:items-center justify-center z-50 sm:p-4"
    @click.self="closeShareModal"
  >
    <div
      class="bg-white rounded-t-lg sm:rounded-lg max-w-md w-full p-6 max-h-[90vh] overflow-y-auto"
    >
      <div class="flex items-center justify-between mb-6">
        <h3 class="text-[15px] font-semibold text-slate-900">
          Partager le document
        </h3>
        <button
          class="text-slate-400 hover:text-slate-600"
          @click="closeShareModal"
        >
          <X class="w-5 h-5" />
        </button>
      </div>

      <div v-if="fetchingShare" class="text-center py-8">
        <p class="text-[13px] text-slate-500">Chargement...</p>
      </div>

      <div v-else-if="!currentShare" class="space-y-4">
        <div>
          <label class="text-[13px] font-medium text-slate-700 block mb-2">
            Expiration du lien
          </label>
          <div class="space-y-2">
            <label
              v-for="option in expirationOptions"
              :key="option.value"
              class="flex items-center gap-2 p-3 border border-slate-200 rounded-lg cursor-pointer hover:border-slate-300 transition-colors"
              :class="{
                'border-slate-900 bg-slate-50':
                  selectedExpiration === option.value,
              }"
            >
              <input
                v-model="selectedExpiration"
                type="radio"
                :value="option.value"
                class="text-slate-900"
              />
              <span class="text-[13px] text-slate-700">{{ option.label }}</span>
            </label>
          </div>
        </div>

        <BaseButton
          :disabled="sharingLoading"
          :full-width="true"
          @click="handleShare"
        >
          {{ sharingLoading ? "Génération..." : "Générer le lien" }}
        </BaseButton>
      </div>

      <div v-else class="space-y-4">
        <div>
          <label class="text-[13px] font-medium text-slate-700 block mb-2">
            Lien de partage
          </label>
          <div class="flex flex-col sm:flex-row gap-2">
            <input
              type="text"
              :value="currentShare.share_url"
              readonly
              class="flex-1 h-10 px-3 text-[13px] border border-slate-200 rounded-lg bg-slate-50 truncate"
            />
            <BaseButton @click="copyToClipboard">
              {{ copied ? "Copié !" : "Copier" }}
            </BaseButton>
          </div>
        </div>

        <div class="p-4 bg-slate-50 rounded-lg space-y-2">
          <div class="flex items-center justify-between text-[13px] gap-4">
            <span class="text-slate-600">Téléchargements</span>
            <span class="font-medium text-slate-900">{{
              currentShare.downloads_count
            }}</span>
          </div>
          <div
            v-if="currentShare.last_downloaded_at"
            class="flex items-center justify-between text-[13px] gap-4"
          >
            <span class="text-slate-600">Dernier téléchargement</span>
            <span class="font-medium text-slate-900 text-right">{{
              formatLongDate(currentShare.last_downloaded_at)
            }}</span>
          </div>
          <div
            v-if="currentShare.expires_at"
            class="flex items-center justify-between text-[13px] gap-4"
          >
            <span class="text-slate-600">Expire le</span>
            <span class="font-medium text-slate-900 text-right">{{
              formatLongDate(currentShare.expires_at)
            }}</span>
          </div>
          <div
            v-else
            class="flex items-center justify-between text-[13px] gap-4"
          >
            <span class="text-slate-600">Expiration</span>
            <span class="font-medium text-slate-900">Jamais</span>
          </div>
        </div>

        <BaseButton
          variant="danger"
          :full-width="true"
          @click="handleRevokeShare"
        >
          Révoquer le lien
        </BaseButton>
      </div>
    </div>
  </div>
</template>
<script setup>
import { Eye, FileText, Share2, Trash2, X } from "lucide-vue-next";
import { computed, onMounted, ref } from "vue";
import BaseAlert from "@/components/BaseAlert.vue";
import BaseButton from "@/components/BaseButton.vue";
import { useDocumentsStore } from "@/stores/documents";
import { useToastStore } from "@/stores/toast";
import { formatLongDate } from "@/utils/date";

const documentsStore = useDocumentsStore();
const toastStore = useToastStore();

const documents = computed(() => documentsStore.documents);
const loading = computed(() => documentsStore.loading);
const error = computed(() => documentsStore.error);

onMounted(() => {
  documentsStore.fetchDocuments();
});

const handleOpen = (documentId) => {
  documentsStore.openDocument(documentId);
};

const handleDelete = async (documentId) => {
  if (!confirm("Êtes-vous sûr de vouloir supprimer ce document ?")) return;

  const success = await documentsStore.deleteDocument(documentId);
  if (success) {
    toastStore.success("Document supprimé avec succès");
  } else {
    toastStore.error(documentsStore.error);
  }
};

const expirationOptions = [
  { value: "24h", label: "24 heures" },
  { value: "7d", label: "7 jours (recommandé)" },
  { value: "30d", label: "30 jours" },
  { value: "never", label: "Jamais" },
];
const selectedDocument = ref(null);
const shareModalOpen = ref(false);
const fetchingShare = ref(false);

const openShareModal = async (document) => {
  selectedDocument.value = document;
  shareModalOpen.value = true;
  currentShare.value = null;
  selectedExpiration.value = "7d";
  fetchingShare.value = true;

  try {
    const shares = await documentsStore.getShares(document.id);
    if (shares.length > 0) {
      currentShare.value = shares[0];
    }
  } catch {
    toastStore.error(documentsStore.error);
  } finally {
    fetchingShare.value = false;
  }
};

const closeShareModal = () => {
  shareModalOpen.value = false;
  selectedDocument.value = null;
  currentShare.value = null;
  copied.value = false;
};

const currentShare = ref(null);
const selectedExpiration = ref("7d");
const sharingLoading = ref(false);

const handleShare = async () => {
  if (!selectedDocument.value) return;

  sharingLoading.value = true;
  const share = await documentsStore.shareDocument(
    selectedDocument.value.id,
    selectedExpiration.value,
  );
  sharingLoading.value = false;

  if (share) {
    currentShare.value = share;
  } else {
    toastStore.error(documentsStore.error);
  }
};

const handleRevokeShare = async () => {
  if (!currentShare.value || !selectedDocument.value) return;
  if (!confirm("Êtes-vous sûr de vouloir révoquer ce lien ?")) return;

  const success = await documentsStore.deleteShare(
    selectedDocument.value.id,
    currentShare.value.id,
  );

  if (success) {
    currentShare.value = null;
    toastStore.success("Lien de partage révoqué");
  } else {
    toastStore.error(documentsStore.error);
  }
};

const copied = ref(false);

const copyToClipboard = async () => {
  if (!currentShare.value) return;

  try {
    await navigator.clipboard.writeText(currentShare.value.share_url);
    toastStore.success("Lien copié dans le presse-papier");
    copied.value = true;
    setTimeout(() => {
      copied.value = false;
    }, 2000);
  } catch {
    toastStore.error("Erreur lors de la copie");
  }
};
</script>
