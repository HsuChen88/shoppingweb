<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class Product extends Model
{
    protected $table = 'Products';
    
    /**
     * 取得所有商品
     * 
     * @param array $conditions WHERE 條件（可選）
     * @return array
     */
    public function all(array $conditions = []): array
    {
        return parent::all($conditions);
    }
    
    /**
     * 根據 ID 查詢商品
     * 
     * @param int $id 商品 ID
     * @return array|null
     */
    public function findById(int $id): ?array
    {
        return parent::findById($id);
    }
    
    /**
     * 搜尋商品
     * 
     * @param string $keyword 搜尋關鍵字
     * @param bool $includeImage 是否同時搜尋圖片欄位（預設 false）
     * @return array
     */
    public function search(string $keyword, bool $includeImage = false): array
    {
        if ($includeImage) {
            // 同時搜尋商品名稱和圖片欄位（支援 picture_name 或 image）
            $stmt = $this->db->prepare(
                "SELECT * FROM {$this->table} 
                WHERE product_name LIKE ? 
                OR picture_name LIKE ? 
                OR image LIKE ? 
                ORDER BY id DESC"
            );
            $searchPattern = "%{$keyword}%";
            $stmt->execute([$searchPattern, $searchPattern, $searchPattern]);
        } else {
            // 僅搜尋商品名稱
            $stmt = $this->db->prepare(
                "SELECT * FROM {$this->table} WHERE product_name LIKE ? ORDER BY id DESC"
            );
            $stmt->execute(["%{$keyword}%"]);
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * 更新庫存
     * 
     * @param int $id 商品 ID
     * @param int $amount 新的庫存數量
     * @return bool
     */
    public function updateStock(int $id, int $amount): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE {$this->table} SET amount = ? WHERE id = ?"
        );
        return $stmt->execute([$amount, $id]);
    }
    
    /**
     * 減少庫存
     * 
     * @param int $id 商品 ID
     * @param int $quantity 要減少的數量
     * @return bool
     */
    public function decreaseStock(int $id, int $quantity): bool
    {
        $product = $this->findById($id);
        if (!$product) {
            return false;
        }
        
        $newAmount = $product['amount'] - $quantity;
        if ($newAmount < 0) {
            return false; // 庫存不足
        }
        
        return $this->updateStock($id, $newAmount);
    }
}

