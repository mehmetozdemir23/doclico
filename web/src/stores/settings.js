import { defineStore } from "pinia";
import { ref } from "vue";
import { apiFetch } from "@/services/apiClient";

export const useSettingsStore = defineStore("settings", () => {
  const submitting = ref(false);
  const error = ref(null);

  async function updatePassword(
    currentPassword,
    newPassword,
    newPasswordConfirmation,
  ) {
    submitting.value = true;
    error.value = null;
    try {
      const response = await apiFetch("/api/profile/password", {
        method: "PUT",
        body: {
          current_password: currentPassword,
          new_password: newPassword,
          new_password_confirmation: newPasswordConfirmation,
        },
      });
      if (!response.ok) {
        const data = await response.json().catch(() => ({}));
        throw new Error(data.message || "Erreur lors du changement de mot de passe");
      }
      return await response.json();
    } catch (err) {
      error.value = err.message || "Erreur lors du changement de mot de passe";
      return null;
    } finally {
      submitting.value = false;
    }
  }

  return {
    submitting,
    error,
    updatePassword,
  };
});
