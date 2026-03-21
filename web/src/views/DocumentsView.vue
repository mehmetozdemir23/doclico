<template>
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-3">
    <div class="flex items-center gap-1.5 overflow-x-auto pb-0.5 sm:flex-wrap sm:overflow-visible sm:pb-0">
      <button
        class="h-8 px-3.5 text-[13px] font-medium rounded-full border transition-colors flex-shrink-0"
        :class="filterTypes.length === 0 ? 'bg-slate-900 text-white border-slate-900' : 'bg-white text-slate-500 border-slate-200 hover:border-slate-300'"
        @click="filterTypes = []"
      >
        Tous
      </button>
      <button
        v-for="t in availableTypes"
        :key="t.type"
        class="h-8 px-3.5 text-[13px] font-medium rounded-full border transition-colors flex-shrink-0"
        :class="filterTypes.includes(t.type) ? 'bg-slate-900 text-white border-slate-900' : 'bg-white text-slate-500 border-slate-200 hover:border-slate-300'"
        @click="toggleFilterType(t.type)"
      >
        {{ t.name }}
      </button>
    </div>
    <div class="hidden sm:flex items-center gap-3 flex-shrink-0">
      <span class="text-[13px] text-slate-400">{{ total }} document{{ total > 1 ? 's' : '' }}</span>
      <button
        class="h-8 px-3.5 text-[13px] font-medium bg-slate-900 text-white rounded-lg hover:bg-slate-800 transition-colors inline-flex items-center gap-1.5"
        @click="openNewDoc"
      >
        <Plus class="w-3.5 h-3.5" />
        Nouveau document
      </button>
    </div>
    <span class="text-[13px] text-slate-400 sm:hidden">{{ total }} document{{ total > 1 ? 's' : '' }}</span>
  </div>

  <!-- FAB mobile -->
  <button
    class="fixed bottom-24 right-4 z-40 sm:hidden h-12 w-12 bg-slate-900 text-white rounded-full shadow-lg hover:bg-slate-800 active:scale-95 transition-all inline-flex items-center justify-center"
    aria-label="Nouveau document"
    @click="openNewDoc"
  >
    <FilePlus class="w-5 h-5" aria-hidden="true" />
  </button>

  <BaseAlert v-if="error" :message="error" type="error" class="mb-6" />

  <div v-if="loading && documents.length === 0" class="text-center py-20">
    <p class="text-[13px] text-slate-500">Chargement...</p>
  </div>

  <div v-else-if="!loading && documents.length === 0" class="text-center py-20">
    <div
      class="inline-flex items-center justify-center w-12 h-12 rounded-lg bg-slate-100 mb-4"
    >
      <FileText class="w-5 h-5 text-slate-400" />
    </div>
    <p class="text-[14px] font-medium text-slate-900 mb-1">Aucun document</p>
    <p class="text-[13px] text-slate-500 mb-5">Créez votre premier document en choisissant un modèle.</p>
    <button
      class="h-8 px-4 text-[13px] font-medium bg-slate-900 text-white rounded-lg hover:bg-slate-800 transition-colors inline-flex items-center gap-1.5"
      @click="openNewDoc"
    >
      <Plus class="w-3.5 h-3.5" />
      Créer un document
    </button>
  </div>

  <template v-else>
    <!-- Mobile card list -->
    <div
      class="sm:hidden divide-y divide-slate-100 border border-slate-200 rounded-xl overflow-hidden transition-opacity"
      :class="{ 'opacity-50': loading }"
    >
      <div
        v-for="document in documents"
        :key="document.id"
        class="flex items-center gap-3 px-4 py-4 bg-white cursor-pointer active:bg-slate-50 transition-colors"
        @click="documentsStore.openDocument(document.id)"
      >
        <div class="flex-1 min-w-0">
          <p class="text-[15px] font-medium text-slate-900 truncate mb-1">{{ document.name }}</p>
          <div class="flex items-center gap-1.5 flex-wrap">
            <span
              class="inline-flex items-center h-5 px-2 rounded text-[11px] font-medium"
              :class="typeClass(document.template?.type)"
            >{{ typeLabel(document.template?.type) }}</span>
            <span v-if="document.client" class="text-[12px] text-slate-500">{{ document.client.nom }}</span>
            <span
              v-if="shareStatus(document)"
              class="inline-flex items-center h-5 px-2 rounded text-[11px] font-medium"
              :class="shareStatusClass[shareStatus(document)]"
            >{{ shareStatusLabel[shareStatus(document)] }}</span>
            <span class="text-[12px] text-slate-400">{{ formatLongDate(document.generatedAt) }}</span>
          </div>
        </div>
        <div class="flex items-center gap-0.5 flex-shrink-0">
          <button
            class="inline-flex items-center justify-center h-11 w-11 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-xl transition-colors"
            :aria-label="`Partager ${document.name}`"
            @click.stop="openShareModal(document)"
          >
            <Share2 class="w-5 h-5" aria-hidden="true" />
          </button>
          <button
            v-if="isDeletable(document)"
            class="inline-flex items-center justify-center h-11 w-11 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-colors"
            :aria-label="`Supprimer ${document.name}`"
            @click.stop="handleDelete(document.id)"
          >
            <Trash2 class="w-5 h-5" aria-hidden="true" />
          </button>
          <span
            v-else
            class="inline-flex items-center justify-center h-11 w-11 text-slate-300 cursor-default"
            role="img"
            aria-label="Conservation légale obligatoire"
          >
            <Lock class="w-5 h-5" aria-hidden="true" />
          </span>
        </div>
      </div>
    </div>

    <!-- Desktop table -->
    <div
      class="hidden sm:block border border-slate-200 rounded-lg overflow-hidden transition-opacity"
      :class="{ 'opacity-50': loading }"
    >
      <table class="w-full">
        <thead>
          <tr class="border-b border-slate-200 bg-slate-50">
            <th class="text-left px-4 py-2.5 w-auto">
              <button
                class="inline-flex items-center gap-1 text-[11px] font-medium text-slate-500 uppercase tracking-wide hover:text-slate-700 transition-colors"
                @click="toggleSort('name')"
              >
                Nom
                <ChevronsUpDown v-if="sortBy !== 'name'" class="w-3 h-3 text-slate-300" />
                <ChevronUp v-else-if="sortDir === 'asc'" class="w-3 h-3" />
                <ChevronDown v-else class="w-3 h-3" />
              </button>
            </th>
            <th class="text-left px-4 py-2.5 text-[11px] font-medium text-slate-500 uppercase tracking-wide">Client</th>
            <th class="text-left px-4 py-2.5 hidden md:table-cell">
              <button
                class="inline-flex items-center gap-1 text-[11px] font-medium text-slate-500 uppercase tracking-wide hover:text-slate-700 transition-colors"
                @click="toggleSort('createdAt')"
              >
                Date
                <ChevronsUpDown v-if="sortBy !== 'createdAt'" class="w-3 h-3 text-slate-300" />
                <ChevronUp v-else-if="sortDir === 'asc'" class="w-3 h-3" />
                <ChevronDown v-else class="w-3 h-3" />
              </button>
            </th>
            <th class="px-4 py-2.5 w-28"></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          <tr
            v-for="document in documents"
            :key="document.id"
            class="group hover:bg-slate-50 transition-colors"
          >
            <td class="px-4 py-3">
              <div class="flex items-center gap-2">
                <span class="text-[13px] font-medium text-slate-900">{{ document.name }}</span>
                <span
                  class="inline-flex items-center h-5 px-1.5 rounded text-[10px] font-medium"
                  :class="typeClass(document.template?.type)"
                >{{ typeLabel(document.template?.type) }}</span>
                <span
                  v-if="shareStatus(document)"
                  class="inline-flex items-center h-5 px-1.5 rounded text-[10px] font-medium"
                  :class="shareStatusClass[shareStatus(document)]"
                >{{ shareStatusLabel[shareStatus(document)] }}</span>
              </div>
            </td>
            <td class="px-4 py-3">
              <router-link
                v-if="document.client"
                :to="{ name: 'dashboard.clients.show', params: { id: document.client.id } }"
                class="text-[13px] text-slate-700 hover:text-slate-900 hover:underline transition-colors"
              >
                {{ document.client.nom }}
              </router-link>
              <span v-else class="text-[13px] text-slate-300">—</span>
            </td>
            <td class="px-4 py-3 hidden md:table-cell">
              <span class="text-[13px] text-slate-400">{{ formatLongDate(document.generatedAt) }}</span>
            </td>
            <td class="px-4 py-3">
              <div class="flex items-center justify-end gap-1">
                <button
                  class="inline-flex items-center justify-center h-7 w-7 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-md transition-colors"
                  :aria-label="`Partager ${document.name}`"
                  @click="openShareModal(document)"
                >
                  <Share2 class="w-3.5 h-3.5" aria-hidden="true" />
                </button>
                <button
                  class="inline-flex items-center justify-center h-7 w-7 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-md transition-colors"
                  :aria-label="`Voir ${document.name}`"
                  @click="documentsStore.openDocument(document.id)"
                >
                  <Eye class="w-3.5 h-3.5" aria-hidden="true" />
                </button>
                <button
                  v-if="isDeletable(document)"
                  class="inline-flex items-center justify-center h-7 w-7 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-md transition-colors"
                  :aria-label="`Supprimer ${document.name}`"
                  @click="handleDelete(document.id)"
                >
                  <Trash2 class="w-3.5 h-3.5" aria-hidden="true" />
                </button>
                <span
                  v-else
                  class="inline-flex items-center justify-center h-7 w-7 text-slate-300 cursor-default"
                  role="img"
                  aria-label="Conservation légale obligatoire"
                >
                  <Lock class="w-3.5 h-3.5" aria-hidden="true" />
                </span>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>

  <div v-if="lastPage > 1" class="flex items-center justify-between mt-4">
    <span class="text-[13px] text-slate-500">
      {{ total }} document{{ total > 1 ? "s" : "" }}
    </span>
    <div class="flex items-center gap-1">
      <button
        class="inline-flex items-center justify-center h-7 w-7 rounded-md text-slate-500 hover:bg-slate-100 disabled:opacity-30 disabled:cursor-not-allowed transition-colors"
        :disabled="page === 1"
        aria-label="Page précédente"
        @click="page--"
      >
        <ChevronLeft class="w-3.5 h-3.5" aria-hidden="true" />
      </button>
      <span class="text-[13px] text-slate-600 px-2" aria-live="polite">{{ page }} / {{ lastPage }}</span>
      <button
        class="inline-flex items-center justify-center h-7 w-7 rounded-md text-slate-500 hover:bg-slate-100 disabled:opacity-30 disabled:cursor-not-allowed transition-colors"
        :disabled="page === lastPage"
        aria-label="Page suivante"
        @click="page++"
      >
        <ChevronRight class="w-3.5 h-3.5" aria-hidden="true" />
      </button>
    </div>
  </div>

  <!-- FAB spacer on mobile -->
  <div class="h-16 sm:hidden" aria-hidden="true"></div>

  <!-- ── Modale nouveau document ──────────────────────────────────── -->
  <BaseModal v-model="newDocModalOpen" title="Nouveau document" size="lg">
    <div v-if="templatesLoading" class="py-12 text-center">
      <p class="text-[13px] text-slate-400">Chargement...</p>
    </div>
    <div v-else class="p-4 space-y-1.5">
      <router-link
        v-for="template in templates"
        :key="template.id"
        :to="{ name: 'template', query: { type: template.type } }"
        class="group flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-50 transition-colors"
        @click="newDocModalOpen = false"
      >
        <div class="flex items-center justify-center w-8 h-8 rounded-md bg-slate-100 group-hover:bg-slate-200 transition-colors flex-shrink-0">
          <DynamicIcon :name="template.icon" class="w-4 h-4 text-slate-600" />
        </div>
        <div class="flex-1 min-w-0">
          <div class="text-[13px] font-medium text-slate-900">{{ template.name }}</div>
          <div class="text-[11px] text-slate-400">{{ template.category }}</div>
        </div>
        <ChevronRight class="w-4 h-4 text-slate-300 group-hover:text-slate-400 transition-colors flex-shrink-0" />
      </router-link>
    </div>
  </BaseModal>

  <BaseModal v-model="shareModalOpen" title="Partager le document">
    <div class="p-6">
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
              :value="currentShare.shareUrl"
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
            <span class="text-slate-600">Vues</span>
            <span class="font-medium text-slate-900">{{ currentShare.viewsCount }}</span>
          </div>
          <div
            v-if="currentShare.firstViewedAt"
            class="flex items-center justify-between text-[13px] gap-4"
          >
            <span class="text-slate-600">Première ouverture</span>
            <span class="font-medium text-slate-900 text-right">{{
              formatLongDate(currentShare.firstViewedAt)
            }}</span>
          </div>
          <div class="flex items-center justify-between text-[13px] gap-4">
            <span class="text-slate-600">Téléchargements</span>
            <span class="font-medium text-slate-900">{{ currentShare.downloadsCount }}</span>
          </div>
          <div
            v-if="currentShare.lastDownloadedAt"
            class="flex items-center justify-between text-[13px] gap-4"
          >
            <span class="text-slate-600">Dernier téléchargement</span>
            <span class="font-medium text-slate-900 text-right">{{
              formatLongDate(currentShare.lastDownloadedAt)
            }}</span>
          </div>
          <div
            v-if="currentShare.expiresAt"
            class="flex items-center justify-between text-[13px] gap-4"
          >
            <span class="text-slate-600">Expire le</span>
            <span class="font-medium text-slate-900 text-right">{{
              formatLongDate(currentShare.expiresAt)
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

        <div
          v-if="selectedDocument?.client?.email"
          class="pt-2 border-t border-slate-100"
        >
          <BaseButton
            :disabled="notifyLoading"
            :full-width="true"
            variant="secondary"
            @click="handleNotify"
          >
            {{ notifyLoading ? "Envoi..." : `Envoyer par email à ${selectedDocument.client.nom}` }}
          </BaseButton>
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
  </BaseModal>

  <BaseConfirm
    v-model="deleteConfirmOpen"
    title="Supprimer le document ?"
    confirm-label="Supprimer"
    @confirm="confirmDelete"
    @cancel="deleteConfirmOpen = false"
  />

  <BaseConfirm
    v-model="revokeConfirmOpen"
    title="Révoquer le lien ?"
    message="Le lien partagé ne sera plus accessible."
    confirm-label="Révoquer"
    @confirm="confirmRevokeShare"
    @cancel="revokeConfirmOpen = false"
  />
</template>
<script setup>
import { ChevronDown, ChevronLeft, ChevronRight, ChevronUp, ChevronsUpDown, Eye, FilePlus, FileText, Lock, Plus, Share2, Trash2 } from "lucide-vue-next";
import { computed, onMounted, onUnmounted, ref, watch } from "vue";
import BaseAlert from "@/components/BaseAlert.vue";
import BaseButton from "@/components/BaseButton.vue";
import BaseConfirm from "@/components/BaseConfirm.vue";
import BaseModal from "@/components/BaseModal.vue";
import DynamicIcon from "@/components/DynamicIcon.vue";
import { useDocumentsStore } from "@/stores/documents";
import { useTemplatesStore } from "@/stores/templates";
import { useToastStore } from "@/stores/toast";
import { formatLongDate } from "@/utils/date";
import { typeLabel, typeClass, NON_DELETABLE_TYPES } from "@/utils/documentTypes";

const documentsStore = useDocumentsStore();
const templatesStore = useTemplatesStore();
const toastStore = useToastStore();

const templates = computed(() => templatesStore.templates);
const templatesLoading = computed(() => templatesStore.loading);

const newDocModalOpen = ref(false);

const openNewDoc = () => {
  templatesStore.fetchTemplates();
  newDocModalOpen.value = true;
};

const documents = computed(() => documentsStore.documents);
const total = computed(() => documentsStore.total);
const loading = computed(() => documentsStore.loading);
const error = computed(() => documentsStore.error);

const PER_PAGE = 20;
const sortBy = ref('createdAt');
const sortDir = ref('desc');
const page = ref(1);
const filterTypes = ref([]);
const lastPage = computed(() => Math.max(1, Math.ceil(total.value / PER_PAGE)));

const availableTypes = computed(() => {
  const seen = new Set();
  return templatesStore.templates
    .filter(t => { if (seen.has(t.type)) return false; seen.add(t.type); return true; })
    .map(t => ({ type: t.type, name: t.name }));
});

const load = () => documentsStore.fetchDocuments({
  page: page.value,
  sortBy: sortBy.value,
  sortDir: sortDir.value,
  templateTypes: filterTypes.value,
});

const toggleFilterType = (type) => {
  filterTypes.value = filterTypes.value.includes(type)
    ? filterTypes.value.filter(t => t !== type)
    : [...filterTypes.value, type];
};

onMounted(() => { templatesStore.fetchTemplates(); load(); });
watch([sortBy, sortDir, filterTypes], () => { page.value = 1; load(); });
watch(page, load);

const toggleSort = (field) => {
  if (sortBy.value === field) {
    sortDir.value = sortDir.value === 'asc' ? 'desc' : 'asc';
  } else {
    sortBy.value = field;
    sortDir.value = 'desc';
  }
  page.value = 1;
};


const isDeletable = (document) => !NON_DELETABLE_TYPES.includes(document.template?.type);

const shareStatus = (document) => {
  const s = document.share;
  if (!s || s.isExpired) return null;
  if (s.downloadsCount > 0) return 'downloaded';
  if (s.viewsCount > 0) return 'viewed';
  return 'shared';
};

const shareStatusLabel = { shared: 'Partagé', viewed: 'Vu', downloaded: 'Téléchargé' };
const shareStatusClass = {
  shared: 'bg-blue-50 text-blue-600',
  viewed: 'bg-amber-50 text-amber-600',
  downloaded: 'bg-green-50 text-green-700',
};

const deleteConfirmOpen = ref(false);
const deleteTargetId = ref(null);

const handleDelete = (documentId) => {
  deleteTargetId.value = documentId;
  deleteConfirmOpen.value = true;
};

const confirmDelete = async () => {
  deleteConfirmOpen.value = false;
  const id = deleteTargetId.value;
  deleteTargetId.value = null;
  const success = await documentsStore.deleteDocument(id);
  if (success) {
    toastStore.success("Document supprimé avec succès");
    documentsStore.invalidate();
    load();
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

  const shares = await documentsStore.getShares(document.id);
  if (shares === null) {
    toastStore.error(documentsStore.error);
  } else if (shares.length > 0) {
    currentShare.value = shares[0];
  }
  fetchingShare.value = false;
};

watch(shareModalOpen, (val) => {
  if (!val) {
    selectedDocument.value = null;
    currentShare.value = null;
    selectedExpiration.value = "7d";
    copied.value = false;
    notifyLoading.value = false;
  }
});

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

const notifyLoading = ref(false);

const handleNotify = async () => {
  if (!selectedDocument.value || !currentShare.value) return;
  notifyLoading.value = true;
  const success = await documentsStore.notifyShareRecipient(currentShare.value.id);
  notifyLoading.value = false;
  if (success) {
    toastStore.success(`Email envoyé à ${selectedDocument.value.client.nom}`);
  } else {
    toastStore.error(documentsStore.error);
  }
  shareModalOpen.value = false;
};

const revokeConfirmOpen = ref(false);

const handleRevokeShare = () => {
  if (!currentShare.value || !selectedDocument.value) return;
  revokeConfirmOpen.value = true;
};

const confirmRevokeShare = async () => {
  revokeConfirmOpen.value = false;
  const success = await documentsStore.deleteShare(currentShare.value.id);

  if (success) {
    currentShare.value = null;
    toastStore.success("Lien de partage révoqué");
  } else {
    toastStore.error(documentsStore.error);
  }
};

const copied = ref(false);

const COPY_FEEDBACK_MS = 2000;
let copyTimer = null;

const copyToClipboard = async () => {
  if (!currentShare.value) return;

  try {
    await navigator.clipboard.writeText(currentShare.value.shareUrl);
    toastStore.success("Lien copié dans le presse-papier");
    copied.value = true;
    clearTimeout(copyTimer);
    copyTimer = setTimeout(() => {
      copied.value = false;
    }, COPY_FEEDBACK_MS);
  } catch {
    toastStore.error("Erreur lors de la copie");
  }
};

onUnmounted(() => clearTimeout(copyTimer));
</script>
