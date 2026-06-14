@echo off
cd /d H:\MAMP\htdocs\grant-management

git status
git add .
git status
git commit -m "Auto Sync"
git pull --rebase origin main
git push origin main

pause
