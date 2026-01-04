<?php

namespace App\Core;

use App\Services\ViewRenderer;

abstract class Controller
{
    protected $viewRenderer;
    
    public function __construct()
    {
        $this->viewRenderer = new ViewRenderer();
    }
    
    /**
     * 渲染視圖
     * 
     * @param string $view 視圖檔案名稱
     * @param array $data 傳遞給視圖的變數
     * @param bool $useLayout 是否使用完整佈局
     */
    protected function render(string $view, array $data = [], bool $useLayout = true): void
    {
        $this->viewRenderer->render($view, $data, $useLayout);
    }
    
    /**
     * 渲染完整頁面（使用 layout.php）
     * 
     * @param string $view 視圖檔案名稱
     * @param array $data 傳遞給視圖的變數
     */
    protected function renderWithLayout(string $view, array $data = []): void
    {
        $this->viewRenderer->renderWithLayout($view, $data);
    }
    
    /**
     * 重定向
     * 
     * @param string $url 重定向的 URL
     */
    protected function redirect(string $url): void
    {
        header("Location: {$url}");
        exit;
    }
    
    /**
     * 返回 JSON 回應
     * 
     * @param array $data 要返回的資料
     * @param int $statusCode HTTP 狀態碼
     */
    protected function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    /**
     * 顯示錯誤訊息並重定向
     * 
     * @param string $message 錯誤訊息
     * @param string $redirectUrl 重定向的 URL
     */
    protected function errorAndRedirect(string $message, string $redirectUrl): void
    {
        echo '<script language="javascript">';
        echo "alert(\"" . addslashes($message) . "\");";
        echo "location.href='{$redirectUrl}';";
        echo "</script>";
        exit;
    }
    
    /**
     * 顯示成功訊息並重定向
     * 
     * @param string $message 成功訊息
     * @param string $redirectUrl 重定向的 URL
     */
    protected function successAndRedirect(string $message, string $redirectUrl): void
    {
        echo '<script language="javascript">';
        echo "alert(\"" . addslashes($message) . "\");";
        echo "location.href='{$redirectUrl}';";
        echo "</script>";
        exit;
    }
}

