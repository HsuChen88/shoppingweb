<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Product;
use App\Services\AuthService;

class HomeController extends Controller
{
    private $productModel;
    private $authService;
    
    public function __construct()
    {
        parent::__construct();
        $this->productModel = new Product();
        $this->authService = new AuthService();
    }
    
    /**
     * 首頁（商品列表）
     */
    public function index(): void
    {
        session_start();
        
        // 取得導航相關資訊
        $navUrls = $this->authService->getNavigationUrls();
        
        // 取得商品列表
        $keyword = $_GET['keyword'] ?? '';
        if (!empty($keyword)) {
            $products = $this->productModel->search($keyword);
        } else {
            $products = $this->productModel->all();
        }
        
        // 準備視圖資料
        $data = [
            'pageTitle' => 'ShawningShop 鍵盤世界',
            'bodyClass' => 'homepage',
            'member' => $navUrls['member'],
            'register_logout_url' => $navUrls['register_logout_url'],
            'login_profile_url' => $navUrls['login_profile_url'],
            'cart_login_url' => $navUrls['cart_login_url'],
            'products' => $products,
            'keyword' => $keyword,
            'vueMethods' => [
                'choose' => 'function(i) { var productCard = document.querySelectorAll("[name^=\'product\']")[i]; var link = productCard.querySelector("a"); if(link) link.click(); }'
            ]
        ];
        
        $this->renderWithLayout('home/index', $data);
    }
}

