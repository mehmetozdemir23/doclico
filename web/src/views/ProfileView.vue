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

      <BaseInput
        id="siret"
        v-model="profileForm.siret"
        label="SIRET"
        type="text"
        size="sm"
      />

      <BaseInput
        id="address"
        v-model="profileForm.address"
        label="Adresse"
        type="text"
        size="sm"
      />

      <BaseInput
        id="phone"
        v-model="profileForm.phone"
        label="Téléphone"
        type="text"
        size="sm"
      />

      <BaseInput
        id="numero_tva"
        v-model="profileForm.numero_tva"
        label="N° TVA intracommunautaire"
        type="text"
        size="sm"
        placeholder="FR12345678901"
      />

      <BaseInput
        id="company_name"
        v-model="profileForm.company_name"
        label="Nom de l'entreprise"
        type="text"
        size="sm"
        placeholder="Acme SAS"
      />

      <!-- Logo -->
      <div class="flex flex-col gap-1.5">
        <label class="text-[13px] font-medium text-slate-700">Logo</label>
        <div v-if="profileForm.logo" class="flex items-center gap-3">
          <img :src="profileForm.logo" alt="Logo" class="h-12 max-w-[140px] object-contain rounded border border-slate-200 bg-slate-50 p-1" @error="profileForm.logo = null" />
          <button
            type="button"
            class="text-[12px] text-red-500 hover:text-red-700 transition-colors"
            @click="handleLogoDelete"
          >
            Supprimer
          </button>
        </div>
        <label
          v-else
          class="flex items-center justify-center h-10 px-3 border border-dashed border-slate-300 rounded-lg cursor-pointer hover:border-slate-400 transition-colors text-[13px] text-slate-500"
        >
          <input type="file" accept="image/*" class="sr-only" @change="handleLogoUpload" />
          Choisir une image...
        </label>
      </div>

      <div class="flex flex-col gap-1">
        <label for="mentions_legales" class="text-[13px] font-medium text-slate-700">
          Mentions légales
        </label>
        <textarea
          id="mentions_legales"
          v-model="profileForm.mentions_legales"
          rows="4"
          placeholder="Ces mentions seront ajoutées automatiquement à vos documents générés."
          class="w-full rounded-md border border-slate-300 px-3 py-2 text-[13px] text-slate-900 placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 resize-y"
        />
      </div>

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
import { useProfileStore } from "@/stores/profile";
import { useToastStore } from "@/stores/toast";
import BaseAlert from "@/components/BaseAlert.vue";
import BaseButton from "@/components/BaseButton.vue";
import BaseInput from "@/components/BaseInput.vue";

const profileStore = useProfileStore();
const toastStore = useToastStore();

const profileForm = ref({
  first_name: "",
  last_name: "",
  email: "",
  siret: "",
  address: "",
  phone: "",
  mentions_legales: "",
  numero_tva: "",
  company_name: "",
  logo: null,
});
const error = computed(() => profileStore.error);

onMounted(() => {
  initializeProfileForm();
});

const initializeProfileForm = async () => {
  const profile = await profileStore.getProfile();
  if (profile) {
    profileForm.value = {
      first_name: profile.firstName ?? "",
      last_name: profile.lastName ?? "",
      email: profile.email ?? "",
      siret: profile.siret ?? "",
      address: profile.address ?? "",
      phone: profile.phone ?? "",
      mentions_legales: profile.mentionsLegales ?? "",
      numero_tva: profile.numeroTva ?? "",
      company_name: profile.companyName ?? "",
      logo: profile.logo ?? null,
    };
  }
};

const pendingLogoFile = ref(null);

const cancelProfileEdit = () => {
  if (pendingLogoFile.value) {
    URL.revokeObjectURL(profileForm.value.logo);
    pendingLogoFile.value = null;
  }
  initializeProfileForm();
};

const handleLogoUpload = (event) => {
  const file = event.target.files?.[0];
  if (!file) return;
  pendingLogoFile.value = file;
  profileForm.value.logo = URL.createObjectURL(file);
};

const handleLogoDelete = async () => {
  if (pendingLogoFile.value) {
    URL.revokeObjectURL(profileForm.value.logo);
    pendingLogoFile.value = null;
    profileForm.value.logo = null;
    return;
  }
  const success = await profileStore.deleteLogo();
  if (success) profileForm.value.logo = null;
};

const handleUpdateProfile = async () => {
  if (pendingLogoFile.value) {
    const url = await profileStore.uploadLogo(pendingLogoFile.value);
    if (!url) {
      toastStore.error(profileStore.error);
      return;
    }
    profileForm.value.logo = url;
    pendingLogoFile.value = null;
  }

  const result = await profileStore.updateProfile({
    first_name: profileForm.value.first_name,
    last_name: profileForm.value.last_name,
    email: profileForm.value.email,
    siret: profileForm.value.siret || null,
    address: profileForm.value.address || null,
    phone: profileForm.value.phone || null,
    mentions_legales: profileForm.value.mentions_legales || null,
    numero_tva: profileForm.value.numero_tva || null,
    company_name: profileForm.value.company_name || null,
  });
  if (result) {
    profileForm.value.logo = result.user?.logo ?? profileForm.value.logo;
    toastStore.success(result.message || "Profil mis à jour avec succès");
  } else {
    toastStore.error(profileStore.error);
  }
};
</script>
