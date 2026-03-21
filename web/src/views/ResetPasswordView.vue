<template>
  <DefaultLayout>
    <div class="min-h-[calc(100vh-16rem)] flex items-center justify-center py-12">
      <div class="w-full max-w-md">
        <div class="text-center mb-8">
          <h1 class="text-2xl font-semibold text-slate-900 mb-2">
            Nouveau mot de passe
          </h1>
          <p class="text-[13px] text-slate-500">
            Choisissez un nouveau mot de passe pour votre compte
          </p>
        </div>

        <div v-if="done" class="bg-slate-50 border border-slate-200 rounded-lg p-6 text-center space-y-3">
          <p class="text-[14px] text-slate-700 font-medium">Mot de passe réinitialisé</p>
          <p class="text-[13px] text-slate-500">
            Votre mot de passe a été mis à jour. Vous pouvez maintenant vous connecter.
          </p>
          <router-link
            :to="{ name: 'login' }"
            class="inline-block text-[13px] text-slate-900 hover:underline font-medium mt-2"
          >
            Se connecter
          </router-link>
        </div>

        <div v-else-if="!token || !email" class="text-center py-8">
          <p class="text-[13px] text-slate-500">Lien de réinitialisation invalide ou expiré.</p>
          <router-link
            :to="{ name: 'forgot-password' }"
            class="inline-block mt-4 text-[13px] text-slate-900 hover:underline font-medium"
          >
            Demander un nouveau lien
          </router-link>
        </div>

        <form v-else class="space-y-4" @submit.prevent="handleSubmit">
          <BaseInput
            id="password"
            v-model="password"
            label="Nouveau mot de passe"
            type="password"
            required
            placeholder="••••••••"
          />

          <BaseInput
            id="password_confirmation"
            v-model="passwordConfirmation"
            label="Confirmer le mot de passe"
            type="password"
            required
            placeholder="••••••••"
          />

          <BaseAlert v-if="error" :message="error" type="error" />

          <BaseButton type="submit" :disabled="loading" :full-width="true">
            {{ loading ? "Réinitialisation..." : "Réinitialiser le mot de passe" }}
          </BaseButton>
        </form>
      </div>
    </div>
  </DefaultLayout>
</template>

<script setup>
import { ref } from "vue";
import { useRoute } from "vue-router";
import BaseAlert from "@/components/BaseAlert.vue";
import BaseButton from "@/components/BaseButton.vue";
import BaseInput from "@/components/BaseInput.vue";
import DefaultLayout from "@/layouts/DefaultLayout.vue";
import { apiFetch } from "@/services/apiClient";

const route = useRoute();
const token = route.query.token ?? null;
const email = route.query.email ?? null;

const password = ref("");
const passwordConfirmation = ref("");
const loading = ref(false);
const error = ref(null);
const done = ref(false);

const handleSubmit = async () => {
  if (password.value !== passwordConfirmation.value) {
    error.value = "Les mots de passe ne correspondent pas";
    return;
  }
  loading.value = true;
  error.value = null;
  try {
    const response = await apiFetch("/api/reset-password", {
      method: "POST",
      body: {
        token,
        email,
        password: password.value,
        password_confirmation: passwordConfirmation.value,
      },
    });
    if (!response.ok) {
      const data = await response.json().catch(() => ({}));
      throw new Error(data.message || "Une erreur est survenue");
    }
    done.value = true;
  } catch (err) {
    error.value = err.message || "Une erreur est survenue";
  } finally {
    loading.value = false;
  }
};
</script>
