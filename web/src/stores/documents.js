import { defineStore } from "pinia";
import { ref } from "vue";
import { api } from "../services/api";

export const useDocumentsStore = defineStore("documents", () => {
  const documents = ref([]);
  const loading = ref(false);
  const submitting = ref(false);
  const error = ref(null);
  let hasFetched = false;

  async function fetchDocuments() {
    if (hasFetched) return;

    loading.value = true;
    error.value = null;
    try {
      documents.value = await api.getDocuments();
      hasFetched = true;
    } catch (err) {
      error.value = err.message || "Impossible de charger les documents";
    } finally {
      loading.value = false;
    }
  }

  function openDocument(documentId) {
    const url = `${
      import.meta.env.VITE_BACKEND_URL
    }/api/documents/${documentId}/preview`;
    window.open(url, "_blank");
  }

  async function deleteDocument(documentId) {
    submitting.value = true;
    error.value = null;
    try {
      await api.deleteDocument(documentId);
      documents.value = documents.value.filter((doc) => doc.id !== documentId);
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

  async function getShares(documentId) {
    try {
      return await api.getShares(documentId);
    } catch (err) {
      error.value = err.message || "Erreur lors de la récupération des partages";
      return [];
    }
  }

  async function deleteShare(documentId, shareId) {
    error.value = null;
    try {
      await api.deleteShare(documentId, shareId);
      return true;
    } catch (err) {
      error.value = err.message || "Erreur lors de la révocation";
      return false;
    }
  }

  return {
    documents,
    loading,
    submitting,
    error,
    fetchDocuments,
    openDocument,
    deleteDocument,
    shareDocument,
    getShares,
    deleteShare,
  };
});
