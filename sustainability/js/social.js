/* ================================================================
   social.js — 사회공헌 페이지 스크립트
   인트로 글자 칠하기는 intro-scroll-lock.js에서 공통 처리
   ================================================================ */

/* ── 모금활동 / 봉사활동 슬라이더 ── */
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.soc-activity-section').forEach(function (section) {
        var slides = section.querySelectorAll('.soc-activity-slide');
        var btns   = section.querySelectorAll('.soc-page-btn');
        if (!slides.length) return;

        var current = 0;

        function updateSlider() {
            slides.forEach(function (slide, i) {
                slide.classList.toggle('soc-activity-slide--active', i === current);
            });
            if (btns[0]) btns[0].disabled = current === 0;
            if (btns[1]) btns[1].disabled = current === slides.length - 1;
        }

        if (btns[0]) {
            btns[0].addEventListener('click', function () {
                if (current > 0) { current--; updateSlider(); }
            });
        }
        if (btns[1]) {
            btns[1].addEventListener('click', function () {
                if (current < slides.length - 1) { current++; updateSlider(); }
            });
        }

        updateSlider();
    });
});

/* ── 핵심가치 타원 점선 (SVG stroke-dasharray로 간격 조절) ── */
document.querySelectorAll('.soc-value-oval').forEach(function (oval) {
    var ns = 'http://www.w3.org/2000/svg';
    var svg = document.createElementNS(ns, 'svg');
    svg.style.cssText = 'position:absolute;inset:0;width:100%;height:100%;pointer-events:none;';

    var rect = document.createElementNS(ns, 'rect');
    rect.setAttribute('fill', 'none');
    rect.setAttribute('stroke', '#D3D3D3');
    rect.setAttribute('stroke-width', '3');
    rect.setAttribute('stroke-dasharray', '7 7');

    svg.appendChild(rect);
    oval.appendChild(svg);

    function update() {
        var w = oval.offsetWidth;
        var h = oval.offsetHeight;
        var r = Math.min(w, h) / 2;
        svg.setAttribute('viewBox', '0 0 ' + w + ' ' + h);
        rect.setAttribute('x', '1.5');
        rect.setAttribute('y', '1.5');
        rect.setAttribute('width', w - 3);
        rect.setAttribute('height', h - 3);
        rect.setAttribute('rx', r);
        rect.setAttribute('ry', r);
    }

    update();
    if (window.ResizeObserver) {
        new ResizeObserver(update).observe(oval);
    } else {
        window.addEventListener('resize', update);
    }
});
