# PPSEDUCRM — образовательная CRM

CRM для колледжей и техникумов: абитуриенты, договоры, педагоги, расписание, личные кабинеты студентов, интеграция с 1С.

**Репозиторий:** https://github.com/Pavels123095/PPSEDUCRM.git

## Структура

```
PPSEDUCRM/
├── backend/     # Laravel 11 API
├── frontend/    # React 19 + Vite + TypeScript
├── desktop/     # Tauri 2 (desktop shell)
├── docs/        # Obsidian-документация
└── docker-compose.yml
```

## Требования

- PHP 8.3+ и Composer (backend)
- Node.js 20+ (frontend, desktop)
- Rust (для сборки Tauri desktop)
- Docker (опционально, MySQL + Redis)

## Быстрый старт

### Backend

```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

API: http://localhost:8000/api

### Frontend

```bash
cd frontend
npm install
cp .env.example .env
npm run dev
```

Web: http://localhost:5173

### Desktop (Tauri)

```bash
cd desktop
npm install
npm run dev
```

## Демо-аккаунты

| Роль | Email | Пароль |
|------|-------|--------|
| Admin | admin@ppseducrm.local | password |
| Менеджер | manager@ppseducrm.local | password |
| Педагог | teacher1@ppseducrm.local | password |
| Студент | student1@ppseducrm.local | password |

## Модули

1. **Менеджеры** — воронка абитуриентов, договоры, подписание
2. **Педагоги** — расписание, аудитории, учёт часов
3. **Студенты** — личный кабинет, расписание пар
4. **1С** — webhook, export/import, external_id (инфраструктура)

## Документация

Obsidian vault: откройте папку `docs/` в Obsidian.

## Лицензия

Proprietary — PPSEDUCRM
