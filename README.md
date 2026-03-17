# Doclico

> SaaS de gestion de documents professionnels

[![Laravel](https://img.shields.io/badge/Laravel-13-FF2D20?style=flat-square&logo=laravel)](https://laravel.com)
[![Vue.js](https://img.shields.io/badge/Vue.js-3-4FC08D?style=flat-square&logo=vue.js)](https://vuejs.org)
[![PHP](https://img.shields.io/badge/PHP-8.4+-777BB4?style=flat-square&logo=php)](https://php.net)

Création et gestion de documents professionnels — factures, devis, avoirs, notes de frais et plus — avec génération PDF, répertoire clients et partage sécurisé.

## Fonctionnalités

- Génération PDF à la demande avec prévisualisation inline
- Répertoire clients avec pré-remplissage automatique des documents
- Numérotation séquentielle par type (FAC-2026-001, DEV-2026-001…)
- Partage par lien avec tracking vues / téléchargements et expiration configurable
- Rappels automatiques pour les documents partagés non consultés
- Authentification email/mot de passe et Google OAuth
- Export des données et suppression de compte

## Screenshots

<table>
  <tr>
    <td align="center">
      <img src="docs/screenshots/templates.png" alt="Catalogue de templates" width="400"/>
      <br/><strong>Catalogue de templates</strong>
    </td>
    <td align="center">
      <img src="docs/screenshots/editor.png" alt="Éditeur de document" width="400"/>
      <br/><strong>Éditeur de document</strong>
    </td>
  </tr>
  <tr>
    <td align="center">
      <img src="docs/screenshots/share.png" alt="Partage de document" width="400"/>
      <br/><strong>Partage de document</strong>
    </td>
    <td align="center">
      <img src="docs/screenshots/dashboard.png" alt="Mes documents" width="400"/>
      <br/><strong>Mes documents</strong>
    </td>
  </tr>
</table>

## Stack

**Backend** : PHP 8.4+ • Laravel 13 • MySQL • Pest
**Frontend** : Vue 3 • Pinia • Tailwind CSS • Vite
**Architecture** : Domain-Driven Design

## Installation

```bash
# Backend
cd api && composer install
cp .env.example .env && php artisan key:generate
php artisan migrate --seed && php artisan serve

# Frontend
cd web && npm install && npm run dev
```

## Licence

Propriétaire — tous droits réservés.
