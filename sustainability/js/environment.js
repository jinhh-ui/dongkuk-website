/* ================================================================
   environment.js — 환경경영 페이지 스크립트
   ================================================================ */

/* ── 인트로 타이틀 글자 색상 전환 (스크롤 기반) ── */
document.addEventListener('DOMContentLoaded', function () {
    const outer = document.querySelector('.env-intro-outer');
    if (!outer) return;

    const title = outer.querySelector('.env-intro-title');
    if (!title) return;

    function wrapCharacters(node) {
        if (node.nodeType === Node.TEXT_NODE) {
            const text = node.textContent;
            const frag = document.createDocumentFragment();
            for (let i = 0; i < text.length; i++) {
                const span = document.createElement('span');
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

    const chars = title.querySelectorAll('.char');

    function onScroll() {
        const rect = outer.getBoundingClientRect();
        const outerH = outer.offsetHeight;
        const innerH = window.innerHeight;
        const scrollRoom = outerH - innerH;
        const scrolled = -rect.top;
        const progress = Math.max(0, Math.min(1, scrolled / Math.max(scrollRoom, 1)));

        const activeCount = Math.floor(progress * chars.length);
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
