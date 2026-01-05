@echo off
echo ========================================
echo 停止 Nginx Web Server
echo ========================================
cd /d %~dp0nginx-1.28.1\nginx-1.28.1

echo 正在停止 Nginx...
nginx.exe -s quit

timeout /t 2 /nobreak >nul

echo.
echo ========================================
echo Nginx 已停止
echo ========================================
pause

