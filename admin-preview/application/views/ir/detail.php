<?php
/* IR자료 상세 — 제보하기 상세 스타일 통일 */
?>
<div class="content-header">
    <h1 class="content-title"><?= htmlspecialchars($ir['title'] ?? 'IR자료') ?></h1>
    <div class="content-header-actions">
        <button type="button" id="detailDeleteBtn" class="btn-row-edit">삭제</button>
        <a href="<?= $baseUrl ?>/ir/edit/<?= $ir['id'] ?? '' ?>" class="btn-primary">IR자료 수정</a>
    </div>
</div>

<div class="detail-body">
    <div class="detail-main">
        <p class="detail-section-label">IR 자료 정보</p>

        <div class="detail-row">
            <div class="detail-label">게시 일시</div>
            <div class="detail-value"><?= $ir['published_at'] ?? '-' ?></div>
        </div>

        <div class="detail-row">
            <div class="detail-label">구분</div>
            <div class="detail-value"><?= htmlspecialchars($ir['category_name'] ?? '-') ?></div>
        </div>

        <?php if (!empty($ir['file_name'])): ?>
        <div class="detail-row detail-row-top">
            <div class="detail-label">첨부파일</div>
            <div class="detail-value">
                <a href="<?= $baseUrl ?>/ir/preview/<?= $ir['id'] ?>" target="_blank" class="detail-file-item">
                    <img src="<?= $baseUrl ?>/public/img/icon_file_attach.svg" alt="" class="detail-file-icon">
                    <span class="detail-file-name"><?= htmlspecialchars($ir['file_name']) ?></span>
                </a>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<div class="detail-footer">
    <a href="<?= $baseUrl ?>/ir/list" class="btn-back-list">목록</a>
</div>

<script>
document.getElementById('detailDeleteBtn').addEventListener('click', function() {
    if (!confirm('IR자료를 삭제하시겠습니까?\n삭제된 데이터는 복구할 수 없습니다.')) return;
    alert('삭제되었습니다.');
    location.href = '<?= $baseUrl ?>/ir/list';
});
</script>
