<template>
  <div v-if="message" :class="alertClasses">
    <p class="text-[13px]">{{ message }}</p>
  </div>
</template>

<script setup>
import { computed } from "vue";

const props = defineProps({
  message: {
    type: String,
    default: null,
  },
  type: {
    type: String,
    default: "info",
    validator: (value) =>
      ["success", "error", "info", "warning"].includes(value),
  },
});

const alertClasses = computed(() => {
  const baseClasses = "p-3 sm:p-4 rounded-lg border";

  const typeClasses = {
    success: "bg-green-50 text-green-800 border-green-200",
    error: "bg-red-50 text-red-800 border-red-200",
    info: "bg-blue-50 text-blue-800 border-blue-200",
    warning: "bg-yellow-50 text-yellow-800 border-yellow-200",
  }[props.type];

  return `${baseClasses} ${typeClasses}`;
});
</script>
