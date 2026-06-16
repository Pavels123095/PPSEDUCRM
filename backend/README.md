# PPSEDUCRM Backend (Laravel 11 API)

Образовательная CRM — API-only backend на Laravel 11.

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

API доступен по адресу `http://localhost:8000/api`.

## Демо-пользователи (пароль: `password`)

| Роль | Email |
|------|-------|
| admin | admin@ppseducrm.local |
| manager | manager@ppseducrm.local |
| teacher | teacher1@ppseducrm.local |
| student | student1@ppseducrm.local |

## Основные эндпоинты

- `POST /api/auth/login` — вход (Sanctum token)
- `GET /api/auth/me` — текущий пользователь
- `GET /api/applicants` — абитуриенты (manager/admin)
- `GET /api/schedule-slots` — расписание (teacher/manager/admin)
- `GET /api/student/schedule` — расписание студента
- `POST /api/integrations/1c/webhook` — webhook 1С (admin)

## Локализация

- Лocale: `ru`
- Timezone: `Europe/Moscow`
- Валидация СНИЛС: `App\Rules\SnilsRule`
