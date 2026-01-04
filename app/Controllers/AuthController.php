<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Services\AuthService;

class AuthController extends Controller
{
    private $authService;
    
    public function __construct()
    {
        parent::__construct();
        $this->authService = new AuthService();
    }
    
    /**
     * 顯示登入頁面
     */
    public function login(): void
    {
        session_start();
        
        // 如果已登入，重定向到首頁
        if ($this->authService->checkAuth()) {
            $this->redirect('./index.php');
            return;
        }
        
        // 取得導航相關資訊
        $navUrls = $this->authService->getNavigationUrls();
        
        // 準備視圖資料
        $data = [
            'pageTitle' => '會員登入 - ShawningShop',
            'bodyClass' => 'homepage',
            'member' => $navUrls['member'],
            'register_logout_url' => $navUrls['register_logout_url'],
            'login_profile_url' => $navUrls['login_profile_url'],
            'cart_login_url' => $navUrls['cart_login_url'],
            'additionalCss' => ['./assets/css/login.css'],
            'vueMethods' => [
                'fun' => 'function(key) { var tagContent = document.getElementsByClassName("v-chip__content"); str = tagContent[key].innerHTML; str = tagContent[key].innerHTML.replace(/\\s/g, ""); document.getElementById("search").value = str; document.getElementById("searchForm").submit(); }'
            ]
        ];
        
        $this->renderWithLayout('auth/login', $data);
    }
    
    /**
     * 處理登入請求
     */
    public function handleLogin(): void
    {
        session_start();
        
        $phone = $_POST['phone'] ?? '';
        $password = $_POST['password'] ?? '';
        
        $result = $this->authService->login($phone, $password);
        
        if ($result['success']) {
            $this->successAndRedirect($result['message'], './index.php');
        } else {
            $this->errorAndRedirect($result['message'], './login.php');
        }
    }
    
    /**
     * 顯示註冊頁面
     */
    public function register(): void
    {
        session_start();
        
        // 如果已登入，重定向到首頁
        if ($this->authService->checkAuth()) {
            $this->redirect('./index.php');
            return;
        }
        
        // 取得導航相關資訊
        $navUrls = $this->authService->getNavigationUrls();
        
        // 準備視圖資料
        $data = [
            'pageTitle' => '加入會員 - ShawningShop',
            'bodyClass' => 'homepage',
            'member' => $navUrls['member'],
            'register_logout_url' => $navUrls['register_logout_url'],
            'login_profile_url' => $navUrls['login_profile_url'],
            'cart_login_url' => $navUrls['cart_login_url'],
            'additionalCss' => ['./assets/css/login.css'],
            'vueMethods' => [
                'fun' => 'function(key) { var tagContent = document.getElementsByClassName("v-chip__content"); str = tagContent[key].innerHTML; str = tagContent[key].innerHTML.replace(/\\s/g, ""); document.getElementById("search").value = str; document.getElementById("searchForm").submit(); }'
            ]
        ];
        
        $this->renderWithLayout('auth/register', $data);
    }
    
    /**
     * 處理註冊請求
     */
    public function handleRegister(): void
    {
        session_start();
        
        $result = $this->authService->register($_POST);
        
        if ($result['success']) {
            $this->successAndRedirect($result['message'], './login.php');
        } else {
            $this->errorAndRedirect($result['message'], './register.php');
        }
    }
    
    /**
     * 處理登出
     */
    public function logout(): void
    {
        $this->authService->logout();
        $this->redirect('./index.php');
    }
}

