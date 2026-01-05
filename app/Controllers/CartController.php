<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Cart;
use App\Models\Product;
use App\Services\AuthService;

class CartController extends Controller
{
    private $cartModel;
    private $productModel;
    private $authService;
    
    public function __construct()
    {
        parent::__construct();
        $this->cartModel = new Cart();
        $this->productModel = new Product();
        $this->authService = new AuthService();
    }
    
    /**
     * 購物車頁面
     */
    public function index(): void
    {
        session_start();
        
        // 要求登入
        $this->authService->requireAuth();
        
        $userId = $this->authService->getCurrentUserId();
        if (!$userId) {
            $this->redirect('./login.php');
            return;
        }
        
        // 處理移除商品
        if (isset($_POST['productID'])) {
            $this->handleRemove((int)$_POST['productID']);
            return;
        }
        
        // 取得導航相關資訊
        $navUrls = $this->authService->getNavigationUrls();
        
        // 取得購物車內容
        $cartItems = $this->cartModel->getCartWithProducts($userId);
        $total = $this->cartModel->getTotal($userId);
        
        // 準備視圖資料
        $data = [
            'pageTitle' => '購物車 - ShawningShop',
            'bodyClass' => 'no-sidebar',
            'member' => $navUrls['member'],
            'register_logout_url' => $navUrls['register_logout_url'],
            'login_profile_url' => $navUrls['login_profile_url'],
            'cart_login_url' => $navUrls['cart_login_url'],
            'cartItems' => $cartItems,
            'total' => $total,
            'isEmpty' => empty($cartItems),
            'vueMethods' => [
                'delBtnFunc' => 'function(i) { func_num="func"+i; document.forms[func_num].submit(); }',
                'fun' => 'function(key) { var tagContent = document.getElementsByClassName("v-chip__content"); str = tagContent[key].innerHTML; str = tagContent[key].innerHTML.replace(/\\s/g, ""); document.getElementById("search").value = str; document.getElementById("searchForm").submit(); }'
            ]
        ];
        
        $this->renderWithLayout('cart/index', $data);
    }
    
    /**
     * 處理移除商品
     */
    private function handleRemove(int $productId): void
    {
        $userId = $this->authService->getCurrentUserId();
        if (!$userId) {
            $this->redirect('./login.php');
            return;
        }
        
        if ($this->cartModel->remove($userId, $productId)) {
            $this->redirect('./ShoppingCart.php');
        } else {
            $this->errorAndRedirect('移除商品失敗', './ShoppingCart.php');
        }
    }
    
    /**
     * 結帳處理
     */
    public function checkout(): void
    {
        session_start();
        
        // 要求登入
        $this->authService->requireAuth();
        
        $userId = $this->authService->getCurrentUserId();
        if (!$userId) {
            $this->redirect('./login.php');
            return;
        }
        
        // 取得購物車內容
        $cartItems = $this->cartModel->getByUserId($userId);
        
        if (empty($cartItems)) {
            $this->errorAndRedirect('購物車是空的', './ShoppingCart.php');
            return;
        }
        
        // 處理庫存和結帳
        foreach ($cartItems as $item) {
            $productId = $item['product_id'];
            $amount = $item['amount'];
            
            // 檢查庫存
            $product = $this->productModel->findById($productId);
            if (!$product || $product['amount'] < $amount) {
                $this->errorAndRedirect('商品庫存不足', './ShoppingCart.php');
                return;
            }
            
            // 減少庫存
            if (!$this->productModel->decreaseStock($productId, $amount)) {
                $this->errorAndRedirect('更新庫存失敗', './ShoppingCart.php');
                return;
            }
        }
        
        // 清空購物車
        $this->cartModel->clear($userId);
        
        // 準備視圖資料
        $data = [
            'pageTitle' => '訂單已完成 - ShawningShop',
            'bodyClass' => ''
        ];
        
        $this->render('cart/checkout', $data, false);
    }
}

