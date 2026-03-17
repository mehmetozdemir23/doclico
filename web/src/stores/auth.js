import { defineStore } from "pinia";
import { computed, ref } from "vue";
import { apiFetch } from "@/services/apiClient";
import { api } from "@/services/api";
import { useClientsStore } from "@/stores/clients";
import { useDocumentsStore } from "@/stores/documents";
import { useTemplatesStore } from "@/stores/templates";

export const useAuthStore = defineStore("auth", () => {
  const user = ref(null);
  const error = ref(null);
  const submitting = ref(false);
  const initialized = ref(false);

  const isAuthenticated = computed(() => !!user.value);

  async function login(email, password) {
    submitting.value = true;
    error.value = null;

    try {
      const response = await apiFetch("/login", {
        method: "POST",
        body: { email, password },
      });
      const data = await response.json();

      if (!response.ok) {
        throw new Error(data.message || "Identifiants incorrects");
      }

      user.value = data.user;
      return true;
    } catch (err) {
      error.value =
        err.message || "Une erreur est survenue lors de la connexion";
      return false;
    } finally {
      submitting.value = false;
    }
  }

  async function register(formData) {
    submitting.value = true;
    error.value = null;

    try {
      const response = await apiFetch("/register", {
        method: "POST",
        body: formData,
      });
      const data = await response.json();

      if (!response.ok) {
        throw new Error(data.message || "Erreur lors de l'inscription");
      }

      user.value = data.user;
      return true;
    } catch (err) {
      error.value =
        err.message || "Une erreur est survenue lors de l'inscription";
      return false;
    } finally {
      submitting.value = false;
    }
  }

  async function logout() {
    await apiFetch("/logout", { method: "POST" });
    user.value = null;
    useDocumentsStore().invalidate();
    useClientsStore().invalidate();
    useTemplatesStore().invalidate();
  }

  async function fetchUser() {
    try {
      const response = await apiFetch("/api/user");
      const data = await response.json();

      if (!response.ok) {
        user.value = null;
        return;
      }

      user.value = data;
    } catch {
      user.value = null;
    } finally {
      initialized.value = true;
    }
  }

  async function deleteAccount() {
    submitting.value = true;
    error.value = null;
    try {
      await api.deleteAccount();
      user.value = null;
      useDocumentsStore().invalidate();
      useClientsStore().invalidate();
      useTemplatesStore().invalidate();
      return true;
    } catch (err) {
      error.value = err.message || "Erreur lors de la suppression du compte";
      return false;
    } finally {
      submitting.value = false;
    }
  }

  function clearError() {
    error.value = null;
  }

  function setError(message) {
    error.value = message;
  }

  return {
    user,
    error,
    submitting,
    initialized,
    isAuthenticated,
    login,
    register,
    logout,
    fetchUser,
    deleteAccount,
    clearError,
    setError,
  };
});
