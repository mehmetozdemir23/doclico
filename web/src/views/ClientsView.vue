<template>
  <div class="flex items-center justify-between mb-6">
    <div class="text-[13px] text-slate-600">
      {{ clients.length }} client{{ clients.length > 1 ? "s" : "" }}
    </div>
    <button
      class="hidden sm:inline-flex h-8 px-3.5 text-[13px] font-medium bg-slate-900 text-white rounded-lg hover:bg-slate-800 transition-colors items-center gap-1.5"
      @click="openCreateModal"
    >
      <Plus class="w-3.5 h-3.5" aria-hidden="true" />
      Nouveau client
    </button>
  </div>

  <!-- FAB mobile -->
  <button
    class="fixed bottom-24 right-4 z-40 sm:hidden h-12 w-12 bg-slate-900 text-white rounded-full shadow-lg hover:bg-slate-800 active:scale-95 transition-all inline-flex items-center justify-center"
    aria-label="Nouveau client"
    @click="openCreateModal"
  >
    <UserPlus class="w-5 h-5" aria-hidden="true" />
  </button>

  <BaseAlert v-if="error" :message="error" type="error" class="mb-6" />

  <div v-if="loading" class="text-center py-20">
    <p class="text-[13px] text-slate-500">Chargement...</p>
  </div>

  <div v-else-if="clients.length === 0" class="text-center py-20">
    <div class="inline-flex items-center justify-center w-12 h-12 rounded-lg bg-slate-100 mb-4">
      <Users class="w-5 h-5 text-slate-400" aria-hidden="true" />
    </div>
    <p class="text-[14px] font-medium text-slate-900 mb-1">Aucun client</p>
    <p class="text-[13px] text-slate-500 mb-5">Ajoutez vos clients pour pré-remplir vos documents automatiquement.</p>
    <button
      class="h-8 px-4 text-[13px] font-medium bg-slate-900 text-white rounded-lg hover:bg-slate-800 transition-colors inline-flex items-center gap-1.5"
      @click="openCreateModal"
    >
      <Plus class="w-3.5 h-3.5" aria-hidden="true" />
      Ajouter un client
    </button>
  </div>

  <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    <div
      v-for="client in clients"
      :key="client.id"
      class="border border-slate-200 rounded-lg p-4 hover:border-slate-300 transition-colors flex flex-col cursor-pointer"
      @click="$router.push({ name: 'dashboard.clients.show', params: { id: client.id } })"
    >
      <div class="flex-1 mb-3">
        <h3 class="text-[15px] font-medium text-slate-900 mb-1">{{ client.nom }}</h3>
        <p v-if="client.adresse" class="text-[12px] text-slate-500 whitespace-pre-line line-clamp-2">{{ client.adresse }}</p>
        <div class="flex flex-col gap-0.5 mt-1.5">
          <span v-if="client.email" class="text-[12px] text-slate-400">{{ client.email }}</span>
          <span v-if="client.telephone" class="text-[12px] text-slate-400">{{ client.telephone }}</span>
        </div>
      </div>
      <div class="hidden sm:flex items-center gap-2 mt-auto pt-3 border-t border-slate-100">
        <button
          class="inline-flex items-center justify-center h-8 w-8 text-slate-700 hover:text-slate-900 hover:bg-slate-100 rounded-lg transition-colors"
          :aria-label="`Modifier ${client.nom}`"
          @click.stop="openEditModal(client)"
        >
          <Pencil class="w-3.5 h-3.5" aria-hidden="true" />
        </button>
        <button
          class="inline-flex items-center justify-center w-8 h-8 text-slate-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors ml-auto"
          :aria-label="`Supprimer ${client.nom}`"
          @click.stop="handleDelete(client.id)"
        >
          <Trash2 class="w-3.5 h-3.5" aria-hidden="true" />
        </button>
      </div>
    </div>
  </div>

  <!-- FAB spacer on mobile -->
  <div class="h-16 sm:hidden" aria-hidden="true"></div>

  <!-- Modale création / édition -->
  <BaseModal v-model="modalOpen" :title="editingClient ? 'Modifier le client' : 'Nouveau client'">
    <form class="p-5 space-y-4" @submit.prevent="handleSubmit">
        <div class="space-y-1.5">
          <label for="client-nom" class="block text-[13px] font-medium text-slate-900">
            Nom <span class="text-red-500" aria-hidden="true">*</span>
          </label>
          <input
            id="client-nom"
            v-model="form.nom"
            type="text"
            required
            placeholder="Acme Corp"
            class="w-full h-10 px-3 text-[15px] bg-white border border-slate-200 rounded-lg focus:outline-none focus:border-slate-400 transition-colors placeholder:text-slate-400"
          />
        </div>

        <div class="space-y-1.5">
          <label for="client-adresse" class="block text-[13px] font-medium text-slate-900">Adresse</label>
          <textarea
            id="client-adresse"
            v-model="form.adresse"
            rows="3"
            placeholder="1 avenue des Champs-Élysées&#10;75008 Paris"
            class="w-full px-3 py-2 text-[15px] bg-white border border-slate-200 rounded-lg focus:outline-none focus:border-slate-400 transition-colors placeholder:text-slate-400 resize-none"
          ></textarea>
        </div>

        <div class="space-y-1.5">
          <label for="client-email" class="block text-[13px] font-medium text-slate-900">Email</label>
          <input
            id="client-email"
            v-model="form.email"
            type="email"
            placeholder="contact@acme.com"
            class="w-full h-10 px-3 text-[15px] bg-white border border-slate-200 rounded-lg focus:outline-none focus:border-slate-400 transition-colors placeholder:text-slate-400"
          />
        </div>

        <div class="space-y-1.5">
          <label for="client-telephone" class="block text-[13px] font-medium text-slate-900">Téléphone</label>
          <input
            id="client-telephone"
            v-model="form.telephone"
            type="tel"
            placeholder="+33 1 23 45 67 89"
            class="w-full h-10 px-3 text-[15px] bg-white border border-slate-200 rounded-lg focus:outline-none focus:border-slate-400 transition-colors placeholder:text-slate-400"
          />
        </div>

        <BaseAlert v-if="formError" :message="formError" type="error" />

        <div class="flex gap-2 pt-2">
          <BaseButton type="submit" :disabled="clientsStore.submitting" :full-width="true">
            {{ clientsStore.submitting ? "Enregistrement..." : (editingClient ? "Mettre à jour" : "Créer") }}
          </BaseButton>
          <BaseButton type="button" variant="secondary" :full-width="true" @click="closeModal">
            Annuler
          </BaseButton>
        </div>
    </form>
  </BaseModal>

  <BaseConfirm
    v-model="deleteConfirmOpen"
    title="Supprimer le client ?"
    message="Cette action est irréversible."
    confirm-label="Supprimer"
    @confirm="confirmDelete"
    @cancel="deleteConfirmOpen = false"
  />
</template>

<script setup>
import { Pencil, Plus, Trash2, UserPlus, Users } from "lucide-vue-next";
import { computed, onMounted, ref, watch } from "vue";
import BaseAlert from "@/components/BaseAlert.vue";
import BaseButton from "@/components/BaseButton.vue";
import BaseConfirm from "@/components/BaseConfirm.vue";
import BaseModal from "@/components/BaseModal.vue";
import { useClientsStore } from "@/stores/clients";
import { useToastStore } from "@/stores/toast";

const clientsStore = useClientsStore();
const toastStore = useToastStore();

const clients = computed(() => clientsStore.clients);
const loading = computed(() => clientsStore.loading);
const error = computed(() => clientsStore.error);

onMounted(() => {
  clientsStore.fetchClients();
});

const modalOpen = ref(false);
const editingClient = ref(null);
const formError = ref(null);

const emptyForm = () => ({ nom: "", adresse: "", email: "", telephone: "" });
const form = ref(emptyForm());

const openCreateModal = () => {
  editingClient.value = null;
  form.value = emptyForm();
  formError.value = null;
  modalOpen.value = true;
};

const openEditModal = (client) => {
  editingClient.value = client;
  form.value = {
    nom: client.nom,
    adresse: client.adresse ?? "",
    email: client.email ?? "",
    telephone: client.telephone ?? "",
  };
  formError.value = null;
  modalOpen.value = true;
};

const closeModal = () => { modalOpen.value = false; };

watch(modalOpen, (val) => {
  if (!val) {
    editingClient.value = null;
    formError.value = null;
  }
});

const handleSubmit = async () => {
  formError.value = null;

  const payload = {
    nom: form.value.nom,
    adresse: form.value.adresse || null,
    email: form.value.email || null,
    telephone: form.value.telephone || null,
  };

  if (editingClient.value) {
    const result = await clientsStore.updateClient(editingClient.value.id, payload);
    if (result) {
      toastStore.success("Client mis à jour");
      closeModal();
    } else {
      formError.value = clientsStore.error;
    }
  } else {
    const result = await clientsStore.createClient(payload);
    if (result) {
      toastStore.success("Client créé");
      closeModal();
    } else {
      formError.value = clientsStore.error;
    }
  }
};

const deleteConfirmOpen = ref(false);
const deleteTargetId = ref(null);

const handleDelete = (id) => {
  deleteTargetId.value = id;
  deleteConfirmOpen.value = true;
};

const confirmDelete = async () => {
  deleteConfirmOpen.value = false;
  const success = await clientsStore.deleteClient(deleteTargetId.value);
  if (success) {
    toastStore.success("Client supprimé");
  } else {
    toastStore.error(clientsStore.error);
  }
};
</script>
