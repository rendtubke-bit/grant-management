@echo off
title Grant Management Full Auto Sync

cd /d H:\MAMP\htdocs\grant-management

echo ========================================
echo   Grant Management Full Auto Sync
echo ========================================
echo   Syncing every 60 seconds...
echo   Press Ctrl + C to stop
echo ========================================

:loop

echo.
echo [%date% %time%] Syncing...

git pull origin main --no-edit

git add .

git diff --cached --quiet
if errorlevel 1 (
    git commit -m "Auto Sync %date% %time%"
)

git push origin main

timeout /t 60 /nobreak >nul

goto loop

