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
</template>
<script setup>
import { onMounted, ref } from "vue";
import BaseButton from "@/components/BaseButton.vue";
import BaseInput from "@/components/BaseInput.vue";
import { useSettingsStore } from "@/stores/settings";
import { useToastStore } from "@/stores/toast";

const settingsStore = useSettingsStore();
const toastStore = useToastStore();

const passwordForm = ref({});

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
