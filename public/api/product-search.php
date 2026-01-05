<?php

/**
 * AJAX API Endpoint: 商品搜尋
 * 替代舊的 action.php，提供安全的商品搜尋 API
 * 
 * 使用方式:
 * POST /api/product-search.php
 * Content-Type: application/json
 * 
 * Request Body:
 * {
 *   "query": "搜尋關鍵字"
 * }
 * 
 * Response:
 * [
 *   {
 *     "id": 1,
 *     "product_name": "商品名稱",
 *     "price": 1000,
 *     ...
 *   },
 *   ...
 * ]
 */

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\ProductController;

$controller = new ProductController();
$controller->apiSearch();

