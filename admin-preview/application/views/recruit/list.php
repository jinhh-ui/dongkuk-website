<?php
/* 채용공고 리스트 — 시멘틱 CSS 클래스 기반 */
$tabs = [
    '' => ['label' => '전체', 'count' => $counts['total'] ?? 0],
    'waiting' => ['label' => '게시 대기', 'count' => $counts['waiting'] ?? 0],
    'active' => ['label' => '게시 중', 'count' => $counts['active'] ?? 0],
    'closed' => ['label' => '마감', 'count' => $counts['closed'] ?? 0],
];
$currentStatus = isset($status) ? $status : '';
?>
<div class="content-header">
    <h1 class="content-title">채용공고</h1>
    <a href="<?= $baseUrl ?>/recruit/create" class="btn-primary">채용공고 등록</a>
</div>

<div class="category-tabs">
    <?php foreach ($tabs as $key => $tab): ?>
    <a href="<?= $baseUrl ?>/recruit/list<?= $key ? '?status='.$key : '' ?>" class="tab <?= ($currentStatus === $key) ? 'active' : '' ?>">
        <span><?= $tab['label'] ?></span>
        <span class="tab-count"><?= $tab['count'] ?></span>
    </a>
    <?php endforeach; ?>
</div>

<div class="content-body">
    <div class="list-toolbar">
        <div class="toolbar-left">
            <span class="selected-count hidden" id="recruitSelectedCount">0개 선택됨</span>
            <div class="toolbar-divider hidden" id="recruitDeleteDivider"><img src="<?= $baseUrl ?>/public/img/icon_divider_vert_v2.svg" alt=""></div>
            <button class="btn-delete-text hidden" id="recruitDeleteBtn">삭제</button>
        </div>
        <form method="get" action="<?= $baseUrl ?>/recruit/list">
            <?php if ($currentStatus): ?><input type="hidden" name="status" value="<?= htmlspecialchars($currentStatus) ?>"><?php endif; ?>
            <div class="search-box">
                <img src="<?= $baseUrl ?>/public/img/icon_search_v2.svg" alt="검색">
                <input type="text" name="keyword" class="search-input" placeholder="검색하기" value="<?= isset($keyword) ? htmlspecialchars($keyword) : '' ?>">
            </div>
        </form>
    </div>

    <div class="list-content"><div class="list-table">
        <div class="table-header">
            <div class="row-title-group">
                <div class="cell-check"><input type="checkbox" id="checkAllRecruit" onclick="toggleAllRecruit(this)"></div>
                <span class="th th-title">채용공고명</span>
            </div>
            <span class="th w-80">직군</span>
            <span class="th w-220">채용정보</span>
            <span class="th w-404">채용기간</span>
        </div>

        <div class="table-body">
            <?php if (empty($list)): ?>
            <div class="table-empty"><p>등록된 채용공고가 없습니다.</p></div>
            <?php else: ?>
            <?php foreach ($list as $row): ?>
            <?php
            $statusLabel = '게시 대기'; $dotImg = 'icon_dot_recruit_wait.svg';
            if (($row['status'] ?? '') === 'active') { $statusLabel = '게시 중'; $dotImg = 'icon_dot_recruit_active.svg'; }
            elseif (($row['status'] ?? '') === 'closed') { $statusLabel = '마감'; $dotImg = 'icon_dot_recruit_closed.svg'; }
            ?>
            <div class="table-row recruit-row">
                <div class="row-data">
                    <div class="row-title-group">
                        <div class="cell-check"><input type="checkbox" name="ids[]" value="<?= $row['id'] ?>" class="recruit-check"></div>
                        <div class="recruit-title-wrap">
                            <p class="recruit-title-text"><?= htmlspecialchars($row['title']) ?></p>
                            <?php if (!empty($row['external_url'])): ?>
                            <a href="<?= htmlspecialchars($row['external_url']) ?>" target="_blank" class="recruit-chip">
                                <span>사람인 공고 확인</span>
                                <img src="<?= $baseUrl ?>/public/img/icon_external_link.svg" alt="">
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <span class="td td-text w-80"><?= htmlspecialchars($row['job_group'] ?? '-') ?></span>
                    <span class="td td-text w-220"><?= htmlspecialchars($row['recruit_info'] ?? '-') ?></span>
                    <div class="recruit-period-pill">
                        <div class="recruit-period-left">
                            <img src="<?= $baseUrl ?>/public/img/<?= $dotImg ?>" alt="">
                            <span><?= $statusLabel ?></span>
                        </div>
                        <div class="recruit-period-right">
                            <span class="recruit-period-dday"><?= $row['d_day'] ?? '' ?></span>
                            <div class="recruit-period-divider"><img src="<?= $baseUrl ?>/public/img/icon_divider_vert_period.svg" alt=""></div>
                            <span class="recruit-period-dates"><?= ($row['start_date'] ?? '') . ' ~ ' . ($row['end_date'] ?? '') ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <?php if (isset($totalPages) && $totalPages > 1): ?>
    <div class="pagination">
        <?php if ($page > 1): ?><a href="<?= $baseUrl ?>/recruit/list?page=<?= $page-1 ?><?= $currentStatus ? '&status='.$currentStatus : '' ?>" class="page-arrow"><img src="<?= $baseUrl ?>/public/img/icon_page_prev.svg" alt="이전"></a><?php endif; ?>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?><a href="<?= $baseUrl ?>/recruit/list?page=<?= $i ?><?= $currentStatus ? '&status='.$currentStatus : '' ?>" class="page-num <?= $i == $page ? 'active' : '' ?>"><?= $i ?></a><?php endfor; ?>
        <?php if ($page < $totalPages): ?><a href="<?= $baseUrl ?>/recruit/list?page=<?= $page+1 ?><?= $currentStatus ? '&status='.$currentStatus : '' ?>" class="page-arrow"><img src="<?= $baseUrl ?>/public/img/icon_page_next.svg" alt="다음"></a><?php endif; ?>
    </div>
    <?php endif; ?>
</div></div>

<script>
function toggleAllRecruit(el) {
    document.querySelectorAll('.recruit-check').forEach(function(c) { c.checked = el.checked; });
    updateRecruitCount();
}
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('recruit-check')) {
        updateRecruitCount();
        var all = document.querySelectorAll('.recruit-check');
        var allChecked = document.querySelectorAll('.recruit-check:checked');
        document.getElementById('checkAllRecruit').checked = (all.length === allChecked.length && all.length > 0);
    }
});
function updateRecruitCount() {
    var cnt = document.querySelectorAll('.recruit-check:checked').length;
    ['recruitSelectedCount','recruitDeleteDivider','recruitDeleteBtn'].forEach(function(id) {
        document.getElementById(id).classList.toggle('hidden', !cnt);
    });
    document.getElementById('recruitSelectedCount').textContent = cnt + '개 선택됨';
}
document.getElementById('recruitDeleteBtn').addEventListener('click', function() {
    var checked = document.querySelectorAll('.recruit-check:checked');
    if (checked.length === 0) return;
    if (!confirm(checked.length + '건의 채용공고를 삭제하시겠습니까?\n삭제된 데이터는 복구할 수 없습니다.')) return;
    checked.forEach(function(c) { c.closest('.recruit-row').remove(); });
    document.getElementById('checkAllRecruit').checked = false;
    updateRecruitCount();
    alert('삭제가 완료되었습니다.');
});
document.querySelector('.search-input').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') { e.preventDefault(); this.closest('form').submit(); }
});
document.querySelector('.search-box img').addEventListener('click', function() {
    this.closest('form').submit();
});
</script>
