<?php
/* 대표 영상 관리 — 시멘틱 CSS 클래스 기반 */
if (isset($video) && is_object($video)) $video = (array)$video;
if (isset($video['url']) && !isset($video['youtube_url'])) $video['youtube_url'] = $video['url'];
if (isset($video['desc']) && !isset($video['description'])) $video['description'] = $video['desc'];
if (isset($video['updated']) && !isset($video['updated_at'])) $video['updated_at'] = $video['updated'];
if (isset($video['tags']) && !isset($video['hashtags'])) $video['hashtags'] = $video['tags'];
?>
<div class="content-header">
    <h1 class="content-title">영상 게시 정보</h1>
    <a href="<?= $baseUrl ?>/video/edit" class="btn-primary">영상 정보 수정</a>
</div>

<div class="content-card">
    <div class="detail-updated-wrap">
        <span class="detail-updated">최종 업데이트: <?= $video['updated_at'] ?? '-' ?></span>
    </div>

    <div class="card-divider"><img src="<?= $baseUrl ?>/public/img/icon_divider_h2.svg" alt=""></div>

    <div class="content-body">
        <div class="detail-row">
            <div class="detail-label">제목</div>
            <div class="detail-value detail-value-single"><?= isset($video['title']) ? htmlspecialchars($video['title']) : '철의 역사를 만들어가는 동국산업' ?></div>
        </div>

        <div class="detail-row">
            <div class="detail-label">설명</div>
            <div class="detail-value detail-value-single"><?= isset($video['desc']) ? htmlspecialchars($video['desc']) : '-' ?></div>
        </div>

        <div class="detail-row detail-row-top">
            <div class="detail-label">영상 URL</div>
            <div class="detail-value detail-value-col">
                <?php $videoUrl = $video['url'] ?? ''; ?>
                <?php if ($videoUrl): ?>
                    <a href="<?= htmlspecialchars($videoUrl) ?>" target="_blank" class="detail-value-link"><?= htmlspecialchars($videoUrl) ?></a>
                    <?php $thumbUrl = $video['thumbnail'] ?? ''; ?>
                    <?php if ($thumbUrl): ?>
                    <div class="video-thumb-preview">
                        <img src="<?= htmlspecialchars($thumbUrl) ?>" alt="영상 썸네일">
                    </div>
                    <?php endif; ?>
                <?php else: ?>
                    <span class="td-muted">등록된 영상이 없습니다.</span>
                <?php endif; ?>
            </div>
        </div>

        <div class="detail-row">
            <div class="detail-label">해시태그</div>
            <div class="detail-value detail-value-tags">
                <?php
                $tags = $video['tags'] ?? ($video['hashtags'] ?? '');
                if (is_string($tags)) { $tags = preg_split('/[\s,]+/', $tags); $tags = array_filter(array_map('trim', $tags)); }
                if (empty($tags)) $tags = ['#동국산업', '#홍보영상', '#냉연강판'];
                foreach ($tags as $tag):
                    $tag = ltrim(trim($tag), '#');
                    if (empty($tag)) continue;
                ?>
                    <span class="tag">#<?= htmlspecialchars($tag) ?></span>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
