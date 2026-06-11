/* ══════════════════════════════════════
   채용공고_없는경우 (job-posting-empty.html)
══════════════════════════════════════ */

/* ── 탭 클릭 (필터 상태만 표시, 항상 empty 상태 유지) ── */
(function () {
    'use strict';
    var tabs = document.querySelectorAll('.jpe-tab');
    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            tabs.forEach(function (t) { t.classList.remove('is-active'); });
            tab.classList.add('is-active');
        });
    });
})();
