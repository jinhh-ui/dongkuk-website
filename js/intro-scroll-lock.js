/* ══════════════════════════════════════
   intro-scroll-lock.js — 공통 인트로 글자 칠하기 (스크롤 잠금)
   모든 *-intro-outer / *-intro-title 요소에 자동 적용
══════════════════════════════════════ */

(function () {
    var outer = document.querySelector('[class*="-intro-outer"]');
    if (!outer) return;

    var title = outer.querySelector('[class*="-intro-title"]');
    if (!title) return;

    /* ── 글자 개별 span 래핑 ── */
    function wrapCharacters(node) {
        if (node.nodeType === Node.TEXT_NODE) {
            var text = node.textContent;
            var frag = document.createDocumentFragment();
            for (var i = 0; i < text.length; i++) {
                if (text[i] === ' ') {
                    frag.appendChild(document.createTextNode(' '));
                } else {
                    var span = document.createElement('span');
                    span.className = 'char';
                    span.textContent = text[i];
                    frag.appendChild(span);
                }
            }
            node.parentNode.replaceChild(frag, node);
        } else if (node.nodeType === Node.ELEMENT_NODE && node.tagName !== 'BR') {
            Array.from(node.childNodes).forEach(wrapCharacters);
        }
    }
    wrapCharacters(title);

    var chars = title.querySelectorAll('.char');
    if (!chars.length) return;

    var locked = false, done = false, progress = 0;
    var TOTAL = 500;
    var savedY = 0, touchY = 0;

    /* ── 로드 시 이미 인트로를 지나간 경우: 즉시 완료 처리 ── */
    var outerRect = outer.getBoundingClientRect();
    if (outerRect.bottom <= 0) {
        done = true;
        for (var i = 0; i < chars.length; i++) chars[i].classList.add('active');
    }

    /* ── 스크롤 잠금 ── */
    function lockScroll() {
        if (locked) return;
        locked = true;
        savedY = window.scrollY;
        document.body.style.position = 'fixed';
        document.body.style.top = '-' + savedY + 'px';
        document.body.style.width = '100%';
        document.body.style.overflowY = 'scroll';
    }

    /* ── 스크롤 해제 ── */
    function unlockScroll(completed) {
        locked = false;
        if (completed) done = true;
        document.body.style.position = '';
        document.body.style.top = '';
        document.body.style.width = '';
        document.body.style.overflowY = '';
        window.scrollTo(0, completed ? savedY : Math.max(0, savedY - 5));
    }

    /* ── 글자 칠하기 ── */
    function updateAnim(delta) {
        progress += delta;
        if (progress <= 0) { progress = 0; unlockScroll(false); return; }
        var ratio = Math.min(progress / TOTAL, 1);
        var count = Math.floor(ratio * chars.length);
        for (var i = 0; i < chars.length; i++) {
            if (i < count) chars[i].classList.add('active');
            else chars[i].classList.remove('active');
        }
        if (count >= chars.length) unlockScroll(true);
    }

    /* ── 이벤트 리스너 ── */
    window.addEventListener('scroll', function () {
        if (done || locked) return;
        var rect = outer.getBoundingClientRect();
        /* 인트로가 뷰포트 위로 완전히 사라진 경우(이미 지나감) → 잠금하지 않고 완료 처리 */
        if (rect.bottom <= 0) {
            done = true;
            for (var i = 0; i < chars.length; i++) chars[i].classList.add('active');
            return;
        }
        if (rect.top <= 0) lockScroll();
    }, { passive: true });

    window.addEventListener('wheel', function (e) {
        if (!locked) return;
        e.preventDefault();
        updateAnim(e.deltaY);
    }, { passive: false });

    window.addEventListener('touchstart', function (e) {
        if (!locked) return;
        touchY = e.touches[0].clientY;
    }, { passive: true });

    window.addEventListener('touchmove', function (e) {
        if (!locked) return;
        e.preventDefault();
        var d = touchY - e.touches[0].clientY;
        touchY = e.touches[0].clientY;
        updateAnim(d);
    }, { passive: false });
})();
