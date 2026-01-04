# 專案健檢報告與修改建議

## 專案概況
- **專案名稱**: ShawningShop 鍵盤世界
- **建立時間**: 2022年6月
- **技術棧**: PHP (原生), SQLite, Vue.js 2.x, Vuetify 2.x
- **專案類型**: 電子商務購物網站

---

## 🔴 嚴重問題（需立即處理）

### 1. SQL Injection 漏洞（最高優先級）
**問題描述**: 所有資料庫查詢都使用字串拼接，存在嚴重的 SQL Injection 風險。

**影響範圍**:
- `index.php` (第7-8行)
- `action.php` (第15-16行)
- `condition.php` (第6-10行, 第103-104行, 第116行)
- `product.php` (第6-7行, 第30行, 第53行, 第60行, 第64行)
- `ShoppingCart.php` (第6-7行, 第27行, 第105行, 第121行)
- `checkout.php` (第6-7行, 第29行, 第42行, 第51行, 第60行)
- `search.php` (第6-7行, 第86-88行)
- `profile.php` (第6-7行)
- `login.php` (第6-7行)
- `register.php` (第6-7行)

**範例漏洞**:
```php
// 危險的寫法
$query = "SELECT Name FROM UserTable WHERE Phone==";
$query = $query."\"".$_COOKIE["user_id_cookie"]."\"";
```

**修復建議**:
```php
// 使用 Prepared Statements
$stmt = $pdo->prepare("SELECT Name FROM UserTable WHERE Phone = ?");
$stmt->execute([$_COOKIE["user_id_cookie"]]);
$data = $stmt->fetchAll(PDO::FETCH_NUM);
```

### 2. 密碼明文儲存
**問題描述**: 使用者密碼以明文形式儲存在資料庫中。

**影響**: 
- 資料庫洩漏時，所有使用者密碼直接暴露
- 違反資料保護法規（如 GDPR）

**修復建議**:
```php
// 註冊時加密
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// 登入時驗證
if (password_verify($password, $hashedPasswordFromDB)) {
    // 登入成功
}
```

### 3. 不安全的身份驗證機制
**問題描述**: 
- 使用 Cookie 儲存使用者手機號碼作為身份識別
- 沒有 Session 驗證
- Cookie 沒有 HttpOnly 和 Secure 標記

**修復建議**:
- 使用 Session 儲存使用者 ID
- Cookie 僅用於記住我功能，且需加密
- 設定 Cookie 的 HttpOnly 和 Secure 屬性
- 實作 CSRF Token 保護

### 4. 缺少輸入驗證與過濾
**問題描述**: 所有使用者輸入未經過適當驗證和過濾。

**修復建議**:
- 使用 `filter_var()` 和 `filter_input()` 驗證輸入
- 實作白名單驗證
- 對輸出進行 HTML 轉義（防止 XSS）

---

## 🟠 架構與程式碼品質問題

### 5. 缺少專案結構與 MVC 架構
**問題描述**: 
- 所有邏輯、視圖、資料庫操作混在一起
- 沒有分層架構
- 程式碼重複嚴重（Header/Footer 出現在每個檔案）

**建議架構**:
```
project/
├── app/
│   ├── Controllers/
│   ├── Models/
│   ├── Views/
│   └── Services/
├── config/
│   ├── database.php
│   └── app.php
├── public/
│   ├── index.php (入口檔案)
│   ├── assets/
│   └── uploads/
├── vendor/ (Composer 依賴)
└── tests/
```

### 6. 沒有使用依賴管理工具
**問題描述**: 
- 沒有 `composer.json`
- 無法管理 PHP 依賴套件
- 無法使用現代 PHP 函式庫

**修復建議**:
- 建立 `composer.json`
- 引入必要的套件（如 Monolog, Dotenv, 驗證函式庫等）

### 7. 資料庫連線重複建立
**問題描述**: 每個檔案都建立新的 PDO 連線。

**修復建議**:
- 建立單一的資料庫連線類別（Singleton 模式）
- 使用依賴注入

### 8. 錯誤處理不足
**問題描述**: 
- 沒有統一的錯誤處理機制
- 錯誤訊息可能洩漏系統資訊
- 沒有日誌記錄

**修復建議**:
- 實作錯誤處理類別
- 使用 Monolog 記錄日誌
- 生產環境隱藏詳細錯誤訊息

### 9. 程式碼重複
**問題描述**: 
- Header 和 Footer 程式碼在每個檔案重複
- 資料庫查詢邏輯重複
- 使用者驗證邏輯重複

**修復建議**:
- 建立共用的 Header/Footer 模板
- 建立 Model 類別處理資料庫操作
- 建立 Auth 類別處理身份驗證

---

## 🟡 多人開發與協作問題

### 10. 缺少版本控制配置
**問題描述**: 
- 沒有 `.gitignore` 檔案
- 資料庫檔案 (`alldata.db`) 可能被提交到版本控制
- 沒有 `.gitattributes`

**修復建議**:
建立 `.gitignore`:
```
# 資料庫
*.db
*.sqlite
*.sqlite3

# 環境變數
.env
.env.local
.env.*.local

# 依賴
/vendor/
/node_modules/

# 系統檔案
.DS_Store
Thumbs.db
*.tmp

# IDE
.idea/
.vscode/
*.swp
*.swo

# 上傳檔案
/uploads/
/product_img/*.jpg
/product_img/*.png
```

### 11. 缺少程式碼規範
**問題描述**: 
- 沒有統一的程式碼風格
- 變數命名不一致
- 沒有註解規範

**修復建議**:
- 採用 PSR-12 程式碼規範
- 使用 PHP_CodeSniffer 檢查
- 使用 PHP-CS-Fixer 自動格式化

### 12. 缺少測試 (暫時不需要)
**問題描述**: 
- 沒有單元測試
- 沒有整合測試
- 無法確保程式碼品質

**修復建議**:
- 使用 PHPUnit 撰寫測試
- 建立 CI/CD 流程自動執行測試

### 13. 缺少文件
**問題描述**: 
- 沒有 API 文件
- 沒有開發文件
- 沒有部署文件

**修復建議**:
- 使用 PHPDoc 註解
- 建立 README.md 說明專案結構
- 建立 DEPLOYMENT.md 說明部署流程

---

## 🟢 部署與環境配置問題

### 14. 硬編碼配置
**問題描述**: 
- 資料庫路徑硬編碼
- 沒有環境變數配置
- 無法區分開發/測試/生產環境 (暫時不需要)

**修復建議**:
- 使用 `.env` 檔案管理配置
- 使用 `vlucas/phpdotenv` 讀取環境變數
- 建立 `config/app.php` 統一管理配置

### 15. 缺少部署腳本
**問題描述**: 
- 沒有自動化部署流程
- 手動部署容易出錯
- 無法快速回滾

**修復建議**:
- 建立部署腳本（Shell/PowerShell）
- 使用 CI/CD 工具（如 GitHub Actions, GitLab CI）
- 實作藍綠部署或滾動更新

### 16. 資料庫遷移管理
**問題描述**: 
- 沒有資料庫遷移工具
- 無法追蹤資料庫結構變更
- 多人開發時資料庫結構不一致

**修復建議**:
- 使用 Phinx 或 Doctrine Migrations
- 建立遷移檔案追蹤資料庫變更

### 17. 檔案上傳安全性
**問題描述**: 
- 沒有檔案上傳驗證
- 可能允許上傳惡意檔案
- 檔案路徑可能被利用

**修復建議**:
- 驗證檔案類型（MIME type）
- 限制檔案大小
- 重新命名上傳檔案
- 將上傳目錄放在 web root 外

---

## 📋 優先級建議

### 第一階段（立即處理 - 1-2週）
1. ✅ 修復所有 SQL Injection 漏洞
2. ✅ 實作密碼加密（password_hash）
3. ✅ 建立 `.gitignore` 和基本版本控制配置
4. ✅ 建立環境變數配置系統
5. ✅ 實作基本的錯誤處理

### 第二階段（短期 - 1個月）
6. ✅ 重構為 MVC 架構
7. ✅ 建立共用模板系統（Header/Footer）
8. ✅ 實作 Session 身份驗證
9. ✅ 建立資料庫連線類別
10. ✅ 實作輸入驗證與過濾

### 第三階段（中期 - 2-3個月）
11. ✅ 引入 Composer 和依賴管理
12. ✅ 建立 Model 類別
13. ✅ 實作 CSRF 保護
14. ✅ 建立日誌系統
15. ✅ 撰寫基本測試

### 第四階段（長期 - 持續改進）
16. ✅ 建立 CI/CD 流程
17. ✅ 實作資料庫遷移
18. ✅ 完善文件
19. ✅ 效能優化
20. ✅ 安全性審計

---

## 🛠️ 具體實作建議

### 建議 1: 建立基礎架構

#### 目錄結構
```
shoppingweb/
├── app/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── ProductController.php
│   │   └── CartController.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Product.php
│   │   └── Cart.php
│   ├── Views/
│   │   ├── layouts/
│   │   │   ├── header.php
│   │   │   └── footer.php
│   │   ├── products/
│   │   └── auth/
│   ├── Services/
│   │   ├── Database.php
│   │   ├── AuthService.php
│   │   └── ValidationService.php
│   └── Middleware/
│       └── AuthMiddleware.php
├── config/
│   ├── database.php
│   └── app.php
├── public/
│   ├── index.php
│   └── assets/
├── vendor/
├── .env.example
├── .env
├── .gitignore
├── composer.json
└── README.md
```

#### 建立 Database 服務類別
```php
// app/Services/Database.php
class Database {
    private static $instance = null;
    private $pdo;
    
    private function __construct() {
        $this->pdo = new PDO(
            'sqlite:' . __DIR__ . '/../../' . getenv('DB_PATH'),
            null,
            null,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->pdo;
    }
}
```

### 建議 2: 建立 Model 類別

```php
// app/Models/User.php
class User {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function findByPhone($phone) {
        $stmt = $this->db->prepare("SELECT * FROM UserTable WHERE Phone = ?");
        $stmt->execute([$phone]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function create($phone, $name, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare(
            "INSERT INTO UserTable (Phone, Name, password) VALUES (?, ?, ?)"
        );
        return $stmt->execute([$phone, $name, $hashedPassword]);
    }
}
```

### 建議 3: 建立環境配置

#### .env.example
```
APP_ENV=development
APP_DEBUG=true
DB_PATH=alldata.db
SESSION_LIFETIME=86400
```

#### config/app.php
```php
return [
    'env' => getenv('APP_ENV') ?: 'production',
    'debug' => getenv('APP_DEBUG') === 'true',
    'db_path' => getenv('DB_PATH') ?: 'alldata.db',
];
```

### 建議 4: 建立 Composer 配置

#### composer.json
```json
{
    "name": "shawning/shop",
    "description": "ShawningShop E-commerce Platform",
    "type": "project",
    "require": {
        "php": ">=7.4",
        "vlucas/phpdotenv": "^5.4",
        "monolog/monolog": "^2.8"
    },
    "autoload": {
        "psr-4": {
            "App\\": "app/"
        }
    },
    "require-dev": {
        "phpunit/phpunit": "^9.5"
    }
}
```

---

## 📊 程式碼品質指標

### 當前狀態
- ❌ 安全性: 0/10 (嚴重漏洞)
- ❌ 可維護性: 2/10 (程式碼重複、無結構)
- ❌ 可擴展性: 1/10 (緊耦合、無抽象)
- ❌ 測試覆蓋率: 0%
- ❌ 文件完整性: 1/10

### 目標狀態（完成重構後）
- ✅ 安全性: 8/10
- ✅ 可維護性: 8/10
- ✅ 可擴展性: 7/10
- ✅ 測試覆蓋率: 60%+
- ✅ 文件完整性: 7/10

---

## 🔐 安全性檢查清單

- [ ] 修復所有 SQL Injection 漏洞
- [ ] 實作密碼加密
- [ ] 實作 Session 身份驗證
- [ ] 實作 CSRF 保護
- [ ] 實作輸入驗證與過濾
- [ ] 實作 XSS 防護
- [ ] 設定安全的 Cookie 屬性
- [ ] 實作速率限制（Rate Limiting）
- [ ] 實作檔案上傳驗證
- [ ] 設定適當的錯誤處理
- [ ] 實作日誌記錄
- [ ] 定期安全性審計

---

## 📚 參考資源

### PHP 最佳實踐
- [PHP The Right Way](https://phptherightway.com/)
- [OWASP PHP Security Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/PHP_Configuration_Cheat_Sheet.html)
- [PSR Standards](https://www.php-fig.org/psr/)

### 工具推薦
- **Composer**: PHP 依賴管理
- **PHPUnit**: 單元測試框架
- **PHP_CodeSniffer**: 程式碼規範檢查
- **Monolog**: 日誌記錄
- **Phinx**: 資料庫遷移
- **PHPStan**: 靜態分析工具

---

## 總結

此專案存在嚴重的安全性和架構問題，需要進行全面重構。建議按照優先級逐步改進，首先處理安全性問題，然後進行架構重構，最後完善開發流程和文件。

**預估工作量**:
- 第一階段（緊急修復）: 1-2週
- 第二階段（架構重構）: 1個月
- 第三階段（完善功能）: 2-3個月
- 第四階段（持續改進）: 持續進行

**建議團隊規模**: 2-3人
**建議技術棧**: PHP 7.4+, Composer, 可選框架（Laravel/Symfony）或自建輕量 MVC

