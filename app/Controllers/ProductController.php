<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Product;
use App\Models\Cart;
use App\Services\AuthService;

class ProductController extends Controller
{
    private $productModel;
    private $cartModel;
    private $authService;
    
    public function __construct()
    {
        parent::__construct();
        $this->productModel = new Product();
        $this->cartModel = new Cart();
        $this->authService = new AuthService();
    }
    
    /**
     * 商品詳情頁
     */
    public function show(): void
    {
        session_start();
        
        // 商品詳情頁使用 GET 參數（符合 RESTful 設計）
        $productId = $_GET['productId'] ?? null;
        
        // 處理加入購物車（POST 請求）
        if (isset($_POST['addAmount']) && isset($_POST['productId'])) {
            $this->handleAddToCart((int)$_POST['productId'], (int)$_POST['addAmount']);
            return;
        }
        
        // 如果沒有 productId，顯示錯誤
        if (!$productId) {
            $this->errorAndRedirect('商品不存在', './index.php');
            return;
        }
        
        $product = $this->productModel->findById((int)$productId);
        if (!$product) {
            $this->errorAndRedirect('商品不存在', './index.php');
            return;
        }
        
        // 取得導航相關資訊
        $navUrls = $this->authService->getNavigationUrls();
        $userId = $this->authService->getCurrentUserId();
        
        // 處理分類標籤
        $categoryArray = explode(",", $product['category'] ?? '');
        
        // 準備視圖資料
        $data = [
            'pageTitle' => $product['product_name'] . ' - ShawningShop',
            'bodyClass' => 'homepage',
            'member' => $navUrls['member'],
            'register_logout_url' => $navUrls['register_logout_url'],
            'login_profile_url' => $navUrls['login_profile_url'],
            'cart_login_url' => $navUrls['cart_login_url'],
            'product' => $product,
            'productId' => $productId,
            'productName' => $product['product_name'],
            'productCategory' => $product['category'] ?? '',
            'productAmount' => $product['amount'],
            'productPrice' => $product['price'],
            'productImage' => $product['picture_name'] ?? $product['image'] ?? '',
            'productDescription' => $product['description'] ?? '',
            'categoryArray' => $categoryArray,
            'userId' => $userId,
            'keyword' => $_GET['search'] ?? '',
            'additionalCss' => ['./assets/css/stickbottom.css'],
            'additionalScripts' => ['https://unpkg.com/axios/dist/axios.min.js', 'https://cdn.jsdelivr.net/npm/vue-sticky-position@2.0.0/dist/sticky.min.js'],
            'vueMethods' => [
                'fun' => 'function(key) { var tagContent = document.getElementsByClassName("v-chip__content"); str = tagContent[key].innerHTML; str = tagContent[key].innerHTML.replace(/\\s/g, ""); document.getElementById("search").value = str; document.getElementById("searchForm").submit(); }'
            ]
        ];
        
        $this->renderWithLayout('products/show', $data);
    }
    
    /**
     * 商品搜尋
     */
    public function search(): void
    {
        session_start();
        
        $keyword = $_GET['search'] ?? '';
        
        // 取得導航相關資訊
        $navUrls = $this->authService->getNavigationUrls();
        
        // 搜尋商品
        if (!empty($keyword)) {
            $products = $this->productModel->search($keyword);
        } else {
            $products = $this->productModel->all();
        }
        
        // 準備視圖資料
        $data = [
            'pageTitle' => '搜尋結果 - ShawningShop',
            'bodyClass' => 'homepage',
            'member' => $navUrls['member'],
            'register_logout_url' => $navUrls['register_logout_url'],
            'login_profile_url' => $navUrls['login_profile_url'],
            'cart_login_url' => $navUrls['cart_login_url'],
            'products' => $products,
            'keyword' => $keyword,
            'vueMethods' => [
                'choose' => 'function(i) { var productCard = document.querySelectorAll("[name^=\'product\']")[i]; var link = productCard.querySelector("a"); if(link) link.click(); }',
                'fun' => 'function(key) { var tagContent = document.getElementsByClassName("v-chip__content"); str = tagContent[key].innerHTML; str = tagContent[key].innerHTML.replace(/\\s/g, ""); document.getElementById("search").value = str; document.getElementById("searchForm").submit(); }'
            ]
        ];
        
        $this->renderWithLayout('products/search', $data);
    }
    
    /**
     * AJAX API: 商品搜尋（替代 action.php）
     * 返回 JSON 格式的商品列表
     */
    public function apiSearch(): void
    {
        // 設定回應標頭
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            // 讀取 JSON 輸入
            $input = file_get_contents('php://input');
            $receivedData = json_decode($input, true);
            
            // 驗證輸入
            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->json([
                    'error' => 'Invalid JSON format',
                    'message' => json_last_error_msg()
                ], 400);
                return;
            }
            
            $keyword = $receivedData['query'] ?? '';
            
            // 搜尋商品（包含圖片欄位搜尋，與 action.php 行為一致）
            if (!empty($keyword)) {
                $products = $this->productModel->search($keyword, true);
            } else {
                $products = $this->productModel->all();
            }
            
            // 返回 JSON 格式的商品列表
            $this->json($products);
            
        } catch (\Exception $e) {
            // 錯誤處理
            $this->json([
                'error' => 'Server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * 處理加入購物車
     */
    private function handleAddToCart(int $productId, int $amount): void
    {
        $userId = $this->authService->getCurrentUserId();
        
        if (!$userId) {
            $this->errorAndRedirect('請先登入會員!', './login.php');
            return;
        }
        
        // 檢查庫存
        $product = $this->productModel->findById($productId);
        if (!$product) {
            $this->errorAndRedirect('商品不存在', './index.php');
            return;
        }
        
        if ($product['amount'] < $amount) {
            $this->errorAndRedirect('庫存不足', './product.php');
            return;
        }
        
        // 加入購物車
        if ($this->cartModel->add($userId, $productId, $amount)) {
            // 重定向時帶上 productId 參數，讓頁面能正常顯示
            $this->successAndRedirect('成功加入購物車!', "./product.php?productId={$productId}");
        } else {
            $this->errorAndRedirect('加入購物車失敗', "./product.php?productId={$productId}");
        }
    }
}

