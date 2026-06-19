<?php

class Controller
{
    public $db = null;
    public $pageTitle = '동국산업 웹사이트 관리 시스템';
    public $currentMenu = '';

    function __construct()
    {
        if (!DEV_MODE) {
            $this->dbConnect();
        }
    }

    private function dbConnect()
    {
        $this->db = new PDO(
            DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8',
            DB_USER, DB_PASS,
            [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }

    protected function loadModel($name)
    {
        $file = './application/models/' . $name . '.php';
        if (file_exists($file)) {
            require_once $file;
            return new $name($this->db);
        }
        return null;
    }

    protected function render($view, $data = [])
    {
        $data = $this->convertToArray($data);
        extract($data);
        $currentMenu = $this->currentMenu;
        $pageTitle = $this->pageTitle;
        $baseUrl = BASE_URL;
        require 'application/views/_layout/header.php';
        require 'application/views/_layout/sidebar.php';
        require 'application/views/' . $view . '.php';
        require 'application/views/_layout/footer.php';
    }

    /** stdClass를 재귀적으로 배열로 변환 */
    private function convertToArray($data)
    {
        if (is_object($data)) $data = (array)$data;
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                if (is_object($v) || is_array($v)) {
                    $data[$k] = $this->convertToArray($v);
                }
            }
        }
        return $data;
    }

    protected function renderLogin($view, $data = [])
    {
        extract($data);
        $baseUrl = BASE_URL;
        require 'application/views/' . $view . '.php';
    }

    protected function redirect($path)
    {
        header('Location:' . BASE_URL . $path);
        exit;
    }

    protected function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    protected function getParam($key, $default = '')
    {
        return isset($_GET[$key]) ? $_GET[$key] : $default;
    }

    protected function postParam($key, $default = '')
    {
        return isset($_POST[$key]) ? $_POST[$key] : $default;
    }
}
