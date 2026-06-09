# deploy-vercel.ps1 — Prepare and deploy this Laravel app to Vercel
#
# Requirements:
#   npm install -g vercel      (Vercel CLI)
#   vercel login               (authenticate once)
#
# Usage:
#   .\deploy-vercel.ps1              # preview deployment
#   .\deploy-vercel.ps1 --prod       # production deployment

param([switch]$prod)

Write-Host ""
Write-Host "=== Laravel → Vercel Deploy ===" -ForegroundColor Cyan

# 1. Install production-only Composer dependencies
Write-Host ""
Write-Host "[1/4] Installing production Composer dependencies..." -ForegroundColor Yellow
composer install --no-dev --optimize-autoloader
if ($LASTEXITCODE -ne 0) { Write-Host "composer install failed" -ForegroundColor Red; exit 1 }

# 2. Generate APP_KEY if .env exists but key is empty
if (Test-Path ".env") {
    $envContent = Get-Content ".env" -Raw
    if ($envContent -match "APP_KEY=\s*$" -or $envContent -match "APP_KEY=$") {
        Write-Host ""
        Write-Host "[2/4] Generating APP_KEY..." -ForegroundColor Yellow
        php artisan key:generate
    } else {
        Write-Host "[2/4] APP_KEY already set — skipping." -ForegroundColor Green
    }
} else {
    Write-Host "[2/4] No .env file found — remember to set APP_KEY in Vercel environment variables." -ForegroundColor Yellow
}

# 3. Clear local caches (avoid deploying stale local paths)
Write-Host ""
Write-Host "[3/4] Clearing cached files..." -ForegroundColor Yellow
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 4. Deploy to Vercel
Write-Host ""
Write-Host "[4/4] Deploying to Vercel..." -ForegroundColor Yellow
if ($prod) {
    Write-Host "  → PRODUCTION deployment" -ForegroundColor Magenta
    vercel --prod
} else {
    Write-Host "  → Preview deployment (use --prod flag for production)" -ForegroundColor Gray
    vercel
}

Write-Host ""
Write-Host "Done!" -ForegroundColor Green
