<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Controllers\AuthController;

$controller = new AuthController();

// 根據 POST 參數決定處理登入或註冊
if (isset($_POST['loginBtn'])) {
    $controller->handleLogin();
} elseif (isset($_POST['addBtn'])) {
    $controller->handleRegister();
} else {
    // 如果沒有正確的 POST 參數，重定向到首頁
    header("Location: ./index.php");
    exit;
}
