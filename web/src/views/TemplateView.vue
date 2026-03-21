<template>
  <DefaultLayout>
    <div class="pt-12 pb-12">
      <router-link
        :to="{ name: 'dashboard.documents' }"
        class="inline-flex items-center gap-1.5 text-[13px] text-slate-500 hover:text-slate-900 transition-colors mb-8"
      >
        <ChevronLeft class="w-3.5 h-3.5" />
        Retour
      </router-link>

      <div v-if="loading" class="text-center py-20">
        <p class="text-[13px] text-slate-500">Chargement...</p>
      </div>

      <BaseAlert v-else-if="error" :message="error" type="error" class="mb-6" />

      <div v-else-if="template">
        <div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-8">
          <div
            class="flex items-center justify-center w-10 h-10 rounded-lg bg-slate-100 border border-slate-100 flex-shrink-0"
          >
            <DynamicIcon :name="template.icon" class="w-5 h-5 text-slate-700" />
          </div>
          <div>
            <h1
              class="text-lg sm:text-xl font-semibold text-slate-900 leading-6"
            >
              {{ template.name }}
            </h1>
            <p class="text-[13px] text-slate-500 mt-0.5">
              {{ template.category }}
            </p>
          </div>
        </div>

        <div class="flex items-center gap-2 mb-5">
          <h2 class="text-[15px] font-semibold text-slate-900">Formulaire</h2>
          <div class="flex-1 h-px bg-slate-200"></div>
        </div>

        <div class="max-w-3xl">
          <form class="space-y-4" @submit.prevent>
            <!-- Sélecteur de client -->
            <div
              v-if="hasClientFields"
              class="flex items-center gap-2 pb-4 border-b border-slate-200"
            >
              <select
                v-model="selectedClientId"
                class="flex-1 h-11 px-3 text-[15px] bg-white border border-slate-200 rounded-lg focus:outline-none focus:border-slate-400 transition-colors"
              >
                <option value="">Choisir un client...</option>
                <option v-for="client in clientsStore.clients" :key="client.id" :value="client.id">
                  {{ client.nom }}
                </option>
              </select>
              <button
                type="button"
                class="h-10 px-3 text-[13px] font-medium text-slate-500 hover:text-slate-900 hover:bg-slate-100 rounded-lg transition-colors whitespace-nowrap"
                @click="showNewClientModal = true"
              >
                + Nouveau client
              </button>
            </div>

            <div
              class="space-y-1.5 pb-4 border-b border-slate-200"
            >
              <label
                for="document-name"
                class="block text-[13px] font-medium text-slate-900"
              >
                Nom du document
              </label>
              <input
                id="document-name"
                v-model="documentForm.name"
                type="text"
                maxlength="255"
                :placeholder="template.type + '_' + formatShortDate()"
                class="w-full h-11 px-3 text-[15px] bg-white border border-slate-200 rounded-lg focus:outline-none focus:border-slate-400 transition-colors placeholder:text-slate-400"
              />
            </div>

            <div v-if="template.type in SEQUENTIAL_NUMBER_FIELDS" class="flex items-center gap-2 text-[13px] text-slate-500 py-1">
              <span class="inline-flex items-center justify-center w-4 h-4 rounded-full bg-slate-200 text-slate-600 text-[10px] font-bold">#</span>
              Numéro assigné automatiquement
            </div>

            <template v-for="field in template.fields" :key="field.name">
            <div
              v-if="!(field.name in SEQUENTIAL_NUMBER_FIELDS)"
              class="space-y-1.5"
            >
              <label
                :for="field.name"
                class="block text-[13px] font-medium text-slate-900"
              >
                {{ field.label }}
                <span v-if="field.required" class="text-red-500">*</span>
              </label>

              <input
                v-if="
                  field.type === 'text' ||
                    field.type === 'email' ||
                    field.type === 'tel' ||
                    field.type === 'date'
                "
                :id="field.name"
                v-model="documentForm[field.name]"
                :type="field.type"
                :required="field.required"
                :placeholder="field.placeholder"
                :maxlength="field.maxlength"
                class="w-full h-11 px-3 text-[15px] bg-white border rounded-lg focus:outline-none transition-colors placeholder:text-slate-400"
                :class="fieldErrors[field.name] ? 'border-red-300 focus:border-red-400' : 'border-slate-200 focus:border-slate-400'"
              />

              <textarea
                v-else-if="field.type === 'textarea'"
                :id="field.name"
                v-model="documentForm[field.name]"
                :required="field.required"
                :placeholder="field.placeholder"
                :maxlength="field.maxlength"
                rows="4"
                class="w-full px-3 py-2 text-[15px] bg-white border rounded-lg focus:outline-none transition-colors placeholder:text-slate-400 resize-none"
                :class="fieldErrors[field.name] ? 'border-red-300 focus:border-red-400' : 'border-slate-200 focus:border-slate-400'"
              ></textarea>

              <select
                v-else-if="field.type === 'select'"
                :id="field.name"
                v-model="documentForm[field.name]"
                :required="field.required"
                class="w-full h-11 px-3 text-[15px] bg-white border rounded-lg focus:outline-none transition-colors"
                :class="fieldErrors[field.name] ? 'border-red-300 focus:border-red-400' : 'border-slate-200 focus:border-slate-400'"
              >
                <option value="">Sélectionnez une option</option>
                <option
                  v-for="option in field.options"
                  :key="option.value"
                  :value="option.value"
                >
                  {{ option.label }}
                </option>
              </select>

              <!-- Lignes de prestation : description + qté + prix unitaire -->
              <div v-else-if="field.type === 'line_items'" class="space-y-2">
                <div class="hidden sm:grid grid-cols-[1fr_72px_112px_96px_28px] gap-2 px-1 text-[11px] font-medium text-slate-400">
                  <span>Description</span>
                  <span class="text-center">Qté</span>
                  <span class="text-right">Prix unitaire</span>
                  <span class="text-right">Sous-total</span>
                  <span></span>
                </div>
                <div
                  v-for="(line, index) in documentForm[field.name]"
                  :key="index"
                  class="flex flex-col gap-2 sm:grid sm:grid-cols-[1fr_72px_112px_96px_28px] sm:items-center"
                >
                  <input
                    v-model="line.description"
                    type="text"
                    placeholder="Description"
                    class="h-9 px-2.5 text-[13px] bg-white border border-slate-200 rounded-lg focus:outline-none focus:border-slate-400 transition-colors w-full"
                  />
                  <div class="grid grid-cols-[56px_1fr_auto_28px] sm:contents gap-2 items-center">
                    <input
                      v-model="line.quantite"
                      type="number"
                      min="0"
                      placeholder="1"
                      class="h-9 px-2.5 text-[13px] bg-white border border-slate-200 rounded-lg focus:outline-none focus:border-slate-400 transition-colors w-full text-center"
                    />
                    <input
                      v-model="line.prix_unitaire"
                      type="number"
                      min="0"
                      step="0.01"
                      placeholder="0,00"
                      class="h-9 px-2.5 text-[13px] bg-white border border-slate-200 rounded-lg focus:outline-none focus:border-slate-400 transition-colors w-full text-right"
                    />
                    <div class="h-9 px-2.5 text-[13px] text-slate-700 font-medium flex items-center justify-end whitespace-nowrap">
                      {{ formatAmount(lineSubtotal(line)) }}
                    </div>
                    <button
                      type="button"
                      class="flex items-center justify-center w-7 h-7 rounded-md text-slate-400 hover:text-red-500 hover:bg-red-50 transition-colors"
                      :disabled="documentForm[field.name].length === 1"
                      @click="removeLine(field.name, index)"
                    >
                      <Minus class="w-3.5 h-3.5" />
                    </button>
                  </div>
                </div>
                <div class="flex items-center justify-between pt-1">
                  <button
                    type="button"
                    class="inline-flex items-center gap-1.5 text-[12px] font-medium text-slate-500 hover:text-slate-900 transition-colors"
                    @click="addLine(field.name, { description: '', quantite: 1, prix_unitaire: '' })"
                  >
                    <Plus class="w-3.5 h-3.5" />
                    Ajouter une ligne
                  </button>
                  <div class="text-[13px] font-semibold text-slate-900">
                    Total HT : {{ formatAmount(linesTotal(field.name)) }} €
                  </div>
                </div>
              </div>

              <!-- Lignes de frais : description + montant -->
              <div v-else-if="field.type === 'expense_items'" class="space-y-2">
                <div class="grid grid-cols-[1fr_90px_28px] sm:grid-cols-[1fr_120px_28px] gap-2 px-1 text-[11px] font-medium text-slate-400">
                  <span>Description</span>
                  <span class="text-right">Montant (€)</span>
                  <span></span>
                </div>
                <div
                  v-for="(line, index) in documentForm[field.name]"
                  :key="index"
                  class="grid grid-cols-[1fr_90px_28px] sm:grid-cols-[1fr_120px_28px] gap-2 items-center"
                >
                  <input
                    v-model="line.description"
                    type="text"
                    placeholder="Description du frais"
                    class="h-9 px-2.5 text-[13px] bg-white border border-slate-200 rounded-lg focus:outline-none focus:border-slate-400 transition-colors w-full"
                  />
                  <input
                    v-model="line.montant"
                    type="number"
                    min="0"
                    step="0.01"
                    placeholder="0,00"
                    class="h-9 px-2.5 text-[13px] bg-white border border-slate-200 rounded-lg focus:outline-none focus:border-slate-400 transition-colors w-full text-right"
                  />
                  <button
                    type="button"
                    class="flex items-center justify-center w-7 h-7 rounded-md text-slate-400 hover:text-red-500 hover:bg-red-50 transition-colors"
                    :disabled="documentForm[field.name].length === 1"
                    @click="removeLine(field.name, index)"
                  >
                    <Minus class="w-3.5 h-3.5" />
                  </button>
                </div>
                <div class="flex items-center justify-between pt-1">
                  <button
                    type="button"
                    class="inline-flex items-center gap-1.5 text-[12px] font-medium text-slate-500 hover:text-slate-900 transition-colors"
                    @click="addLine(field.name, { description: '', montant: '' })"
                  >
                    <Plus class="w-3.5 h-3.5" />
                    Ajouter une ligne
                  </button>
                  <div class="text-[13px] font-semibold text-slate-900">
                    Total : {{ formatAmount(expenseTotal(field.name)) }} €
                  </div>
                </div>
              </div>
              <p v-if="fieldErrors[field.name]" class="text-[12px] text-red-500">{{ fieldErrors[field.name] }}</p>
            </div>
            </template>

            <div
              v-if="template.fields.length === 0"
              class="bg-slate-50 border border-slate-200 rounded-lg p-12"
            >
              <p class="text-[13px] text-slate-500 text-center">
                Le formulaire pour ce document sera disponible prochainement.
              </p>
            </div>

            <div v-if="template.fields.length > 0" class="space-y-4 pt-6">
              <div
                class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pt-4 border-t border-slate-200"
              >
                <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                  <BaseButton
                    type="button"
                    class="w-full sm:w-auto"
                    :disabled="submitting"
                    @click="handleGenerate"
                  >
                    {{ submitting ? "Génération..." : "Générer" }}
                  </BaseButton>
                  <a
                    v-if="downloadUrl"
                    :href="downloadUrl"
                    target="_blank"
                    class="inline-flex items-center justify-center gap-2 h-9 px-4 text-[13px] font-medium text-slate-700 hover:bg-slate-100 rounded-lg transition-colors w-full sm:w-auto"
                  >
                    Télécharger
                  </a>
                </div>
                <button
                  type="button"
                  class="text-[13px] text-slate-500 hover:text-slate-900 transition-colors text-center sm:text-right"
                  @click="initializeTemplateForm"
                >
                  Réinitialiser
                </button>
              </div>

            </div>
          </form>
        </div>
      </div>

      <div v-else class="text-center py-20">
        <div
          class="inline-flex items-center justify-center w-12 h-12 rounded-lg bg-slate-100 mb-4"
        >
          <FileText class="w-6 h-6 text-slate-400" />
        </div>
        <h3 class="text-[15px] font-semibold text-slate-900 mb-1">
          Modèle introuvable
        </h3>
        <p class="text-[13px] text-slate-500 mb-6">
          Ce modèle n'existe pas ou a été supprimé
        </p>
        <BaseButton :to="{ name: 'dashboard.documents' }">Retour aux documents</BaseButton>
      </div>
    </div>

    <!-- Modale nouveau client -->
    <BaseModal v-model="showNewClientModal" title="Nouveau client">
      <div class="p-5 space-y-3">
        <div class="space-y-1.5">
          <label class="block text-[13px] font-medium text-slate-900">Nom <span class="text-red-500">*</span></label>
          <input
            v-model="newClientForm.nom"
            type="text"
            placeholder="Acme Corp"
            class="w-full h-10 px-3 text-[15px] bg-white border border-slate-200 rounded-lg focus:outline-none focus:border-slate-400 transition-colors"
          />
        </div>
        <div class="space-y-1.5">
          <label class="block text-[13px] font-medium text-slate-900">Adresse</label>
          <textarea
            v-model="newClientForm.adresse"
            rows="3"
            placeholder="1 avenue des Champs-Élysées&#10;75008 Paris"
            class="w-full px-3 py-2 text-[15px] bg-white border border-slate-200 rounded-lg focus:outline-none focus:border-slate-400 transition-colors resize-none"
          ></textarea>
        </div>
        <div class="grid grid-cols-2 gap-3">
          <div class="space-y-1.5">
            <label class="block text-[13px] font-medium text-slate-900">Email</label>
            <input
              v-model="newClientForm.email"
              type="email"
              placeholder="contact@acme.fr"
              class="w-full h-10 px-3 text-[15px] bg-white border border-slate-200 rounded-lg focus:outline-none focus:border-slate-400 transition-colors"
            />
          </div>
          <div class="space-y-1.5">
            <label class="block text-[13px] font-medium text-slate-900">Téléphone</label>
            <input
              v-model="newClientForm.telephone"
              type="tel"
              placeholder="+33 6 00 00 00 00"
              class="w-full h-10 px-3 text-[15px] bg-white border border-slate-200 rounded-lg focus:outline-none focus:border-slate-400 transition-colors"
            />
          </div>
        </div>
        <div class="flex items-center justify-end gap-2 pt-2">
          <BaseButton variant="secondary" @click="showNewClientModal = false">Annuler</BaseButton>
          <BaseButton :disabled="newClientSubmitting || !newClientForm.nom.trim()" @click="submitNewClient">
            {{ newClientSubmitting ? 'Enregistrement...' : 'Créer le client' }}
          </BaseButton>
        </div>
      </div>
    </BaseModal>
  </DefaultLayout>
</template>

<script setup>
import { ChevronLeft, FileText, Minus, Plus } from "lucide-vue-next";
import { computed, onMounted, ref } from "vue";
import { useRoute } from "vue-router";
import BaseAlert from "@/components/BaseAlert.vue";
import BaseButton from "@/components/BaseButton.vue";
import BaseModal from "@/components/BaseModal.vue";
import DynamicIcon from "@/components/DynamicIcon.vue";
import DefaultLayout from "@/layouts/DefaultLayout.vue";
import { api } from "@/services/api";
import { useClientsStore } from "@/stores/clients";
import { useDocumentsStore } from "@/stores/documents";
import { useTemplatesStore } from "@/stores/templates";
import { useToastStore } from "@/stores/toast";
import { formatShortDate } from "@/utils/date";

const SEQUENTIAL_NUMBER_FIELDS = {
  facture: "numero_facture",
  devis: "numero_devis",
  avoir: "numero_avoir",
};

const route = useRoute();
const templatesStore = useTemplatesStore();
const clientsStore = useClientsStore();
const documentsStore = useDocumentsStore();
const toastStore = useToastStore();
const downloadUrl = ref(null);

const template = ref(null);
const loading = computed(() => templatesStore.loading);
const error = computed(() => templatesStore.error);

const CLIENT_SUPPORTING_TYPES = ['facture', 'devis', 'avoir', 'prestation', 'reclamation', 'note_frais'];

const hasClientFields = computed(() =>
  CLIENT_SUPPORTING_TYPES.includes(template.value?.type)
);

const selectedClientId = ref('');
const showNewClientModal = ref(false);
const newClientForm = ref({ nom: '', adresse: '', email: '', telephone: '' });
const newClientSubmitting = ref(false);

const submitNewClient = async () => {
  if (!newClientForm.value.nom.trim()) return;
  newClientSubmitting.value = true;
  try {
    const client = await clientsStore.createClient(newClientForm.value);
    if (!client) return;
    selectedClientId.value = client.id;
    showNewClientModal.value = false;
    newClientForm.value = { nom: '', adresse: '', email: '', telephone: '' };
  } finally {
    newClientSubmitting.value = false;
  }
};

onMounted(async () => {
  template.value = await templatesStore.fetchTemplate(route.query.type);
  if (template.value) initializeTemplateForm();
  if (hasClientFields.value) clientsStore.fetchClients();
});

const documentForm = ref({});
const fieldErrors = ref({});
const submitting = computed(() => documentsStore.submitting);

const initializeTemplateForm = () => {
  selectedClientId.value = '';
  fieldErrors.value = {};
  if (template.value?.fields) {
    template.value.fields.forEach((field) => {
      if (field.type === 'line_items') {
        documentForm.value[field.name] = [{ description: '', quantite: 1, prix_unitaire: '' }];
      } else if (field.type === 'expense_items') {
        documentForm.value[field.name] = [{ description: '', montant: '' }];
      } else {
        documentForm.value[field.name] = '';
      }
    });
    documentForm.value['name'] = template.value.type + "_" + formatShortDate();
  }
};

const addLine = (fieldName, emptyRow) => {
  documentForm.value[fieldName].push({ ...emptyRow });
};

const removeLine = (fieldName, index) => {
  documentForm.value[fieldName].splice(index, 1);
};

const lineSubtotal = (line) => {
  return (parseFloat(line.quantite) || 0) * (parseFloat(line.prix_unitaire) || 0);
};

const linesTotal = (fieldName) => {
  return (documentForm.value[fieldName] || []).reduce((sum, l) => sum + lineSubtotal(l), 0);
};

const expenseTotal = (fieldName) => {
  return (documentForm.value[fieldName] || []).reduce((sum, l) => sum + (parseFloat(l.montant) || 0), 0);
};

const formatAmount = (value) => {
  return new Intl.NumberFormat('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(value);
};

const validateForm = () => {
  const errors = {};
  for (const field of template.value?.fields ?? []) {
    if (!field.required) continue;
    const value = documentForm.value[field.name];
    if (field.type === 'line_items' || field.type === 'expense_items') {
      if (!(value ?? []).some((l) => l.description?.trim())) {
        errors[field.name] = 'Ajoutez au moins une ligne avec une description';
      }
    } else if (!value || !String(value).trim()) {
      errors[field.name] = 'Ce champ est obligatoire';
    }
  }
  fieldErrors.value = errors;
  return Object.keys(errors).length === 0;
};

const handleGenerate = async () => {
  if (!validateForm()) return;

  const { name, ...formData } = documentForm.value;
  const document = await documentsStore.createDocument(template.value.id, name || null, formData, selectedClientId.value || null);
  if (!document) {
    toastStore.error(documentsStore.error);
    return;
  }

  downloadUrl.value = api.getDocumentDownloadUrl(document.id);
  toastStore.success("Document prêt !");
  documentsStore.invalidate();
  initializeTemplateForm();
};
</script>
