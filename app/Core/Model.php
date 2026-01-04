<?php

namespace App\Core;

use App\Services\Database;
use PDO;

abstract class Model
{
    protected $db;
    protected $table;
    
    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * 根據 ID 查詢單筆記錄
     * 
     * @param int $id 記錄 ID
     * @return array|null
     */
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }
    
    /**
     * 查詢所有記錄
     * 
     * @param array $conditions WHERE 條件（可選）
     * @return array
     */
    public function all(array $conditions = []): array
    {
        $sql = "SELECT * FROM {$this->table}";
        $params = [];
        
        if (!empty($conditions)) {
            $where = [];
            foreach ($conditions as $key => $value) {
                $where[] = "{$key} = ?";
                $params[] = $value;
            }
            $sql .= " WHERE " . implode(" AND ", $where);
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * 建立新記錄
     * 
     * @param array $data 資料陣列
     * @return bool
     */
    public function create(array $data): bool
    {
        $columns = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_fill(0, count($data), "?"));
        
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute(array_values($data));
    }
    
    /**
     * 更新記錄
     * 
     * @param int $id 記錄 ID
     * @param array $data 要更新的資料
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        $set = [];
        $params = [];
        
        foreach ($data as $key => $value) {
            $set[] = "{$key} = ?";
            $params[] = $value;
        }
        
        $params[] = $id;
        $sql = "UPDATE {$this->table} SET " . implode(", ", $set) . " WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute($params);
    }
    
    /**
     * 刪除記錄
     * 
     * @param int $id 記錄 ID
     * @return bool
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    /**
     * 執行自訂查詢
     * 
     * @param string $sql SQL 語句
     * @param array $params 參數陣列
     * @return \PDOStatement
     */
    protected function query(string $sql, array $params = []): \PDOStatement
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
}

