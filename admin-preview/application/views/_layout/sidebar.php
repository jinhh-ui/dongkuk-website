<!-- LNB 사이드바 — 디자인 파일 1:218 (8w3aLuqU0kym8j9AYXh6Bn)
     w-320 bg-white px-30 py-40 gap-60 flex-col
     로고: gap-6, img 58x30, text 20px Bold black leading-1.5
     카테고리: 18px Medium #a7a7a7 px-24 py-4 leading-1.6
     메뉴: 20px SemiBold #111 px-24 py-10 leading-1.6 rounded-12
     서브메뉴: 18px Medium #111 px-40 py-10 rounded-12 leading-1.6
     활성: bg-[#ecf0f9] #003592 SemiBold rounded-12
     로그아웃: 20px Regular #4c4d4d underline px-24 py-16 h-97 leading-1.6
-->
<aside class="lnb">
    <!-- 로고: gap-6, img 58x30, text 20px Bold — 1:219 -->
    <a href="<?= $baseUrl ?>/news/list" class="lnb-logo">
        <img src="<?= $baseUrl ?>/public/img/logo_dongkuk.svg" alt="DK">
        <span class="lnb-logo-text">동국산업 웹사이트<br>관리 시스템</span>
    </a>

    <!-- 메뉴 컨테이너: gap-12 — 1:225 -->
    <nav class="lnb-nav">
        <!-- 메뉴 그룹: gap-16 — 1:226 -->
        <div class="lnb-group">
            <div class="lnb-group-items">
                <!-- 카테고리: 18px Medium #a7a7a7 px-24 py-4 — 1:228 -->
                <div class="lnb-section">게시물 관리</div>

                <!-- 뉴스룸 (토글 부모) — 1:230 -->
                <?php $newsOpen = in_array($currentMenu, ['news-video','news-article']); ?>
                <a href="javascript:;" class="lnb-item" onclick="this.nextElementSibling.classList.toggle('show'); var a=this.querySelector('.lnb-arrow'); a.classList.toggle('open');">
                    <span>뉴스룸</span>
                    <img src="<?= $baseUrl ?>/public/img/icon_chevron_down.svg" alt="" class="lnb-arrow <?= $newsOpen ? 'open' : '' ?>">
                </a>
                <div class="lnb-submenu <?= $newsOpen ? 'show' : '' ?>">
                    <a href="<?= $baseUrl ?>/video/list" class="lnb-sub <?= $currentMenu === 'news-video' ? 'active' : '' ?>">대표 영상 관리</a>
                    <a href="<?= $baseUrl ?>/news/list" class="lnb-sub <?= $currentMenu === 'news-article' ? 'active' : '' ?>">뉴스 기사 관리</a>
                </div>

                <!-- 1단계 메뉴: 20px SemiBold #111 px-24 py-10 rounded-12 — 1:238~ -->
                <a href="<?= $baseUrl ?>/notice/list" class="lnb-item <?= $currentMenu === 'notice' ? 'active' : '' ?>"><span>공고</span></a>
                <a href="<?= $baseUrl ?>/ir/list" class="lnb-item <?= $currentMenu === 'ir' ? 'active' : '' ?>"><span>IR자료실</span></a>
                <a href="<?= $baseUrl ?>/recruit/list" class="lnb-item <?= $currentMenu === 'recruit' ? 'active' : '' ?>"><span>채용공고</span></a>
            </div>
        </div>

        <!-- 구분선 — 1:244 -->
        <div class="lnb-divider"><img src="<?= $baseUrl ?>/public/img/icon_divider_h.svg" alt=""></div>

        <!-- 고객지원 그룹 — 1:245 -->
        <div class="lnb-group">
            <div class="lnb-group-items">
                <div class="lnb-section">고객지원</div>
                <a href="<?= $baseUrl ?>/report/list" class="lnb-item <?= $currentMenu === 'report' ? 'active' : '' ?>"><span>제보하기</span></a>
                <a href="<?= $baseUrl ?>/inquiry/list" class="lnb-item <?= $currentMenu === 'inquiry' ? 'active' : '' ?>"><span>문의하기</span></a>
            </div>
        </div>

        <!-- 구분선 — 1:252 -->
        <div class="lnb-divider"><img src="<?= $baseUrl ?>/public/img/icon_divider_h.svg" alt=""></div>

        <!-- 설정 그룹 — 1:253 -->
        <div class="lnb-group">
            <div class="lnb-group-items">
                <div class="lnb-section">설정</div>
                <a href="<?= $baseUrl ?>/deletelog/list" class="lnb-item <?= $currentMenu === 'deletelog' ? 'active' : '' ?>"><span>파기 이력</span></a>
            </div>
        </div>

        <!-- 구분선 — 1:258 -->
        <div class="lnb-divider"><img src="<?= $baseUrl ?>/public/img/icon_divider_h.svg" alt=""></div>

        <!-- 로그아웃: 20px Regular #4c4d4d underline px-24 py-16 h-97 — 1:259 -->
        <a href="<?= $baseUrl ?>/logout" class="lnb-logout">로그아웃</a>
    </nav>
</aside>

<!-- 메인 콘텐츠 -->
<main class="content">
