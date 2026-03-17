import { defineStore } from "pinia";
import { ref } from "vue";
import { apiFetch } from "@/services/apiClient";

export const useProfileStore = defineStore("profile", () => {
  const loading = ref(false);
  const submitting = ref(false);
  const error = ref(null);
  const cachedProfile = ref(null);

  async function getProfile() {
    if (cachedProfile.value) return cachedProfile.value;
    loading.value = true;
    error.value = null;
    try {
      const response = await apiFetch("/api/profile");
      if (!response.ok) {
        const data = await response.json().catch(() => ({}));
        throw new Error(data.message || "Impossible de charger le profil");
      }
      cachedProfile.value = await response.json();
      return cachedProfile.value;
    } catch (err) {
      error.value = err.message || "Impossible de charger le profil";
      return null;
    } finally {
      loading.value = false;
    }
  }

  async function updateProfile(fields) {
    submitting.value = true;
    error.value = null;
    try {
      const response = await apiFetch("/api/profile", {
        method: "PATCH",
        body: fields,
      });
      if (!response.ok) {
        const data = await response.json().catch(() => ({}));
        throw new Error(data.message || "Erreur lors de la mise à jour du profil");
      }
      const data = await response.json();
      cachedProfile.value = data.user ?? null;
      return data;
    } catch (err) {
      error.value = err.message || "Erreur lors de la mise à jour du profil";
      return null;
    } finally {
      submitting.value = false;
    }
  }

  async function uploadLogo(file) {
    submitting.value = true;
    error.value = null;
    try {
      const formData = new FormData();
      formData.append("logo", file);
      const response = await apiFetch("/api/profile/logo", {
        method: "POST",
        body: formData,
      });
      if (!response.ok) {
        const data = await response.json().catch(() => ({}));
        throw new Error(data.message || "Erreur lors de l'upload du logo");
      }
      cachedProfile.value = null;
      return (await response.json()).url;
    } catch (err) {
      error.value = err.message;
      return null;
    } finally {
      submitting.value = false;
    }
  }

  async function deleteLogo() {
    submitting.value = true;
    error.value = null;
    try {
      const response = await apiFetch("/api/profile/logo", { method: "DELETE" });
      if (!response.ok) {
        const data = await response.json().catch(() => ({}));
        throw new Error(data.message || "Erreur lors de la suppression du logo");
      }
      cachedProfile.value = null;
      return true;
    } catch (err) {
      error.value = err.message;
      return false;
    } finally {
      submitting.value = false;
    }
  }

  return {
    loading,
    submitting,
    error,
    cachedProfile,
    getProfile,
    updateProfile,
    uploadLogo,
    deleteLogo,
  };
});
