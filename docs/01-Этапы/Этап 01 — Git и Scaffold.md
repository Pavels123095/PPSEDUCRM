---
tags: [ppseducrm, этап, этап-01, backend, frontend]
предыдущий: —
следующий: "[[Этап 02 — Auth и RBAC]]"
статус: ✅
---

# Этап 01 — Git и Scaffold

> [!info] Цель этапа
> Создать monorepo в [PPSEDUCRM](https://github.com/Pavels123095/PPSEDUCRM.git): `backend/`, `frontend/`, `desktop/`, Docker, README, `.env.example`.

## Задачи

- [x] `git init` в `c:\Work\CRM`, remote `https://github.com/Pavels123095/PPSEDUCRM.git`
- [x] Корневой `.gitignore` — `vendor/`, `node_modules/`, `.env`, `dist/`, `target/`
- [x] `backend/` — Laravel 11 API-only (`composer.json`, структура `app/`, `routes/api.php`)
- [x] `frontend/` — React 19 + TypeScript + Vite
- [x] `desktop/` — заготовка Tauri 2
- [x] `docker-compose.yml` — MySQL 8, Redis, Mailpit
- [x] `.env.example` — шаблон переменных backend + frontend
- [x] `README.md` — описание проекта и запуск

## Структура monorepo

```
PPSEDUCRM/
├── backend/          # Laravel API
├── frontend/         # React + Vite
├── desktop/          # Tauri 2
├── docker-compose.yml
├── .env.example
└── README.md
```

## Критерий завершения

> [!success] Готово, когда
> `docker-compose up` поднимает MySQL и Redis; `backend/` и `frontend/` запускаются локально; репозиторий готов к push в `main`.

## Связанные заметки

- [[Git и репозиторий]]
- [[Стек]]
- [[Этап 02 — Auth и RBAC]]
