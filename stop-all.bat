@echo off
echo ========================================
echo 停止所有服務 (PHP-FPM + Nginx)
echo ========================================
echo.

echo [1/2] 停止 PHP-FPM...
taskkill /F /IM php-cgi.exe >nul 2>&1
if %errorlevel% equ 0 (
    echo PHP-FPM 已停止
) else (
    echo PHP-FPM 未執行或已停止
)

echo.
echo [2/2] 停止 Nginx...
cd /d %~dp0nginx-1.28.1\nginx-1.28.1
nginx.exe -s quit >nul 2>&1

timeout /t 2 /nobreak >nul

echo.
echo ========================================
echo 所有服務已停止
echo ========================================
pause

