<script setup>
import { RouterView } from "vue-router";
import ToastContainer from "@/components/ToastContainer.vue";
import { useAuthStore } from "@/stores/auth";

const authStore = useAuthStore();
</script>

<template>
  <RouterView v-if="authStore.initialized" />
  <ToastContainer />
</template>
