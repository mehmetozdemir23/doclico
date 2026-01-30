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

        <registerForm class="space-y-4" @submit.prevent="handleRegister">
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

          <BaseButton type="submit" :disabled="loading" :full-width="true">
            {{ loading ? "Création..." : "Créer mon compte" }}
          </BaseButton>

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
        </registerForm>
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

const handleRegister = async () => {
  if (registerForm.value.password !== registerForm.value.password_confirmation) {
    authStore.error = "Les mots de passe ne correspondent pas";
    return;
  }

  const success = await authStore.register(registerForm.value);
  if (success) {
    router.push("/");
  }
};
</script>
