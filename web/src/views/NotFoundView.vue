<template>
  <div class="min-h-screen flex items-center justify-center px-4">
    <div class="text-center">
      <p class="text-[64px] font-bold text-slate-200 leading-none mb-4">404</p>
      <p class="text-[16px] font-medium text-slate-900 mb-1">Page introuvable</p>
      <p class="text-[13px] text-slate-500 mb-6">Cette page n'existe pas ou a été déplacée.</p>
      <router-link
        to="/"
        class="inline-flex items-center h-9 px-4 text-[13px] font-medium bg-slate-900 text-white rounded-lg hover:bg-slate-800 transition-colors"
      >
        Retour à l'accueil
      </router-link>
    </div>
  </div>
</template>
