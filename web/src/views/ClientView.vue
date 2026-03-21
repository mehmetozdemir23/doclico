<template>
  <!-- Back + header -->
  <div class="flex items-center gap-3 mb-6">
    <button
      class="inline-flex items-center justify-center h-8 w-8 text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-lg transition-colors"
      aria-label="Retour aux clients"
      @click="$router.push({ name: 'dashboard.clients' })"
    >
      <ChevronLeft class="w-4 h-4" aria-hidden="true" />
    </button>
    <div class="flex-1 min-w-0">
      <h2 class="text-[16px] font-semibold text-slate-900 truncate">{{ client?.nom ?? '…' }}</h2>
    </div>
    <button
      class="inline-flex items-center justify-center h-8 w-8 text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-lg transition-colors"
      aria-label="Modifier le client"
      @click="openEditModal"
    >
      <Pencil class="w-3.5 h-3.5" aria-hidden="true" />
    </button>
    <button
      class="inline-flex items-center justify-center h-8 w-8 text-slate-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
      aria-label="Supprimer le client"
      @click="handleDelete"
    >
      <Trash2 class="w-3.5 h-3.5" aria-hidden="true" />
    </button>
  </div>

  <BaseAlert v-if="clientError" :message="clientError" type="error" class="mb-6" />

  <div v-if="!client && !clientError" class="text-center py-20">
    <p class="text-[13px] text-slate-500">Chargement...</p>
  </div>

  <template v-else-if="client">
    <!-- Client info card -->
    <div class="border border-slate-200 rounded-lg p-4 mb-8 space-y-2">
      <div v-if="client.adresse" class="text-[13px] text-slate-600 whitespace-pre-line">{{ client.adresse }}</div>
      <div class="flex flex-col gap-0.5">
        <span v-if="client.email" class="text-[13px] text-slate-500">{{ client.email }}</span>
        <span v-if="client.telephone" class="text-[13px] text-slate-500">{{ client.telephone }}</span>
        <span v-if="client.siret" class="text-[13px] text-slate-500">SIRET : {{ client.siret }}</span>
      </div>
      <div v-if="!client.adresse && !client.email && !client.telephone && !client.siret" class="text-[13px] text-slate-400">
        Aucune information complémentaire
      </div>
    </div>

    <!-- Documents section -->
    <div class="mb-4 flex items-center justify-between">
      <h3 class="text-[13px] font-medium text-slate-900">Documents</h3>
      <span class="text-[13px] text-slate-400">{{ docsTotal }} document{{ docsTotal > 1 ? 's' : '' }}</span>
    </div>

    <div v-if="docsLoading && docs.length === 0" class="text-center py-12">
      <p class="text-[13px] text-slate-500">Chargement...</p>
    </div>

    <div v-else-if="docs.length === 0" class="text-center py-12">
      <div class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-slate-100 mb-3">
        <FileText class="w-4 h-4 text-slate-400" aria-hidden="true" />
      </div>
      <p class="text-[13px] text-slate-500">Aucun document pour ce client</p>
    </div>

    <template v-else>
      <!-- Mobile card list -->
      <div
        class="sm:hidden divide-y divide-slate-100 border border-slate-200 rounded-xl overflow-hidden transition-opacity"
        :class="{ 'opacity-50': docsLoading }"
      >
        <div
          v-for="doc in docs"
          :key="doc.id"
          class="flex items-center gap-3 px-4 py-3.5 bg-white"
        >
          <div class="flex-1 min-w-0">
            <p class="text-[13px] font-medium text-slate-900 truncate mb-1">{{ doc.name }}</p>
            <div class="flex items-center gap-1.5">
              <span
                class="inline-flex items-center h-4 px-1.5 rounded text-[10px] font-medium"
                :class="typeClass(doc.template?.type)"
              >{{ typeLabel(doc.template?.type) }}</span>
              <span class="text-[11px] text-slate-400">{{ formatLongDate(doc.generatedAt) }}</span>
            </div>
          </div>
          <button
            class="inline-flex items-center justify-center h-9 w-9 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-lg transition-colors flex-shrink-0"
            :aria-label="`Voir le document ${doc.name}`"
            @click="openDocument(doc.id)"
          >
            <Eye class="w-4 h-4" aria-hidden="true" />
          </button>
        </div>
      </div>

      <!-- Desktop table -->
      <div
        class="hidden sm:block border border-slate-200 rounded-lg overflow-hidden transition-opacity"
        :class="{ 'opacity-50': docsLoading }"
      >
        <table class="w-full">
          <thead>
            <tr class="border-b border-slate-200 bg-slate-50">
              <th class="text-left px-4 py-2.5 text-[11px] font-medium text-slate-500 uppercase tracking-wide" scope="col">Nom</th>
              <th class="text-left px-4 py-2.5 text-[11px] font-medium text-slate-500 uppercase tracking-wide" scope="col">Type</th>
              <th class="text-left px-4 py-2.5 text-[11px] font-medium text-slate-500 uppercase tracking-wide hidden md:table-cell" scope="col">Date</th>
              <th class="px-4 py-2.5 w-20" scope="col"><span class="sr-only">Actions</span></th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            <tr v-for="doc in docs" :key="doc.id" class="hover:bg-slate-50 transition-colors">
              <td class="px-4 py-3">
                <span class="text-[13px] font-medium text-slate-900">{{ doc.name }}</span>
              </td>
              <td class="px-4 py-3">
                <span
                  class="inline-flex items-center h-5 px-1.5 rounded text-[10px] font-medium"
                  :class="typeClass(doc.template?.type)"
                >{{ typeLabel(doc.template?.type) }}</span>
              </td>
              <td class="px-4 py-3 hidden md:table-cell">
                <span class="text-[13px] text-slate-400">{{ formatLongDate(doc.generatedAt) }}</span>
              </td>
              <td class="px-4 py-3">
                <div class="flex items-center justify-end">
                  <button
                    class="inline-flex items-center justify-center h-7 w-7 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-md transition-colors"
                    :aria-label="`Voir le document ${doc.name}`"
                    @click="openDocument(doc.id)"
                  >
                    <Eye class="w-3.5 h-3.5" aria-hidden="true" />
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </template>

    <div v-if="docsLastPage > 1" class="flex items-center justify-end gap-1 mt-4">
      <button
        class="inline-flex items-center justify-center h-7 w-7 rounded-md text-slate-500 hover:bg-slate-100 disabled:opacity-30 disabled:cursor-not-allowed transition-colors"
        :disabled="docsPage === 1"
        aria-label="Page précédente"
        @click="docsPage--"
      >
        <ChevronLeft class="w-3.5 h-3.5" aria-hidden="true" />
      </button>
      <span class="text-[13px] text-slate-600 px-2" aria-live="polite">{{ docsPage }} / {{ docsLastPage }}</span>
      <button
        class="inline-flex items-center justify-center h-7 w-7 rounded-md text-slate-500 hover:bg-slate-100 disabled:opacity-30 disabled:cursor-not-allowed transition-colors"
        :disabled="docsPage === docsLastPage"
        aria-label="Page suivante"
        @click="docsPage++"
      >
        <ChevronRight class="w-3.5 h-3.5" aria-hidden="true" />
      </button>
    </div>
  </template>

  <!-- Edit modal -->
  <BaseModal v-model="modalOpen" title="Modifier le client">
    <form class="p-5 space-y-4" @submit.prevent="handleSubmit">
        <div class="space-y-1.5">
          <label for="edit-client-nom" class="block text-[13px] font-medium text-slate-900">
            Nom <span class="text-red-500" aria-hidden="true">*</span>
          </label>
          <input
            id="edit-client-nom"
            v-model="form.nom"
            type="text"
            required
            placeholder="Acme Corp"
            class="w-full h-10 px-3 text-[15px] bg-white border border-slate-200 rounded-lg focus:outline-none focus:border-slate-400 transition-colors placeholder:text-slate-400"
          />
        </div>

        <div class="space-y-1.5">
          <label for="edit-client-adresse" class="block text-[13px] font-medium text-slate-900">Adresse</label>
          <textarea
            id="edit-client-adresse"
            v-model="form.adresse"
            rows="3"
            placeholder="1 avenue des Champs-Élysées&#10;75008 Paris"
            class="w-full px-3 py-2 text-[15px] bg-white border border-slate-200 rounded-lg focus:outline-none focus:border-slate-400 transition-colors placeholder:text-slate-400 resize-none"
          ></textarea>
        </div>

        <div class="space-y-1.5">
          <label for="edit-client-email" class="block text-[13px] font-medium text-slate-900">Email</label>
          <input
            id="edit-client-email"
            v-model="form.email"
            type="email"
            placeholder="contact@acme.com"
            class="w-full h-10 px-3 text-[15px] bg-white border border-slate-200 rounded-lg focus:outline-none focus:border-slate-400 transition-colors placeholder:text-slate-400"
          />
        </div>

        <div class="space-y-1.5">
          <label for="edit-client-telephone" class="block text-[13px] font-medium text-slate-900">Téléphone</label>
          <input
            id="edit-client-telephone"
            v-model="form.telephone"
            type="tel"
            placeholder="+33 1 23 45 67 89"
            class="w-full h-10 px-3 text-[15px] bg-white border border-slate-200 rounded-lg focus:outline-none focus:border-slate-400 transition-colors placeholder:text-slate-400"
          />
        </div>

        <div class="space-y-1.5">
          <label for="edit-client-siret" class="block text-[13px] font-medium text-slate-900">SIRET</label>
          <input
            id="edit-client-siret"
            v-model="form.siret"
            type="text"
            inputmode="numeric"
            maxlength="14"
            placeholder="12345678901234"
            class="w-full h-10 px-3 text-[15px] bg-white border border-slate-200 rounded-lg focus:outline-none focus:border-slate-400 transition-colors placeholder:text-slate-400"
          />
        </div>

        <BaseAlert v-if="formError" :message="formError" type="error" />

        <div class="flex gap-2 pt-2">
          <BaseButton type="submit" :disabled="clientsStore.submitting" :full-width="true">
            {{ clientsStore.submitting ? "Enregistrement..." : "Mettre à jour" }}
          </BaseButton>
          <BaseButton type="button" variant="secondary" :full-width="true" @click="closeModal">
            Annuler
          </BaseButton>
        </div>
    </form>
  </BaseModal>

  <BaseConfirm
    v-model="deleteConfirmOpen"
    title="Supprimer le client ?"
    message="Cette action est irréversible."
    confirm-label="Supprimer"
    @confirm="confirmDelete"
    @cancel="deleteConfirmOpen = false"
  />
</template>

<script setup>
import { ChevronLeft, ChevronRight, Eye, FileText, Pencil, Trash2 } from "lucide-vue-next";
import { computed, onMounted, ref, watch } from "vue";
import { useRoute, useRouter } from "vue-router";
import BaseAlert from "@/components/BaseAlert.vue";
import BaseButton from "@/components/BaseButton.vue";
import BaseConfirm from "@/components/BaseConfirm.vue";
import BaseModal from "@/components/BaseModal.vue";
import { api } from "@/services/api";
import { useClientsStore } from "@/stores/clients";
import { useDocumentsStore } from "@/stores/documents";
import { useToastStore } from "@/stores/toast";
import { formatLongDate } from "@/utils/date";
import { typeLabel, typeClass } from "@/utils/documentTypes";

const route = useRoute();
const router = useRouter();
const clientsStore = useClientsStore();
const documentsStore = useDocumentsStore();
const toastStore = useToastStore();

const clientId = computed(() => route.params.id);
const clientError = ref(null);

const client = computed(() => clientsStore.clients.find((c) => c.id === clientId.value) ?? null);

onMounted(async () => {
  await clientsStore.fetchClients();
  if (!client.value) {
    clientError.value = "Client introuvable";
  }
  loadDocs();
});

const docs = ref([]);
const docsTotal = ref(0);
const docsLoading = ref(false);
const docsPage = ref(1);
const PER_PAGE = 20;
const docsLastPage = computed(() => Math.max(1, Math.ceil(docsTotal.value / PER_PAGE)));

const loadDocs = async () => {
  docsLoading.value = true;
  try {
    const result = await api.getDocuments({ page: docsPage.value, clientId: clientId.value });
    docs.value = result.data;
    docsTotal.value = result.meta.total;
  } catch {
    // ignore
  } finally {
    docsLoading.value = false;
  }
};

watch(docsPage, loadDocs);

const openDocument = (documentId) => documentsStore.openDocument(documentId);

const modalOpen = ref(false);
const formError = ref(null);
const form = ref({ nom: "", adresse: "", email: "", telephone: "", siret: "" });

const openEditModal = () => {
  form.value = {
    nom: client.value.nom,
    adresse: client.value.adresse ?? "",
    email: client.value.email ?? "",
    telephone: client.value.telephone ?? "",
    siret: client.value.siret ?? "",
  };
  formError.value = null;
  modalOpen.value = true;
};

const closeModal = () => { modalOpen.value = false; };

watch(modalOpen, (val) => {
  if (!val) {
    formError.value = null;
    form.value = { nom: "", adresse: "", email: "", telephone: "", siret: "" };
  }
});

const handleSubmit = async () => {
  formError.value = null;
  const payload = {
    nom: form.value.nom,
    adresse: form.value.adresse || null,
    email: form.value.email || null,
    telephone: form.value.telephone || null,
    siret: form.value.siret || null,
  };
  const result = await clientsStore.updateClient(clientId.value, payload);
  if (result) {
    toastStore.success("Client mis à jour");
    closeModal();
  } else {
    formError.value = clientsStore.error;
  }
};

const deleteConfirmOpen = ref(false);

const handleDelete = () => {
  deleteConfirmOpen.value = true;
};

const confirmDelete = async () => {
  deleteConfirmOpen.value = false;
  const success = await clientsStore.deleteClient(clientId.value);
  if (success) {
    toastStore.success("Client supprimé");
    router.push({ name: "dashboard.clients" });
  } else {
    toastStore.error(clientsStore.error);
  }
};
</script>
