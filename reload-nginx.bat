@echo off
echo ========================================
echo 重新載入 Nginx 設定檔
echo ========================================
cd /d %~dp0nginx-1.28.1\nginx-1.28.1

echo 測試設定檔語法...
nginx.exe -t
if %errorlevel% neq 0 (
    echo 錯誤：設定檔語法有誤，請檢查！
    pause
    exit /b 1
)

echo 重新載入設定檔...
nginx.exe -s reload

echo.
echo ========================================
echo Nginx 設定檔已重新載入
echo ========================================
pause

