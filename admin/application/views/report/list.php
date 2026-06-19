<?php
/* 제보하기 리스트 — 시멘틱 CSS 클래스 기반 */
$currentStatus = isset($status) ? $status : '';
$tabs = [
    '' => ['label' => '전체', 'count' => $counts['total'] ?? 0],
    'received' => ['label' => '제보접수', 'count' => $counts['received'] ?? 0],
    'review' => ['label' => '검토 중', 'count' => $counts['review'] ?? 0],
    'done' => ['label' => '처리완료', 'count' => $counts['done'] ?? 0],
];
?>
<div class="content-header">
    <h1 class="content-title">제보하기</h1>
</div>

<?php if (isset($alertMessage) && $alertMessage): ?>
<div id="alertBar" class="report-alert">
    <div class="report-alert-content">
        <span class="report-alert-title"><?= htmlspecialchars($alertMessage['title'] ?? '이메일 발송 실패') ?></span>
        <span class="report-alert-desc"><?= htmlspecialchars($alertMessage['desc'] ?? '') ?></span>
    </div>
    <button type="button" onclick="document.getElementById('alertBar').remove()" class="report-alert-close">
        <img src="<?= $baseUrl ?>/public/img/icon_close_red.svg" alt="닫기">
    </button>
</div>
<?php endif; ?>

<div class="category-tabs">
    <?php foreach ($tabs as $key => $tab): ?>
    <a href="<?= $baseUrl ?>/report/list<?= $key ? '?status='.$key : '' ?>" class="tab <?= ($currentStatus === $key) ? 'active' : '' ?>">
        <span><?= $tab['label'] ?></span>
        <span class="tab-count"><?= $tab['count'] ?></span>
    </a>
    <?php endforeach; ?>
</div>

<div class="content-body">
    <div class="list-toolbar">
        <div class="toolbar-left">
            <span class="selected-count hidden" id="reportSelectedCount">0개 선택됨</span>
            <div class="toolbar-divider hidden" id="reportDeleteDivider"><div class="toolbar-divider-line"></div></div>
            <button class="btn-delete-text hidden" id="reportDeleteBtn">삭제</button>
        </div>
        <form method="get" action="<?= $baseUrl ?>/report/list">
            <?php if ($currentStatus): ?><input type="hidden" name="status" value="<?= htmlspecialchars($currentStatus) ?>"><?php endif; ?>
            <div class="search-box">
                <img src="<?= $baseUrl ?>/public/img/icon_search_v2.svg" alt="검색">
                <input type="text" name="keyword" class="search-input" placeholder="검색하기" value="<?= isset($keyword) ? htmlspecialchars($keyword) : '' ?>">
            </div>
        </form>
    </div>

    <div class="list-content"><div class="list-table">
        <div class="table-header">
            <div class="row-data">
                <div class="row-title-group">
                    <div class="cell-check"><input type="checkbox" id="checkAllReport" onclick="toggleAllReport(this)"></div>
                    <span class="th th-title">제보 내용</span>
                </div>
                <span class="th w-200">접수번호</span>
                <span class="th w-200">상태</span>
                <span class="th w-200">접수일</span>
                <span class="th w-200">답변일</span>
            </div>
        </div>

        <div class="table-body">
            <?php if (empty($list)): ?>
            <div class="table-empty"><p>등록된 제보가 없습니다.</p></div>
            <?php else: ?>
            <?php foreach ($list as $row): ?>
            <?php
            $statusLabel = '제보접수'; $dotImg = 'icon_dot_report_received.svg';
            if (($row['status'] ?? '') === 'review') { $statusLabel = '검토 중'; $dotImg = 'icon_dot_report_review.svg'; }
            elseif (($row['status'] ?? '') === 'done') { $statusLabel = '처리완료'; $dotImg = 'icon_dot_report_done.svg'; }
            ?>
            <div class="table-row report-row">
                <div class="row-data">
                    <div class="row-title-group">
                        <div class="cell-check"><input type="checkbox" name="ids[]" value="<?= $row['id'] ?>" class="report-check"></div>
                        <a href="<?= $baseUrl ?>/report/detail/<?= $row['id'] ?>" class="td-title"><?= htmlspecialchars($row['title'] ?? '제보합니다.') ?></a>
                    </div>
                    <span class="td td-text w-200"><?= htmlspecialchars($row['code'] ?? '-') ?></span>
                    <div class="td w-200 report-status-cell">
                        <span class="badge-dot"><img src="<?= $baseUrl ?>/public/img/<?= $dotImg ?>" alt=""><?= $statusLabel ?></span>
                        <?php if (($row['status'] ?? '') !== 'done'): ?>
                        <button type="button" class="btn-status-change" data-id="<?= $row['id'] ?>" data-status="<?= $row['status'] ?? 'received' ?>">상태변경</button>
                        <?php endif; ?>
                    </div>
                    <span class="td td-date w-200"><?= $row['created_at'] ?? '-' ?></span>
                    <span class="td td-date w-200"><?= $row['answered_at'] ?? '-' ?></span>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <?php if (isset($totalPages) && $totalPages > 1): ?>
    <div class="pagination">
        <?php if ($page > 1): ?><a href="<?= $baseUrl ?>/report/list?page=<?= $page-1 ?><?= $currentStatus ? '&status='.$currentStatus : '' ?>" class="page-arrow"><img src="<?= $baseUrl ?>/public/img/icon_page_prev.svg" alt="이전"></a><?php endif; ?>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?><a href="<?= $baseUrl ?>/report/list?page=<?= $i ?><?= $currentStatus ? '&status='.$currentStatus : '' ?>" class="page-num <?= $i == $page ? 'active' : '' ?>"><?= $i ?></a><?php endfor; ?>
        <?php if ($page < $totalPages): ?><a href="<?= $baseUrl ?>/report/list?page=<?= $page+1 ?><?= $currentStatus ? '&status='.$currentStatus : '' ?>" class="page-arrow"><img src="<?= $baseUrl ?>/public/img/icon_page_next.svg" alt="다음"></a><?php endif; ?>
    </div>
    <?php endif; ?>
</div>

<!-- 상태변경 모달 -->
<div id="statusModal" class="modal-overlay hidden">
    <div class="modal-box">
        <h2 class="modal-title">상태 변경</h2>
        <form method="post" id="statusChangeForm">
            <p class="modal-desc">변경할 상태를 선택해 주세요.</p>
            <div class="status-options" id="statusOptions">
                <label class="status-option" data-value="received">
                    <input type="radio" name="new_status" value="received">
                    <img src="<?= $baseUrl ?>/public/img/icon_dot_report_received.svg" alt="">
                    <span>제보접수</span>
                </label>
                <label class="status-option" data-value="review">
                    <input type="radio" name="new_status" value="review">
                    <img src="<?= $baseUrl ?>/public/img/icon_dot_report_review.svg" alt="">
                    <span>검토 중</span>
                </label>
                <label class="status-option" data-value="done">
                    <input type="radio" name="new_status" value="done">
                    <img src="<?= $baseUrl ?>/public/img/icon_dot_report_done.svg" alt="">
                    <span>처리완료</span>
                </label>
            </div>
            <div class="modal-actions">
                <button type="button" id="cancelStatus" class="btn-modal-cancel">취소</button>
                <button type="submit" class="btn-modal-confirm">변경</button>
            </div>
        </form>
    </div>
</div></div>

<script>
function toggleAllReport(el) {
    document.querySelectorAll('.report-check').forEach(function(c) { c.checked = el.checked; });
    updateReportCount();
}
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('report-check')) {
        updateReportCount();
        var all = document.querySelectorAll('.report-check');
        var allChecked = document.querySelectorAll('.report-check:checked');
        document.getElementById('checkAllReport').checked = (all.length === allChecked.length && all.length > 0);
    }
});
function updateReportCount() {
    var cnt = document.querySelectorAll('.report-check:checked').length;
    ['reportSelectedCount','reportDeleteDivider','reportDeleteBtn'].forEach(function(id) {
        document.getElementById(id).classList.toggle('hidden', !cnt);
    });
    document.getElementById('reportSelectedCount').textContent = cnt + '개 선택됨';
}
document.getElementById('reportDeleteBtn').addEventListener('click', function() {
    var checked = document.querySelectorAll('.report-check:checked');
    if (checked.length === 0) return;
    if (!confirm(checked.length + '건의 제보를 삭제하시겠습니까?\n삭제된 데이터는 복구할 수 없습니다.')) return;
    checked.forEach(function(c) { c.closest('.report-row').remove(); });
    document.getElementById('checkAllReport').checked = false;
    updateReportCount();
    alert('삭제가 완료되었습니다.');
});

(function() {
    var modal = document.getElementById('statusModal');
    var form = document.getElementById('statusChangeForm');
    var currentId = null;

    document.querySelectorAll('.btn-status-change').forEach(function(btn) {
        btn.addEventListener('click', function() {
            currentId = this.dataset.id;
            var cs = this.dataset.status;
            form.querySelectorAll('input[name="new_status"]').forEach(function(r) { r.checked = (r.value === cs); });
            form.querySelectorAll('.status-option').forEach(function(label) {
                label.classList.toggle('active', label.dataset.value === cs);
            });
            modal.classList.remove('hidden');
        });
    });

    form.querySelectorAll('input[name="new_status"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            form.querySelectorAll('.status-option').forEach(function(l) { l.classList.remove('active'); });
            this.closest('.status-option').classList.add('active');
        });
    });

    document.getElementById('cancelStatus').addEventListener('click', function() { modal.classList.add('hidden'); });
    modal.addEventListener('click', function(e) { if (e.target === modal) modal.classList.add('hidden'); });

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        var selected = form.querySelector('input[name="new_status"]:checked');
        if (!selected) return;
        alert('상태가 변경되었습니다.');
        modal.classList.add('hidden');
        location.reload();
    });
})();

document.querySelector('.search-input').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') { e.preventDefault(); this.closest('form').submit(); }
});
document.querySelector('.search-box img').addEventListener('click', function() {
    this.closest('form').submit();
});
</script>
