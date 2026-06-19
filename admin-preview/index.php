<?php
session_start();

// [DEV] 로컬 개발용 — 로그인 스킵
if (!isset($_SESSION["userinfo"])) {
    $_SESSION["userinfo"] = (object)[
        'USER_ID' => 'dev',
        'USER_NAME' => '개발자',
        'LEVEL_CODE' => '0'
    ];
}

require_once './application/config/config.php';
require_once './application/libs/Controller.php';
require_once './application/libs/App.php';
$app = new App();
