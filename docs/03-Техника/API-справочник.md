---
tags: [ppseducrm, backend, api]
---

# API-справочник

> [!info] Базовый URL
> `http://localhost:8000/api` (см. `VITE_API_URL` в `.env.example`)

Все эндпоинты (кроме login) требуют `Authorization: Bearer {token}` или Sanctum cookie.

---

## Auth

| Метод | Путь | Auth | Описание |
|-------|------|------|----------|
| POST | `/auth/login` | — | Вход (email + password) |
| POST | `/auth/logout` | sanctum | Выход |
| GET | `/auth/me` | sanctum | Текущий пользователь и роль |

---

## Справочники

| Метод | Путь | Роль | Описание |
|-------|------|------|----------|
| GET | `/classrooms` | * | Список аудиторий |
| GET | `/teachers-list` | * | Список педагогов |
| GET | `/groups` | * | Учебные группы |

---

## Менеджеры

Роль: `admin` | `manager`

| Метод | Путь | Описание |
|-------|------|----------|
| GET | `/applicants` | Список (фильтры: status, manager_id) |
| POST | `/applicants` | Создание абитуриента |
| GET | `/applicants/{id}` | Карточка |
| PUT/PATCH | `/applicants/{id}` | Обновление |
| DELETE | `/applicants/{id}` | Удаление |
| PATCH | `/applicants/{id}/status` | Смена статуса воронки |
| POST | `/applicants/{id}/contracts` | Создание договора |
| GET | `/contracts/{id}` | Просмотр договора |
| POST | `/contracts/{id}/sign` | Подписание (файл + дата) |
| GET | `/managers/dashboard` | Сводка по воронке |

### Статусы воронки

`new` · `contacted` · `contract_draft` · `contract_signed` · `enrolled` · `rejected`

---

## Педагоги

Роль: `admin` | `manager` | `teacher`

| Метод | Путь | Описание |
|-------|------|----------|
| GET/POST | `/schedule-slots` | CRUD расписания |
| GET/PUT/PATCH/DELETE | `/schedule-slots/{id}` | Операции со слотом |
| POST | `/classrooms` | Создание аудитории |
| GET | `/classrooms/availability` | Свободные слоты |
| GET/POST | `/work-sessions` | Учёт часов |
| GET | `/teachers` | Список педагогов |
| GET | `/teachers/{id}` | Профиль педагога |
| GET | `/teachers/{id}/hours-report` | Отчёт за период |

### Типы занятий

`lecture` · `lab` · `consultation`

---

## Студенты

Роль: `student`

| Метод | Путь | Описание |
|-------|------|----------|
| GET | `/student/schedule` | Расписание (day/week) |
| GET | `/student/profile` | Профиль студента |
| GET | `/student/notifications` | Уведомления |

---

## Интеграция 1С

Роль: `admin`

| Метод | Путь | Описание |
|-------|------|----------|
| POST | `/integrations/1c/webhook` | Webhook (подпись в заголовке) |
| GET | `/integrations/1c/export/{entity}` | Export JSON (`applicants`, `students`, …) |
| POST | `/integrations/1c/import` | Import пакета JSON |

---

## Связанные заметки

- [[Модуль — Абитуриенты и договоры]]
- [[Модуль — Педагоги]]
- [[Модуль — Студенты]]
- [[Модуль — Интеграция 1С]]
- `backend/routes/api.php`
