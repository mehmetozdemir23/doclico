<template>
  <DefaultLayout :mobile-bottom-nav="true">
    <div class="pt-4 sm:pt-12 sm:pb-12">
      <div class="sm:mb-8">
        <nav class="hidden sm:flex gap-1 border-b border-slate-200 overflow-x-auto" aria-label="Navigation du tableau de bord">
          <router-link
            v-for="(tab, index) in tabs"
            :key="tab.id"
            :to="{ name: tab.routeName }"
            :class="{'pl-px':index < 1}"
            class="px-4 py-2.5 text-slate-500 text-[13px] font-medium transition-colors whitespace-nowrap flex-shrink-0"
            active-class="text-slate-900 bg-slate-50 border-b-2 border-slate-900"
            :aria-current="isActive(tab.routeName) ? 'page' : undefined"
          >
            {{ tab.label }}
          </router-link>
        </nav>
      </div>

      <RouterView />
    </div>
    <BottomNav />
  </DefaultLayout>
</template>

<script setup>
import DefaultLayout from "../layouts/DefaultLayout.vue";
import BottomNav from "@/components/BottomNav.vue";
import { useActiveRoute } from "@/composables/useActiveRoute";

const { isActive } = useActiveRoute();

const tabs = [
  { id: "documents", label: "Mes documents", routeName: "dashboard.documents" },
  { id: "clients", label: "Clients", routeName: "dashboard.clients" },
  { id: "profile", label: "Profil", routeName: "dashboard.profile" },
  { id: "settings", label: "Paramètres", routeName: "dashboard.settings" },
];
</script>
