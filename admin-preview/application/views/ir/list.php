<?php
/* IR자료실 리스트 — 시멘틱 CSS 클래스 기반 */
$tabs = [
    '' => ['label' => '전체', 'count' => $counts['total'] ?? 0],
    'letter' => ['label' => 'IR레터', 'count' => $counts['letter'] ?? 0],
    'book' => ['label' => 'IR북', 'count' => $counts['book'] ?? 0],
];
$currentCat = isset($category) ? $category : '';
?>
<div class="content-header">
    <h1 class="content-title">IR자료실</h1>
    <a href="<?= $baseUrl ?>/ir/create" class="btn-primary">IR자료 등록</a>
</div>

<div class="category-tabs">
    <?php foreach ($tabs as $key => $tab): ?>
    <a href="<?= $baseUrl ?>/ir/list<?= $key ? '?category='.$key : '' ?>" class="tab <?= ($currentCat === $key) ? 'active' : '' ?>">
        <span><?= $tab['label'] ?></span>
        <span class="tab-count"><?= $tab['count'] ?></span>
    </a>
    <?php endforeach; ?>
</div>

<div class="content-body">
    <div class="list-toolbar">
        <div class="toolbar-left">
            <span class="selected-count hidden" id="irSelectedCount">0개 선택됨</span>
            <div class="toolbar-divider hidden" id="irDeleteDivider"><img src="<?= $baseUrl ?>/public/img/icon_divider_vert_v2.svg" alt=""></div>
            <button class="btn-delete-text hidden" id="irDeleteBtn">삭제</button>
        </div>
        <form method="get" action="<?= $baseUrl ?>/ir/list">
            <?php if ($currentCat): ?><input type="hidden" name="category" value="<?= htmlspecialchars($currentCat) ?>"><?php endif; ?>
            <div class="search-box">
                <img src="<?= $baseUrl ?>/public/img/icon_search_v2.svg" alt="검색">
                <input type="text" name="keyword" class="search-input" placeholder="검색하기" value="<?= isset($keyword) ? htmlspecialchars($keyword) : '' ?>">
            </div>
        </form>
    </div>

    <div class="list-content"><div class="list-table">
        <div class="table-header has-edit">
            <div class="row-title-group">
                <div class="cell-check"><input type="checkbox" id="checkAllIR" onclick="toggleAllIR(this)"></div>
                <span class="th th-title">IR 자료명</span>
            </div>
            <span class="th w-120">구분</span>
            <span class="th w-200">등록일</span>
            <span class="th w-200">최종업데이트</span>
        </div>

        <div class="table-body">
            <?php if (empty($list)): ?>
            <div class="table-empty"><p>등록된 IR자료가 없습니다.</p></div>
            <?php else: ?>
            <?php foreach ($list as $row): ?>
            <?php
            $catName = '기타'; $dotImg = 'icon_dot_ir_letter.svg';
            if (($row['category'] ?? '') === 'letter') { $catName = 'IR레터'; $dotImg = 'icon_dot_ir_letter.svg'; }
            elseif (($row['category'] ?? '') === 'book') { $catName = 'IR북'; $dotImg = 'icon_dot_ir_book.svg'; }
            ?>
            <div class="table-row ir-row">
                <div class="row-data">
                    <div class="row-title-group">
                        <div class="cell-check"><input type="checkbox" name="ids[]" value="<?= $row['id'] ?>" class="ir-check"></div>
                        <a href="<?= $baseUrl ?>/ir/detail/<?= $row['id'] ?>" class="td-title"><?= htmlspecialchars($row['title']) ?></a>
                    </div>
                    <div class="td w-120">
                        <span class="badge-dot"><img src="<?= $baseUrl ?>/public/img/<?= $dotImg ?>" alt=""><?= $catName ?></span>
                    </div>
                    <span class="td td-date w-200"><?= $row['created_at'] ?? '-' ?></span>
                    <span class="td td-date w-200"><?= $row['updated_at'] ?? '-' ?></span>
                </div>
                <a href="<?= $baseUrl ?>/ir/edit/<?= $row['id'] ?>" class="btn-row-edit">IR자료 수정</a>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <?php if (isset($totalPages) && $totalPages > 1): ?>
    <div class="pagination">
        <?php if ($page > 1): ?><a href="<?= $baseUrl ?>/ir/list?page=<?= $page-1 ?><?= $currentCat ? '&category='.$currentCat : '' ?>" class="page-arrow"><img src="<?= $baseUrl ?>/public/img/icon_page_prev.svg" alt="이전"></a><?php endif; ?>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?><a href="<?= $baseUrl ?>/ir/list?page=<?= $i ?><?= $currentCat ? '&category='.$currentCat : '' ?>" class="page-num <?= $i == $page ? 'active' : '' ?>"><?= $i ?></a><?php endfor; ?>
        <?php if ($page < $totalPages): ?><a href="<?= $baseUrl ?>/ir/list?page=<?= $page+1 ?><?= $currentCat ? '&category='.$currentCat : '' ?>" class="page-arrow"><img src="<?= $baseUrl ?>/public/img/icon_page_next.svg" alt="다음"></a><?php endif; ?>
    </div>
    <?php endif; ?>
</div></div>

<script>
function toggleAllIR(el) {
    document.querySelectorAll('.ir-check').forEach(function(c) { c.checked = el.checked; });
    updateIRCount();
}
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('ir-check')) {
        updateIRCount();
        var all = document.querySelectorAll('.ir-check');
        var allChecked = document.querySelectorAll('.ir-check:checked');
        document.getElementById('checkAllIR').checked = (all.length === allChecked.length && all.length > 0);
    }
});
function updateIRCount() {
    var cnt = document.querySelectorAll('.ir-check:checked').length;
    ['irSelectedCount','irDeleteDivider','irDeleteBtn'].forEach(function(id) {
        document.getElementById(id).classList.toggle('hidden', !cnt);
    });
    document.getElementById('irSelectedCount').textContent = cnt + '개 선택됨';
}
document.getElementById('irDeleteBtn').addEventListener('click', function() {
    var checked = document.querySelectorAll('.ir-check:checked');
    if (checked.length === 0) return;
    if (!confirm(checked.length + '건의 IR자료를 삭제하시겠습니까?\n삭제된 데이터는 복구할 수 없습니다.')) return;
    checked.forEach(function(c) { c.closest('.ir-row').remove(); });
    document.getElementById('checkAllIR').checked = false;
    updateIRCount();
    alert('삭제가 완료되었습니다.');
});
document.querySelector('.search-input').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') { e.preventDefault(); this.closest('form').submit(); }
});
document.querySelector('.search-box img').addEventListener('click', function() {
    this.closest('form').submit();
});
</script>
