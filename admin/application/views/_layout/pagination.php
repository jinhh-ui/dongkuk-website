<?php if (isset($totalPages) && $totalPages > 1): ?>
<div class="pagination">
    <?php if ($page > 1): ?>
        <a href="?page=<?= $page-1 ?>&text=<?= urlencode($search ?? '') ?>" class="page-arrow"><span class="material-icons-outlined">chevron_left</span></a>
    <?php endif; ?>
    <?php
    $s = max(1, $page - 2); $e = min($totalPages, $s + 4); $s = max(1, $e - 4);
    for ($i = $s; $i <= $e; $i++): ?>
        <a href="?page=<?= $i ?>&text=<?= urlencode($search ?? '') ?>" class="page-num <?= $i===$page?'active':'' ?>"><?= $i ?></a>
    <?php endfor; ?>
    <?php if ($page < $totalPages): ?>
        <a href="?page=<?= $page+1 ?>&text=<?= urlencode($search ?? '') ?>" class="page-arrow"><span class="material-icons-outlined">chevron_right</span></a>
    <?php endif; ?>
</div>
<?php endif; ?>
