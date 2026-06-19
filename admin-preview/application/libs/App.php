<?php

class App
{
    public function __construct()
    {
        $url = '';
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
        }

        $parts = $url ? explode('/', $url) : [];
        $controllerName = isset($parts[0]) && $parts[0] ? $parts[0] : 'login';
        $action = isset($parts[1]) && $parts[1] ? $parts[1] : 'index';
        $params = array_slice($parts, 2);

        // 컨트롤러 매핑
        $map = [
            'login'     => 'LoginController',
            'logout'    => 'LogoutController',
            'news'      => 'NewsController',
            'video'     => 'VideoController',
            'notice'    => 'NoticeController',
            'ir'        => 'IrController',
            'recruit'   => 'RecruitController',
            'report'    => 'ReportController',
            'inquiry'   => 'InquiryController',
            'deletelog' => 'DeleteLogController',
        ];

        if (!isset($map[$controllerName])) {
            http_response_code(404);
            echo "<!DOCTYPE html><html><head><meta charset='utf-8'></head><body><h1>페이지를 찾을 수 없습니다.</h1></body></html>";
            return;
        }

        $className = $map[$controllerName];
        $file = './application/controllers/' . $className . '.php';

        if (!file_exists($file)) {
            http_response_code(404);
            echo "<!DOCTYPE html><html><head><meta charset='utf-8'></head><body><h1>준비 중입니다.</h1></body></html>";
            return;
        }

        require_once $file;
        $controller = new $className();

        if (method_exists($controller, $action)) {
            call_user_func_array([$controller, $action], $params);
        } else {
            // action이 없으면 index에 파라미터로 전달
            if (method_exists($controller, 'index')) {
                array_unshift($params, $action);
                call_user_func_array([$controller, 'index'], $params);
            }
        }
    }
}
