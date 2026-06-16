<?php
/* 채용공고 상세/수정 — 제보하기 상세 스타일 통일 */
$isEdit = isset($recruit) && !empty($recruit);
$formTitle = $isEdit ? (isset($isDetail) ? '채용공고 상세' : '채용공고 수정') : '채용공고 등록';
$formAction = $isEdit ? ($baseUrl . '/recruit/update/' . $recruit['id']) : ($baseUrl . '/recruit/store');
?>

<?php if (isset($isDetail)): ?>
<!-- 상세보기 -->
<div class="content-header">
    <h1 class="content-title"><?= htmlspecialchars($recruit['title'] ?? '채용공고') ?></h1>
    <div class="content-header-actions">
        <a href="<?= $baseUrl ?>/recruit/delete/<?= $recruit['id'] ?>" onclick="return confirm('삭제하시겠습니까?')" class="btn-row-edit">삭제</a>
        <a href="<?= $baseUrl ?>/recruit/edit/<?= $recruit['id'] ?>" class="btn-primary">수정</a>
    </div>
</div>

<div class="detail-body">
    <div class="detail-main">
        <p class="detail-section-label">채용공고 정보</p>

        <div class="detail-row">
            <div class="detail-label">공고명</div>
            <div class="detail-value"><?= htmlspecialchars($recruit['title'] ?? '') ?></div>
        </div>
        <div class="detail-row">
            <div class="detail-label">직군</div>
            <div class="detail-value"><?= htmlspecialchars($recruit['job_group'] ?? '-') ?></div>
        </div>
        <div class="detail-row">
            <div class="detail-label">채용정보</div>
            <div class="detail-value"><?= htmlspecialchars($recruit['recruit_info'] ?? '-') ?></div>
        </div>
        <div class="detail-row">
            <div class="detail-label">채용기간</div>
            <div class="detail-value"><?= ($recruit['start_date'] ?? '-') ?> ~ <?= ($recruit['end_date'] ?? '-') ?></div>
        </div>
        <div class="detail-row">
            <div class="detail-label">상태</div>
            <div class="detail-value">
                <?php $rStatus = $recruit['recruit_status'] ?? 'waiting'; $sText = '게시 대기'; if ($rStatus === 'active') $sText = '게시 중'; elseif ($rStatus === 'closed') $sText = '마감'; ?>
                <?= $sText ?>
            </div>
        </div>
        <?php if (!empty($recruit['external_url'])): ?>
        <div class="detail-row">
            <div class="detail-label">사람인 링크</div>
            <div class="detail-value"><a href="<?= htmlspecialchars($recruit['external_url']) ?>" target="_blank" class="detail-value-link"><?= htmlspecialchars($recruit['external_url']) ?></a></div>
        </div>
        <?php endif; ?>
    </div>
</div>

<div class="detail-footer">
    <a href="<?= $baseUrl ?>/recruit/list" class="btn-back-list">목록</a>
</div>

<?php else: ?>
<!-- 등록/수정 폼 -->
<div class="content-header">
    <h1 class="content-title"><?= $formTitle ?></h1>
</div>

<form method="post" action="<?= $formAction ?>" enctype="multipart/form-data">
    <div class="detail-body">
        <div class="detail-main">
            <p class="detail-required-note"><span class="text-red">*</span>는 필수 입력 항목입니다.</p>

            <div class="detail-row">
                <div class="detail-label">공고명 <span class="text-red">*</span></div>
                <input type="text" name="title" value="<?= isset($recruit['title']) ? htmlspecialchars($recruit['title']) : '' ?>" placeholder="공고명을 입력해주세요." class="detail-input" required>
            </div>
            <div class="detail-row">
                <div class="detail-label">직군 <span class="text-red">*</span></div>
                <input type="text" name="job_group" value="<?= isset($recruit['job_group']) ? htmlspecialchars($recruit['job_group']) : '' ?>" placeholder="직군을 입력해주세요." class="detail-input" required>
            </div>
            <div class="detail-row">
                <div class="detail-label">채용정보</div>
                <input type="text" name="recruit_info" value="<?= isset($recruit['recruit_info']) ? htmlspecialchars($recruit['recruit_info']) : '' ?>" placeholder="채용정보를 입력해주세요." class="detail-input">
            </div>
            <div class="detail-row">
                <div class="detail-label">채용기간 <span class="text-red">*</span></div>
                <div class="detail-date-range">
                    <input type="date" name="start_date" value="<?= isset($recruit['start_date']) ? $recruit['start_date'] : '' ?>" class="detail-input-date" required>
                    <span class="filter-date-sep">~</span>
                    <input type="date" name="end_date" value="<?= isset($recruit['end_date']) ? $recruit['end_date'] : '' ?>" class="detail-input-date">
                </div>
            </div>
            <div class="detail-row">
                <div class="detail-label">사람인 링크</div>
                <input type="url" name="external_url" value="<?= isset($recruit['external_url']) ? htmlspecialchars($recruit['external_url']) : '' ?>" placeholder="사람인 공고 URL" class="detail-input">
            </div>
        </div>
    </div>

    <div class="detail-footer">
        <a href="<?= $baseUrl ?>/recruit/list" class="btn-back-list">취소</a>
        <button type="submit" class="btn-back-list btn-back-list-primary"><?= $isEdit ? '수정' : '등록' ?></button>
    </div>
</form>
<?php endif; ?>
