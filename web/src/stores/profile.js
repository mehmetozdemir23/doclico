import { defineStore } from "pinia";
import { ref } from "vue";
import { apiFetch } from "../services/apiClient";

export const useProfileStore = defineStore("profile", () => {
  const loading = ref(false);
  const submitting = ref(false);
  const error = ref(null);

  async function getProfile() {
    loading.value = true;
    error.value = null;
    try {
      const response = await apiFetch("/api/profile");
      if (!response.ok) {
        const data = await response.json().catch(() => ({}));
        throw new Error(data.message || "Impossible de charger le profil");
      }
      return await response.json();
    } catch (err) {
      error.value = err.message || "Impossible de charger le profil";
      return null;
    } finally {
      loading.value = false;
    }
  }

  async function updateProfile(first_name, last_name, email) {
    submitting.value = true;
    error.value = null;
    try {
      const response = await apiFetch("/api/profile", {
        method: "PUT",
        body: { first_name, last_name, email },
      });
      if (!response.ok) {
        const data = await response.json().catch(() => ({}));
        throw new Error(data.message || "Erreur lors de la mise à jour du profil");
      }
      return await response.json();
    } catch (err) {
      error.value = err.message || "Erreur lors de la mise à jour du profil";
      return null;
    } finally {
      submitting.value = false;
    }
  }

  return {
    loading,
    submitting,
    error,
    getProfile,
    updateProfile,
  };
});
