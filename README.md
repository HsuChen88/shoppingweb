# ShawningShop 鍵盤世界
<img width="1405" height="1929" alt="購物網站主頁" src="https://github.com/user-attachments/assets/b3ca03e7-dae6-4661-a7dd-d9946589704d" />

---

## 專案結構

```
shoppingweb/
├── app/                          # 應用程式程式碼（MVC 架構）
│   ├── Controllers/              # 控制器
│   │   ├── AuthController.php    # 身份驗證控制器
│   │   ├── CartController.php    # 購物車控制器
│   │   ├── HomeController.php    # 首頁控制器
│   │   └── ProductController.php # 商品控制器
│   ├── Models/                   # 模型
│   │   ├── Cart.php              # 購物車模型
│   │   ├── Product.php           # 商品模型
│   │   └── User.php              # 使用者模型
│   ├── Views/                    # 視圖
│   │   ├── auth/                 # 身份驗證相關視圖
│   │   │   ├── login.php
│   │   │   └── register.php
│   │   ├── cart/                 # 購物車相關視圖
│   │   │   ├── checkout.php
│   │   │   └── index.php
│   │   ├── home/                 # 首頁視圖
│   │   │   └── index.php
│   │   ├── layouts/              # 共用佈局
│   │   │   ├── footer.php
│   │   │   ├── header.php
│   │   │   └── layout.php
│   │   └── products/             # 商品相關視圖
│   │       ├── search.php
│   │       └── show.php
│   ├── Services/                 # 服務層
│   │   ├── AuthService.php        # 身份驗證服務
│   │   ├── Database.php          # 資料庫服務
│   │   └── ViewRenderer.php      # 視圖渲染服務
│   └── Core/                     # 核心類別
│       ├── Controller.php        # 控制器基底類別
│       └── Model.php             # 模型基底類別
├── config/                       # 配置檔案
│   ├── app.php                   # 應用程式配置
│   └── database.php              # 資料庫配置
├── public/                       # Web Server Document Root
│   ├── index.php                 # 入口檔案
│   ├── assets/                   # 靜態資源
│   │   ├── css/                  # CSS 樣式表
│   │   ├── js/                   # JavaScript 檔案
│   │   ├── sass/                 # SASS 原始檔
│   │   └── webfonts/             # 網頁字型
│   ├── images/                   # 圖片資源
│   ├── product_img/              # 商品圖片
│   └── api/                      # API 端點
│       └── product-search.php
├── vendor/                       # Composer 依賴套件
├── nginx-1.28.1/                # Nginx Web Server（Windows 版本）
│   └── nginx-1.28.1/
│       ├── conf/                 # Nginx 設定檔
│       │   ├── nginx.conf        # 主設定檔
│       │   └── shoppingweb.conf # 專案設定檔
│       ├── logs/                 # 日誌檔案
│       └── nginx.exe             # Nginx 執行檔
├── alldata.db                    # SQLite 資料庫檔案
├── composer.json                 # Composer 依賴定義
├── env.example                   # 環境變數範例
├── nginx.conf.example            # Nginx 配置範例（參考用）
├── start-all.bat                 # 啟動所有服務腳本
├── stop-all.bat                  # 停止所有服務腳本
├── start-nginx.bat               # 啟動 Nginx 腳本
├── stop-nginx.bat                # 停止 Nginx 腳本
└── reload-nginx.bat              # 重新載入 Nginx 設定腳本
```

## 安裝與設定

### 1. 安裝依賴
```bash
composer install
```

### 2. 設定環境變數
```bash
cp env.example .env
# 編輯 .env 檔案設定資料庫路徑等配置
```

### 3. Web Server 設定

本專案使用 **Nginx** 作為 Web Server，搭配 **PHP-FPM** 處理 PHP 請求。

#### 快速啟動（推薦）

使用專案提供的批次檔快速啟動所有服務：

```bash
# 啟動所有服務（PHP-FPM + Nginx）
start-all.bat

# 停止所有服務
stop-all.bat
```

#### Nginx 設定說明

專案已包含完整的 Nginx 設定：

- **主設定檔**：`nginx-1.28.1/nginx-1.28.1/conf/nginx.conf`
- **專案設定檔**：`nginx-1.28.1/nginx-1.28.1/conf/shoppingweb.conf`
- **Document Root**：`public/` 目錄
- **監聽連接埠**：80
- **PHP-FPM**：127.0.0.1:9000

**主要功能**：
- ✅ 靜態資源快取（CSS、JS、圖片等快取 1 年）
- ✅ Gzip 壓縮已啟用
- ✅ 安全性保護（禁止訪問 `app/`、`config/`、`vendor/` 等敏感目錄）
- ✅ 檔案保護（禁止訪問 `.env`、`.json`、`.lock` 等敏感檔案）

#### PHP-FPM 設定

**Windows 系統**：
1. 下載並安裝 PHP（Thread Safe 版本）
2. 設定 `php.ini` 啟用必要擴充功能
3. 啟動 PHP-CGI：
   ```bash
   php-cgi.exe -b 127.0.0.1:9000 -c php.ini
   ```

詳細設定說明請參考 [`NGINX_SETUP_GUIDE.md`](NGINX_SETUP_GUIDE.md)

#### 其他 Web Server 選項

1. **Apache**：
    - 設定 Document Root 指向 `public/` 目錄
    - 使用 `.htaccess`（未包含在 `public/` 中）

2. **PHP 內建伺服器（僅開發用）**：
    ```bash
    php -S localhost:8000 -t public
    ```

## 商品詳細介紹

<img width="960" height="445" alt="購物網站4" src="https://github.com/user-attachments/assets/c48fd442-9590-495d-8d06-6ab001145cd5" />

---

## 購物車
<img width="960" height="450" alt="購物網站7" src="https://github.com/user-attachments/assets/f6a4949e-054f-425f-993b-7f330a49d42d" />

---

## 未來可改善部分

本專案仍有許多改進空間，以下列出各階段的改善建議：

### 第一階段：緊急修復

#### 安全性修復
- [✅] **修復 SQL Injection 漏洞**
  - 將所有資料庫查詢改為使用 Prepared Statements
  - 優先處理登入/註冊功能（`condition.php`）
  - 逐步替換所有直接字串拼接的 SQL 查詢

- [✅] **實作密碼加密**
  - 使用 `password_hash()` 和 `password_verify()` 取代明文密碼
  - 建立密碼遷移腳本處理現有使用者
  - 在 User Model 中實作密碼驗證方法

- [✅] **基本錯誤處理**
  - 建立統一的錯誤處理機制
  - 避免向使用者暴露敏感錯誤訊息
  - 實作錯誤日誌記錄

#### 基礎設施
- [✅] **環境變數配置**
  - 完成 `.env` 檔案設定
  - 移除硬編碼的配置值
  - 確保敏感資訊不會提交到版本控制

- [✅] **版本控制**
  - 確認 `.gitignore` 已正確設定
  - 確保資料庫檔案和敏感配置不會被提交

### 第二階段：架構重構

#### MVC 架構完善
- [✅] **共用模板系統**
  - 完善 `layouts/header.php` 和 `layouts/footer.php`
  - 統一所有頁面使用共用模板
  - 減少重複程式碼

- [✅] **Model 類別擴充**
  - 完善 User、Product、Cart Model
  - 實作資料驗證邏輯
  - 統一資料存取介面

- [✅] **Controller 類別完善**
  - 實作統一的請求處理流程
  - 加入輸入驗證和錯誤處理
  - 實作回應格式化

#### 身份驗證改進
- [✅] **Session 身份驗證**
  - 移除 Cookie 身份驗證機制
  - 實作完整的 Session 管理
  - 加入 Session 過期和安全性檢查

- [✅] **輸入驗證**
  - 建立 `ValidationService` 統一處理輸入驗證
  - 實作電話號碼、電子郵件等格式驗證
  - 加入輸入清理（Sanitization）機制

### 第三階段：完善功能

#### 安全性增強
- [ ] **CSRF 保護**
  - 在所有表單中加入 CSRF Token
  - 實作 Token 生成和驗證機制
  - 防止跨站請求偽造攻擊

- [✅] **輸入清理**
  - 實作 XSS 防護
  - 對所有使用者輸入進行適當的清理和轉義

#### 監控與日誌
- [ ] **日誌系統**
  - 使用 Monolog 建立完整的日誌系統
  - 記錄重要操作（登入、註冊、購物等）
  - 實作日誌輪轉和清理機制

#### 測試
- [ ] **單元測試**
  - 使用 PHPUnit 撰寫 Model 和 Service 的單元測試
  - 建立測試資料庫環境
  - 實作 CI/CD 自動執行測試

- [ ] **整合測試**
  - 測試完整的使用者流程（註冊、登入、購物、結帳）
  - 驗證 API 端點功能

### 第四階段：持續改進

#### 開發流程
- [ ] **CI/CD 流程**
  - 建立 GitHub Actions 工作流程
  - 自動執行測試和程式碼檢查
  - 自動化部署流程

- [ ] **程式碼品質**
  - 使用 PHPStan 進行靜態分析
  - 使用 PHP_CodeSniffer 統一程式碼風格
  - 定期更新依賴套件

#### 資料庫管理
- [ ] **資料庫遷移**
  - 使用 Phinx 管理資料庫結構變更
  - 建立版本化的遷移腳本
  - 支援資料庫結構回滾

#### 效能優化
- [ ] **快取機制**
  - 實作商品列表快取
  - 使用快取減少資料庫查詢
  - 考慮使用 Redis 或 Memcached

- [ ] **資料庫優化**
  - 為常用查詢欄位建立索引
  - 優化複雜查詢語句
  - 分析慢查詢並改進

- [ ] **前端優化**
  - 實作資源壓縮和合併
  - 使用 CDN 載入靜態資源
  - 優化圖片載入（懶加載、WebP 格式）

#### 安全性審計
- [ ] **定期安全性檢查**
  - 使用 OWASP ZAP 進行安全性掃描
  - 定期進行滲透測試
  - 監控安全漏洞公告並及時更新

---

## 相關文件

- [實作指南](IMPLEMENTATION_GUIDE.md) - 詳細的改進實作步驟
- [專案健康檢查](PROJECT_HEALTH_CHECK.md) - 專案現況分析
- [Nginx 設定指南](NGINX_SETUP_GUIDE.md) - Nginx 完整設定說明
