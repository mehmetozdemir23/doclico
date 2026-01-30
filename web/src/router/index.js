import { createRouter, createWebHistory } from "vue-router";
import ProfileView from "@/components/ProfileView.vue";
import { useAuthStore } from "@/stores/auth";
import DashboardView from "@/views/DashboardView.vue";
import DocumentsView from "@/views/DocumentsView.vue";
import HomeView from "@/views/HomeView.vue";
import LoginView from "@/views/LoginView.vue";
import RegisterView from "@/views/RegisterView.vue";
import SettingsView from "@/views/SettingsView.vue";
import TemplateView from "@/views/TemplateView.vue";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  scrollBehavior: () => ({ top: 0 }),
  routes: [
    {
      path: "/",
      name: "home",
      component: HomeView,
      meta: { requiresAuth: false },
    },
    {
      path: "/template",
      name: "template",
      component: TemplateView,
      meta: { requiresAuth: false },
    },
    {
      path: "/login",
      name: "login",
      component: LoginView,
      meta: { requiresAuth: false },
    },
    {
      path: "/register",
      name: "register",
      component: RegisterView,
      meta: { requiresAuth: false },
    },
    {
      path: "/dashboard",
      name: "dashboard",
      component: DashboardView,
      redirect: "/dashboard/documents",
      meta: { requiresAuth: true },
      children: [
        {
          path: "documents",
          name: "dashboard.documents",
          component: DocumentsView,
        },
        {
          path: "profile",
          name: "dashboard.profile",
          component: ProfileView,
        },
        {
          path: "settings",
          name: "dashboard.settings",
          component: SettingsView,
        },
      ],
    },
  ],
});

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
    return {
      name: "login",
      query: { redirect: to.fullPath },
    };
  }
});

export default router;
