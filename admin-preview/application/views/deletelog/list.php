<?php
/* 파기이력 — 피그마 744:6452 100% 매칭
 * Contents: p-60 gap-60
 * 통계+경고: gap-30
 *   통계: gap-20
 *     좌 카드: bg-white rounded-20 p-24 w-486
 *     우 카드: bg-white rounded-20 p-24 flex-1 gap-40
 *   경고 바: bg-rgba(242,113,0,0.05) border-#f27100 rounded-12 px-24 py-16 gap-8
 * 필터+테이블: gap-24
 *   필터: gap-20
 *     메뉴/유형: bg-white border-#a7a7a7 h-60 w-355 rounded-10 px-24
 *     날짜: flex-1 gap-8, input h-60 flex-1 rounded-10 px-24
 *   테이블: gap-12
 *     헤더: bg-#e0eaf9 h-68 rounded-12 px-24 py-16 gap-24
 *       파기일시(w-258) + 메뉴명(w-258) + 건수(flex-1) + 파기유형(w-380) + 관리자명(w-258)
 *     행: bg-white rounded-12 px-24 py-20 gap-24
 *       pill: bg-#f8f9fb rounded-800 px-20 py-10 w-380 gap-20
 */
$filterMenu = isset($filterMenu) ? $filterMenu : '';
$filterType = isset($filterType) ? $filterType : '';
$startDate = isset($startDate) ? $startDate : '';
$endDate = isset($endDate) ? $endDate : '';
$page = isset($page) ? (int)$page : 1;
$totalPages = isset($totalPages) ? (int)$totalPages : 1;
?>
<div class="content-header">
    <h1 class="content-title">파기 이력</h1>
</div>

<div class="deletelog-summary">
    <div class="deletelog-stats">
        <div class="stat-card stat-card-total">
            <span class="stat-card-title">전체 파기</span>
            <div class="stat-card-bottom">
                <div class="stat-card-value">
                    <span class="stat-card-num"><?= $stats['total'] ?? '0' ?></span>
                    <span class="stat-card-unit">건</span>
                </div>
                <span class="stat-card-desc">누적 </span>
            </div>
        </div>
        <div class="stat-card stat-card-split">
            <div class="stat-card-col">
                <span class="stat-card-title">자동 파기</span>
                <div class="stat-card-bottom">
                    <div class="stat-card-value">
                        <span class="stat-card-num"><?= $stats['auto'] ?? '0' ?></span>
                        <span class="stat-card-unit">건</span>
                    </div>
                    <span class="stat-card-desc">보유기간 만료</span>
                </div>
            </div>
            <div class="stat-card-divider-v"></div>
            <div class="stat-card-col">
                <span class="stat-card-title">수동 파기</span>
                <div class="stat-card-bottom">
                    <div class="stat-card-value">
                        <span class="stat-card-num"><?= $stats['manual'] ?? '0' ?></span>
                        <span class="stat-card-unit">건</span>
                    </div>
                    <span class="stat-card-desc">관리자 직접 삭제</span>
                </div>
            </div>
        </div>
    </div>

    <div class="deletelog-alert">
        <img src="<?= $baseUrl ?>/public/img/icon_info_orange.svg" alt="" class="deletelog-alert-icon">
        <span class="deletelog-alert-text">파기 이력은 개인정보처리방침에 따라 보존되며, 수정 및 삭제가 불가합니다.</span>
    </div>
</div>

<div class="content-body">
    <form method="get" action="<?= $baseUrl ?>/deletelog/list" class="deletelog-filter" id="filterForm">
        <select name="menu" class="filter-select deletelog-select" onchange="this.form.submit()">
            <option value="">메뉴</option>
            <option value="report" <?= $filterMenu === 'report' ? 'selected' : '' ?>>제보하기</option>
            <option value="inquiry" <?= $filterMenu === 'inquiry' ? 'selected' : '' ?>>문의하기</option>
        </select>
        <select name="type" class="filter-select deletelog-select" onchange="this.form.submit()">
            <option value="">파기 유형</option>
            <option value="auto" <?= $filterType === 'auto' ? 'selected' : '' ?>>자동파기</option>
            <option value="manual" <?= $filterType === 'manual' ? 'selected' : '' ?>>수동파기</option>
        </select>
        <div class="deletelog-date-range">
            <input type="date" name="start_date" value="<?= htmlspecialchars($startDate) ?>" class="filter-date deletelog-date" placeholder="시작일" onchange="this.form.submit()">
            <span class="filter-date-sep">~</span>
            <input type="date" name="end_date" value="<?= htmlspecialchars($endDate) ?>" class="filter-date deletelog-date" placeholder="종료일" onchange="this.form.submit()">
        </div>
    </form>

    <div class="list-content"><div class="list-table">
        <div class="table-header deletelog-header">
            <span class="th deletelog-col-date">파기 일시</span>
            <span class="th deletelog-col-menu">메뉴 명</span>
            <span class="th deletelog-col-count">건수 파기</span>
            <span class="th deletelog-col-type">파기 유형 및 사유</span>
            <span class="th deletelog-col-admin">관리자 명</span>
        </div>

        <div class="table-body">
            <?php if (empty($list)): ?>
            <div class="table-empty"><p>파기 이력이 없습니다.</p></div>
            <?php else: ?>
            <?php foreach ($list as $row): ?>
            <?php $isAuto = ($row['type'] ?? 'auto') === 'auto'; ?>
            <div class="table-row deletelog-row">
                <span class="td td-text deletelog-col-date"><?= htmlspecialchars($row['destroyed_at'] ?? '-') ?></span>
                <span class="td td-text deletelog-col-menu"><?= htmlspecialchars($row['menu_name'] ?? '제보하기') ?></span>
                <span class="td td-text deletelog-col-count"><?= htmlspecialchars($row['count'] ?? '1') ?>건</span>
                <div class="deletelog-type-pill">
                    <div class="deletelog-type-label">
                        <img src="<?= $baseUrl ?>/public/img/icon_dot_v2_<?= $isAuto ? '3' : '2' ?>.svg" alt="" class="deletelog-dot">
                        <span><?= $isAuto ? '자동파기' : '수동파기' ?></span>
                    </div>
                    <span class="deletelog-type-divider"></span>
                    <span class="deletelog-type-reason"><?= $isAuto ? '보유기간 만료 자동 파기' : '관리자 직접 삭제' ?></span>
                </div>
                <span class="td td-text deletelog-col-admin"><?= htmlspecialchars($row['admin_name'] ?? 'SYSTEM') ?></span>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($totalPages > 1): ?>
    <div class="pagination">
        <?php if ($page > 1): ?>
        <a href="<?= $baseUrl ?>/deletelog/list?page=<?= $page-1 ?><?= $filterMenu ? '&menu='.$filterMenu : '' ?><?= $filterType ? '&type='.$filterType : '' ?><?= $startDate ? '&start_date='.$startDate : '' ?><?= $endDate ? '&end_date='.$endDate : '' ?>" class="page-arrow"><img src="<?= $baseUrl ?>/public/img/icon_page_prev.svg" alt="이전"></a>
        <?php endif; ?>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="<?= $baseUrl ?>/deletelog/list?page=<?= $i ?><?= $filterMenu ? '&menu='.$filterMenu : '' ?><?= $filterType ? '&type='.$filterType : '' ?><?= $startDate ? '&start_date='.$startDate : '' ?><?= $endDate ? '&end_date='.$endDate : '' ?>" class="page-num <?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>
        <?php if ($page < $totalPages): ?>
        <a href="<?= $baseUrl ?>/deletelog/list?page=<?= $page+1 ?><?= $filterMenu ? '&menu='.$filterMenu : '' ?><?= $filterType ? '&type='.$filterType : '' ?><?= $startDate ? '&start_date='.$startDate : '' ?><?= $endDate ? '&end_date='.$endDate : '' ?>" class="page-arrow"><img src="<?= $baseUrl ?>/public/img/icon_page_next.svg" alt="다음"></a>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div></div>
