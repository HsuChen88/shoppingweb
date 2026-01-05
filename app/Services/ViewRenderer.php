<?php

namespace App\Services;

class ViewRenderer
{
    private $viewsPath;
    
    public function __construct()
    {
        $this->viewsPath = __DIR__ . '/../Views';
    }
    
    /**
     * 渲染視圖
     * 
     * @param string $view 視圖檔案名稱（相對於 Views 目錄）
     * @param array $data 傳遞給視圖的變數
     * @param bool $useLayout 是否使用完整佈局（包含 header 和 footer）
     */
    public function render(string $view, array $data = [], bool $useLayout = true): void
    {
        // 將資料陣列轉換為變數
        extract($data);
        
        if ($useLayout) {
            // 載入 header
            $headerPath = $this->viewsPath . '/layouts/header.php';
            if (file_exists($headerPath)) {
                include $headerPath;
            }
        }
        
        // 載入主要視圖
        $viewPath = $this->viewsPath . '/' . $view . '.php';
        if (!file_exists($viewPath)) {
            throw new \RuntimeException("View not found: {$view}");
        }
        
        include $viewPath;
        
        if ($useLayout) {
            // 載入 footer
            $footerPath = $this->viewsPath . '/layouts/footer.php';
            if (file_exists($footerPath)) {
                include $footerPath;
            }
        }
    }
    
    /**
     * 渲染完整頁面（使用 layout.php）
     * 
     * @param string $view 視圖檔案名稱
     * @param array $data 傳遞給視圖的變數
     */
    public function renderWithLayout(string $view, array $data = []): void
    {
        $layoutPath = $this->viewsPath . '/layouts/layout.php';
        if (!file_exists($layoutPath)) {
            throw new \RuntimeException("Layout not found: layouts/layout.php");
        }
        
        // 將資料陣列轉換為變數
        extract($data);
        
        // 設定視圖路徑
        $viewPath = $this->viewsPath . '/' . $view . '.php';
        if (!file_exists($viewPath)) {
            throw new \RuntimeException("View not found: {$view}");
        }
        
        // 設定視圖路徑供 layout 使用
        $contentView = $viewPath;
        
        include $layoutPath;
    }
    
    /**
     * 取得視圖路徑
     */
    public function getViewsPath(): string
    {
        return $this->viewsPath;
    }
}
