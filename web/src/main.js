import { createPinia } from "pinia";
import { ViteSSG } from "vite-ssg";

import "./main.css";
import App from "./App.vue";
import { routes } from "./router";
import { useAuthStore } from "./stores/auth";

export const createApp = ViteSSG(
  App,
  { routes, scrollBehavior: () => ({ top: 0 }) },
  ({ app, router, isClient }) => {
    app.use(createPinia());

    if (isClient) {
      router.beforeEach(async (to) => {
        const authStore = useAuthStore();

        if (!authStore.initialized) {
          await authStore.fetchUser();
        }

        if (
          (to.name === "login" || to.name === "register") &&
          authStore.isAuthenticated
        ) {
          return { name: "dashboard" };
        }

        if (to.meta.requiresAuth && !authStore.isAuthenticated) {
          return { name: "login" };
        }
      });
    }
  }
);
