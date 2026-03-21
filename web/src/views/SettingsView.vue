<template>
  <div class="bg-white border border-slate-200 rounded-lg p-4 sm:p-6">
    <h2 class="text-[15px] font-semibold text-slate-900 mb-4 sm:mb-6">
      Changer le mot de passe
    </h2>
    <form class="space-y-4" @submit.prevent="handleUpdatePassword">
      <BaseInput
        id="current_password"
        v-model="passwordForm.currentPassword"
        label="Mot de passe actuel"
        type="password"
        required
        size="sm"
      />

      <BaseInput
        id="new_password"
        v-model="passwordForm.newPassword"
        label="Nouveau mot de passe"
        type="password"
        required
        minlength="8"
        hint="Au moins 8 caractères"
        size="sm"
      />

      <BaseInput
        id="confirm_password"
        v-model="passwordForm.newPasswordConfirmation"
        label="Confirmer le nouveau mot de passe"
        type="password"
        required
        size="sm"
      />

      <div class="flex gap-2 pt-2">
        <BaseButton
          type="submit"
          :disabled="settingsStore.submitting"
          :full-width="true"
          class="sm:w-auto"
        >
          {{ settingsStore.submitting ? "Changement..." : "Changer" }}
        </BaseButton>
        <BaseButton
          type="button"
          variant="secondary"
          :disabled="settingsStore.submitting"
          :full-width="true"
          class="sm:w-auto"
          @click="cancelPasswordEdit"
        >
          Annuler
        </BaseButton>
      </div>
    </form>
  </div>

  <div class="mt-6 bg-white border border-slate-200 rounded-lg p-4 sm:p-6">
    <h2 class="text-[15px] font-semibold text-slate-900 mb-1">
      Mes données personnelles
    </h2>
    <p class="text-[13px] text-slate-500 mb-4">
      Téléchargez une copie de toutes vos données (profil, clients, documents) au format JSON.
    </p>
    <BaseButton variant="secondary" :disabled="exporting" @click="handleExport">
      {{ exporting ? "Export en cours..." : "Télécharger mes données" }}
    </BaseButton>
  </div>

  <div class="mt-6 bg-white border border-red-200 rounded-lg p-4 sm:p-6">
    <h2 class="text-[15px] font-semibold text-slate-900 mb-1">
      Supprimer mon compte
    </h2>
    <p class="text-[13px] text-slate-500 mb-4">
      Cette action est irréversible. Toutes vos données (documents, clients, fichiers générés) seront définitivement supprimées.
    </p>
    <BaseButton variant="danger" :disabled="authStore.submitting" @click="handleDeleteAccount">
      {{ authStore.submitting ? "Suppression..." : "Supprimer mon compte" }}
    </BaseButton>
  </div>

  <BaseConfirm
    v-model="deleteAccountConfirmOpen"
    title="Supprimer votre compte ?"
    message="Cette action est irréversible. Toutes vos données seront définitivement supprimées."
    confirm-label="Supprimer"
    @confirm="confirmDeleteAccount"
    @cancel="deleteAccountConfirmOpen = false"
  />
</template>

<script setup>
import { onMounted, ref } from "vue";
import { useRouter } from "vue-router";
import BaseButton from "@/components/BaseButton.vue";
import BaseConfirm from "@/components/BaseConfirm.vue";
import BaseInput from "@/components/BaseInput.vue";
import { useAuthStore } from "@/stores/auth";
import { useSettingsStore } from "@/stores/settings";
import { useToastStore } from "@/stores/toast";
import { api } from "@/services/api";

const router = useRouter();
const authStore = useAuthStore();
const settingsStore = useSettingsStore();
const toastStore = useToastStore();

const passwordForm = ref({ currentPassword: "", newPassword: "", newPasswordConfirmation: "" });
const exporting = ref(false);

onMounted(() => {
  initializePasswordForm();
});

const initializePasswordForm = () => {
  passwordForm.value.currentPassword = "";
  passwordForm.value.newPassword = "";
  passwordForm.value.newPasswordConfirmation = "";
};

const cancelPasswordEdit = () => {
  initializePasswordForm();
};

const handleExport = async () => {
  exporting.value = true;
  try {
    await api.exportUserData();
  } catch {
    toastStore.error("Erreur lors de l'export des données");
  } finally {
    exporting.value = false;
  }
};

const deleteAccountConfirmOpen = ref(false);

const handleDeleteAccount = () => {
  deleteAccountConfirmOpen.value = true;
};

const confirmDeleteAccount = async () => {
  deleteAccountConfirmOpen.value = false;
  const success = await authStore.deleteAccount();
  if (success) {
    router.push({ name: "home" });
  } else {
    toastStore.error(authStore.error);
  }
};

const handleUpdatePassword = async () => {
  const result = await settingsStore.updatePassword(
    passwordForm.value.currentPassword,
    passwordForm.value.newPassword,
    passwordForm.value.newPasswordConfirmation,
  );

  if (result) {
    toastStore.success(result.message || "Mot de passe modifié avec succès");
    initializePasswordForm();
  } else {
    toastStore.error(settingsStore.error);
  }
};
</script>
