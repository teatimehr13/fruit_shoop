#!/bin/bash
# 正式站部署腳本。在專案根目錄執行：./deploy.sh
# git pull 之後該做的事全部串在一起，避免手動操作漏步驟。
set -e

echo "==> Pulling latest code"
git pull

echo "==> Installing PHP dependencies"
composer install --no-dev --optimize-autoloader

echo "==> Installing & building frontend assets"
npm ci
npm run build

echo "==> Running migrations"
php artisan migrate --force

echo "==> Rebuilding cache"
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "==> Deploy complete."
