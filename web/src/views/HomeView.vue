<template>
  <DefaultLayout>
    <!-- ── Hero ──────────────────────────────────────────────────────── -->
    <section class="pt-14 sm:pt-20 pb-16 border-b border-slate-100 text-center bg-gradient-to-b from-slate-50 to-white -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8">
      <div class="max-w-2xl mx-auto">
        <h1 class="text-[30px] sm:text-[46px] text-slate-900 leading-[1.1] tracking-tight mb-5">
          <span class="font-normal">Vos documents pro</span><br />
          <span class="font-bold text-[26px] sm:text-[40px]">sans abonnement.</span>
        </h1>

        <p class="text-[16px] sm:text-[18px] text-slate-500 leading-relaxed mb-8 max-w-xl mx-auto">
          Créez, générez et partagez vos documents en quelques secondes.
        </p>

        <router-link
          :to="{ name: 'register' }"
          class="h-11 px-6 text-[14px] font-medium bg-slate-900 text-white rounded-full
      hover:bg-slate-800 transition-all duration-200 inline-flex items-center
      gap-2 hover:gap-3 shadow-sm group"
        >
          <span>Créer mon compte gratuitement</span>
          <ArrowRight class="w-4 h-4 transition-transform duration-200 ease-out group-hover:translate-x-1" />
        </router-link>

        <p class="text-[12px] text-slate-400 mt-4">
          Aucune carte requise
        </p>
      </div>
    </section>


    <!-- ── Features ──────────────────────────────────────────────────── -->
    <section class="py-14 border-b border-slate-100">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        <div class="border border-slate-200 p-4 rounded-lg">
          <FileText class="w-4 h-4 mb-4 text-slate-700" />
          <div class="text-[14px] font-semibold text-slate-900 mb-1.5">
            Conformes sans y penser
          </div>
          <div class="text-[13px] text-slate-500 leading-relaxed">
            Mentions légales, numérotation, TVA — chaque modèle respecte le droit français en vigueur.
          </div>
        </div>

        <div class="border border-slate-200 p-4 rounded-lg">
          <Zap class="w-4 h-4 mb-4 text-slate-700" />
          <div class="text-[14px] font-semibold text-slate-900 mb-1.5">
            Prêt en quelques secondes
          </div>
          <div class="text-[13px] text-slate-500 leading-relaxed">
            Renseignez vos informations. Votre document est prêt. Aucune installation requise.
          </div>
        </div>

        <div class="border border-slate-200 p-4 rounded-lg">
          <Share2 class="w-4 h-4 mb-4 text-slate-700" />
          <div class="text-[14px] font-semibold text-slate-900 mb-1.5">
            Partagez sans quitter l'espace
          </div>
          <div class="text-[13px] text-slate-500 leading-relaxed">
            Lien sécurisé avec date d'expiration, envoyé directement à votre client.
          </div>
        </div>

        <div class="border border-slate-200 p-4 rounded-lg">
          <Users class="w-4 h-4 mb-4 text-slate-700" />
          <div class="text-[14px] font-semibold text-slate-900 mb-1.5">
            Vos clients enregistrés
          </div>
          <div class="text-[13px] text-slate-500 leading-relaxed">
            Enregistrez leurs informations une fois et réutilisez-les automatiquement dans vos prochains documents.
          </div>
        </div>
      </div>
    </section>


    <!-- ── Documents disponibles ─────────────────────────────────────── -->
    <section class="py-14 border-b border-slate-100">
      <div class="flex items-center gap-2 mb-8">
        <h2 class="text-[13px] font-semibold text-slate-900 whitespace-nowrap">
          Tout ce qu'il vous faut
        </h2>
        <div class="flex-1 h-px bg-slate-100"></div>
      </div>

      <div class="flex flex-wrap gap-3">
        <div
          v-for="doc in featuredDocuments"
          :key="doc.label"
          class="flex items-center gap-2.5 px-3.5 py-2.5 bg-white border border-slate-200 rounded-lg"
        >
          <component :is="doc.icon" class="w-4 h-4 text-slate-500 flex-shrink-0" />
          <span class="text-[13px] font-medium text-slate-900">
            {{ doc.label }}
          </span>
        </div>

        <div class="flex items-center gap-2.5 px-3.5 py-2.5 bg-slate-50 border border-dashed border-slate-200 rounded-lg">
          <span class="text-[13px] text-slate-400">
            et plus encore…
          </span>
        </div>
      </div>
    </section>


    <!-- ── Comment ça marche ──────────────────────────────────────────── -->
    <section class="py-14 border-b border-slate-100">
      <div class="flex items-center gap-2 mb-8">
        <h2 class="text-[13px] font-semibold text-slate-900 whitespace-nowrap">
          Comment ça marche
        </h2>
        <div class="flex-1 h-px bg-slate-100"></div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-3 gap-8">
        <div class="flex gap-4">
          <div class="flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-full bg-slate-900 text-white text-[12px] font-semibold">
            1
          </div>
          <div>
            <div class="text-[14px] font-semibold text-slate-900 mb-1">
              Choisissez un modèle
            </div>
            <div class="text-[13px] text-slate-500 leading-relaxed">
              Sélectionnez le type de document dont vous avez besoin.
            </div>
          </div>
        </div>

        <div class="flex gap-4">
          <div class="flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-full bg-slate-900 text-white text-[12px] font-semibold">
            2
          </div>
          <div>
            <div class="text-[14px] font-semibold text-slate-900 mb-1">
              Remplissez le formulaire
            </div>
            <div class="text-[13px] text-slate-500 leading-relaxed">
              Renseignez vos informations et celles de votre client, ou sélectionnez un client enregistré.
            </div>
          </div>
        </div>

        <div class="flex gap-4">
          <div class="flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-full bg-slate-900 text-white text-[12px] font-semibold">
            3
          </div>
          <div>
            <div class="text-[14px] font-semibold text-slate-900 mb-1">
              Consultez, téléchargez, partagez
            </div>
            <div class="text-[13px] text-slate-500 leading-relaxed">
              Accédez à l'ensemble de vos documents depuis votre tableau de bord, et partagez-les en un clic.
            </div>
          </div>
        </div>
      </div>
    </section>


    <!-- ── CTA final ──────────────────────────────────────────────────── -->
    <section class="py-16 text-center bg-gradient-to-b from-white to-slate-50 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8">
      <h2 class="text-[24px] font-bold text-slate-900 tracking-tight mb-3">
        Votre premier document en deux minutes.
      </h2>

      <router-link
        :to="{ name: 'register' }"
        class="h-11 px-6 text-[14px] font-medium bg-slate-900 text-white rounded-full
         hover:bg-slate-800 transition-all duration-200 inline-flex items-center
         gap-2 hover:gap-3 shadow-sm group"
      >
        <span>Créer mon compte gratuitement</span>
        <ArrowRight class="w-4 h-4 transition-transform duration-200 ease-out group-hover:translate-x-1" />
      </router-link>
    </section>
  </DefaultLayout>
</template>

<script setup>
import { useHead, useSeoMeta } from "@unhead/vue";

import {
  ArrowRight,
  FileText,
  Receipt,
  RotateCcw,
  Share2,
  Zap,
  FilePen,
  Users
} from "lucide-vue-next";

import DefaultLayout from "@/layouts/DefaultLayout.vue";


useSeoMeta({
  title: "Doclico — Documents professionnels sans abonnement",
  description:
    "Générez vos factures, devis, contrats et notes de frais en quelques secondes. Archivage automatique, partage sécurisé. Gratuit, sans abonnement, conforme aux exigences françaises.",
  ogTitle: "Doclico — Documents professionnels sans abonnement",
  ogDescription:
    "Remplissez un formulaire, récupérez votre PDF. Factures, devis, contrats archivés et accessibles depuis votre espace.",
  ogType: "website",
  twitterCard: "summary",
});


useHead({
  script: [
    {
      type: "application/ld+json",
      innerHTML: JSON.stringify({
        "@context": "https://schema.org",
        "@type": "WebApplication",
        name: "Doclico",
        description:
          "Générez et gérez vos documents professionnels : factures, devis, contrats, notes de frais.",
        applicationCategory: "BusinessApplication",
        operatingSystem: "Web",
        offers: {
          "@type": "Offer",
          price: "0",
          priceCurrency: "EUR"
        },
        inLanguage: "fr"
      })
    }
  ]
});


const featuredDocuments = [
  { label: "Facture", icon: Receipt },
  { label: "Devis", icon: FileText },
  { label: "Avoir", icon: RotateCcw },
  { label: "Contrat de prestation", icon: FilePen }
];
</script>

