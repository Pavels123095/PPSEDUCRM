---
tags: [ppseducrm, git]
repo: https://github.com/Pavels123095/PPSEDUCRM.git
---

# Git и репозиторий

> [!info] Репозиторий
> [https://github.com/Pavels123095/PPSEDUCRM.git](https://github.com/Pavels123095/PPSEDUCRM.git)

## Правила

| Параметр | Значение |
|----------|----------|
| Remote | `https://github.com/Pavels123095/PPSEDUCRM.git` |
| Локальный путь | `c:\Work\CRM` |
| Структура | Monorepo |
| Ветка по умолчанию | `main` |

## Структура репозитория

```
PPSEDUCRM/
├── backend/          # Laravel 11 API
├── frontend/         # React 19 + Vite + TS
├── desktop/          # Tauri 2
├── docs/             # Obsidian-документация (этот vault)
├── docker-compose.yml
├── .env.example
├── .gitignore
└── README.md
```

## Что не коммитить

- `.env` (корневой, `backend/.env`, `frontend/.env`)
- `backend/vendor/`
- `frontend/node_modules/`, `frontend/dist/`
- `desktop/src-tauri/target/`
- Загруженные файлы договоров (`storage/app/contracts/`)

## Workflow

> [!info] Коммиты
> Каждый завершённый слой (auth, миграции, UI-модуль) — отдельный коммит в `main` или feature-ветка → PR.

## Первый push

```bash
git init
git remote add origin https://github.com/Pavels123095/PPSEDUCRM.git
git add .
git commit -m "scaffold: monorepo PPSEDUCRM"
git push -u origin main
```

## Obsidian vault

Откройте `c:\Work\CRM\docs` как папку vault в Obsidian:

1. **Settings → Vault** → Open folder as vault
2. Или добавьте `docs/` как подпапку существующего vault

## Связанные заметки

- [[00-Главная]]
- [[Этап 01 — Git и Scaffold]]
- [[Стек]]
