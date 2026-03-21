<template>
  <DefaultLayout>
    <div class="max-w-3xl mx-auto py-14">
      <h1 class="text-2xl font-semibold text-slate-900 mb-8">Mentions légales</h1>

      <div class="text-[14px] text-slate-600 space-y-6">

        <section>
          <h2 class="text-[15px] font-semibold text-slate-900 mb-2">Éditeur du site</h2>
          <p>
            <strong>[NOM / RAISON SOCIALE]</strong><br>
            [ADRESSE]<br>
            SIRET : [SIRET]<br>
            E-mail : [EMAIL DE CONTACT]
          </p>
        </section>

        <section>
          <h2 class="text-[15px] font-semibold text-slate-900 mb-2">Hébergement</h2>
          <p>
            [NOM DE L'HÉBERGEUR]<br>
            [ADRESSE DE L'HÉBERGEUR]
          </p>
        </section>

        <section>
          <h2 class="text-[15px] font-semibold text-slate-900 mb-2">Propriété intellectuelle</h2>
          <p>L'ensemble des contenus du site Doclico (textes, interfaces, logo) est protégé par le droit d'auteur. Toute reproduction sans autorisation est interdite.</p>
        </section>

        <section>
          <h2 class="text-[15px] font-semibold text-slate-900 mb-2">Données personnelles</h2>
          <p>Pour toute information sur le traitement de vos données personnelles, consultez notre
            <router-link :to="{ name: 'privacy' }" class="text-slate-900 hover:underline">politique de confidentialité</router-link>.
          </p>
        </section>

      </div>
    </div>
  </DefaultLayout>
</template>

<script setup>
import DefaultLayout from "@/layouts/DefaultLayout.vue";
</script>
