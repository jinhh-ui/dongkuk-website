<?php
/* 뉴스 기사 관리 — 시멘틱 CSS 클래스 기반 */
?>
<div class="content-header">
    <h1 class="content-title">뉴스 기사 관리</h1>
    <a href="<?= $baseUrl ?>/news/create" class="btn-primary">뉴스 기사 등록</a>
</div>

<div class="content-body">
    <div class="list-toolbar">
        <div class="toolbar-left">
            <span class="selected-count hidden" id="newsSelectedCount">0개 선택됨</span>
            <div class="toolbar-divider hidden" id="newsDeleteDivider"><img src="<?= $baseUrl ?>/public/img/icon_divider_vert_v2.svg" alt=""></div>
            <button class="btn-delete-text hidden" id="newsDeleteBtn">삭제</button>
        </div>
        <form method="get" action="<?= $baseUrl ?>/news/list">
            <div class="search-box">
                <img src="<?= $baseUrl ?>/public/img/icon_search_v2.svg" alt="검색">
                <input type="text" name="keyword" class="search-input" placeholder="검색하기" value="<?= isset($keyword) ? htmlspecialchars($keyword) : '' ?>">
            </div>
        </form>
    </div>

    <div class="list-content"><div class="list-table">
        <div class="table-header has-edit">
            <div class="row-title-group">
                <div class="cell-check"><input type="checkbox" id="checkAll" onclick="toggleAll(this)"></div>
                <span class="th th-title">뉴스 제목</span>
            </div>
            <span class="th w-200">게시일</span>
            <span class="th w-200">최종업데이트</span>
        </div>

        <div class="table-body">
            <?php if (empty($list)): ?>
            <div class="table-empty"><p>등록된 뉴스가 없습니다.</p></div>
            <?php else: ?>
            <?php foreach ($list as $row): ?>
            <div class="table-row news-row">
                <div class="row-data">
                    <div class="row-title-group">
                        <div class="cell-check"><input type="checkbox" name="ids[]" value="<?= $row['id'] ?>" class="news-check"></div>
                        <div class="news-thumb">
                            <?php if (!empty($row['thumbnail'])): ?>
                            <img src="<?= htmlspecialchars($row['thumbnail']) ?>" alt="">
                            <?php endif; ?>
                        </div>
                        <a href="<?= $baseUrl ?>/news/detail/<?= $row['id'] ?>" class="td-title"><?= htmlspecialchars($row['title']) ?></a>
                    </div>
                    <span class="td td-date w-200"><?= $row['created_at'] ?? '-' ?></span>
                    <span class="td td-date w-200"><?= $row['updated_at'] ?? '-' ?></span>
                </div>
                <a href="<?= $baseUrl ?>/news/edit/<?= $row['id'] ?>" class="btn-row-edit">뉴스 기사 수정</a>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <?php if (isset($totalPages) && $totalPages > 1): ?>
    <div class="pagination">
        <?php if ($page > 1): ?><a href="<?= $baseUrl ?>/news/list?page=<?= $page-1 ?>" class="page-arrow"><img src="<?= $baseUrl ?>/public/img/icon_page_prev.svg" alt="이전"></a><?php endif; ?>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?><a href="<?= $baseUrl ?>/news/list?page=<?= $i ?>" class="page-num <?= $i == $page ? 'active' : '' ?>"><?= $i ?></a><?php endfor; ?>
        <?php if ($page < $totalPages): ?><a href="<?= $baseUrl ?>/news/list?page=<?= $page+1 ?>" class="page-arrow"><img src="<?= $baseUrl ?>/public/img/icon_page_next.svg" alt="다음"></a><?php endif; ?>
    </div>
    <?php endif; ?>
</div></div>

<script>
function toggleAll(el) {
    document.querySelectorAll('.news-check').forEach(function(c) { c.checked = el.checked; });
    updateCount();
}
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('news-check')) {
        updateCount();
        var all = document.querySelectorAll('.news-check');
        var allChecked = document.querySelectorAll('.news-check:checked');
        document.getElementById('checkAll').checked = (all.length === allChecked.length && all.length > 0);
    }
});
function updateCount() {
    var cnt = document.querySelectorAll('.news-check:checked').length;
    ['newsSelectedCount','newsDeleteDivider','newsDeleteBtn'].forEach(function(id) {
        document.getElementById(id).classList.toggle('hidden', !cnt);
    });
    document.getElementById('newsSelectedCount').textContent = cnt + '개 선택됨';
}
document.getElementById('newsDeleteBtn').addEventListener('click', function() {
    var checked = document.querySelectorAll('.news-check:checked');
    if (checked.length === 0) return;
    if (!confirm(checked.length + '건의 뉴스 기사를 삭제하시겠습니까?\n삭제된 데이터는 복구할 수 없습니다.')) return;
    // TODO: AJAX POST /news/deleteMulti
    checked.forEach(function(c) { c.closest('.news-row').remove(); });
    document.getElementById('checkAll').checked = false;
    updateCount();
    alert('삭제가 완료되었습니다.');
});
document.querySelector('.search-input').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') { e.preventDefault(); this.closest('form').submit(); }
});
document.querySelector('.search-box img').addEventListener('click', function() {
    this.closest('form').submit();
});
</script>
