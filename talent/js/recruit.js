/* ══════════════════════════════════════
   채용안내 (recruit.html) — page script
══════════════════════════════════════ */

/* ── 도입 타이틀 글자 색상 전환 (스크롤 기반, talent.js 동일 패턴) ── */
document.addEventListener('DOMContentLoaded', function () {
    var outer = document.querySelector('.rec-intro-outer');
    if (!outer) return;

    var title = outer.querySelector('.rec-intro-title');
    if (!title) return;

    function wrapCharacters(node) {
        if (node.nodeType === Node.TEXT_NODE) {
            var text = node.textContent;
            var frag = document.createDocumentFragment();
            for (var i = 0; i < text.length; i++) {
                var span = document.createElement('span');
                span.className = 'char';
                span.textContent = text[i];
                frag.appendChild(span);
            }
            node.parentNode.replaceChild(frag, node);
        } else if (node.nodeType === Node.ELEMENT_NODE && node.tagName !== 'BR') {
            Array.from(node.childNodes).forEach(wrapCharacters);
        }
    }
    wrapCharacters(title);

    var chars = title.querySelectorAll('.char');

    function onScroll() {
        var rect = outer.getBoundingClientRect();
        var outerH = outer.offsetHeight;
        var innerH = window.innerHeight;
        var scrollRoom = outerH - innerH;
        var scrolled = -rect.top;
        var progress = Math.max(0, Math.min(1, scrolled / Math.max(scrollRoom, 1)));

        var activeCount = Math.floor(progress * chars.length);
        chars.forEach(function (c, i) {
            if (i < activeCount) c.classList.add('active');
        });

        if (activeCount >= chars.length) {
            window.removeEventListener('scroll', onScroll);
        }
    }

    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
});


/* ── FAQ 탭 필터링 ── */
document.addEventListener('DOMContentLoaded', function () {
    var tabs = document.querySelectorAll('.rec-faq-tab');
    var items = document.querySelectorAll('.rec-faq-item');

    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            // 탭 active 상태
            tabs.forEach(function (t) { t.classList.remove('is-active'); });
            tab.classList.add('is-active');

            var category = tab.getAttribute('data-tab');

            items.forEach(function (item) {
                if (category === 'all') {
                    item.style.display = '';
                } else {
                    var itemCat = item.getAttribute('data-category');
                    item.style.display = (itemCat === category) ? '' : 'none';
                }
            });
        });
    });
});


/* ── FAQ 아코디언 토글 ── */
document.addEventListener('DOMContentLoaded', function () {
    var items = document.querySelectorAll('.rec-faq-item');

    items.forEach(function (item) {
        var qRow = item.querySelector('.rec-faq-q');
        if (!qRow) return;

        qRow.addEventListener('click', function () {
            var isOpen = item.classList.contains('is-open');

            // 다른 아이템 모두 닫기
            items.forEach(function (other) {
                other.classList.remove('is-open');
            });

            // 클릭한 아이템 토글
            if (!isOpen) {
                item.classList.add('is-open');
            }
        });
    });
});


/* ── 이미지 없을 때 placeholder 처리 ── */
(function () {
    var img = document.querySelector('.rec-intro-img');
    if (img) {
        img.addEventListener('error', function () {
            var wrap = img.parentElement;
            if (wrap) {
                img.style.display = 'none';
                wrap.style.background = '#D9D9D9';
            }
        });
    }
})();
