@echo off
echo ========================================
echo 啟動 Nginx Web Server
echo ========================================
cd /d %~dp0nginx-1.28.1\nginx-1.28.1

echo 測試設定檔語法...
nginx.exe -t
if %errorlevel% neq 0 (
    echo 錯誤：設定檔語法有誤，請檢查！
    pause
    exit /b 1
)

echo 啟動 Nginx...
start "Nginx Web Server" nginx.exe

timeout /t 1 /nobreak >nul

echo.
echo ========================================
echo Nginx 已啟動！
echo 請在瀏覽器中訪問: http://localhost
echo ========================================
echo.
echo 提示：關閉此視窗不會停止 Nginx
echo 要停止 Nginx，請執行 stop-nginx.bat
echo.
pause

