<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>동국산업 관리자 로그인</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pretendard:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Pretendard',sans-serif; background:#f1f3f7; display:flex; align-items:center; justify-content:center; min-height:100vh; }
        .login-card { width:560px; background:#fff; border-radius:20px; padding:60px; display:flex; flex-direction:column; gap:40px; }
        .login-logo { display:flex; gap:6px; align-items:flex-start; }
        .login-logo img { width:58px; height:30px; }
        .login-logo-text { font-size:20px; font-weight:700; color:#000; line-height:1.5; }
        .login-title { font-size:36px; font-weight:700; color:#000; line-height:1.5; }
        .login-form { display:flex; flex-direction:column; gap:20px; }
        .login-input { width:100%; height:60px; border:1px solid #a7a7a7; border-radius:10px; padding:0 24px; font-size:22px; font-weight:400; color:#000; font-family:'Pretendard',sans-serif; }
        .login-input::placeholder { color:#a7a7a7; }
        .login-btn { width:100%; height:80px; background:#003592; border:none; border-radius:10px; font-size:24px; font-weight:700; color:#fff; cursor:pointer; font-family:'Pretendard',sans-serif; margin-top:20px; }
        .login-error { font-size:18px; font-weight:500; color:#f27100; line-height:1.6; display:none; }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-logo">
            <img src="<?= isset($baseUrl) ? $baseUrl : '' ?>/public/img/logo_small.svg" alt="동국산업">
            <div class="login-logo-text">동국산업 웹사이트<br>관리 시스템</div>
        </div>
        <div class="login-title">로그인</div>
        <?php if (isset($error)): ?>
        <div class="login-error" style="display:block;"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="post" action="<?= isset($baseUrl) ? $baseUrl : '' ?>/auth/login" class="login-form">
            <input type="text" name="username" class="login-input" placeholder="아이디를 입력해주세요." required>
            <input type="password" name="password" class="login-input" placeholder="비밀번호를 입력해주세요." required>
            <button type="submit" class="login-btn">로그인</button>
        </form>
    </div>
</body>
</html>
