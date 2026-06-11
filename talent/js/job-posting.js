/* ================================================================
   채용공고 (job-posting.html) — 탭 필터 + 검색
   ================================================================ */
(function () {
  'use strict';

  var tabs = document.querySelectorAll('.job-tab');
  var rows = document.querySelectorAll('.job-list-row');
  var searchInput = document.getElementById('job-search-input');
  var boardList = document.getElementById('job-board-list');
  var emptyEl = document.getElementById('job-empty');

  var currentFilter = 'all';

  /* ── 필터링 + 검색 통합 ── */
  function applyFilter() {
    var keyword = searchInput ? searchInput.value.trim().toLowerCase() : '';
    var visibleCount = 0;

    rows.forEach(function (row) {
      var category = row.getAttribute('data-category') || '';
      var title = row.querySelector('.job-row-title');
      var titleText = title ? title.textContent.toLowerCase() : '';

      var matchFilter = (currentFilter === 'all') || (category === currentFilter);
      var matchSearch = !keyword || titleText.indexOf(keyword) !== -1;

      if (matchFilter && matchSearch) {
        row.style.display = '';
        visibleCount++;
      } else {
        row.style.display = 'none';
      }
    });

    if (emptyEl) {
      emptyEl.style.display = visibleCount === 0 ? 'flex' : 'none';
    }
  }

  /* ── 탭 클릭 ── */
  tabs.forEach(function (tab) {
    tab.addEventListener('click', function () {
      tabs.forEach(function (t) { t.classList.remove('is-active'); });
      tab.classList.add('is-active');
      currentFilter = tab.getAttribute('data-filter') || 'all';
      applyFilter();
    });
  });

  /* ── 검색 입력 ── */
  if (searchInput) {
    searchInput.addEventListener('input', applyFilter);
    searchInput.addEventListener('keydown', function (e) {
      if (e.key === 'Enter') applyFilter();
    });
  }

  /* ── 초기 실행 ── */
  applyFilter();
})();
