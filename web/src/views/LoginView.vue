<template>
  <DefaultLayout>
    <div
      class="min-h-[calc(100vh-16rem)] flex items-center justify-center py-12"
    >
      <div class="w-full max-w-md">
        <div class="text-center mb-8">
          <h1 class="text-2xl font-semibold text-slate-900 mb-2">
            Se connecter
          </h1>
          <p class="text-[13px] text-slate-500">
            Accédez à tous vos documents sauvegardés
          </p>
        </div>

        <form class="space-y-4" @submit.prevent="handleLogin">
          <BaseInput
            id="email"
            v-model="loginForm.email"
            label="Email"
            type="email"
            required
            placeholder="vous@exemple.com"
          />

          <BaseInput
            id="password"
            v-model="loginForm.password"
            label="Mot de passe"
            type="password"
            required
            placeholder="••••••••"
          />

          <div class="flex justify-end">
            <router-link
              :to="{ name: 'forgot-password' }"
              class="text-[13px] text-slate-500 hover:text-slate-900 transition-colors"
            >
              Mot de passe oublié ?
            </router-link>
          </div>

          <BaseAlert v-if="error" :message="error" type="error" />

          <BaseButton type="submit" :disabled="loading" :full-width="true">
            {{ loading ? "Connexion..." : "Se connecter" }}
          </BaseButton>

          <div class="relative flex items-center gap-3">
            <div class="flex-1 h-px bg-slate-200"></div>
            <span class="text-[12px] text-slate-400 flex-shrink-0">ou</span>
            <div class="flex-1 h-px bg-slate-200"></div>
          </div>

          <GoogleAuthButton />

          <div class="text-center">
            <p class="text-[13px] text-slate-500">
              Pas encore de compte ?
              <router-link
                :to="{ name: 'register' }"
                class="text-slate-900 hover:underline font-medium"
              >
                Créer un compte
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

const loginForm = ref({
  email: "",
  password: "",
});
const loading = computed(() => authStore.submitting);
const error = computed(() => authStore.error);

const handleLogin = async () => {
  const success = await authStore.login(loginForm.value.email, loginForm.value.password);
  if (success) {
    router.push("/dashboard");
  }
};
</script>
