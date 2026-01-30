<template>
  <DefaultLayout>
    <div class="pt-16 pb-8">
      <div class="max-w-2xl">
        <div class="relative">
          <Search
            class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"
          />
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Rechercher..."
            class="w-full h-10 pl-10 pr-4 text-[15px] bg-white border border-slate-200 rounded-lg focus:outline-none focus:border-slate-400 transition-colors placeholder:text-slate-400"
          />
        </div>

        <div class="flex gap-2 mt-4 overflow-x-auto py-2">
          <button
            :class="[
              'h-7 px-3 text-[13px] font-medium rounded-md transition-colors',
              selectedCategory === null
                ? 'bg-slate-900 text-white'
                : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100',
            ]"
            @click="selectedCategory = null"
          >
            Tous
          </button>
          <button
            v-for="category in categories"
            :key="category"
            :class="[
              'h-7 px-3 text-[13px] font-medium rounded-md transition-colors',
              selectedCategory === category
                ? 'bg-slate-900 text-white'
                : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100',
            ]"
            @click="selectedCategory = category"
          >
            {{ category }}
          </button>
        </div>
      </div>
    </div>

    <div class="pb-12">
      <div v-if="!searchQuery" class="flex items-center gap-2 mb-5">
        <h2 class="text-[15px] font-semibold text-slate-900">
          Tous les modèles
        </h2>
        <div class="flex-1 h-px bg-slate-200"></div>
      </div>
      <div v-if="searchQuery" class="mb-4">
        <p class="text-[13px] text-slate-500">
          {{ filteredTemplates.length }} résultat{{
            filteredTemplates.length > 1 ? "s" : ""
          }}
        </p>
      </div>

      <div v-if="loading" class="text-center py-20">
        <p class="text-[13px] text-slate-500">Chargement...</p>
      </div>
      <div
        v-else-if="filteredTemplates.length > 0"
        class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3"
      >
        <router-link
          v-for="template in filteredTemplates"
          :key="template.id"
          :to="{ name: 'template', query: { type: template.type } }"
          class="group p-4 bg-white border border-slate-200 rounded-lg hover:border-slate-300 hover:shadow-sm transition-all text-left block"
        >
          <div class="flex items-start gap-3">
            <div
              class="flex items-center justify-center w-9 h-9 rounded-md bg-slate-100 border border-slate-100 group-hover:border-slate-200 transition-colors flex-shrink-0"
            >
              <DynamicIcon
                :name="template.icon"
                class="w-4 h-4 text-slate-700"
              />
            </div>
            <div class="flex-1 min-w-0 pt-0.5">
              <h3
                class="text-[14px] font-medium text-slate-900 leading-5 line-clamp-2"
              >
                {{ template.name }}
              </h3>
              <p class="text-[12px] text-slate-500 leading-4 mt-0.5">
                {{ template.category }}
              </p>
            </div>
          </div>
        </router-link>
      </div>
    </div>

    <div
      v-if="!loading && filteredTemplates.length === 0"
      class="text-center py-20"
    >
      <div
        class="inline-flex items-center justify-center w-12 h-12 rounded-lg bg-slate-100 mb-4"
      >
        <DynamicIcon name="FileText" class="w-6 h-6 text-slate-400" />
      </div>
      <h3 class="text-[15px] font-semibold text-slate-900 mb-1">
        Aucun résultat
      </h3>
      <p class="text-[13px] text-slate-500 mb-6">
        Aucun modèle ne correspond à votre recherche
      </p>
      <BaseButton
        @click="
          searchQuery = '';
          selectedCategory = null;
        "
      >
        Réinitialiser
      </BaseButton>
    </div>
  </DefaultLayout>
</template>

<script setup>
import { Search } from "lucide-vue-next";
import { computed, onMounted, ref } from "vue";
import BaseButton from "@/components/BaseButton.vue";
import DynamicIcon from "@/components/DynamicIcon.vue";
import DefaultLayout from "@/layouts/DefaultLayout.vue";
import { useTemplatesStore } from "@/stores/templates";

const templatesStore = useTemplatesStore();

const templates = computed(() => templatesStore.templates);
const loading = computed(() => templatesStore.loading);
const categories = computed(() => templatesStore.categories);

onMounted(() => {
  templatesStore.fetchTemplates();
});

const searchQuery = ref("");
const selectedCategory = ref(null);

const filteredTemplates = computed(() => {
  let filtered = templates.value;

  if (selectedCategory.value) {
    filtered = filtered.filter((t) => t.category === selectedCategory.value);
  }

  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    filtered = filtered.filter(
      (template) =>
        template.name.toLowerCase().includes(query) ||
        template.category.toLowerCase().includes(query),
    );
  }

  return filtered;
});
</script>
