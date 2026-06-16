# Отчёт о восстановлении Mr Prizrak CRM (PPSEDUCRM)

**Дата:** 16 июня 2026  
**Автор и правообладатель:** Mr Prizrak  
**Репозиторий:** https://github.com/Pavels123095/PPSEDUCRM.git  
**Статус:** v1 восстановлена, код размещён в GitHub

---

## 1. Резюме

Восстановлена образовательная CRM для колледжей и техникумов с нуля (исходный код был утерян).  
Продукт: **Mr Prizrak CRM**, кодовое имя **PPSEDUCRM**.

Реализованы все 4 модуля первой версии:
1. Абитуриенты и договоры (менеджеры)
2. Педагоги — расписание, аудитории, учёт часов
3. Личные кабинеты студентов
4. Подготовка интеграции с 1С

---

## 2. Технический стек

| Слой | Технологии |
|------|------------|
| Backend | Laravel 12, PHP 8.3, Sanctum, Spatie Permission, SQLite |
| Frontend | React 19, TypeScript, Vite 8, Tailwind CSS 4, TanStack Query |
| Desktop | Tauri 2, Rust |
| Документация | Obsidian (`docs/`, 20 заметок) |
| DevOps | Docker Compose, `scripts/setup.ps1` |

---

## 3. Структура репозитория

```
PPSEDUCRM/
├── backend/          # Laravel API (112+ файлов)
├── frontend/         # React UI (12+ страниц по ролям)
├── desktop/          # Tauri desktop shell
├── docs/             # Obsidian-документация
├── scripts/          # setup.ps1
├── LEGAL.md          # Патент / правообладатель Mr Prizrak
├── REPORT.md         # Этот отчёт
├── docker-compose.yml
└── README.md
```

---

## 4. Реализованные модули

### 4.1 Менеджеры / абитуриенты
- Воронка статусов: `new` → `contacted` → `contract_draft` → `contract_signed` → `enrolled` / `rejected`
- CRUD абитуриентов, создание и подписание договоров
- Dashboard менеджера со сводкой по воронке
- Валидация СНИЛС (контрольная сумма)
- Аудит-лог критичных действий

### 4.2 Педагоги
- Расписание (слоты: лекция, лабораторная, консультация)
- Аудитории с вместимостью и оборудованием
- Учёт отработанных часов (`work_sessions`)
- Конфликт-детекция аудиторий и педагогов
- Отчёт по часам за период

### 4.3 Студенты
- Личный кабинет: расписание на неделю
- Профиль (группа, курс)
- Уведомления

### 4.4 Интеграция 1С (инфраструктура)
- Поля `external_id`, `sync_status`, `last_synced_at`
- API: webhook, export, import
- Jobs: `SyncApplicantTo1C`, `ImportStudentsFrom1C`
- Таблица `integration_logs`

---

## 5. Роли и доступ (RBAC)

| Роль | Доступ |
|------|--------|
| `admin` | Интеграции 1С, справочники |
| `manager` | Абитуриенты, договоры, dashboard |
| `teacher` | Расписание, аудитории, часы |
| `student` | Личный кабинет, расписание |

Авторизация: Laravel Sanctum (Bearer token).

---

## 6. База данных

- **18 миграций** домена + RBAC + audit + integration
- **13 Eloquent-моделей**
- **11 API-контроллеров**
- SQLite для разработки (MySQL через Docker — опционально)

### Демо-данные (seeders)
| Сущность | Количество |
|----------|------------|
| Абитуриенты | 10 |
| Педагоги | 3 |
| Студенты | 5 |
| Слоты расписания | 20 |
| Аудитории | 3 |

---

## 7. Frontend

- Адаптивный UI: mobile (bottom nav), tablet, desktop (sidebar)
- Русская локализация (`ru.json`)
- Страницы по ролям: manager, teacher, student, admin
- Tauri token storage для desktop

---

## 8. Desktop (Tauri)

- Продукт: **Mr Prizrak CRM**
- Identifier: `ru.mrprizrak.ppseducrm`
- Собранный exe: `desktop/src-tauri/target/release/ppseducrm-desktop.exe`
- Иконка: MR Prizrak CRM

---

## 9. Правовая информация

| Поле | Значение |
|------|----------|
| Правообладатель | **Mr Prizrak** |
| Коммерческое название | Mr Prizrak CRM |
| Кодовое имя | PPSEDUCRM |
| Copyright | © 2026 Mr Prizrak |
| Лицензия | Proprietary |

Подробности: [LEGAL.md](LEGAL.md)

---

## 10. Проверка работоспособности

| Тест | Результат |
|------|-----------|
| `composer install` (Laravel 12) | ✅ |
| `php artisan migrate --seed` | ✅ 18 миграций |
| API `POST /api/auth/login` | ✅ |
| API `GET /api/managers/dashboard` | ✅ 10 абитуриентов |
| API `GET /api/student/schedule` | ✅ 20 слотов |
| `npm run build` (frontend) | ✅ |
| Tauri build (exe) | ✅ (MSI — timeout WiX, exe собран) |
| Git push GitHub | ✅ |

### Демо-аккаунты

| Роль | Email | Пароль |
|------|-------|--------|
| Admin | admin@ppseducrm.local | password |
| Менеджер | manager@ppseducrm.local | password |
| Педагог | teacher1@ppseducrm.local | password |
| Студент | student1@ppseducrm.local | password |

---

## 11. Запуск

```powershell
# Backend
cd backend
php artisan serve

# Frontend
cd frontend
npm run dev

# Или Docker
docker compose up -d --build backend frontend
```

- API: http://localhost:8000/api  
- Web: http://localhost:5173  

---

## 12. История коммитов

| Коммит | Описание |
|--------|----------|
| `48b33dc` | Initial monorepo: Laravel, React, Tauri, Obsidian docs |
| `6e2f9c4` | Docker, setup script, Laravel 12, backend verified |
| текущий | Mr Prizrak branding, LEGAL.md, REPORT.md |

---

## 13. Критерии готовности v1

- [x] Менеджер ведёт абитуриента от «новый» до «договор подписан»
- [x] Педагог видит расписание, аудитории, вносит часы
- [x] Студент видит пары с временем и аудиторией
- [x] Admin видит API интеграции 1С
- [x] Tauri desktop открывает тот же интерфейс
- [x] Русская локализация, валидация СНИЛС
- [x] Код в GitHub PPSEDUCRM
- [x] Брендинг и патент Mr Prizrak

---

## 14. Следующие шаги (рекомендации)

1. Регистрация программы для ЭВМ в Роспатент (номер в LEGAL.md)
2. Настройка реального обмена с 1С (по спецификации заказчика)
3. Электронная подпись договоров (КЭП/Госключ)
4. Деплой на production-сервер
5. Пересборка Tauri MSI-инсталлятора (при наличии WiX)

---

**Mr Prizrak** · Mr Prizrak CRM (PPSEDUCRM) · © 2026
