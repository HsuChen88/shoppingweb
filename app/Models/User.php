<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class User extends Model
{
    protected $table = 'UserTable';
    
    /**
     * 根據手機號碼查找使用者
     * 
     * @param string $phone 手機號碼
     * @return array|null
     */
    public function findByPhone(string $phone): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE Phone = ?");
        $stmt->execute([$phone]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }
    
    /**
     * 根據 ID 查找使用者
     * 
     * @param int $id 使用者 ID
     * @return array|null
     */
    public function findById(int $id): ?array
    {
        return parent::findById($id);
    }
    
    /**
     * 驗證使用者密碼
     * 
     * @param string $phone 手機號碼
     * @param string $password 密碼
     * @return bool
     */
    public function verifyPassword(string $phone, string $password): bool
    {
        $user = $this->findByPhone($phone);
        if (!$user) {
            return false;
        }
        
        // 檢查密碼是否已加密（以 $ 開頭表示已使用 password_hash）
        if (strpos($user['password'], '$') === 0) {
            return password_verify($password, $user['password']);
        } else {
            // 舊的明文密碼，直接比較（向後相容）
            return $password === $user['password'];
        }
    }
    
    /**
     * 建立新使用者
     * 
     * @param array $data 使用者資料 ['phone', 'name', 'password']
     * @return bool
     */
    public function create(array $data): bool
    {
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        
        $stmt = $this->db->prepare(
            "INSERT INTO {$this->table} (Phone, Name, password) VALUES (?, ?, ?)"
        );
        
        return $stmt->execute([
            $data['phone'],
            $data['name'] ?? 'user',
            $hashedPassword
        ]);
    }
    
    /**
     * 檢查手機號碼是否已註冊
     * 
     * @param string $phone 手機號碼
     * @return bool
     */
    public function phoneExists(string $phone): bool
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} WHERE Phone = ?");
        $stmt->execute([$phone]);
        return $stmt->fetchColumn() > 0;
    }
    
    /**
     * 更新使用者資料
     * 
     * @param int $id 使用者 ID
     * @param array $data 要更新的資料
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        // 如果更新密碼，需要加密
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        return parent::update($id, $data);
    }
}

