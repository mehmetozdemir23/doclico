# Doclico

> SaaS de génération de documents professionnels

[![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=flat-square&logo=laravel)](https://laravel.com)
[![Vue.js](https://img.shields.io/badge/Vue.js-3-4FC08D?style=flat-square&logo=vue.js)](https://vuejs.org)
[![PHP](https://img.shields.io/badge/PHP-8.4-777BB4?style=flat-square&logo=php)](https://php.net)

Génération de documents professionnels (CV, lettres, factures, contrats) via templates personnalisables.

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

**Backend** : PHP 8.4 • Laravel 12 • MySQL • Pest<br>
**Frontend** : Vue 3 • Tailwind CSS • Vite<br>
**Architecture** : DDD / Clean Architecture

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

MIT
