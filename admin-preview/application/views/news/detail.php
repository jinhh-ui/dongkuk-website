<?php
/* 뉴스 기사 상세 — 기획서 P_01.02.02 기반 */
?>
<!-- 헤더: 제목 + 삭제/수정 버튼 -->
<div style="display:flex; justify-content:space-between; align-items:flex-start; gap:20px;">
    <h1 class="content-title" style="flex:1; min-width:0;"><?= htmlspecialchars($item['title'] ?? '뉴스 기사') ?></h1>
    <div style="display:flex; gap:12px; flex-shrink:0; align-items:center;">
        <button type="button" id="detailDeleteBtn" class="btn-outline">삭제</button>
        <a href="<?= $baseUrl ?>/news/edit/<?= $item['id'] ?? '' ?>" class="btn-primary">뉴스 기사 수정</a>
    </div>
</div>

<!-- 게시 일시 -->
<div style="display:flex; gap:16px; align-items:center; margin-top:20px;">
    <span style="font-size:18px; font-weight:700; color:#363636;">게시 일시</span>
    <span style="font-size:18px; font-weight:500; color:#7a7a7a;"><?= isset($item['created_at']) ? $item['created_at'] : '-' ?></span>
</div>

<!-- 본문 프리뷰 -->
<div style="margin-top:40px; padding:40px; background:#fff; border-radius:12px; border:1px solid #e9e9e9;">
    <div style="font-size:18px; color:#222; line-height:1.8; word-break:break-word;">
        <?= $item['content'] ?? '' ?>
    </div>
</div>

<!-- 출처 -->
<?php if (!empty($item['source'])): ?>
<div style="margin-top:20px; font-size:16px; color:#7a7a7a;">
    출처: <?= htmlspecialchars($item['source']) ?>
</div>
<?php endif; ?>

<!-- 목록 버튼 -->
<div style="display:flex; justify-content:center; margin-top:60px;">
    <a href="<?= $baseUrl ?>/news/list" style="display:flex; align-items:center; justify-content:center; width:120px; height:60px; border:1px solid rgba(34,34,34,0.2); border-radius:10px; font-size:20px; font-weight:700; color:#363636; font-family:var(--font);">목록</a>
</div>

<script>
// 삭제 버튼
document.getElementById('detailDeleteBtn').addEventListener('click', function() {
    if (!confirm('뉴스 기사를 삭제하시겠습니까?\n삭제된 데이터는 복구할 수 없습니다.')) return;
    // DEV_MODE: 삭제 시뮬레이션
    alert('삭제되었습니다.');
    location.href = '<?= $baseUrl ?>/news/list';
});
</script>
