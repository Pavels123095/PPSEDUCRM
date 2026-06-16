---
tags: [ppseducrm, этап, этап-02, backend, rf-standards]
предыдущий: "[[Этап 01 — Git и Scaffold]]"
следующий: "[[Этап 03 — Доменная модель]]"
статус: ✅
---

# Этап 02 — Auth и RBAC

> [!info] Цель этапа
> Настроить Laravel Sanctum + Spatie Permission: роли, login API, seed пользователей.

## Задачи

- [x] Установить `laravel/sanctum` и `spatie/laravel-permission`
- [x] Роли: `admin`, `manager`, `teacher`, `student`
- [x] `POST /api/auth/login` — аутентификация (cookie SPA + token)
- [x] `POST /api/auth/logout`, `GET /api/auth/me`
- [x] Middleware `role:*` на API-группах
- [x] `RoleSeeder` + демо-пользователи по ролям
- [ ] Frontend: страница login, `ProtectedRoute` по роли

## Роли и доступ

| Роль | Доступ |
|------|--------|
| `admin` | Пользователи, справочники, интеграции, отчёты |
| `manager` | Абитуриенты, воронка, договоры |
| `teacher` | Расписание, аудитории, учёт часов |
| `student` | Личный кабинет: расписание, профиль, уведомления |

## Критерий завершения

> [!success] Готово, когда
> Backend: login возвращает токен/сессию, `GET /api/auth/me` отдаёт роль пользователя; seed создаёт по одному пользователю каждой роли.

## Связанные файлы

- `backend/app/Http/Controllers/Api/AuthController.php`
- `backend/database/seeders/RoleSeeder.php`
- `backend/routes/api.php`

## Связанные заметки

- [[API-справочник#Auth]]
- [[Этап 03 — Доменная модель]]
