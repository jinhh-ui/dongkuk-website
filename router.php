<?php
/**
 * PHP 내장 서버용 라우터 (로컬 개발 전용)
 * 실행: php -S localhost:8080 router.php
 */

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// 정적 파일 (css, js, 이미지) → 직접 서빙
if (preg_match('/\.(css|js|png|jpg|jpeg|gif|svg|ico|woff2?|ttf|eot)$/i', $uri)) {
    $file = __DIR__ . $uri;
    if (file_exists($file)) {
        return false; // 내장 서버가 알아서 처리
    }
}

// /admin/ 하위 요청 → admin/index.php로 라우팅
if (strpos($uri, '/admin') === 0) {
    $path = substr($uri, strlen('/admin'));
    $path = ltrim($path, '/');
    $_GET['url'] = $path;
    chdir(__DIR__ . '/admin');
    require __DIR__ . '/admin/index.php';
    return;
}

// 그 외 → 정적 파일 (메인 사이트)
$file = __DIR__ . $uri;
if (is_file($file)) {
    return false;
}
if (is_dir($file) && file_exists($file . '/index.html')) {
    readfile($file . '/index.html');
    return;
}

// 루트 → index.html
if ($uri === '/') {
    readfile(__DIR__ . '/index.html');
    return;
}

http_response_code(404);
echo '404 Not Found';
