<template>
  <nav
    class="sm:hidden fixed bottom-0 inset-x-0 z-30 bg-white/90 backdrop-blur-md border-t border-slate-200/80 pb-1"
    aria-label="Navigation principale"
  >
    <div class="flex justify-evenly w-full">
      <router-link
        v-for="item in items"
        :key="item.routeName"
        :to="{ name: item.routeName }"
        class="flex flex-col items-center justify-center gap-1 py-2.5 transition-colors"
        :class="isActive(item.routeName) ? 'text-slate-900' : 'text-slate-400'"
        :aria-current="isActive(item.routeName) ? 'page' : undefined"
      >
        <div
          class="w-10 h-7 flex items-center justify-center rounded-full transition-colors"
          :class="isActive(item.routeName) ? 'bg-slate-100' : ''"
        >
          <component :is="item.icon" class="w-[18px] h-[18px]" aria-hidden="true" />
        </div>
        <span class="text-[10px] font-medium leading-none">{{ item.label }}</span>
      </router-link>
    </div>
  </nav>
</template>

<script setup>
import { FileText, Users, User, Settings } from "lucide-vue-next";
import { useActiveRoute } from "@/composables/useActiveRoute";

const { isActive } = useActiveRoute();

const items = [
  { label: "Documents", routeName: "dashboard.documents", icon: FileText },
  { label: "Clients", routeName: "dashboard.clients", icon: Users },
  { label: "Profil", routeName: "dashboard.profile", icon: User },
  { label: "Paramètres", routeName: "dashboard.settings", icon: Settings },
];
</script>
