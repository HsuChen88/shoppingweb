<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class Cart extends Model
{
    protected $table = 'Cart';
    
    /**
     * 取得使用者的購物車
     * 
     * @param int $userId 使用者 ID
     * @return array
     */
    public function getByUserId(int $userId): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->table} WHERE user_id = ?"
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * 加入購物車
     * 
     * @param int $userId 使用者 ID
     * @param int $productId 商品 ID
     * @param int $amount 數量
     * @return bool
     */
    public function add(int $userId, int $productId, int $amount): bool
    {
        // 檢查購物車中是否已有此商品
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->table} WHERE user_id = ? AND product_id = ?"
        );
        $stmt->execute([$userId, $productId]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($existing) {
            // 更新數量
            $newAmount = $existing['amount'] + $amount;
            $stmt = $this->db->prepare(
                "UPDATE {$this->table} SET amount = ? WHERE user_id = ? AND product_id = ?"
            );
            return $stmt->execute([$newAmount, $userId, $productId]);
        } else {
            // 新增商品
            $stmt = $this->db->prepare(
                "INSERT INTO {$this->table} (user_id, product_id, amount) VALUES (?, ?, ?)"
            );
            return $stmt->execute([$userId, $productId, $amount]);
        }
    }
    
    /**
     * 更新購物車商品數量
     * 
     * @param int $userId 使用者 ID
     * @param int $productId 商品 ID
     * @param int $amount 新數量
     * @return bool
     */
    public function updateAmount(int $userId, int $productId, int $amount): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE {$this->table} SET amount = ? WHERE user_id = ? AND product_id = ?"
        );
        return $stmt->execute([$amount, $userId, $productId]);
    }
    
    /**
     * 移除購物車中的商品
     * 
     * @param int $userId 使用者 ID
     * @param int $productId 商品 ID
     * @return bool
     */
    public function remove(int $userId, int $productId): bool
    {
        $stmt = $this->db->prepare(
            "DELETE FROM {$this->table} WHERE user_id = ? AND product_id = ?"
        );
        return $stmt->execute([$userId, $productId]);
    }
    
    /**
     * 清空購物車
     * 
     * @param int $userId 使用者 ID
     * @return bool
     */
    public function clear(int $userId): bool
    {
        $stmt = $this->db->prepare(
            "DELETE FROM {$this->table} WHERE user_id = ?"
        );
        return $stmt->execute([$userId]);
    }
    
    /**
     * 計算購物車總金額
     * 
     * @param int $userId 使用者 ID
     * @return float
     */
    public function getTotal(int $userId): float
    {
        $cartItems = $this->getByUserId($userId);
        $total = 0;
        
        $productModel = new \App\Models\Product();
        
        foreach ($cartItems as $item) {
            $product = $productModel->findById($item['product_id']);
            if ($product) {
                $total += $product['price'] * $item['amount'];
            }
        }
        
        return $total;
    }
    
    /**
     * 取得購物車商品詳情（包含商品資訊）
     * 
     * @param int $userId 使用者 ID
     * @return array
     */
    public function getCartWithProducts(int $userId): array
    {
        $cartItems = $this->getByUserId($userId);
        $productModel = new \App\Models\Product();
        $result = [];
        
        foreach ($cartItems as $item) {
            $product = $productModel->findById($item['product_id']);
            if ($product) {
                $result[] = [
                    'cart_id' => $item['id'],
                    'product_id' => $product['id'],
                    'product_name' => $product['product_name'],
                    'price' => $product['price'],
                    'amount' => $item['amount'],
                    'picture_name' => $product['picture_name'] ?? $product['image'] ?? '',
                    'subtotal' => $product['price'] * $item['amount']
                ];
            }
        }
        
        return $result;
    }
}

