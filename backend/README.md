# Mr Prizrak CRM — Backend API (PPSEDUCRM)

**Автор и правообладатель:** Mr Prizrak  
**Патент / ИС:** © 2026 Mr Prizrak — см. [../LEGAL.md](../LEGAL.md)

Образовательная CRM — API-only backend на Laravel 12.

## Требования

- PHP 8.2+
- Composer 2.x
- SQLite (по умолчанию для разработки) или MySQL 8

## Установка

```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

## Демо-аккаунты

| Роль | Email | Пароль |
|------|-------|--------|
| admin | admin@ppseducrm.local | password |
| manager | manager@ppseducrm.local | password |
| teacher | teacher1@ppseducrm.local | password |
| student | student1@ppseducrm.local | password |

## API

Базовый URL: `http://localhost:8000/api`

Авторизация: Bearer token (Sanctum).
