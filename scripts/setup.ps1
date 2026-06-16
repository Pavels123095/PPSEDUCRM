#Requires -Version 5.1
<#
.SYNOPSIS
  Локальная настройка PPSEDUCRM на Windows (без Docker).
#>
param(
    [switch]$SkipBackend
)

$ErrorActionPreference = "Stop"
$Root = Split-Path -Parent $MyInvocation.MyCommand.Path

Write-Host "=== Mr Prizrak CRM (PPSEDUCRM) Setup ===" -ForegroundColor Cyan
Write-Host "Author / Patent holder: Mr Prizrak" -ForegroundColor DarkGray

# Frontend
Write-Host "`n[1/3] Frontend..." -ForegroundColor Yellow
Push-Location "$Root\frontend"
if (-not (Test-Path node_modules)) { npm install }
if (-not (Test-Path .env)) { Copy-Item .env.example .env }
Pop-Location
Write-Host "OK: frontend ready (npm run dev)" -ForegroundColor Green

# Desktop
Write-Host "`n[2/3] Desktop..." -ForegroundColor Yellow
Push-Location "$Root\desktop"
if (-not (Test-Path node_modules)) { npm install }
Pop-Location
Write-Host "OK: desktop ready (npm run dev)" -ForegroundColor Green

# Backend
if (-not $SkipBackend) {
    Write-Host "`n[3/3] Backend..." -ForegroundColor Yellow
    $php = Get-Command php -ErrorAction SilentlyContinue
    $composer = Get-Command composer -ErrorAction SilentlyContinue
    if (-not $php -or -not $composer) {
        Write-Host "PHP/Composer не найдены." -ForegroundColor Red
        Write-Host "Варианты:" -ForegroundColor Yellow
        Write-Host "  1. Установите PHP 8.3 + Composer: https://windows.php.net/download/"
        Write-Host "  2. Или запустите через Docker: docker compose up -d --build backend frontend"
        Write-Host "  3. Или: winget install PHP.PHP.8.3"
    } else {
        Push-Location "$Root\backend"
        if (-not (Test-Path vendor)) { composer install }
        if (-not (Test-Path .env)) { Copy-Item .env.example .env }
        if (-not (Test-Path database\database.sqlite)) { New-Item database\database.sqlite -ItemType File | Out-Null }
        php artisan key:generate --force
        php artisan migrate --seed --force
        Pop-Location
        Write-Host "OK: backend ready (php artisan serve)" -ForegroundColor Green
    }
}

Write-Host "`n=== Демо-логины ===" -ForegroundColor Cyan
Write-Host "manager@ppseducrm.local / password"
Write-Host "teacher1@ppseducrm.local / password"
Write-Host "student1@ppseducrm.local / password"
Write-Host "admin@ppseducrm.local / password"
