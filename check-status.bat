@echo off
echo ========================================
echo 檢查服務狀態
echo ========================================
echo.

echo [檢查 Nginx]
tasklist /FI "IMAGENAME eq nginx.exe" 2>NUL | find /I /N "nginx.exe">NUL
if %errorlevel% equ 0 (
    echo ✓ Nginx 正在執行
    netstat -an | findstr ":80" | findstr "LISTENING"
) else (
    echo ✗ Nginx 未執行
)

echo.
echo [檢查 PHP-FPM]
tasklist /FI "IMAGENAME eq php-cgi.exe" 2>NUL | find /I /N "php-cgi.exe">NUL
if %errorlevel% equ 0 (
    echo ✓ PHP-FPM 正在執行
    netstat -an | findstr ":9000" | findstr "LISTENING"
) else (
    echo ✗ PHP-FPM 未執行
)

echo.
echo [檢查連接埠]
echo 連接埠 80 (Nginx):
netstat -an | findstr ":80" | findstr "LISTENING"
echo.
echo 連接埠 9000 (PHP-FPM):
netstat -an | findstr ":9000" | findstr "LISTENING"

echo.
echo ========================================
pause

