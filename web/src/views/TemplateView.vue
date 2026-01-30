<template>
  <DefaultLayout>
    <div class="pt-12 pb-12">
      <router-link
        to="/"
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
            <div
              v-if="isAuthenticated"
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
                class="w-full h-10 px-3 text-[15px] bg-white border border-slate-200 rounded-lg focus:outline-none focus:border-slate-400 transition-colors placeholder:text-slate-400"
              />
            </div>

            <div
              v-for="field in template.fields"
              :key="field.name"
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
                class="w-full h-10 px-3 text-[15px] bg-white border border-slate-200 rounded-lg focus:outline-none focus:border-slate-400 transition-colors placeholder:text-slate-400"
              />

              <textarea
                v-else-if="field.type === 'textarea'"
                :id="field.name"
                v-model="documentForm[field.name]"
                :required="field.required"
                :placeholder="field.placeholder"
                :maxlength="field.maxlength"
                rows="4"
                class="w-full px-3 py-2 text-[15px] bg-white border border-slate-200 rounded-lg focus:outline-none focus:border-slate-400 transition-colors placeholder:text-slate-400 resize-none"
              ></textarea>

              <select
                v-else-if="field.type === 'select'"
                :id="field.name"
                v-model="documentForm[field.name]"
                :required="field.required"
                class="w-full h-10 px-3 text-[15px] bg-white border border-slate-200 rounded-lg focus:outline-none focus:border-slate-400 transition-colors"
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
            </div>

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
                <div class="flex items-center gap-3">
                  <BaseButton
                    type="button"
                    :disabled="submitting"
                    @click="handleGenerate"
                  >
                    {{ submitting ? "Génération..." : "Générer" }}
                  </BaseButton>
                  <a
                    v-if="downloadUrl"
                    :href="downloadUrl"
                    target="_blank"
                    class="inline-flex items-center justify-center gap-2 h-9 px-4 text-[13px] font-medium text-slate-700 hover:bg-slate-100 rounded-lg transition-colors"
                  >
                    Télécharger
                  </a>
                </div>
                <button
                  type="button"
                  class="text-[13px] text-slate-500 hover:text-slate-900 transition-colors"
                  @click="initializeTemplateForm"
                >
                  Réinitialiser
                </button>
              </div>

              <div
                v-if="!isAuthenticated"
                class="pt-4 border-t border-slate-200"
              >
                <p class="text-[13px] text-slate-500">
                  <router-link :to="{ name: 'login' }" class="underline">
                    Connectez-vous
                  </router-link>
                  pour sauvegarder vos documents.
                </p>
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
        <BaseButton to="/">Retour à l'accueil</BaseButton>
      </div>
    </div>
  </DefaultLayout>
</template>

<script setup>
import { ChevronLeft, FileText } from "lucide-vue-next";
import { computed, onMounted, ref } from "vue";
import { useRoute } from "vue-router";
import BaseAlert from "@/components/BaseAlert.vue";
import BaseButton from "@/components/BaseButton.vue";
import DynamicIcon from "@/components/DynamicIcon.vue";
import DefaultLayout from "@/layouts/DefaultLayout.vue";
import { useFileGeneration } from "@/composables/useFileGeneration";
import { useAuthStore } from "@/stores/auth";
import { useTemplatesStore } from "@/stores/templates";
import { useToastStore } from "@/stores/toast";
import { formatShortDate } from "@/utils/date";

const route = useRoute();
const authStore = useAuthStore();
const templatesStore = useTemplatesStore();
const toastStore = useToastStore();
const { isGenerating, error: generationError, downloadUrl, generateFile } = useFileGeneration();

const template = ref(null);
const loading = computed(() => templatesStore.loading);
const error = computed(() => templatesStore.error);

onMounted(async () => {
  template.value = await templatesStore.fetchTemplate(route.query.type);
  if (template.value) initializeTemplateForm();
});

const documentForm = ref({});
const isAuthenticated = computed(() => authStore.isAuthenticated);
const submitting = computed(() => isGenerating.value);

const initializeTemplateForm = () => {
  if (template.value?.fields) {
    template.value.fields.forEach((field) => {
      documentForm.value[field.name] = "";
    });
    documentForm.value['name'] = template.value.type + "_" + formatShortDate();
  }
};

const handleGenerate = async () => {
  const onComplete = () => {
    toastStore.success("Document prêt !");
  };

  const id = await generateFile(template.value.id, documentForm.value, "pdf", onComplete);

  if (id) {
    toastStore.info("Génération en cours...");
    if (isAuthenticated.value) {
      initializeTemplateForm();
    }
  } else {
    toastStore.error(generationError.value);
  }
};
</script>
