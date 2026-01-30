import { defineStore } from "pinia";
import { ref } from "vue";

export const useToastStore = defineStore("toast", () => {
  const toasts = ref([]);
  let nextId = 0;

  function show(message, type = "success", duration = 4000) {
    const id = nextId++;
    const toast = { id, message, type };

    toasts.value.push(toast);

    if (duration > 0) {
      setTimeout(() => {
        remove(id);
      }, duration);
    }

    return id;
  }

  function remove(id) {
    const index = toasts.value.findIndex((t) => t.id === id);
    if (index > -1) {
      toasts.value.splice(index, 1);
    }
  }

  function success(message, duration) {
    return show(message, "success", duration);
  }

  function error(message, duration) {
    return show(message, "error", duration);
  }

  function info(message, duration) {
    return show(message, "info", duration);
  }

  return {
    toasts,
    show,
    remove,
    success,
    error,
    info,
  };
});
