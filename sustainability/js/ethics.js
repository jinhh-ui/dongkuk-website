/* 윤리경영 - 인트로 타이틀 글자 색상 전환 (스크롤 기반) */
document.addEventListener('DOMContentLoaded', function () {
    const outer = document.querySelector('.eth-intro-outer');
    if (!outer) return;

    const title = outer.querySelector('.eth-intro-title');
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

/* 윤리경영 - 아코디언 */
document.addEventListener('DOMContentLoaded', () => {

    const items = document.querySelectorAll('.eth-accordion-item');

    items.forEach(item => {
        const header = item.querySelector('.eth-accordion-header');

        header.addEventListener('click', () => {
            const isOpen = item.classList.contains('is-open');

            // 현재 항목 토글 (다른 항목은 유지)
            item.classList.toggle('is-open', !isOpen);
            header.setAttribute('aria-expanded', String(!isOpen));

            // 열릴 때 스크롤 보정
            if (!isOpen) {
                setTimeout(() => {
                    const top = header.getBoundingClientRect().top + window.scrollY - 80;
                    window.scrollTo({ top, behavior: 'smooth' });
                }, 50);
            }
        });
    });
});
