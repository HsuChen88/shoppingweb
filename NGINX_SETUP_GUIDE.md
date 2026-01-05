# Nginx 設定與啟用完整指南（Windows 系統）

## 📚 目錄
1. [Nginx 是什麼？](#nginx-是什麼)
2. [在此專案中使用 Nginx 的好處](#在此專案中使用-nginx-的好處)
3. [專案中的 Nginx 設定](#專案中的-nginx-設定)
4. [設定 PHP-FPM](#設定-php-fpm)
5. [啟動 Nginx](#啟動-nginx)
6. [驗證與測試](#驗證與測試)
7. [常見問題排除](#常見問題排除)

---

## Nginx 是什麼？

**Nginx**（發音為 "engine-x"）是一個高效能的 **Web Server** 和 **Reverse Proxy Server**，主要用途包括：

### 核心功能
1. **Web Server**：直接處理 HTTP/HTTPS 請求，提供靜態檔案服務
2. **Reverse Proxy**：作為應用程式伺服器的前端，轉發請求到後端（如 PHP-FPM）
3. **Load Balancer**：在多個後端伺服器間分配負載
4. **SSL/TLS Terminator**：處理 HTTPS 加密連線

### 為什麼選擇 Nginx？
- ⚡ **高效能**：事件驅動架構，可處理大量並發連線
- 💾 **低記憶體使用**：比 Apache 更節省資源
- 🔒 **安全性**：內建安全功能，易於配置防火牆規則
- 📈 **可擴展性**：適合高流量網站

---

## 在此專案中使用 Nginx 的好處

### 1. **效能提升**
- ✅ **靜態資源快取**：CSS、JS、圖片等可直接由 Nginx 提供，無需經過 PHP
- ✅ **減少 PHP 處理負擔**：靜態檔案不經過 PHP-FPM，提升整體效能
- ✅ **Gzip 壓縮**：已啟用自動壓縮，減少傳輸量

### 2. **安全性增強**
- ✅ **目錄保護**：禁止訪問敏感目錄（`app/`、`config/`、`vendor/`）
- ✅ **檔案保護**：禁止訪問 `.env`、`.json`、`.lock` 等敏感檔案
- ✅ **隱藏檔案保護**：禁止訪問以 `.` 開頭的隱藏檔案

### 3. **專業部署**
- ✅ **生產環境標準**：大多數 PHP 專案在生產環境使用 Nginx
- ✅ **易於擴展**：未來可輕鬆加入 Load Balancing、CDN 整合
- ✅ **SSL/HTTPS 支援**：易於設定 HTTPS 憑證

### 4. **開發體驗**
- ✅ **更接近生產環境**：開發環境與生產環境一致
- ✅ **除錯方便**：清晰的錯誤日誌和存取日誌

---

## 專案中的 Nginx 設定

### 專案結構

您的專案中已包含 Nginx，結構如下：
```
shoppingweb/
├── nginx-1.28.1/
│   └── nginx-1.28.1/
│       ├── conf/
│       │   ├── nginx.conf          # 主設定檔（已設定好）
│       │   └── shoppingweb.conf    # 專案設定檔（已設定好）
│       ├── logs/                   # 日誌目錄
│       └── nginx.exe               # Nginx 執行檔
└── public/                          # Document Root
```

### 已完成的設定

✅ **主設定檔** (`nginx-1.28.1/nginx-1.28.1/conf/nginx.conf`)
- 已啟用 Gzip 壓縮
- 已引入專案設定檔 `shoppingweb.conf`
- 預設 server 區塊已註解

✅ **專案設定檔** (`nginx-1.28.1/nginx-1.28.1/conf/shoppingweb.conf`)
- Document Root 指向：`C:/Users/ivonn/SideProjects/shoppingweb/public`
- 監聽連接埠：80
- 已設定 PHP-FPM 連線（127.0.0.1:9000）
- 已設定靜態資源快取
- 已設定安全性規則（保護敏感目錄和檔案）

### 設定檔內容說明

**專案設定檔主要設定：**
- `root`: 專案的 `public/` 目錄路徑
- `fastcgi_pass`: PHP-FPM 連線位址（Windows 使用 TCP: 127.0.0.1:9000）
- 靜態資源快取：CSS、JS、圖片等快取 1 年
- 安全性：禁止訪問 `app/`、`config/`、`vendor/` 等目錄

---

## 設定 PHP-FPM

### 步驟 1：下載並安裝 PHP

1. **下載 PHP**
   - 前往 [PHP Windows 下載頁面](https://windows.php.net/download/)
   - 下載 **Thread Safe** 版本的 PHP（例如：PHP 8.x）
   - 解壓縮到您偏好的位置（例如：`C:\php-8.5.1-Win32-vs17-x64`）

2. **設定 PHP**
   編輯 PHP 目錄下的 `php.ini`（如果不存在，複製 `php.ini-development` 並重新命名）：
   ```ini
   # 啟用必要的擴充功能
   extension_dir = "ext"
   extension=mysqli
   extension=pdo_sqlite
   extension=sqlite3
   
   # 設定時區
   date.timezone = "Asia/Taipei"
   ```

### 步驟 2：啟動 PHP-FPM

#### 方法 1：使用 PHP 內建 CGI（簡單方式）

在 PowerShell 中執行（以管理員身份）：
```powershell
# 切換到 PHP 目錄（請修改為您的實際路徑）
cd C:\php-8.5.1-Win32-vs17-x64

# 啟動 PHP-CGI（監聽 9000 port）
.\php-cgi.exe -b 127.0.0.1:9000 -c php.ini
```

**⚠️ 注意**：此方式需要保持 PowerShell 視窗開啟，關閉視窗會停止 PHP-CGI。

#### 方法 2：使用 RunHiddenConsole（背景執行）

1. 下載 [RunHiddenConsole.exe](https://redmine.lighttpd.net/attachments/660/RunHiddenConsole.zip)
2. 建立啟動腳本 `start-php-fpm.bat`：
   ```batch
   @echo off
   cd /d C:\php-8.5.1-Win32-vs17-x64
   RunHiddenConsole.exe php-cgi.exe -b 127.0.0.1:9000 -c php.ini
   ```
3. 執行此批次檔即可在背景啟動 PHP-CGI

#### 方法 3：使用 NSSM 安裝為 Windows 服務（推薦）

1. 下載 [NSSM](https://nssm.cc/download)
2. 解壓縮並執行以下命令（請修改為您的實際 PHP 路徑）：
   ```powershell
   # 安裝 PHP-CGI 為服務
   nssm install PHP-FPM "C:\php-8.5.1-Win32-vs17-x64\php-cgi.exe"
   nssm set PHP-FPM AppParameters "-b 127.0.0.1:9000 -c C:\php-8.5.1-Win32-vs17-x64\php.ini"
   nssm set PHP-FPM AppDirectory "C:\php-8.5.1-Win32-vs17-x64"
   nssm set PHP-FPM DisplayName "PHP-FPM"
   
   # 啟動服務
   nssm start PHP-FPM
   ```

### 驗證 PHP-FPM 是否執行

在 PowerShell 中執行：
```powershell
# 檢查連接埠 9000 是否被佔用
netstat -an | findstr :9000
```

如果看到 `127.0.0.1:9000` 的 LISTENING 狀態，表示 PHP-FPM 已成功啟動。

---

## 啟動 Nginx

### 步驟 1：測試設定檔語法

在 PowerShell 中，切換到 Nginx 目錄並執行：
```powershell
cd C:\Users\ivonn\SideProjects\shoppingweb\nginx-1.28.1\nginx-1.28.1

# 測試設定檔語法
.\nginx.exe -t
```

如果看到 `nginx: configuration file ... test is successful`，表示設定檔正確。

### 步驟 2：啟動 Nginx

```powershell
# 以管理員身份執行 PowerShell，然後執行：
.\nginx.exe
```

### 步驟 3：檢查 Nginx 是否執行

```powershell
# 檢查程序
Get-Process nginx

# 檢查連接埠 80
netstat -an | findstr :80
```

如果看到 `0.0.0.0:80` 的 LISTENING 狀態，表示 Nginx 已成功啟動。

### 常用命令

```powershell
# 停止 Nginx
.\nginx.exe -s stop

# 優雅停止（等待處理完現有請求）
.\nginx.exe -s quit

# 重新載入設定檔（不中斷服務）
.\nginx.exe -s reload

# 重新開啟日誌檔案
.\nginx.exe -s reopen
```

### 設定為 Windows 服務（選用）

如果您希望 Nginx 開機自動啟動，可以使用 NSSM：

```powershell
# 下載並解壓縮 NSSM
# 執行以下命令
nssm install nginx "C:\Users\ivonn\SideProjects\shoppingweb\nginx-1.28.1\nginx-1.28.1\nginx.exe"
nssm set nginx AppDirectory "C:\Users\ivonn\SideProjects\shoppingweb\nginx-1.28.1\nginx-1.28.1"
nssm set nginx DisplayName "Nginx Web Server"
nssm start nginx
```

---

## 驗證與測試

### 1. 檢查服務狀態

```powershell
# 檢查 Nginx 程序
Get-Process nginx

# 檢查 PHP-CGI 程序
Get-Process php-cgi

# 檢查連接埠
netstat -an | findstr ":80 :9000"
```

### 2. 測試網站存取

在瀏覽器中開啟：
```
http://localhost
```

如果看到您的購物網站首頁，表示設定成功！

### 3. 檢查日誌

```powershell
# 查看存取日誌
Get-Content nginx-1.28.1\nginx-1.28.1\logs\shoppingweb_access.log -Tail 20

# 查看錯誤日誌
Get-Content nginx-1.28.1\nginx-1.28.1\logs\shoppingweb_error.log -Tail 20

# 即時監控日誌（按 Ctrl+C 停止）
Get-Content nginx-1.28.1\nginx-1.28.1\logs\shoppingweb_error.log -Wait -Tail 10
```

### 4. 測試 PHP 處理

建立測試檔案 `public/test.php`：
```php
<?php
phpinfo();
```

訪問 `http://localhost/test.php`，應該會看到 PHP 資訊頁面。

**⚠️ 測試完成後請刪除此檔案，避免洩露系統資訊！**

---

## 常見問題排除

### 問題 1：502 Bad Gateway

**原因**：PHP-FPM 未執行或連線設定錯誤

**解決方法**：
1. 確認 PHP-CGI 正在執行
   ```powershell
   Get-Process php-cgi
   ```
   如果沒有程序，請重新啟動 PHP-CGI：
   ```powershell
   cd C:\php-8.5.1-Win32-vs17-x64
   .\php-cgi.exe -b 127.0.0.1:9000 -c php.ini
   ```

2. 檢查連接埠 9000 是否被佔用
   ```powershell
   netstat -an | findstr :9000
   ```

3. 確認 Nginx 設定檔中的 `fastcgi_pass` 設定為 `127.0.0.1:9000`

### 問題 2：403 Forbidden

**原因**：檔案權限問題或路徑錯誤

**解決方法**：
1. 確認 `root` 路徑正確（應指向 `public/` 目錄）
2. 檢查檔案是否存在
3. 確認 Windows 使用者有讀取權限

### 問題 3：404 Not Found

**原因**：路由設定錯誤或檔案不存在

**解決方法**：
1. 確認 `public/index.php` 是否存在
2. 檢查 Nginx 設定檔中的 `try_files` 設定
3. 查看錯誤日誌找出具體問題：
   ```powershell
   Get-Content nginx-1.28.1\nginx-1.28.1\logs\shoppingweb_error.log -Tail 50
   ```

### 問題 4：靜態資源無法載入

**原因**：路徑設定錯誤或快取問題

**解決方法**：
1. 確認靜態資源位於 `public/assets/` 目錄
2. 清除瀏覽器快取（按 Ctrl+F5）
3. 檢查瀏覽器開發者工具的 Network 標籤，查看資源載入狀態

### 問題 5：無法訪問網站（連接被拒絕）

**原因**：防火牆阻擋或 Nginx 未啟動

**解決方法**：
1. 檢查 Nginx 是否執行
   ```powershell
   Get-Process nginx
   ```
   如果沒有，請啟動：
   ```powershell
   cd nginx-1.28.1\nginx-1.28.1
   .\nginx.exe
   ```

2. 檢查 Windows 防火牆設定
   - 開啟「Windows Defender 防火牆」
   - 允許 Nginx 通過防火牆

3. 確認連接埠 80 未被其他程式佔用
   ```powershell
   netstat -ano | findstr :80
   ```
   如果被佔用，可以：
   - 停止佔用連接埠的程式
   - 或修改 Nginx 監聽其他連接埠（例如 8080）

### 問題 6：Nginx 啟動失敗

**原因**：設定檔語法錯誤或連接埠被佔用

**解決方法**：
1. 測試設定檔語法
   ```powershell
   .\nginx.exe -t
   ```
   根據錯誤訊息修正設定檔

2. 檢查連接埠是否被佔用
   ```powershell
   netstat -ano | findstr :80
   ```

3. 查看錯誤日誌
   ```powershell
   Get-Content logs\error.log -Tail 20
   ```

---

## 快速啟動腳本

為了方便使用，您可以建立以下批次檔：

### start-nginx.bat
```batch
@echo off
echo 啟動 Nginx...
cd /d %~dp0nginx-1.28.1\nginx-1.28.1
start "Nginx" nginx.exe
echo Nginx 已啟動
pause
```

### stop-nginx.bat
```batch
@echo off
echo 停止 Nginx...
cd /d %~dp0nginx-1.28.1\nginx-1.28.1
nginx.exe -s stop
echo Nginx 已停止
pause
```

### start-all.bat（同時啟動 PHP-FPM 和 Nginx）
```batch
@echo off
echo 啟動 PHP-FPM...
start "PHP-FPM" C:\php-8.5.1-Win32-vs17-x64\php-cgi.exe -b 127.0.0.1:9000 -c C:\php-8.5.1-Win32-vs17-x64\php.ini

timeout /t 2 /nobreak >nul

echo 啟動 Nginx...
cd /d %~dp0nginx-1.28.1\nginx-1.28.1
start "Nginx" nginx.exe

echo 所有服務已啟動
echo 請在瀏覽器中訪問 http://localhost
pause
```

---

## 進階設定（選用）

### 修改監聽連接埠

如果連接埠 80 被佔用，可以修改 `shoppingweb.conf`：
```nginx
server {
    listen 8080;  # 改為其他連接埠
    server_name localhost;
    # ...
}
```

然後訪問 `http://localhost:8080`

### 設定 HTTPS（生產環境）

1. **取得 SSL 憑證**（可使用 Let's Encrypt 或自簽憑證）
2. **修改設定檔**，在 `shoppingweb.conf` 中加入 HTTPS server 區塊：

```nginx
# HTTPS server
server {
    listen 443 ssl http2;
    server_name localhost;
    root C:/Users/ivonn/SideProjects/shoppingweb/public;
    index index.php;

    ssl_certificate C:/path/to/certificate.crt;
    ssl_certificate_key C:/path/to/private.key;

    # SSL 設定
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;

    # 其他設定與 HTTP 相同
    # ...
}

# HTTP 重定向到 HTTPS
server {
    listen 80;
    server_name localhost;
    return 301 https://$server_name$request_uri;
}
```

---

## 總結

完成以上步驟後，您的專案應該已經成功使用 Nginx 作為 Web Server。主要優勢包括：

✅ **效能提升**：靜態資源直接由 Nginx 提供  
✅ **安全性增強**：保護敏感檔案和目錄  
✅ **專業部署**：符合生產環境標準  
✅ **易於擴展**：未來可輕鬆加入更多功能  

### 啟動流程總結

1. **啟動 PHP-FPM**：
   ```powershell
   cd C:\php-8.5.1-Win32-vs17-x64
   .\php-cgi.exe -b 127.0.0.1:9000 -c php.ini
   ```

2. **啟動 Nginx**：
   ```powershell
   cd C:\Users\ivonn\SideProjects\shoppingweb\nginx-1.28.1\nginx-1.28.1
   .\nginx.exe
   ```

3. **訪問網站**：在瀏覽器中開啟 `http://localhost`

如有任何問題，請參考日誌檔案或查閱 [Nginx 官方文件](https://nginx.org/en/docs/)。
