/* 윤리경영 — 인트로 글자 칠하기는 intro-scroll-lock.js에서 공통 처리 */

/* 윤리경영 - 아코디언 */
document.addEventListener('DOMContentLoaded', () => {

    const items = document.querySelectorAll('.eth-accordion-item');

    items.forEach(item => {
        const header = item.querySelector('.eth-accordion-header');

        header.addEventListener('click', () => {
            const isOpen = item.classList.contains('is-open');

            // 다른 항목 모두 닫기
            items.forEach(other => {
                if (other !== item) {
                    other.classList.remove('is-open');
                    other.querySelector('.eth-accordion-header').setAttribute('aria-expanded', 'false');
                }
            });

            // 현재 항목 토글
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
