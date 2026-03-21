# Doclico — API

Backend Laravel de l'application Doclico.

## Stack

- Laravel 13, MySQL
- Laravel Sanctum (auth token)
- DomPDF (génération PDF)
- Pest (tests d'intégration)
- Resend (emails)

## Installation

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

## Tests

```bash
php artisan test
```

## Architecture

```
app/
├── Domain/          # Entités, value objects, interfaces de repository
├── Application/     # Use cases
├── Infrastructure/  # Eloquent, services tiers
└── Http/            # Controllers, Form Requests
```
