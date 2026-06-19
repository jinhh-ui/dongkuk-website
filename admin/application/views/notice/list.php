<?php
/* 공고 리스트 — 시멘틱 CSS 클래스 기반 */
$currentCategory = isset($category) ? $category : '';
?>
<div class="content-header">
    <h1 class="content-title">공고</h1>
    <a href="<?= $baseUrl ?>/notice/create" class="btn-primary">공고 등록</a>
</div>

<div class="category-tabs">
    <a href="<?= $baseUrl ?>/notice/list" class="tab <?= (!$currentCategory) ? 'active' : '' ?>"><span>전체</span><span class="tab-count"><?= $counts['total'] ?? '0' ?></span></a>
    <a href="<?= $baseUrl ?>/notice/list?category=settlement" class="tab <?= ($currentCategory === 'settlement') ? 'active' : '' ?>"><span>결산공고</span><span class="tab-count"><?= $counts['settlement'] ?? '0' ?></span></a>
    <a href="<?= $baseUrl ?>/notice/list?category=shareholder" class="tab <?= ($currentCategory === 'shareholder') ? 'active' : '' ?>"><span>주주총회</span><span class="tab-count"><?= $counts['shareholder'] ?? '0' ?></span></a>
    <a href="<?= $baseUrl ?>/notice/list?category=etc" class="tab <?= ($currentCategory === 'etc') ? 'active' : '' ?>"><span>기타</span><span class="tab-count"><?= $counts['etc'] ?? '0' ?></span></a>
</div>

<div class="content-body">
    <div class="list-toolbar">
        <div class="toolbar-left">
            <span class="selected-count hidden" id="noticeSelectedCount">0개 선택됨</span>
            <div class="toolbar-divider hidden" id="noticeDeleteDivider"><img src="<?= $baseUrl ?>/public/img/icon_divider_vert_v2.svg" alt=""></div>
            <button class="btn-delete-text hidden" id="noticeDeleteBtn">삭제</button>
        </div>
        <form method="get" action="<?= $baseUrl ?>/notice/list">
            <?php if ($currentCategory): ?><input type="hidden" name="category" value="<?= htmlspecialchars($currentCategory) ?>"><?php endif; ?>
            <div class="search-box">
                <img src="<?= $baseUrl ?>/public/img/icon_search_v2.svg" alt="검색">
                <input type="text" name="keyword" class="search-input" placeholder="검색하기" value="<?= isset($keyword) ? htmlspecialchars($keyword) : '' ?>">
            </div>
        </form>
    </div>

    <div class="list-content"><div class="list-table">
        <div class="table-header has-edit">
            <div class="row-data">
                <div class="row-title-group">
                    <div class="cell-check"><input type="checkbox" id="checkAllNotice" onclick="toggleAllNotice(this)"></div>
                    <span class="th th-title">공고명</span>
                </div>
                <span class="th w-200">구분</span>
                <span class="th w-200">게시일</span>
                <span class="th w-200">파일</span>
            </div>
        </div>

        <div class="table-body">
            <?php if (empty($list)): ?>
            <div class="table-empty"><p>등록된 공고가 없습니다.</p></div>
            <?php else: ?>
            <?php foreach ($list as $row): ?>
            <div class="table-row notice-row">
                <div class="row-data">
                    <div class="row-title-group">
                        <div class="cell-check"><input type="checkbox" name="ids[]" value="<?= $row['id'] ?>" class="notice-check"></div>
                        <a href="<?= $baseUrl ?>/notice/detail/<?= $row['id'] ?>" class="td-title"><?= htmlspecialchars($row['title']) ?></a>
                    </div>
                    <span class="td td-text w-200"><?= htmlspecialchars($row['category_name'] ?? '-') ?></span>
                    <span class="td td-date w-200"><?= $row['published_at'] ?? '-' ?></span>
                    <div class="td w-200">
                        <?php if (!empty($row['file_name'])): ?>
                        <a href="<?= $baseUrl ?>/notice/download/<?= $row['id'] ?>" class="td-link"><?= htmlspecialchars($row['file_name']) ?></a>
                        <?php else: ?>
                        <span class="td-muted">-</span>
                        <?php endif; ?>
                    </div>
                </div>
                <a href="<?= $baseUrl ?>/notice/edit/<?= $row['id'] ?>" class="btn-row-edit">수정</a>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <?php if (isset($totalPages) && $totalPages > 1): ?>
    <div class="pagination">
        <?php if ($page > 1): ?><a href="<?= $baseUrl ?>/notice/list?page=<?= $page-1 ?><?= $currentCategory ? '&category='.$currentCategory : '' ?>" class="page-arrow"><img src="<?= $baseUrl ?>/public/img/icon_page_prev.svg" alt="이전"></a><?php endif; ?>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?><a href="<?= $baseUrl ?>/notice/list?page=<?= $i ?><?= $currentCategory ? '&category='.$currentCategory : '' ?>" class="page-num <?= $i == $page ? 'active' : '' ?>"><?= $i ?></a><?php endfor; ?>
        <?php if ($page < $totalPages): ?><a href="<?= $baseUrl ?>/notice/list?page=<?= $page+1 ?><?= $currentCategory ? '&category='.$currentCategory : '' ?>" class="page-arrow"><img src="<?= $baseUrl ?>/public/img/icon_page_next.svg" alt="다음"></a><?php endif; ?>
    </div>
    <?php endif; ?>
</div></div>

<script>
function toggleAllNotice(el) {
    document.querySelectorAll('.notice-check').forEach(function(c) { c.checked = el.checked; });
    updateNoticeCount();
}
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('notice-check')) {
        updateNoticeCount();
        var all = document.querySelectorAll('.notice-check');
        var allChecked = document.querySelectorAll('.notice-check:checked');
        document.getElementById('checkAllNotice').checked = (all.length === allChecked.length && all.length > 0);
    }
});
function updateNoticeCount() {
    var cnt = document.querySelectorAll('.notice-check:checked').length;
    ['noticeSelectedCount','noticeDeleteDivider','noticeDeleteBtn'].forEach(function(id) {
        document.getElementById(id).classList.toggle('hidden', !cnt);
    });
    document.getElementById('noticeSelectedCount').textContent = cnt + '개 선택됨';
}
document.getElementById('noticeDeleteBtn').addEventListener('click', function() {
    var checked = document.querySelectorAll('.notice-check:checked');
    if (checked.length === 0) return;
    if (!confirm(checked.length + '건의 공고를 삭제하시겠습니까?\n삭제된 데이터는 복구할 수 없습니다.')) return;
    // TODO: AJAX POST /notice/deleteMulti
    checked.forEach(function(c) { c.closest('.notice-row').remove(); });
    document.getElementById('checkAllNotice').checked = false;
    updateNoticeCount();
    alert('삭제가 완료되었습니다.');
});
document.querySelector('.search-input').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') { e.preventDefault(); this.closest('form').submit(); }
});
document.querySelector('.search-box img').addEventListener('click', function() {
    this.closest('form').submit();
});
</script>
