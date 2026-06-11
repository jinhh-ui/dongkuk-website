/* ══════════════════════════════════════
   복리후생 (welfare.html) — page script
══════════════════════════════════════ */

// 이미지 없을 때 placeholder 처리
(function () {
    var imgs = document.querySelectorAll('.wel-card-img');
    imgs.forEach(function (img) {
        img.addEventListener('error', function () {
            var wrap = img.parentElement;
            if (wrap) {
                img.style.display = 'none';
                wrap.style.background = '#D9D9D9';
            }
        });
    });
})();
