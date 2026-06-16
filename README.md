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

### Вариант A: Docker (рекомендуется, если нет PHP локально)

```bash
docker compose up -d --build backend frontend
```

- API: http://localhost:8000/api  
- Web: http://localhost:8080  

### Вариант B: Windows setup-скрипт

```powershell
.\scripts\setup.ps1
```

### Вариант C: Вручную

#### Backend

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

Собранный exe: `desktop\src-tauri\target\release\ppseducrm-desktop.exe`

```bash
cd desktop
npm install
npm run dev    # разработка
npm run build  # сборка exe
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
