<?php

namespace App\Services;

use App\Models\User;

class AuthService
{
    private $userModel;
    
    public function __construct()
    {
        $this->userModel = new User();
    }
    
    /**
     * 檢查使用者是否已登入
     * 
     * @return bool
     */
    public function checkAuth(): bool
    {
        return isset($_COOKIE['user_id_cookie']) && !empty($_COOKIE['user_id_cookie']);
    }
    
    /**
     * 登入處理
     * 
     * @param string $phone 手機號碼
     * @param string $password 密碼
     * @return array ['success' => bool, 'message' => string, 'user' => array|null]
     */
    public function login(string $phone, string $password): array
    {
        // 驗證輸入
        if (empty($phone) || empty($password)) {
            return [
                'success' => false,
                'message' => '帳號或密碼空白\n請再輸入一次!',
                'user' => null
            ];
        }
        
        // 驗證密碼
        if (!$this->userModel->verifyPassword($phone, $password)) {
            return [
                'success' => false,
                'message' => '密碼不正確..\n請再輸入一次',
                'user' => null
            ];
        }
        
        // 取得使用者資訊
        $user = $this->userModel->findByPhone($phone);
        if (!$user) {
            return [
                'success' => false,
                'message' => '查無此帳號..\n請再輸入一次或先加入會員!',
                'user' => null
            ];
        }
        
        // 設定 Session
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_phone'] = $phone;
        $_SESSION['user_name'] = $user['Name'];
        
        // 設定 Cookie
        $config = require __DIR__ . '/../../config/app.php';
        $cookieLifetime = $config['session']['cookie_lifetime'] ?? 86400 * 7;
        setcookie(
            'user_id_cookie',
            $phone,
            time() + $cookieLifetime,
            '/'
        );
        
        return [
            'success' => true,
            'message' => '登入成功',
            'user' => $user
        ];
    }
    
    /**
     * 登出處理
     * 
     * @return void
     */
    public function logout(): void
    {
        session_start();
        
        // 清除 Session
        $_SESSION = [];
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }
        session_destroy();
        
        // 清除 Cookie
        if (isset($_COOKIE['user_id_cookie'])) {
            setcookie('user_id_cookie', '', time() - 3600, '/');
        }
    }
    
    /**
     * 註冊處理
     * 
     * @param array $data 註冊資料 ['name', 'phone', 'password', 'confirmPassword']
     * @return array ['success' => bool, 'message' => string]
     */
    public function register(array $data): array
    {
        $name = $data['name'] ?? 'user';
        $phone = $data['phone'] ?? '';
        $password = $data['password'] ?? '';
        $confirmPassword = $data['confirmPassword'] ?? '';
        
        // 驗證手機號碼格式
        $phonePattern = "/^09[0-9]{8}$/";
        if (!preg_match($phonePattern, $phone)) {
            return [
                'success' => false,
                'message' => '請輸入正確的電話號碼'
            ];
        }
        
        // 驗證密碼長度
        if (strlen($password) < 8) {
            return [
                'success' => false,
                'message' => '輸入的密碼未達8個字\n請再輸入一次'
            ];
        }
        
        // 驗證兩次密碼是否相同
        if ($password !== $confirmPassword) {
            return [
                'success' => false,
                'message' => '兩次輸入的密碼不相同\n請再輸入一次'
            ];
        }
        
        // 檢查手機號碼是否已註冊
        if ($this->userModel->phoneExists($phone)) {
            return [
                'success' => false,
                'message' => '您已註冊過會員 請登入'
            ];
        }
        
        // 建立新使用者
        if ($this->userModel->create([
            'name' => $name,
            'phone' => $phone,
            'password' => $password
        ])) {
            return [
                'success' => true,
                'message' => '您已成功加入會員 請登入'
            ];
        } else {
            return [
                'success' => false,
                'message' => '註冊失敗，請稍後再試'
            ];
        }
    }
    
    /**
     * 取得目前使用者資訊
     * 
     * @return array|null
     */
    public function getCurrentUser(): ?array
    {
        if (!$this->checkAuth()) {
            return null;
        }
        
        $phone = $_COOKIE['user_id_cookie'] ?? null;
        if (!$phone) {
            return null;
        }
        
        return $this->userModel->findByPhone($phone);
    }
    
    /**
     * 取得目前使用者 ID
     * 
     * @return int|null
     */
    public function getCurrentUserId(): ?int
    {
        $user = $this->getCurrentUser();
        return $user ? (int)$user['id'] : null;
    }
    
    /**
     * 要求登入（未登入則重定向）
     * 
     * @param string $redirectUrl 未登入時重定向的 URL
     * @return void
     */
    public function requireAuth(string $redirectUrl = './login.php'): void
    {
        if (!$this->checkAuth()) {
            header("Location: {$redirectUrl}");
            exit;
        }
    }
    
    /**
     * 取得導航相關的 URL
     * 
     * @return array
     */
    public function getNavigationUrls(): array
    {
        $isLoggedIn = $this->checkAuth();
        $user = $this->getCurrentUser();
        
        return [
            'register_logout_url' => $isLoggedIn ? './logout.php' : './register.php',
            'login_profile_url' => $isLoggedIn ? './profile.php' : './login.php',
            'cart_login_url' => $isLoggedIn ? './ShoppingCart.php' : './login.php',
            'member' => $user ? $user['Name'] : null
        ];
    }
}

