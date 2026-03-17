import { defineStore } from "pinia";
import { computed, ref } from "vue";
import { api } from "@/services/api";

export const useTemplatesStore = defineStore("templates", () => {
  const templates = ref([]);
  const loading = ref(false);
  const error = ref(null);
  let hasFetched = false;

  const categories = computed(() => {
    return [...new Set(templates.value.map((t) => t.category))];
  });

  async function fetchTemplates() {
    if (hasFetched) return;

    loading.value = true;
    error.value = null;
    try {
      templates.value = await api.getTemplates();
      hasFetched = true;
    } catch (err) {
      error.value = err.message || "Erreur lors du chargement des templates";
    } finally {
      loading.value = false;
    }
  }

  async function fetchTemplate(type) {
    const cached = templates.value.find((t) => t.type === type) || null;
    if (cached) {
      return cached;
    }

    loading.value = true;
    error.value = null;
    try {
      const template = await api.getTemplate(type);
      if (!templates.value.find((t) => t.id === template.id)) {
        templates.value.push(template);
      }
      return template;
    } catch (err) {
      error.value = err.message || "Erreur lors du chargement du template";
      return null;
    } finally {
      loading.value = false;
    }
  }

  function invalidate() {
    hasFetched = false;
  }

  return {
    templates,
    loading,
    error,
    categories,
    fetchTemplates,
    fetchTemplate,
    invalidate,
  };
});
