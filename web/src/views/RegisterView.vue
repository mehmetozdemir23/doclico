<template>
  <DefaultLayout>
    <div
      class="min-h-[calc(100vh-16rem)] flex items-center justify-center py-12"
    >
      <div class="w-full max-w-md">
        <div class="text-center mb-8">
          <h1 class="text-2xl font-semibold text-slate-900 mb-2">
            Créer un compte
          </h1>
          <p class="text-[13px] text-slate-500">
            Sauvegardez et gérez tous vos documents
          </p>
        </div>

        <form class="space-y-4" @submit.prevent="handleRegister">
          <BaseInput
            id="first_name"
            v-model="registerForm.first_name"
            label="Prénom"
            type="text"
            required
            placeholder="Jean"
          />

          <BaseInput
            id="last_name"
            v-model="registerForm.last_name"
            label="Nom"
            type="text"
            required
            placeholder="Dupont"
          />

          <BaseInput
            id="email"
            v-model="registerForm.email"
            label="Email"
            type="email"
            required
            placeholder="vous@exemple.com"
          />

          <BaseInput
            id="password"
            v-model="registerForm.password"
            label="Mot de passe"
            type="password"
            required
            minlength="8"
            placeholder="••••••••"
            hint="Minimum 8 caractères"
          />

          <BaseInput
            id="password_confirmation"
            v-model="registerForm.password_confirmation"
            label="Confirmer le mot de passe"
            type="password"
            required
            minlength="8"
            placeholder="••••••••"
          />

          <BaseAlert v-if="error" :message="error" type="error" />

          <div class="flex items-start gap-2.5">
            <input
              id="consent"
              v-model="consent"
              type="checkbox"
              required
              class="mt-0.5 h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-500 cursor-pointer flex-shrink-0"
            />
            <label for="consent" class="text-[13px] text-slate-500 cursor-pointer leading-relaxed">
              J'accepte la
              <router-link :to="{ name: 'privacy' }" class="text-slate-900 hover:underline">politique de confidentialité</router-link>
              et le traitement de mes données personnelles conformément au RGPD.
            </label>
          </div>

          <BaseButton type="submit" :disabled="loading || !consent" :full-width="true">
            {{ loading ? "Création..." : "Créer mon compte" }}
          </BaseButton>

          <div class="relative flex items-center gap-3">
            <div class="flex-1 h-px bg-slate-200"></div>
            <span class="text-[12px] text-slate-400 flex-shrink-0">ou</span>
            <div class="flex-1 h-px bg-slate-200"></div>
          </div>

          <GoogleAuthButton />

          <div class="text-center">
            <p class="text-[13px] text-slate-500">
              Vous avez déjà un compte ?
              <router-link
                :to="{ name: 'login' }"
                class="text-slate-900 hover:underline font-medium"
              >
                Se connecter
              </router-link>
            </p>
          </div>
        </form>
      </div>
    </div>
  </DefaultLayout>
</template>

<script setup>
import { computed, ref } from "vue";
import { useRouter } from "vue-router";
import BaseAlert from "@/components/BaseAlert.vue";
import BaseButton from "@/components/BaseButton.vue";
import BaseInput from "@/components/BaseInput.vue";
import GoogleAuthButton from "@/components/GoogleAuthButton.vue";
import DefaultLayout from "@/layouts/DefaultLayout.vue";
import { useAuthStore } from "@/stores/auth";

const router = useRouter();
const authStore = useAuthStore();

const registerForm = ref({
  first_name: "",
  last_name: "",
  email: "",
  password: "",
  password_confirmation: "",
});
const loading = computed(() => authStore.submitting);
const error = computed(() => authStore.error);
const consent = ref(false);

const handleRegister = async () => {
  if (registerForm.value.password !== registerForm.value.password_confirmation) {
    authStore.setError("Les mots de passe ne correspondent pas");
    return;
  }

  const success = await authStore.register({ ...registerForm.value, consent: consent.value });
  if (success) {
    router.push("/dashboard");
  }
};
</script>
