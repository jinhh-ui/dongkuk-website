/* admin.js — 관리자 공통 스크립트 */

// LNB 토글
document.addEventListener('DOMContentLoaded', function() {
    var toggles = document.querySelectorAll('.lnb-toggle');
    toggles.forEach(function(toggle) {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            var parent = this.closest('.lnb-has-sub');
            if (parent) parent.classList.toggle('open');
        });
    });
});
