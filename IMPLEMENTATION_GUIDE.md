# 實作指南

本指南提供逐步實作改進建議的具體步驟。

## 第一階段：緊急修復（1-2週）

### 步驟 1: 建立版本控制

1. 初始化 Git 儲存庫（如果還沒有）
```bash
git init
```

2. 複製 `.gitignore` 到專案根目錄
```bash
# .gitignore 已經建立
```

3. 建立初始 commit
```bash
git add .
git commit -m "Initial commit with health check"
```

### 步驟 2: 建立環境配置系統

1. 安裝 Composer（如果還沒有）
```bash
# 下載 Composer
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
php -r "unlink('composer-setup.php');"
```

2. 建立 `composer.json`
```bash
# 複製 composer.json.example 並重新命名
cp composer.json.example composer.json
```

3. 安裝依賴
```bash
composer install
```

4. 建立環境變數檔案
```bash
# 複製 env.example 並建立 .env
cp env.example .env
```

5. 修改 `.env` 檔案設定實際值

### 步驟 3: 修復 SQL Injection 漏洞

#### 3.1 建立 Database 服務類別

1. 建立目錄結構
```bash
mkdir -p app/Services
```

2. 建立 `app/Services/Database.php`（參考 REFACTORING_EXAMPLES.md）

3. 在 `public/index.php` 或入口檔案載入
```php
<?php
require_once __DIR__ . '/../vendor/autoload.php';

// 載入環境變數
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();
```

#### 3.2 逐步替換所有資料庫查詢

**優先順序**:
1. `condition.php` - 登入/註冊功能（最高優先級）
2. `index.php` - 首頁顯示
3. `product.php` - 商品頁面
4. `ShoppingCart.php` - 購物車
5. `checkout.php` - 結帳功能
6. 其他檔案

**替換範例**:
```php
// 舊程式碼
$pdo = new PDO('sqlite:alldata.db');
$query = "SELECT Name FROM UserTable WHERE Phone==";
$query = $query."\"".$_COOKIE["user_id_cookie"]."\"";
$sth = $pdo->query($query);

// 新程式碼
use App\Services\Database;
$db = Database::getInstance()->getConnection();
$stmt = $db->prepare("SELECT Name FROM UserTable WHERE Phone = ?");
$stmt->execute([$_COOKIE["user_id_cookie"] ?? '']);
$data = $stmt->fetchAll(PDO::FETCH_NUM);
```

### 步驟 4: 實作密碼加密

1. 建立 User Model（參考 REFACTORING_EXAMPLES.md）

2. 修改註冊流程
```php
// 在 condition.php 或新的 AuthController 中
use App\Models\User;

$userModel = new User();
$userModel->create([
    'name' => $name,
    'phone' => $phone,
    'password' => $password  // Model 內部會加密
]);
```

3. 修改登入流程
```php
// 使用 Model 的 verifyPassword 方法
if ($userModel->verifyPassword($phone, $password)) {
    // 登入成功
}
```

4. **重要**: 需要遷移現有使用者密碼
   - 建立遷移腳本將現有明文密碼轉換為 hash
   - 或要求現有使用者重設密碼

### 步驟 5: 實作基本錯誤處理

1. 建立 `app/Services/ErrorHandler.php`（參考 REFACTORING_EXAMPLES.md）

2. 在入口檔案註冊錯誤處理
```php
use App\Services\ErrorHandler;

ErrorHandler::register();
```

---

## 第二階段：架構重構（1個月）

### 步驟 6: 建立 MVC 架構

#### 6.1 建立目錄結構
```bash
mkdir -p app/Controllers
mkdir -p app/Models
mkdir -p app/Views/layouts
mkdir -p app/Views/products
mkdir -p app/Views/auth
mkdir -p config
mkdir -p public
```

#### 6.2 建立共用模板

1. 建立 `app/Views/layouts/header.php`（參考 REFACTORING_EXAMPLES.md）
2. 建立 `app/Views/layouts/footer.php`（參考 REFACTORING_EXAMPLES.md）

3. 重構現有頁面使用模板
```php
<?php
// index.php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$pageTitle = '首頁';
include __DIR__ . '/../app/Views/layouts/header.php';
?>

<!-- 頁面內容 -->

<?php include __DIR__ . '/../app/Views/layouts/footer.php'; ?>
```

#### 6.3 建立 Model 類別

1. `app/Models/User.php` - 使用者模型
2. `app/Models/Product.php` - 商品模型
3. `app/Models/Cart.php` - 購物車模型

#### 6.4 建立 Controller 類別

1. `app/Controllers/AuthController.php` - 身份驗證
2. `app/Controllers/ProductController.php` - 商品管理
3. `app/Controllers/CartController.php` - 購物車管理

### 步驟 7: 實作 Session 身份驗證

1. 建立 `app/Services/AuthService.php`（參考 REFACTORING_EXAMPLES.md）

2. 修改所有需要登入的頁面
```php
use App\Services\AuthService;

$authService = new AuthService();
$authService->requireAuth(); // 需要登入才能存取
```

3. 移除 Cookie 身份驗證，改用 Session

### 步驟 8: 實作輸入驗證

1. 建立 `app/Services/ValidationService.php`（參考 REFACTORING_EXAMPLES.md）

2. 在所有接收使用者輸入的地方使用驗證
```php
use App\Services\ValidationService;

$phone = ValidationService::sanitizeInput($_POST['phone'] ?? '');
if (!ValidationService::validatePhone($phone)) {
    // 處理錯誤
}
```

---

## 第三階段：完善功能（2-3個月）

### 步驟 9: 實作 CSRF 保護

1. 在 `ValidationService` 中已有 CSRF 方法

2. 在所有表單中加入 CSRF Token
```php
<input type="hidden" name="csrf_token" value="<?= ValidationService::generateCsrfToken() ?>">
```

3. 在處理表單時驗證 Token
```php
if (!ValidationService::validateCsrfToken($_POST['csrf_token'] ?? '')) {
    // 處理錯誤
}
```

### 步驟 10: 建立日誌系統

1. 使用 Monolog（已在 composer.json 中）
```php
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$logger = new Logger('app');
$logger->pushHandler(new StreamHandler(__DIR__ . '/../logs/app.log', Logger::DEBUG));

$logger->info('User logged in', ['user_id' => $userId]);
```

### 步驟 11: 撰寫測試

1. 安裝 PHPUnit（已在 composer.json dev 依賴中）

2. 建立測試目錄
```bash
mkdir -p tests/Unit
mkdir -p tests/Integration
```

3. 建立測試範例
```php
// tests/Unit/UserTest.php
use PHPUnit\Framework\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    public function testFindByPhone()
    {
        $user = new User();
        $result = $user->findByPhone('0912345678');
        $this->assertIsArray($result);
    }
}
```

4. 執行測試
```bash
composer test
```

---

## 第四階段：持續改進

### 步驟 12: 建立 CI/CD

#### GitHub Actions 範例

建立 `.github/workflows/ci.yml`:
```yaml
name: CI

on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest
    
    steps:
    - uses: actions/checkout@v2
    
    - name: Setup PHP
      uses: shivammathur/setup-php@v2
      with:
        php-version: '7.4'
    
    - name: Install dependencies
      run: composer install
    
    - name: Run tests
      run: composer test
    
    - name: Check code style
      run: composer cs-check
```

### 步驟 13: 資料庫遷移

1. 安裝 Phinx
```bash
composer require robmorgan/phinx
```

2. 初始化 Phinx
```bash
vendor/bin/phinx init
```

3. 建立遷移檔案
```bash
vendor/bin/phinx create AddPasswordHashToUsers
```

### 步驟 14: 效能優化

1. 實作快取機制
2. 優化資料庫查詢
3. 使用 CDN 載入靜態資源
4. 實作資料庫索引

### 步驟 15: 安全性審計

1. 使用 PHPStan 進行靜態分析
```bash
composer analyse
```

2. 使用 OWASP ZAP 進行安全性掃描
3. 定期更新依賴套件
```bash
composer update
```

---

## 檢查清單

### 第一階段完成檢查
- [ ] 所有 SQL Injection 漏洞已修復
- [ ] 密碼加密已實作
- [ ] 環境變數配置已建立
- [ ] 基本錯誤處理已實作
- [ ] `.gitignore` 已建立
- [ ] Git 版本控制已設定

### 第二階段完成檢查
- [ ] MVC 架構已建立
- [ ] 共用模板系統已實作
- [ ] Model 類別已建立
- [ ] Controller 類別已建立
- [ ] Session 身份驗證已實作
- [ ] 輸入驗證已實作

### 第三階段完成檢查
- [ ] CSRF 保護已實作
- [ ] 日誌系統已建立
- [ ] 基本測試已撰寫
- [ ] 程式碼規範已統一

### 第四階段完成檢查
- [ ] CI/CD 流程已建立
- [ ] 資料庫遷移工具已設定
- [ ] 文件已完善
- [ ] 效能已優化

---

## 常見問題

### Q: 如何處理現有使用者的密碼遷移？
A: 建立一個遷移腳本，在下次登入時檢查密碼格式，如果是明文則要求重設。

### Q: 如何在不影響現有功能的情況下重構？
A: 採用漸進式重構，先建立新的類別和服務，然後逐步替換舊程式碼。

### Q: 如何確保重構後的程式碼不會引入新問題？
A: 撰寫測試、使用程式碼審查、逐步部署並監控錯誤日誌。

### Q: 多人開發時如何避免衝突？
A: 使用 Git 分支策略、程式碼規範、定期合併、使用 CI/CD 自動檢查。

---

## 資源連結

- [PHP The Right Way](https://phptherightway.com/)
- [Composer 文件](https://getcomposer.org/doc/)
- [PHPUnit 文件](https://phpunit.de/documentation.html)
- [PSR 標準](https://www.php-fig.org/psr/)

