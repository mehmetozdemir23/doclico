<template>
  <DefaultLayout>
    <div class="min-h-[calc(100vh-16rem)] flex items-center justify-center py-12">
      <div class="w-full max-w-md">
        <div class="text-center mb-8">
          <h1 class="text-2xl font-semibold text-slate-900 mb-2">
            Mot de passe oublié
          </h1>
          <p class="text-[13px] text-slate-500">
            Entrez votre adresse email pour recevoir un lien de réinitialisation
          </p>
        </div>

        <div v-if="sent" class="bg-slate-50 border border-slate-200 rounded-lg p-6 text-center space-y-3">
          <p class="text-[14px] text-slate-700 font-medium">Email envoyé</p>
          <p class="text-[13px] text-slate-500">
            Si un compte existe pour cette adresse, vous recevrez un email avec un lien de réinitialisation.
          </p>
          <router-link
            :to="{ name: 'login' }"
            class="inline-block text-[13px] text-slate-900 hover:underline font-medium mt-2"
          >
            Retour à la connexion
          </router-link>
        </div>

        <form v-else class="space-y-4" @submit.prevent="handleSubmit">
          <BaseInput
            id="email"
            v-model="email"
            label="Adresse email"
            type="email"
            required
            placeholder="vous@exemple.com"
          />

          <BaseAlert v-if="error" :message="error" type="error" />

          <BaseButton type="submit" :disabled="loading" :full-width="true">
            {{ loading ? "Envoi..." : "Envoyer le lien" }}
          </BaseButton>

          <div class="text-center">
            <router-link
              :to="{ name: 'login' }"
              class="text-[13px] text-slate-500 hover:text-slate-900 transition-colors"
            >
              Retour à la connexion
            </router-link>
          </div>
        </form>
      </div>
    </div>
  </DefaultLayout>
</template>

<script setup>
import { ref } from "vue";
import BaseAlert from "@/components/BaseAlert.vue";
import BaseButton from "@/components/BaseButton.vue";
import BaseInput from "@/components/BaseInput.vue";
import DefaultLayout from "@/layouts/DefaultLayout.vue";
import { apiFetch } from "@/services/apiClient";

const email = ref("");
const loading = ref(false);
const error = ref(null);
const sent = ref(false);

const handleSubmit = async () => {
  loading.value = true;
  error.value = null;
  try {
    const response = await apiFetch("/api/forgot-password", {
      method: "POST",
      body: { email: email.value },
    });
    if (!response.ok) {
      const data = await response.json().catch(() => ({}));
      throw new Error(data.message || "Une erreur est survenue");
    }
    sent.value = true;
  } catch (err) {
    error.value = err.message || "Une erreur est survenue";
  } finally {
    loading.value = false;
  }
};
</script>
