<?php
/* 문의하기 상세 — 제보하기 상세와 완벽히 동일 구조 */
$title = isset($inquiry['title']) ? htmlspecialchars($inquiry['title']) : htmlspecialchars($inquiry['subject'] ?? '문의합니다.');
$isDone = ($inquiry['status'] ?? '') === 'done';

$statusLabel = '답변대기'; $dotImg = 'icon_dot_inquiry_waiting.svg';
if ($isDone) { $statusLabel = '답변완료'; $dotImg = 'icon_dot_inquiry_done.svg'; }
?>
<div class="content-header">
    <h1 class="content-title"><?= $title ?></h1>
    <button type="button" id="openDeleteModal" class="btn-row-edit">삭제</button>
</div>

<div class="detail-body">
    <div class="detail-body-columns">
        <div class="detail-main">
            <p class="detail-section-label">문의 내용</p>
            <div class="detail-row detail-row-top">
                <div class="detail-label">내용</div>
                <div class="detail-value"><?= nl2br(htmlspecialchars($inquiry['content'] ?? '')) ?></div>
            </div>
            <?php if (!empty($inquiry['files'])): ?>
            <div class="detail-row detail-row-top">
                <div class="detail-label">첨부파일</div>
                <div class="detail-value">
                    <div class="detail-files">
                    <?php foreach ($inquiry['files'] as $file): ?>
                    <div class="detail-file-item">
                        <img src="<?= $baseUrl ?>/public/img/icon_file_attach.svg" alt="" class="detail-file-icon">
                        <span class="detail-file-name"><?= htmlspecialchars($file['name'] ?? '') ?></span>
                        <span class="detail-file-size">(<?= $file['size'] ?? '' ?>)</span>
                    </div>
                    <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <div class="detail-sidebar">
            <div class="detail-status-card">
                <div class="detail-status-inner">
                    <span class="badge-dot"><img src="<?= $baseUrl ?>/public/img/<?= $dotImg ?>" alt=""><?= $statusLabel ?></span>
                    <?php if (!$isDone): ?>
                    <button type="button" id="openStatusModal" class="btn-status-change">상태변경</button>
                    <?php endif; ?>
                </div>
            </div>
            <div class="detail-info-card">
                <div class="detail-info-section">
                    <span class="detail-info-title">문의자 정보</span>
                    <div class="detail-info-rows">
                        <div class="detail-info-row"><span class="detail-info-label">이름</span><span class="detail-info-value"><?= htmlspecialchars($inquiry['name'] ?? '-') ?></span></div>
                        <div class="detail-info-row"><span class="detail-info-label">연락처</span><span class="detail-info-value"><?= htmlspecialchars($inquiry['phone'] ?? '-') ?></span></div>
                        <div class="detail-info-row"><span class="detail-info-label">이메일</span><span class="detail-info-value"><?= htmlspecialchars($inquiry['email'] ?? '-') ?></span></div>
                    </div>
                </div>
                <div class="detail-info-divider"></div>
                <div class="detail-info-section">
                    <span class="detail-info-title">접수 정보</span>
                    <div class="detail-info-rows">
                        <div class="detail-info-row"><span class="detail-info-label">접수 일시</span><span class="detail-info-value"><?= htmlspecialchars($inquiry['created_at'] ?? '-') ?></span></div>
                        <?php if ($isDone && !empty($inquiry['answered_at'])): ?>
                        <div class="detail-info-row"><span class="detail-info-label">답변 일시</span><span class="detail-info-value"><?= htmlspecialchars($inquiry['answered_at']) ?></span></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if ($isDone): ?>
    <div class="detail-answer-card">
        <p class="detail-answer-label">답변 내용</p>
        <div class="detail-row detail-row-top">
            <div class="detail-label">답변 내용</div>
            <div class="detail-value"><?= nl2br(htmlspecialchars($inquiry['answer_content'] ?? '')) ?></div>
        </div>
        <div class="detail-row">
            <div class="detail-label">작성자 명</div>
            <div class="detail-value"><?= htmlspecialchars($inquiry['answer_author'] ?? '-') ?></div>
        </div>
    </div>
    <?php else: ?>
    <form method="post" action="<?= $baseUrl ?>/inquiry/answer/<?= $inquiry['id'] ?? '' ?>" id="answerForm">
        <div class="detail-answer-card detail-answer-form">
            <p class="detail-required-note"><span class="text-red">*</span>는 필수 입력 항목입니다.</p>
            <div class="detail-row detail-row-top">
                <div class="detail-label">답변 작성 <span class="text-red">*</span></div>
                <textarea name="answer_content" id="answerContent" placeholder="내용을 입력해 주세요." class="detail-textarea" required></textarea>
            </div>
            <div class="detail-row">
                <div class="detail-label">작성자 명 <span class="text-red">*</span></div>
                <input type="text" name="answer_author" id="answerAuthor" placeholder="작성자 명을 입력해 주세요." class="detail-input" required>
            </div>
            <button type="submit" id="submitBtn" class="btn-submit-answer disabled">등록</button>
        </div>
    </form>
    <?php endif; ?>
</div>

<div class="detail-footer">
    <a href="<?= $baseUrl ?>/inquiry/list" class="btn-back-list">목록</a>
</div>

<!-- 삭제(파기) 모달 -->
<div id="deleteModal" class="modal-overlay hidden">
    <div class="modal-box modal-box-wide">
        <h2 class="modal-title">삭제 확인</h2>
        <div class="modal-warning">
            <p>삭제 시 개인정보를 포함한 모든 데이터가 즉시 파기되며 복구가 불가합니다.<br>관리자 명을 입력 후 삭제를 진행해 주세요. <span class="text-red">*</span>는 필수 입력 항목입니다.</p>
        </div>
        <form method="post" action="<?= $baseUrl ?>/inquiry/delete/<?= $inquiry['id'] ?? '' ?>" id="deleteForm">
            <div class="modal-field">
                <label class="modal-field-label">파기자 명 <span class="text-red">*</span></label>
                <input type="text" name="destroyer_name" id="destroyerName" placeholder="파기자 명을 입력해 주세요." class="modal-field-input">
                <div id="destroyerError" class="modal-field-error hidden">파기자 명을 입력해 주세요.</div>
            </div>
            <div class="modal-actions">
                <button type="button" id="cancelDelete" class="btn-modal-cancel">취소</button>
                <button type="submit" class="btn-modal-danger">삭제</button>
            </div>
        </form>
    </div>
</div>

<?php if (!$isDone): ?>
<!-- 상태변경 모달 -->
<div id="statusModal" class="modal-overlay hidden">
    <div class="modal-box">
        <h2 class="modal-title">상태 변경</h2>
        <form method="post" id="statusChangeForm">
            <p class="modal-desc">변경할 상태를 선택해 주세요.</p>
            <div class="status-options" id="statusOptions">
                <label class="status-option" data-value="waiting">
                    <input type="radio" name="new_status" value="waiting" <?= ($inquiry['status'] ?? '') !== 'done' ? 'checked' : '' ?>>
                    <img src="<?= $baseUrl ?>/public/img/icon_dot_inquiry_waiting.svg" alt="">
                    <span>답변대기</span>
                </label>
                <label class="status-option" data-value="done">
                    <input type="radio" name="new_status" value="done">
                    <img src="<?= $baseUrl ?>/public/img/icon_dot_inquiry_done.svg" alt="">
                    <span>답변완료</span>
                </label>
            </div>
            <div class="modal-actions">
                <button type="button" id="cancelStatusModal" class="btn-modal-cancel">취소</button>
                <button type="submit" class="btn-modal-confirm">변경</button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<script>
(function() {
    // 답변 폼 활성화
    var contentEl = document.getElementById('answerContent');
    var authorEl = document.getElementById('answerAuthor');
    if (contentEl && authorEl) {
        [contentEl, authorEl].forEach(function(el) {
            el.addEventListener('input', function() {
                var btn = document.getElementById('submitBtn');
                if (contentEl.value.trim() && authorEl.value.trim()) { btn.classList.remove('disabled'); btn.classList.add('active'); }
                else { btn.classList.add('disabled'); btn.classList.remove('active'); }
            });
        });
    }

    // 삭제 모달
    var deleteModal = document.getElementById('deleteModal');
    document.getElementById('openDeleteModal').addEventListener('click', function() { deleteModal.classList.remove('hidden'); });
    document.getElementById('cancelDelete').addEventListener('click', function() { deleteModal.classList.add('hidden'); });
    deleteModal.addEventListener('click', function(e) { if (e.target === deleteModal) deleteModal.classList.add('hidden'); });
    document.getElementById('deleteForm').addEventListener('submit', function(e) {
        var name = document.getElementById('destroyerName').value.trim();
        if (!name) { e.preventDefault(); document.getElementById('destroyerError').classList.remove('hidden'); document.getElementById('destroyerName').focus(); return; }
        document.getElementById('destroyerError').classList.add('hidden');
    });

    // 상태변경 모달
    var statusModal = document.getElementById('statusModal');
    var statusBtn = document.getElementById('openStatusModal');
    if (statusModal && statusBtn) {
        statusBtn.addEventListener('click', function() {
            var current = '<?= $inquiry['status'] ?? 'waiting' ?>';
            document.querySelectorAll('#statusOptions .status-option').forEach(function(label) {
                label.classList.toggle('active', label.dataset.value === current);
            });
            statusModal.classList.remove('hidden');
        });
        document.getElementById('cancelStatusModal').addEventListener('click', function() { statusModal.classList.add('hidden'); });
        statusModal.addEventListener('click', function(e) { if (e.target === statusModal) statusModal.classList.add('hidden'); });
        document.querySelectorAll('#statusOptions input[name="new_status"]').forEach(function(radio) {
            radio.addEventListener('change', function() {
                document.querySelectorAll('#statusOptions .status-option').forEach(function(l) { l.classList.remove('active'); });
                this.closest('.status-option').classList.add('active');
            });
        });
        document.getElementById('statusChangeForm').addEventListener('submit', function(e) {
            e.preventDefault();
            var selected = this.querySelector('input[name="new_status"]:checked');
            if (!selected) return;
            alert('상태가 변경되었습니다.');
            statusModal.classList.add('hidden'); location.reload();
        });
    }
})();
</script>
