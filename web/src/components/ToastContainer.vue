<template>
  <div
    class="fixed top-4 right-4 z-50 flex flex-col gap-2 max-w-sm w-full pointer-events-none px-4 sm:px-0"
  >
    <TransitionGroup name="toast">
      <div
        v-for="toast in toastStore.toasts"
        :key="toast.id"
        :role="toast.type === 'error' ? 'alert' : 'status'"
        :aria-live="toast.type === 'error' ? 'assertive' : 'polite'"
        :class="toastClasses(toast.type)"
        class="pointer-events-auto"
      >
        <p class="text-[13px]">{{ toast.message }}</p>
        <button
          class="ml-3 text-current opacity-60 hover:opacity-100 transition-opacity"
          aria-label="Fermer"
          @click="toastStore.remove(toast.id)"
        >
          <X class="w-4 h-4" aria-hidden="true" />
        </button>
      </div>
    </TransitionGroup>
  </div>
</template>

<script setup>
import { X } from "lucide-vue-next";
import { useToastStore } from "@/stores/toast";

const toastStore = useToastStore();

const toastClasses = (type) => {
  const baseClasses =
    "flex items-center justify-between p-4 rounded-lg shadow-lg border backdrop-blur-sm";

  const typeClasses = {
    success: "bg-green-50/95 text-green-800 border-green-200",
    error: "bg-red-50/95 text-red-800 border-red-200",
    info: "bg-blue-50/95 text-blue-800 border-blue-200",
  }[type];

  return `${baseClasses} ${typeClasses}`;
};
</script>

<style scoped>
.toast-enter-active,
.toast-leave-active {
  transition: all 0.3s ease;
}

.toast-enter-from {
  opacity: 0;
  transform: translateX(2rem);
}

.toast-leave-to {
  opacity: 0;
  transform: translateX(2rem);
}

.toast-move {
  transition: transform 0.3s ease;
}
</style>
