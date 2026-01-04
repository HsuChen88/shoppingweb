@echo off
echo ========================================
echo 啟動所有服務 (PHP-FPM + Nginx)
echo ========================================
echo.

REM 設定 PHP 路徑
set PHP_PATH=C:\php-8.5.1-Win32-vs17-x64

REM 檢查 PHP 是否存在
if not exist "%PHP_PATH%\php-cgi.exe" (
    echo 錯誤：找不到 PHP-CGI！
    echo 請確認 PHP 已安裝在 %PHP_PATH% 目錄
    echo 或修改此批次檔中的 PHP_PATH 變數
    pause
    exit /b 1
)

echo [1/2] 啟動 PHP-FPM...
start "PHP-FPM" "%PHP_PATH%\php-cgi.exe" -b 127.0.0.1:9000 -c "%PHP_PATH%\php.ini"
if %errorlevel% neq 0 (
    echo 警告：PHP-FPM 啟動失敗，請檢查 PHP 設定
) else (
    echo PHP-FPM 已啟動
)

timeout /t 2 /nobreak >nul

echo.
echo [2/2] 啟動 Nginx...
cd /d %~dp0nginx-1.28.1\nginx-1.28.1

echo 測試設定檔語法...
nginx.exe -t
if %errorlevel% neq 0 (
    echo 錯誤：Nginx 設定檔語法有誤，請檢查！
    pause
    exit /b 1
)

start "Nginx Web Server" nginx.exe

timeout /t 1 /nobreak >nul

echo.
echo ========================================
echo 所有服務已啟動！
echo ========================================
echo.
echo PHP-FPM: 127.0.0.1:9000
echo Nginx: http://localhost
echo.
echo 提示：關閉此視窗不會停止服務
echo 要停止服務，請執行 stop-all.bat
echo.
pause

