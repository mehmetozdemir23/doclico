<template>
  <div class="max-w-2xl bg-white border border-slate-200 rounded-lg p-4 sm:p-6">
    <h2 class="text-[15px] font-semibold text-slate-900 mb-4 sm:mb-6">
      Informations personnelles
    </h2>
    <form class="space-y-4" @submit.prevent="handleUpdateProfile">
      <BaseInput
        id="first_name"
        v-model="profileForm.first_name"
        label="Prénom"
        type="text"
        required
        size="sm"
      />

      <BaseInput
        id="last_name"
        v-model="profileForm.last_name"
        label="Nom"
        type="text"
        required
        size="sm"
      />

      <BaseInput
        id="email"
        v-model="profileForm.email"
        label="Adresse email"
        type="email"
        required
        size="sm"
      />

      <BaseAlert v-if="error" :message="error" type="error" />

      <div class="flex gap-2 pt-2">
        <BaseButton
          type="submit"
          :disabled="profileStore.submitting"
          :full-width="true"
          class="sm:w-auto"
        >
          {{ profileStore.submitting ? "Enregistrement..." : "Enregistrer" }}
        </BaseButton>
        <BaseButton
          type="button"
          variant="secondary"
          :disabled="profileStore.submitting"
          :full-width="true"
          class="sm:w-auto"
          @click="cancelProfileEdit"
        >
          Annuler
        </BaseButton>
      </div>
    </form>
  </div>
</template>
<script setup>
import { computed, onMounted, ref } from "vue";
import { useProfileStore } from "../stores/profile";
import { useToastStore } from "../stores/toast";
import BaseAlert from "./BaseAlert.vue";
import BaseButton from "./BaseButton.vue";
import BaseInput from "./BaseInput.vue";

const profileStore = useProfileStore();
const toastStore = useToastStore();

const profileForm = ref({});
const error = computed(() => profileStore.error);

onMounted(() => {
  initializeProfileForm();
});

const initializeProfileForm = async () => {
  const profile = await profileStore.getProfile();
  if (profile) profileForm.value = profile;
};

const cancelProfileEdit = () => {
  initializeProfileForm();
};

const handleUpdateProfile = async () => {
  const result = await profileStore.updateProfile(
    profileForm.value.first_name,
    profileForm.value.last_name,
    profileForm.value.email,
  );
  if (result) {
    toastStore.success(result.message || "Profil mis à jour avec succès");
  } else {
    toastStore.error(profileStore.error);
  }
};
</script>
