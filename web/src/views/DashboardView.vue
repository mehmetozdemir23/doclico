<template>
  <DefaultLayout>
    <div class="pt-16 pb-12 px-4 sm:px-0">
      <div class="mb-8">
        <nav class="flex gap-1 border-b border-slate-200 overflow-x-auto">
          <router-link
            v-for="(tab, index) in tabs"
            :key="tab"
            :to="{ name: tab.routeName }"
            :class="{ 'pl-4': index > 0 }"
            class="pr-4 py-2.5 text-slate-500 text-[12px] sm:text-[13px] font-medium transition-colors"
            exact-active-class="text-slate-900 bg-slate-50 border-b-2 border-slate-900"
          >
            {{ tab.label }}
          </router-link>
        </nav>
      </div>

      <RouterView />
    </div>
  </DefaultLayout>
</template>

<script setup>
import DefaultLayout from "../layouts/DefaultLayout.vue";

const tabs = [
  { id: "documents", label: "Mes documents", routeName: "dashboard.documents" },
  { id: "profile", label: "Profil", routeName: "dashboard.profile" },
  { id: "settings", label: "Paramètres", routeName: "dashboard.settings" },
];
</script>
