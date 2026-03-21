<template>
  <div class="min-h-screen bg-white">
    <header
      class="border-b border-slate-200/50 sticky top-0 z-20 bg-white/80 backdrop-blur-md"
    >
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
          <div class="flex items-center">
            <router-link to="/" aria-label="Doclico — accueil">
              <AppLogo class="text-slate-900" />
            </router-link>
          </div>
          <div v-if="isAuthenticated" class="flex items-center gap-3">
            <router-link
              :to="{ name: 'dashboard' }"
              class="hidden sm:block text-[13px] font-medium text-slate-700 hover:text-slate-900 transition-colors"
            >
              Dashboard
            </router-link>
            <button
              class="text-[13px] font-medium text-slate-500 hover:text-slate-900 transition-colors"
              aria-label="Déconnexion"
              @click="handleLogout"
            >
              <LogOut class="w-4 h-4" aria-hidden="true" />
            </button>
          </div>
          <router-link
            v-else
            :to="{ name: 'login' }"
            class="h-8 px-3 text-[13px] font-medium text-slate-700 hover:text-slate-900 hover:bg-slate-100 rounded-md transition-colors inline-flex items-center"
          >
            Se connecter
          </router-link>
        </div>
      </div>
    </header>

    <main
      class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"
      :class="mobileBottomNav ? 'pb-24 sm:pb-0' : ''"
    >
      <slot></slot>
    </main>
  </div>
  <footer class="border-t border-slate-200 mt-24" :class="mobileBottomNav ? 'hidden sm:block' : ''">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
        <div class="flex items-center gap-6 text-[13px] order-2 sm:order-1">
          <router-link :to="{ name: 'legal' }" class="text-slate-500 hover:text-slate-900 transition-colors">Mentions légales</router-link>
          <router-link :to="{ name: 'privacy' }" class="text-slate-500 hover:text-slate-900 transition-colors">Confidentialité</router-link>
          <router-link :to="{ name: 'terms' }" class="text-slate-500 hover:text-slate-900 transition-colors">CGU</router-link>
        </div>
        <div class="flex items-center gap-1.5 order-1 sm:order-2">
          <span class="text-[13px] text-slate-400">&copy; 2026 Doclico</span>
        </div>
      </div>
    </div>
  </footer>
</template>

<script setup>
import { LogOut } from "lucide-vue-next";
import { computed } from "vue";
import { useRouter } from "vue-router";
import AppLogo from "@/components/AppLogo.vue";
import { useAuthStore } from "@/stores/auth";

defineProps({ mobileBottomNav: Boolean });

const router = useRouter();
const authStore = useAuthStore();

const isAuthenticated = computed(() => authStore.isAuthenticated);

const handleLogout = () => {
  authStore.logout();
  router.push("/");
};
</script>
