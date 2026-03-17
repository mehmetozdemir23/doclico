<template>
  <DefaultLayout>
    <div class="max-w-3xl mx-auto py-14">
      <h1 class="text-2xl font-semibold text-slate-900 mb-8">Politique de confidentialité</h1>

      <div class="prose prose-slate text-[14px] text-slate-600 space-y-6">

        <section>
          <h2 class="text-[15px] font-semibold text-slate-900 mb-2">1. Responsable du traitement</h2>
          <p>
            Le responsable du traitement des données personnelles collectées sur Doclico est
            <strong>[NOM / RAISON SOCIALE]</strong>, auto-entrepreneur, joignable à l'adresse
            <strong>[EMAIL DE CONTACT]</strong>.
          </p>
        </section>

        <section>
          <h2 class="text-[15px] font-semibold text-slate-900 mb-2">2. Données collectées</h2>
          <p>Lors de la création de votre compte et de l'utilisation du service, nous collectons :</p>
          <ul class="list-disc pl-5 mt-2 space-y-1">
            <li>Identité : prénom, nom</li>
            <li>Coordonnées : adresse e-mail, adresse postale, téléphone</li>
            <li>Informations professionnelles : SIRET, numéro de TVA, mentions légales</li>
            <li>Données de vos clients (nom, adresse, e-mail, téléphone) — dont vous êtes responsable du traitement</li>
            <li>Contenu de vos documents générés</li>
            <li>Données techniques : cookie de session, adresse IP (stockée temporairement pour la gestion des sessions)</li>
          </ul>
        </section>

        <section>
          <h2 class="text-[15px] font-semibold text-slate-900 mb-2">3. Finalités du traitement</h2>
          <p>Vos données sont utilisées exclusivement pour :</p>
          <ul class="list-disc pl-5 mt-2 space-y-1">
            <li>La gestion de votre compte et l'authentification</li>
            <li>La génération et l'archivage de vos documents professionnels</li>
            <li>Le partage de documents avec vos clients</li>
            <li>La sécurisation des sessions (cookie CSRF et cookie de session)</li>
          </ul>
          <p class="mt-2">Aucune donnée n'est utilisée à des fins publicitaires ou transmise à des tiers à des fins commerciales.</p>
        </section>

        <section>
          <h2 class="text-[15px] font-semibold text-slate-900 mb-2">4. Base légale</h2>
          <p>Le traitement repose sur votre consentement explicite donné lors de l'inscription (Art. 6.1.a du RGPD), ainsi que sur l'exécution du contrat de service (Art. 6.1.b).</p>
        </section>

        <section>
          <h2 class="text-[15px] font-semibold text-slate-900 mb-2">5. Durée de conservation</h2>
          <p>Vos données sont conservées pendant toute la durée d'activité de votre compte. En cas de suppression de compte, toutes vos données personnelles sont définitivement effacées dans un délai de <strong>30 jours</strong> suivant votre demande. Les cookies de session expirent à la fermeture de votre navigateur ou après 2 heures d'inactivité.</p>
        </section>

        <section>
          <h2 class="text-[15px] font-semibold text-slate-900 mb-2">6. Cookies</h2>
          <p>Doclico utilise uniquement des cookies strictement nécessaires au fonctionnement du service :</p>
          <ul class="list-disc pl-5 mt-2 space-y-1">
            <li><strong>Cookie de session</strong> — maintient votre connexion entre les pages</li>
            <li><strong>Cookie XSRF-TOKEN</strong> — protège le service contre les attaques CSRF</li>
          </ul>
          <p class="mt-2">Ces cookies ne nécessitent pas de consentement au sens de la directive ePrivacy, car ils sont indispensables au fonctionnement du service. Aucun cookie publicitaire ou de tracking tiers n'est utilisé.</p>
        </section>

        <section>
          <h2 class="text-[15px] font-semibold text-slate-900 mb-2">7. Hébergement et sous-traitants</h2>
          <p>Doclico est hébergé par <strong>[NOM DE L'HÉBERGEUR]</strong>, dont les serveurs sont situés <strong>[LOCALISATION — ex : en France / dans l'Union Européenne]</strong>. Les données ne sont pas transférées en dehors de l'Union Européenne.</p>
          <p class="mt-2">Les e-mails transactionnels (réinitialisation de mot de passe, rappels) sont envoyés via <strong>[PRESTATAIRE EMAIL — ex : Mailgun, Brevo]</strong>.</p>
        </section>

        <section>
          <h2 class="text-[15px] font-semibold text-slate-900 mb-2">8. Vos droits</h2>
          <p>Conformément au RGPD, vous disposez des droits suivants :</p>
          <ul class="list-disc pl-5 mt-2 space-y-1">
            <li><strong>Accès et rectification</strong> — via votre espace Profil</li>
            <li><strong>Effacement</strong> — via Paramètres &gt; Supprimer mon compte</li>
            <li><strong>Portabilité</strong> — via Paramètres &gt; Télécharger mes données</li>
            <li><strong>Opposition</strong> — en nous contactant à <strong>[EMAIL DE CONTACT]</strong></li>
          </ul>
          <p class="mt-2">Vous pouvez également introduire une réclamation auprès de la <a href="https://www.cnil.fr" target="_blank" rel="noopener" class="text-slate-900 hover:underline">CNIL</a>.</p>
        </section>

        <section>
          <h2 class="text-[15px] font-semibold text-slate-900 mb-2">9. Sécurité</h2>
          <p>Les mots de passe sont hachés (bcrypt). Les communications sont chiffrées (HTTPS). Les accès sont authentifiés par session sécurisée avec protection CSRF. Les données sont hébergées sur des serveurs sécurisés.</p>
        </section>

        <section>
          <h2 class="text-[15px] font-semibold text-slate-900 mb-2">10. Contact</h2>
          <p>Pour toute question relative à vos données personnelles : <strong>[EMAIL DE CONTACT]</strong></p>
        </section>

      </div>
    </div>
  </DefaultLayout>
</template>

<script setup>
import DefaultLayout from "@/layouts/DefaultLayout.vue";
</script>
