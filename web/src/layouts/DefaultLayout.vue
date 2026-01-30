<template>
  <div class="min-h-screen bg-white">
    <header
      class="border-b border-slate-200/50 sticky top-0 z-20 bg-white/80 backdrop-blur-md"
    >
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
          <div class="flex items-center">
            <router-link to="/">
              <img src="/doclico.svg" alt="Doclico" class="h-6 w-auto" />
            </router-link>
          </div>
          <div v-if="isAuthenticated" class="flex items-center gap-3">
            <router-link
              :to="{ name: 'dashboard' }"
              class="text-[13px] font-medium text-slate-700 hover:text-slate-900 transition-colors"
            >
              Dashboard
            </router-link>
            <button
              class="text-[13px] font-medium text-slate-500 hover:text-slate-900 transition-colors"
              title="Déconnexion"
              @click="handleLogout"
            >
              <LogOut class="w-4 h-4" />
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

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <slot></slot>
    </main>
  </div>
  <footer class="border-t border-slate-200 mt-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
        <div class="flex items-center gap-6 text-[13px] order-2 sm:order-1">
          <a
            href="#"
            class="text-slate-500 hover:text-slate-900 transition-colors"
          >À propos</a>
          <a
            href="#"
            class="text-slate-500 hover:text-slate-900 transition-colors"
          >Mentions légales</a>
          <a
            href="#"
            class="text-slate-500 hover:text-slate-900 transition-colors"
          >Contact</a>
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
import { useAuthStore } from "@/stores/auth";

const router = useRouter();
const authStore = useAuthStore();

const isAuthenticated = computed(() => authStore.isAuthenticated);

const handleLogout = () => {
  authStore.logout();
  router.push("/");
};
</script>

<style>
@import url("https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap");

body {
  font-family: "Inter", system-ui, sans-serif;
}
</style>
