import { defineStore } from "pinia";
import { ref } from "vue";
import { api } from "@/services/api";

export const useDocumentsStore = defineStore("documents", () => {
  const documents = ref([]);
  const total = ref(0);
  const loading = ref(false);
  const submitting = ref(false);
  const error = ref(null);
  let lastFetchedKey = null;

  async function fetchDocuments({ page = 1, sortBy = 'createdAt', sortDir = 'desc', templateTypes = [] } = {}) {
    const params = { page, sortBy, sortDir, templateTypes };
    const key = JSON.stringify(params);
    if (key === lastFetchedKey) return;

    loading.value = true;
    error.value = null;
    try {
      const result = await api.getDocuments(params);
      documents.value = result.data;
      total.value = result.meta.total;
      lastFetchedKey = key;
    } catch (err) {
      error.value = err.message || "Impossible de charger les documents";
    } finally {
      loading.value = false;
    }
  }

  function invalidate() {
    lastFetchedKey = null;
  }

  function openDocument(documentId) {
    const url = `${
      import.meta.env.VITE_BACKEND_URL
    }/api/documents/${documentId}/preview`;
    window.open(url, "_blank", "noopener");
  }

  async function createDocument(templateId, name, data, clientId = null) {
    submitting.value = true;
    error.value = null;
    try {
      const result = await api.createDocument(templateId, name, data, clientId);
      return result.document;
    } catch (err) {
      error.value = err.message || "Erreur lors de la création du document";
      return null;
    } finally {
      submitting.value = false;
    }
  }

  async function deleteDocument(documentId) {
    submitting.value = true;
    error.value = null;
    try {
      await api.deleteDocument(documentId);
      documents.value = documents.value.filter((doc) => doc.id !== documentId);
      total.value = Math.max(0, total.value - 1);
      return true;
    } catch (err) {
      error.value = err.message || "Erreur lors de la suppression";
      return false;
    } finally {
      submitting.value = false;
    }
  }

  async function shareDocument(documentId, expiresIn) {
    error.value = null;
    try {
      const data = await api.shareDocument(documentId, expiresIn);
      return data.share;
    } catch (err) {
      error.value = err.message || "Erreur lors du partage";
      return null;
    }
  }

  async function notifyShareRecipient(shareId) {
    error.value = null;
    try {
      await api.notifyShareRecipient(shareId);
      return true;
    } catch (err) {
      error.value = err.message || "Erreur lors de l'envoi de la notification";
      return false;
    }
  }

  async function getShares(documentId) {
    error.value = null;
    try {
      return await api.getShares(documentId);
    } catch (err) {
      error.value = err.message || "Erreur lors de la récupération des partages";
      return null;
    }
  }

  async function deleteShare(shareId) {
    error.value = null;
    try {
      await api.deleteShare(shareId);
      return true;
    } catch (err) {
      error.value = err.message || "Erreur lors de la révocation";
      return false;
    }
  }

  return {
    documents,
    total,
    loading,
    submitting,
    error,
    fetchDocuments,
    invalidate,
    openDocument,
    createDocument,
    deleteDocument,
    shareDocument,
    notifyShareRecipient,
    getShares,
    deleteShare,
  };
});
