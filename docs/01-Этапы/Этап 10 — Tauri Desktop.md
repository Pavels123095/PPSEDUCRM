---
tags: [ppseducrm, этап, этап-10, tauri, frontend, desktop]
предыдущий: "[[Этап 09 — Адаптивный UI]]"
следующий: —
статус: ⬜
---

# Этап 10 — Tauri Desktop

> [!todo] Цель этапа
> Desktop shell на Tauri 2: загрузка `frontend/dist`, хранение токена, сборка.

## Задачи

- [ ] Проект `desktop/` — Tauri 2, загрузка `frontend/dist/index.html`
- [ ] `VITE_API_URL` — тот же API base URL, что и в web
- [ ] Sanctum token в secure storage Tauri
- [ ] Сборка: `pnpm build` (frontend) → `tauri build`
- [ ] Smoke-тест: desktop открывает тот же интерфейс, что браузер

## Сборка

```bash
cd frontend && pnpm build
cd ../desktop && pnpm tauri build
```

## Справочный паттерн

> [!info] Образец monorepo + Tauri
> См. `c:\Work\roirc-tauri` — другой домен, но полезен как reference для структуры `desktop/`.

## Критерий завершения

> [!success] Готово, когда
> Tauri desktop открывает тот же интерфейс, что и браузер; авторизация сохраняется между сессиями.

## Связанные файлы

- `desktop/src-tauri/`
- `desktop/package.json`
- `frontend/dist/`

## Связанные заметки

- [[Стек#Desktop]]
- [[Архитектура#Клиенты]]
- [[Критерии готовности v1]]
