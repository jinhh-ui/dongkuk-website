/* ══════════════════════════════════════
   채용안내 (recruit.html) — page script
   인트로 글자 칠하기는 intro-scroll-lock.js에서 공통 처리
══════════════════════════════════════ */


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
