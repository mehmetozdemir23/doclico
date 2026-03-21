import { defineStore } from "pinia";
import { ref } from "vue";
import { api } from "@/services/api";

export const useClientsStore = defineStore("clients", () => {
  const clients = ref([]);
  const loading = ref(false);
  const submitting = ref(false);
  const error = ref(null);
  let hasFetched = false;

  async function fetchClients() {
    if (hasFetched) return;

    loading.value = true;
    error.value = null;
    try {
      clients.value = await api.getClients();
      hasFetched = true;
    } catch (err) {
      error.value = err.message || "Impossible de charger les clients";
    } finally {
      loading.value = false;
    }
  }

  function invalidate() {
    hasFetched = false;
  }

  async function createClient(data) {
    submitting.value = true;
    error.value = null;
    try {
      const client = await api.createClient(data);
      clients.value.push(client);
      clients.value.sort((a, b) => a.nom.localeCompare(b.nom));
      return client;
    } catch (err) {
      error.value = err.message || "Erreur lors de la création du client";
      return null;
    } finally {
      submitting.value = false;
    }
  }

  async function updateClient(id, data) {
    submitting.value = true;
    error.value = null;
    try {
      const updated = await api.updateClient(id, data);
      const index = clients.value.findIndex((c) => c.id === id);
      if (index !== -1) clients.value[index] = updated;
      clients.value.sort((a, b) => a.nom.localeCompare(b.nom));
      return updated;
    } catch (err) {
      error.value = err.message || "Erreur lors de la mise à jour du client";
      return null;
    } finally {
      submitting.value = false;
    }
  }

  async function deleteClient(id) {
    submitting.value = true;
    error.value = null;
    try {
      await api.deleteClient(id);
      clients.value = clients.value.filter((c) => c.id !== id);
      return true;
    } catch (err) {
      error.value = err.message || "Erreur lors de la suppression du client";
      return false;
    } finally {
      submitting.value = false;
    }
  }

  return {
    clients,
    loading,
    submitting,
    error,
    fetchClients,
    invalidate,
    createClient,
    updateClient,
    deleteClient,
  };
});
