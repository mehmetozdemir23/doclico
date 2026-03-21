import { createRouter, createWebHistory } from "vue-router";

export const routes = [
  {
    path: "/",
    name: "home",
    component: () => import("@/views/HomeView.vue"),
    meta: { requiresAuth: false },
  },
  {
    path: "/template",
    name: "template",
    component: () => import("@/views/TemplateView.vue"),
    meta: { requiresAuth: true },
  },
  {
    path: "/login",
    name: "login",
    component: () => import("@/views/LoginView.vue"),
    meta: { requiresAuth: false },
  },
  {
    path: "/register",
    name: "register",
    component: () => import("@/views/RegisterView.vue"),
    meta: { requiresAuth: false },
  },
  {
    path: "/forgot-password",
    name: "forgot-password",
    component: () => import("@/views/ForgotPasswordView.vue"),
    meta: { requiresAuth: false },
  },
  {
    path: "/reset-password",
    name: "reset-password",
    component: () => import("@/views/ResetPasswordView.vue"),
    meta: { requiresAuth: false },
  },
  {
    path: "/share/:token",
    name: "shared-document",
    component: () => import("@/views/SharedDocumentView.vue"),
    meta: { requiresAuth: false },
  },
  {
    path: "/privacy",
    name: "privacy",
    component: () => import("@/views/PrivacyView.vue"),
    meta: { requiresAuth: false },
  },
  {
    path: "/legal",
    name: "legal",
    component: () => import("@/views/LegalView.vue"),
    meta: { requiresAuth: false },
  },
  {
    path: "/terms",
    name: "terms",
    component: () => import("@/views/TermsView.vue"),
    meta: { requiresAuth: false },
  },
  {
    path: "/dashboard",
    name: "dashboard",
    component: () => import("@/views/DashboardView.vue"),
    redirect: "/dashboard/documents",
    meta: { requiresAuth: true },
    children: [
      {
        path: "documents",
        name: "dashboard.documents",
        component: () => import("@/views/DocumentsView.vue"),
      },
      {
        path: "clients",
        name: "dashboard.clients",
        component: () => import("@/views/ClientsView.vue"),
      },
      {
        path: "clients/:id",
        name: "dashboard.clients.show",
        component: () => import("@/views/ClientView.vue"),
      },
      {
        path: "profile",
        name: "dashboard.profile",
        component: () => import("@/views/ProfileView.vue"),
      },
      {
        path: "settings",
        name: "dashboard.settings",
        component: () => import("@/views/SettingsView.vue"),
      },
    ],
  },
  {
    path: "/:pathMatch(.*)*",
    name: "not-found",
    component: () => import("@/views/NotFoundView.vue"),
    meta: { requiresAuth: false },
  },
];

export default createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
});
