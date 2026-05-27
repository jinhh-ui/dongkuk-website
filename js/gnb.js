/* ================================================================
   GNB Menu — Dynamic injection + behavior
   
   Usage:
     <body data-page="nickel">
     <script src="../js/gnb.js"></script>
   ================================================================ */
(function () {
  'use strict';

  /* 경로 자동 감지: <script src="../js/gnb.js"> → B = "../" */
  var scripts = document.getElementsByTagName('script');
  var thisScript = scripts[scripts.length - 1];
  var src = thisScript.getAttribute('src') || '';
  var B = src.replace(/js\/gnb\.js.*$/, '');
  if (!B) B = './';

  var M = B || './';
  var LOGO = B + 'assets/common/logo.svg';
  var THUMB = B + 'assets/common/gnb-thumb.png';
  var PAGE = document.body.getAttribute('data-page') || '';

  /* ── 메뉴 데이터 ── */
  var categories = [
    {
      title: '회사정보',
      img: 'gnb/img_company.png',
      items: [
        { text: '회사소개', href: B + 'company/compnay-info.html', key: 'company-info' },
        { text: '연혁', href: B + 'company/history.html', key: 'history' },
        { text: '네트워크', href: B + 'company/network.html', key: 'network' },
        { text: 'CI', href: B + 'company/ci.html', key: 'ci' },
        { text: '뉴스룸', href: B + 'company/news.html', key: 'news' }
      ]
    },
    {
      title: '제품정보',
      img: 'gnb/img_product.png',
      items: [
        { text: '제품정보 센터', href: B + 'product/product-center.html', key: 'product-center' },
        { text: '니켈도금강판: DiKel', href: B + 'product/dikel.html', key: 'dikel', tabs: [
          { text: '제품소개', href: B + 'product/dikel.html#tab-product' },
          { text: 'DiKel 가치', href: B + 'product/dikel.html#tab-dikel' },
          { text: '브랜드 리소스', href: B + 'product/dikel.html#tab-brand' }
        ] },
        { text: '냉연강판', href: B + 'product/cold-rolled.html', key: 'cold-rolled' },
        { text: 'Q/T 열처리강판', href: B + 'product/heat-treated.html', key: 'heat-treated' },
        { text: '연구개발', href: B + 'product/rnd.html', key: 'rnd' }
      ]
    },
    {
      title: '투자정보',
      img: 'gnb/img_invest.png',
      items: [
        { text: '투자정보 센터', href: B + 'invest/invest-center.html', key: 'invest-center' },
        { text: '주식정보', href: B + 'invest/stock-info.html', key: 'stock-info', tabs: [
          { text: '주가현황', href: B + 'invest/stock-info.html#tab-stock' },
          { text: '주주환원', href: B + 'invest/stock-info.html#tab-return' }
        ] },
        { text: '재무정보', href: B + 'invest/finance-info.html', key: 'finance-info', tabs: [
          { text: '재무상태표', href: B + 'invest/finance-info.html#tab-stock' },
          { text: '손익계산서', href: B + 'invest/finance-info.html#tab-return' }
        ] },
        { text: '공시 및 공고', href: B + 'invest/disclosures.html', key: 'disclosures', tabs: [
          { text: '공시', href: B + 'invest/disclosures.html#tab-stock' },
          { text: '공고', href: B + 'invest/disclosures.html#tab-return' }
        ] },
        { text: 'IR 자료실', href: B + 'invest/ir.html', key: 'ir' }
      ]
    },
    {
      title: '지속가능경영',
      img: 'gnb/img_sustainability.png',
      items: [
        { text: '지속가능경영 센터', href: '#', key: '' },
        { text: '환경경영', href: '#', key: '' },
        { text: '사회경영', href: '#', key: '', tabs: [
          { text: '안전경영', href: '#' },
          { text: '품질경영', href: '#' },
          { text: '사회공헌', href: '#' }
        ] },
        { text: '윤리경영', href: '#', key: '', tabs: [
          { text: '윤리강령', href: '#' },
          { text: '윤리실천 활동', href: '#' },
          { text: '윤리경영 위반 신고', href: '#' }
        ] }
      ]
    },
    {
      title: '인재경영',
      img: 'gnb/img_career.png',
      items: [
        { text: '인재상', href: '#', key: '' },
        { text: '직무소개', href: '#', key: '' },
        { text: '복리후생', href: '#', key: '' },
        { text: '채용안내', href: '#', key: '' },
        { text: '채용공고', href: '#', key: '' }
      ]
    }
  ];

  /* ── 현재 카테고리 판별 ── */
  function getActiveCategory() {
    for (var ci = 0; ci < categories.length; ci++) {
      for (var ii = 0; ii < categories[ci].items.length; ii++) {
        if (categories[ci].items[ii].key && categories[ci].items[ii].key === PAGE) return ci;
      }
    }
    if (PAGE === 'dikel-value' || PAGE === 'brand-resource' || PAGE === 'battery') return 1;
    return -1;
  }

  /* ── HTML 빌더 ── */
  function linkHTML(item) {
    var cls = (item.key && item.key === PAGE) ? ' class="is-active"' : '';
    return '<a href="' + item.href + '"' + cls + '>' + item.text + '</a>';
  }

  function desktopHTML() {
    var h = '<div class="gnb-sitemap">';
    for (var i = 0; i < categories.length; i++) {
      var c = categories[i];
      h += '<div class="sitemap-row">';
      h += '<div class="sitemap-row-title">' + c.title + '</div>';
      h += '<div class="sitemap-row-items">';
      for (var j = 0; j < c.items.length; j++) {
        var itm = c.items[j];
        h += '<div class="sitemap-item-col">';
        h += '<a href="' + itm.href + '" class="sitemap-item">' + itm.text + '</a>';
        if (itm.tabs && itm.tabs.length > 0) {
          h += '<div class="sitemap-item-tabs">';
          for (var k = 0; k < itm.tabs.length; k++) {
            h += '<a href="' + itm.tabs[k].href + '" class="sitemap-tab">' + itm.tabs[k].text + '</a>';
          }
          h += '</div>';
        }
        h += '</div>';
      }
      h += '</div></div>';
    }
    return h + '</div>';
  }

  /* ── 모바일 HTML — Figma 16:17003 ──
     화살표/가로선 없음, 카테고리 + 서브메뉴 심플 리스트 */
  function mobileHTML() {
    var activeCat = getActiveCategory();
    var h = '<div class="gnb-menu-mobile"><div class="gnb-mob-list">';
    for (var i = 0; i < categories.length; i++) {
      var c = categories[i];
      var isActive = (i === activeCat);
      // 카테고리 타이틀
      h += '<div class="gnb-mob-cat' + (isActive ? ' is-active' : '') + '" data-cat="' + i + '">' + c.title + '</div>';
      // 서브메뉴 패널
      h += '<div class="gnb-mob-sub' + (isActive ? ' is-open' : '') + '">';
      h += '<div class="gnb-mob-sub-inner">';
      for (var j = 0; j < c.items.length; j++) {
        var item = c.items[j];
        var cls = (item.key && item.key === PAGE) ? ' class="is-active"' : '';
        h += '<a href="' + item.href + '"' + cls + '>' + item.text + '</a>';
        /* 서브서브메뉴 (tabs) 렌더링 — Figma MOB_HOME_KOR */
        if (item.tabs && item.tabs.length > 0) {
          h += '<div class="gnb-mob-tabs">';
          for (var k = 0; k < item.tabs.length; k++) {
            h += '<a href="' + item.tabs[k].href + '" class="gnb-mob-tab">' + item.tabs[k].text + '</a>';
          }
          h += '</div>';
        }
      }
      h += '</div></div>';
    }
    return h + '</div></div>';
  }

  /* ── DOM 주입 ── */
  var html = '';
  html += '<div class="gnb-overlay" id="gnb-overlay"></div>';
  html += '<div class="gnb-menu" id="gnb-menu">';
  // 모바일 전용 헤더 (데스크톱에서는 CSS로 숨김)
  html += '<div class="gnb-menu-header">';
  html += '<a href="' + B + 'index.html"><img class="gnb-menu-logo" src="' + LOGO + '" alt="DK 동국산업"></a>';
  html += '<button class="gnb-menu-close" id="gnb-close" aria-label="닫기">';
  html += '<svg viewBox="0 0 24 24" fill="none"><path d="M18 6L6 18M6 6l12 12" stroke="#1b1b1b" stroke-width="2" stroke-linecap="round"/></svg>';
  html += '</button></div>';
  // Desktop body — 기존 헤더 아래에서 바로 콘텐츠
  html += '<div class="gnb-menu-body">' + desktopHTML() + '</div>';
  // Mobile accordion
  html += mobileHTML();
  html += '</div>';

  var w = document.createElement('div');
  w.innerHTML = html;
  while (w.firstChild) document.body.appendChild(w.firstChild);

  /* 기존 헤더에 GNB 중앙 메뉴 삽입 (한화에어로스페이스 구조) */
  var header = document.querySelector('.gnb');
  var gnbUtils = document.querySelector('.gnb-utils');

  /* ── 모바일 전용 GNB 바 생성 (PC GNB와 완전 분리) ── */
  if (header) {
    var mobBar = document.createElement('header');
    mobBar.className = 'gnb-mob-bar';
    // 투명/다크 모드 복제
    if (header.classList.contains('gnb-transparent')) {
      mobBar.classList.add('gnb-mob-transparent');
      mobBar.setAttribute('data-gnb-mob-transparent', '');
    }
    if (header.classList.contains('gnb-dark')) {
      mobBar.classList.add('gnb-mob-dark');
    }
    mobBar.innerHTML = '<div class="gnb-mob-bar-logo">'
      + '<a href="' + B + 'index.html"><img src="' + LOGO + '" alt="동국산업 로고"></a>'
      + '</div>'
      + '<button class="gnb-mob-bar-btn" aria-label="메뉴"><span></span><span></span><span></span></button>';
    header.insertAdjacentElement('afterend', mobBar);

    // 모바일 햄버거 → 동일 메뉴 열기
    mobBar.querySelector('.gnb-mob-bar-btn').addEventListener('click', function(e) {
      e.preventDefault();
      e.stopPropagation();
      toggleMenu();
    });
  }

  if (header && gnbUtils && !document.querySelector('.gnb-nav')) {
      // ── nav 빌드: 각 항목에 gnb-submenu-wrap > gnb-submenu 구조 ──
      var navHtml = '<nav class="gnb-nav">';
      for (var i = 0; i < categories.length; i++) {
          navHtml += '<div class="gnb-nav-item" data-idx="' + i + '">';
          navHtml += '<a href="' + categories[i].items[0].href + '" class="gnb-nav-link">' + categories[i].title + '</a>';
          
          // 서브메뉴 래퍼 (overflow:hidden + height 트랜지션)
          navHtml += '<div class="gnb-submenu-wrap">';
          navHtml += '<div class="gnb-submenu">';
          for (var j = 0; j < categories[i].items.length; j++) {
              navHtml += '<a href="' + categories[i].items[j].href + '">' + categories[i].items[j].text + '</a>';
          }
          navHtml += '</div>'; // .gnb-submenu
          navHtml += '</div>'; // .gnb-submenu-wrap
          
          navHtml += '</div>'; // .gnb-nav-item
      }
      navHtml += '</nav>';
      gnbUtils.insertAdjacentHTML('beforebegin', navHtml);

      /* ═══ 호버 로직 ═══
         ::before 하나가 GNB 헤더 + 서브메뉴 전체를 커버.
         --gnb-expanded-height CSS 변수로 높이를 제어. */
      var navElement = header.querySelector('.gnb-nav');
      var navItems = header.querySelectorAll('.gnb-nav-item');
      var hoverTimeout = null;
      var activeIdx = -1;

      function openSubmenu(idx) {
        if (window.innerWidth <= 1439) return;
        clearTimeout(hoverTimeout);
        
        var item = navItems[idx];
        var wrap = item.querySelector('.gnb-submenu-wrap');
        var submenu = item.querySelector('.gnb-submenu');
        if (!wrap || !submenu) return;

        // 이전에 열린 다른 서브메뉴 닫기
        if (activeIdx !== -1 && activeIdx !== idx) {
          var prevWrap = navItems[activeIdx].querySelector('.gnb-submenu-wrap');
          if (prevWrap) prevWrap.classList.remove('is-active');
        }
        activeIdx = idx;

        // 해당 서브메뉴 표시
        wrap.classList.add('is-active');

        // header padding-bottom으로 확장 (한화에어로스페이스 방식)
        var contentH = submenu.scrollHeight;
        header.style.setProperty('--gnb-submenu-height', contentH);

        header.classList.add('is-hover');

        // 스크롤 차단
        document.body.style.overflow = 'hidden';
      }

      function closeAllSubmenus() {
        if (window.innerWidth <= 1439) return;
        hoverTimeout = setTimeout(function() {
          for (var i = 0; i < navItems.length; i++) {
            var wrap = navItems[i].querySelector('.gnb-submenu-wrap');
            if (wrap) wrap.classList.remove('is-active');
          }
          header.style.setProperty('--gnb-submenu-height', 0);
          header.classList.remove('is-hover');
          activeIdx = -1;

          // 스크롤 복원
          document.body.style.overflow = '';
        }, 80);
      }

      // 각 nav-item에 mouseenter만 바인딩 (어떤 서브메뉴를 열지 결정)
      for (var ni = 0; ni < navItems.length; ni++) {
        (function(idx) {
          navItems[idx].addEventListener('mouseenter', function() {
            openSubmenu(idx);
          });
        })(ni);
      }

      // 헤더 전체에서 mouseleave — 확장된 영역 포함 전체를 벗어나야 닫힘
      header.addEventListener('mouseleave', function() {
        closeAllSubmenus();
      });
  }

  /* ── 요소 ── */
  var menu = document.getElementById('gnb-menu');
  var overlay = document.getElementById('gnb-overlay');
  var closeBtn = document.getElementById('gnb-close');

  var savedScrollY = 0;

  function openMenu() {
    savedScrollY = window.scrollY;
    menu.classList.add('is-open');
    overlay.classList.add('is-open');
    // body를 fixed로 고정 → 스크롤바는 유지, 스크롤만 차단 → 밀림 없음
    document.body.style.position = 'fixed';
    document.body.style.top = -savedScrollY + 'px';
    document.body.style.width = '100%';
    document.body.style.overflowY = 'scroll';
    var pageHeader = document.querySelector('#nav, .gnb, .header');
    if (pageHeader) {
        // 모바일/태블릿에서는 gnb-menu-active 안 붙임 (GNB 스타일 변경 없이 사이드 메뉴만)
        if (window.innerWidth > 1439) {
            pageHeader.classList.add('gnb-menu-active');
        }
        // 항상 다크 모드 사이트맵
        menu.classList.add('sitemap-dark');
    }
  }
  function closeMenu() {
    menu.classList.remove('is-open');
    overlay.classList.remove('is-open');
    document.body.style.position = '';
    document.body.style.top = '';
    document.body.style.width = '';
    document.body.style.overflowY = '';
    window.scrollTo(0, savedScrollY);
    var pageHeader = document.querySelector('#nav, .gnb, .header');
    if (pageHeader) pageHeader.classList.remove('gnb-menu-active');
  }
  function toggleMenu() {
    if (menu.classList.contains('is-open')) {
      closeMenu();
    } else {
      openMenu();
    }
  }

  /* ── 햄버거 토글 연결 (열기/닫기 모두) ── */
  var triggers = document.querySelectorAll('.nav-hamburger, .gnb-menu-btn, .btn-menu');
  for (var t = 0; t < triggers.length; t++) {
    triggers[t].addEventListener('click', function (e) {
      e.preventDefault();
      e.stopPropagation();
      toggleMenu();
    });
  }

  closeBtn.addEventListener('click', closeMenu);
  overlay.addEventListener('click', closeMenu);
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && menu.classList.contains('is-open')) closeMenu();
  });

  /* ── 모바일 카테고리 토글 ── */
  var mobCats = menu.querySelectorAll('.gnb-mob-cat');
  for (var a = 0; a < mobCats.length; a++) {
    mobCats[a].addEventListener('click', function (e) {
      e.preventDefault();
      var c = this.getAttribute('data-cat');
      var allCats = menu.querySelectorAll('.gnb-mob-cat');
      var allSubs = menu.querySelectorAll('.gnb-mob-sub');
      for (var i = 0; i < allCats.length; i++) {
        if (i == c) {
          allCats[i].classList.toggle('is-active');
          allSubs[i].classList.toggle('is-open');
        } else {
          allCats[i].classList.remove('is-active');
          allSubs[i].classList.remove('is-open');
        }
      }
    });
  }

  /* ── 창 크기 조절 시 hover 간섭(깜빡임) 방지 ── */
  var resizeTimer;
  window.addEventListener('resize', function() {
    document.body.classList.add('is-resizing');
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(function() {
      document.body.classList.remove('is-resizing');
    }, 200);
  });
})();

/* ══════════════════════════════════════════════════════════
   GNB 스크롤 숨김/표시 — 모든 페이지 통합 로직
   (한화에어로스페이스 스타일)
   
   원칙:
   1. 스크롤 다운 → GNB 위로 숨김
   2. 스크롤 업 → GNB 다시 표시
   3. 최상단에서는 항상 표시
   4. 투명 GNB 페이지: 최상단 = 투명, 스크롤 = solid
   ══════════════════════════════════════════════════════════ */
document.addEventListener('DOMContentLoaded', () => {
  const gnb = document.querySelector('.gnb');
  const mobBar = document.querySelector('.gnb-mob-bar');
  if (!gnb && !mobBar) return;

  // 히어로 영역 자동 감지
  const hasHero = (gnb && gnb.hasAttribute('data-gnb-transparent'))
    || (mobBar && mobBar.hasAttribute('data-gnb-mob-transparent'))
    || document.querySelector('.hero-section, #hero, .page-hero');
  
  if (gnb && hasHero && !gnb.classList.contains('gnb-transparent')) {
    gnb.classList.add('gnb-transparent');
    gnb.setAttribute('data-gnb-transparent', '');
  }

  const useTransparent = (gnb && gnb.hasAttribute('data-gnb-transparent'))
    || (mobBar && mobBar.hasAttribute('data-gnb-mob-transparent'));
  let lastScrollY = window.scrollY;
  let ticking = false;

  function updateGnb() {
    // 메뉴 열려있으면 스킵 (body fixed → scrollY=0이 되어 상태 꼬임 방지)
    if (document.body.style.position === 'fixed') {
      ticking = false;
      return;
    }
    const currentScrollY = window.scrollY;
    const delta = currentScrollY - lastScrollY;

    // 1. 투명 ↔ solid 전환
    if (useTransparent) {
      const atTop = currentScrollY <= 10;
      if (gnb) {
        gnb.classList.toggle('gnb-transparent', atTop);
        gnb.classList.toggle('gnb-solid', !atTop);
      }
      if (mobBar) {
        mobBar.classList.toggle('gnb-mob-transparent', atTop);
        mobBar.classList.toggle('gnb-mob-solid', !atTop);
      }
    }

    // 2. 스크롤 숨김/표시
    if (currentScrollY <= 5) {
      if (gnb) gnb.classList.remove('gnb-hidden');
      if (mobBar) mobBar.classList.remove('gnb-mob-hidden');
    } else if (delta > 3) {
      if (gnb) gnb.classList.add('gnb-hidden');
      if (mobBar) mobBar.classList.add('gnb-mob-hidden');
    } else if (delta < -3) {
      if (gnb) gnb.classList.remove('gnb-hidden');
      if (mobBar) mobBar.classList.remove('gnb-mob-hidden');
    }

    lastScrollY = currentScrollY;
    ticking = false;
  }

  window.addEventListener('scroll', function() {
    if (!ticking) {
      requestAnimationFrame(updateGnb);
      ticking = true;
    }
  }, { passive: true });

  // 초기 실행
  updateGnb();
});

/* ── BTN TOP 공통 로직 (Responsive) ── */
document.addEventListener('DOMContentLoaded', function () {
  const btnTop = document.getElementById('btn-top');
  const footer = document.querySelector('.footer');

  if (!btnTop) return;

  btnTop.addEventListener('click', function (e) {
    e.preventDefault();
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });

  window.addEventListener('scroll', function () {
    const scrollY = window.scrollY;
    const windowHeight = window.innerHeight;
    const scrollBottom = scrollY + windowHeight;

    if (scrollY > 300) {
      btnTop.classList.add('show');
    } else {
      btnTop.classList.remove('show');
    }

      const boundaryEl = footer;
    if (boundaryEl) {
      const boundaryTop = boundaryEl.getBoundingClientRect().top + scrollY;
      const overlap = scrollBottom - boundaryTop;
      const isMobile = window.innerWidth <= 1439;
      const hasProductTabs = !!document.querySelector('.product-tabs');
      const hasHistoryFab = !!document.querySelector('.history-fab');
      
      let baseBottom = 16;
      if (isMobile) {
        if (hasProductTabs || hasHistoryFab) {
          baseBottom = 83; // 53px (tabs height/fab height) + 30px (gap) = 83px
        } else {
          baseBottom = 16;
        }
      }

      // Calculate btnTop bottom position
      if (isMobile && (hasProductTabs || hasHistoryFab)) {
        if (overlap > 0) {
          btnTop.style.bottom = `${overlap + baseBottom}px`;
        } else {
          btnTop.style.bottom = `${baseBottom}px`;
        }
      } else {
        if (overlap > baseBottom) {
          btnTop.style.bottom = `${overlap}px`;
        } else {
          btnTop.style.bottom = `${baseBottom}px`;
        }
      }

      // Adjust product-tabs position to prevent overlapping boundaryEl
      const productTabsList = document.querySelectorAll('.product-tabs');
      if (productTabsList.length > 0) {
        productTabsList.forEach(tabs => {
          if (isMobile) {
            if (overlap > 0) {
              tabs.style.bottom = `${overlap}px`;
            } else {
              tabs.style.bottom = `0px`;
            }
          } else {
            if (overlap > 40) {
              tabs.style.bottom = `${overlap}px`;
            } else {
              tabs.style.bottom = `40px`;
            }
          }
        });
      }
    }
  });
});

/* ══════════════════════════════════════════════════════════
   커스텀 오버레이 스크롤바 (한화에어로스페이스 .c-scrollbar 방식)
   네이티브 스크롤바는 CSS로 숨기고, position:fixed 요소로 대체.
   스크롤 시 나타나고 멈추면 서서히 사라짐.
   ══════════════════════════════════════════════════════════ */
(function() {
  var bar = document.createElement('div');
  bar.className = 'c-scrollbar';
  var thumb = document.createElement('div');
  thumb.className = 'c-scrollbar-thumb';
  bar.appendChild(thumb);
  document.body.appendChild(bar);

  var hideTimer = null;
  var isDragging = false;
  var dragStartY = 0;
  var dragStartScroll = 0;

  function updateThumb() {
    var docH = document.documentElement.scrollHeight;
    var winH = window.innerHeight;
    if (docH <= winH) { bar.style.opacity = '0'; return; }
    var ratio = winH / docH;
    var thumbH = Math.max(ratio * winH, 40);
    var scrollRatio = window.scrollY / (docH - winH);
    var maxTop = winH - thumbH;
    thumb.style.height = thumbH + 'px';
    thumb.style.top = (scrollRatio * maxTop) + 'px';
  }

  function showBar() {
    bar.style.opacity = '1';
    clearTimeout(hideTimer);
    hideTimer = setTimeout(function() {
      if (!isDragging) bar.style.opacity = '0';
    }, 1200);
  }

  window.addEventListener('scroll', function() {
    updateThumb();
    showBar();
  }, { passive: true });

  window.addEventListener('resize', updateThumb);

  // 드래그
  thumb.addEventListener('mousedown', function(e) {
    isDragging = true;
    dragStartY = e.clientY;
    dragStartScroll = window.scrollY;
    bar.style.opacity = '1';
    document.body.style.userSelect = 'none';
    e.preventDefault();
  });

  window.addEventListener('mousemove', function(e) {
    if (!isDragging) return;
    var docH = document.documentElement.scrollHeight;
    var winH = window.innerHeight;
    var delta = e.clientY - dragStartY;
    var ratio = delta / winH;
    window.scrollTo(0, dragStartScroll + ratio * (docH - winH));
  });

  window.addEventListener('mouseup', function() {
    if (!isDragging) return;
    isDragging = false;
    document.body.style.userSelect = '';
    showBar();
  });

  // 바 클릭으로 점프
  bar.addEventListener('click', function(e) {
    if (e.target === thumb) return;
    var docH = document.documentElement.scrollHeight;
    var winH = window.innerHeight;
    var ratio = e.clientY / winH;
    window.scrollTo({ top: ratio * (docH - winH), behavior: 'smooth' });
  });

  updateThumb();
})();
