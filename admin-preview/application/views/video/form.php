<?php
/* 대표 영상 관리 — 수정 폼
 * 기획: 689-11771 (기획서 구조)
 * 스타일: 디자인 파일의 기존 컴포넌트 스타일 적용
 *
 * 필드 구성:
 * 1. 제목 (input, 필수, 30자 제한, 글자수 카운트)
 * 2. 설명 (textarea, 필수, 150자 제한, 글자수 카운트)
 * 3. 영상 URL (input, 필수, 유효성 검사, 썸네일 미리보기)
 * 4. 해시태그 (input, 태그 추가/삭제, 3개 제한)
 * 하단: 저장 버튼
 */
if (isset($video) && is_object($video)) $video = (array)$video;
$isEdit = isset($video) && !empty($video);
$title  = isset($video['title']) ? htmlspecialchars($video['title']) : '';
$desc   = isset($video['desc']) ? htmlspecialchars($video['desc']) : '';
$url    = isset($video['url']) ? htmlspecialchars($video['url']) : '';
$tags   = isset($video['tags']) ? $video['tags'] : '';
$formAction = $baseUrl . '/video/save';
?>

<!-- 헤더 -->
<div class="content-header">
    <h1 class="content-title">영상 정보 수정</h1>
    <span style="font-size:18px; font-weight:500; color:#7a7a7a;" id="autoSaveLabel"></span>
</div>

<!-- 필수 항목 안내 -->
<div style="display:flex; justify-content:flex-end; margin-bottom:20px;">
    <span style="font-size:18px; font-weight:500; color:#000;"><span style="color:red;">*</span>는 필수 입력 항목입니다.</span>
</div>

<form method="post" action="<?= $formAction ?>" id="videoEditForm">
    <?php if ($isEdit && isset($video['id'])): ?>
    <input type="hidden" name="id" value="<?= $video['id'] ?>">
    <?php endif; ?>

    <!-- 콘텐츠 카드 -->
    <div class="content-card" style="padding:60px;">
        <div class="content-body">

            <!-- 1. 제목 (필수, 30자) -->
            <div class="detail-row" style="align-items:flex-start;">
                <div class="detail-label" style="padding-top:16px;">
                    <span>제목</span><span style="color:red; margin-left:4px;">*</span>
                </div>
                <div style="flex:1; display:flex; flex-direction:column; gap:8px;">
                    <input type="text" name="title" id="inputTitle" value="<?= $title ?>"
                           placeholder="제목을 입력해주세요."
                           maxlength="30"
                           style="width:100%; height:60px; border:1px solid #a7a7a7; border-radius:10px; padding:0 24px; font-size:22px; font-family:var(--font); color:#111; box-sizing:border-box;">
                    <div style="display:flex; justify-content:flex-end;">
                        <span style="font-size:16px; color:#a7a7a7;" id="titleCount"><?= mb_strlen(html_entity_decode($title)) ?>/30</span>
                    </div>
                </div>
            </div>

            <!-- 구분선 -->
            <div class="card-divider"><img src="<?= $baseUrl ?>/public/img/icon_divider_h2.svg" alt=""></div>

            <!-- 2. 설명 (필수, 150자) -->
            <div class="detail-row" style="align-items:flex-start;">
                <div class="detail-label" style="padding-top:16px;">
                    <span>설명</span><span style="color:red; margin-left:4px;">*</span>
                </div>
                <div style="flex:1; display:flex; flex-direction:column; gap:8px;">
                    <textarea name="desc" id="inputDesc"
                              placeholder="상세 설명을 입력해주세요."
                              maxlength="150"
                              style="width:100%; height:150px; border:1px solid #a7a7a7; border-radius:10px; padding:16px 24px; font-size:22px; font-family:var(--font); color:#111; resize:none; box-sizing:border-box; line-height:1.6;"><?= $desc ?></textarea>
                    <div style="display:flex; justify-content:flex-end;">
                        <span style="font-size:16px; color:#a7a7a7;" id="descCount"><?= mb_strlen(html_entity_decode($desc)) ?>/150</span>
                    </div>
                </div>
            </div>

            <!-- 구분선 -->
            <div class="card-divider"><img src="<?= $baseUrl ?>/public/img/icon_divider_h2.svg" alt=""></div>

            <!-- 3. 영상 URL (필수, 유효성 검사 + 썸네일) -->
            <div class="detail-row" style="align-items:flex-start;">
                <div class="detail-label" style="padding-top:16px;">
                    <span>영상 URL</span><span style="color:red; margin-left:4px;">*</span>
                </div>
                <div style="flex:1; display:flex; flex-direction:column; gap:16px;">
                    <input type="text" name="url" id="inputUrl" value="<?= $url ?>"
                           placeholder="영상 URL을 입력해주세요."
                           style="width:100%; height:60px; border:1px solid #a7a7a7; border-radius:10px; padding:0 24px; font-size:22px; font-family:var(--font); color:#111; box-sizing:border-box;">
                    <div id="urlError" style="display:none; font-size:16px; color:#e53e3e;">올바른 영상 URL 주소를 입력해 주세요.</div>
                    <!-- 썸네일 미리보기 -->
                    <div id="thumbPreview" style="<?= $url ? '' : 'display:none;' ?>">
                        <div style="width:576px; height:324px; overflow:hidden;">
                            <img id="thumbImg" src="<?= $url ? '' : '' ?>" alt="영상 썸네일" style="width:100%; height:100%; object-fit:cover;">
                        </div>
                    </div>
                </div>
            </div>

            <!-- 구분선 -->
            <div class="card-divider"><img src="<?= $baseUrl ?>/public/img/icon_divider_h2.svg" alt=""></div>

            <!-- 4. 해시태그 (3개 제한) -->
            <div class="detail-row" style="align-items:flex-start;">
                <div class="detail-label" style="padding-top:16px;">
                    <span>해시태그</span>
                </div>
                <div style="flex:1; display:flex; flex-direction:column; gap:16px;">
                    <input type="text" id="inputHashtag"
                           placeholder="해시태그를 입력해 주세요."
                           style="width:100%; height:60px; border:1px solid #a7a7a7; border-radius:10px; padding:0 24px; font-size:22px; font-family:var(--font); color:#111; box-sizing:border-box;">
                    <input type="hidden" name="tags" id="hiddenTags" value="<?= htmlspecialchars($tags) ?>">
                    <!-- 생성된 해시태그 목록 -->
                    <div id="tagList" style="display:flex; gap:12px; flex-wrap:wrap;"></div>
                    <div id="tagMsg" style="display:none; font-size:16px; color:#e53e3e; margin-top:4px;"></div>
                    <div style="display:flex; justify-content:flex-end;">
                        <span style="font-size:16px; color:#a7a7a7;" id="tagCount">0/5</span>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- 저장 버튼 -->
    <div style="display:flex; justify-content:center; margin-top:40px;">
        <button type="submit" style="display:flex; align-items:center; justify-content:center; width:346px; height:80px; background:#003592; border:none; border-radius:10px; font-size:24px; font-weight:700; color:#fff; cursor:pointer; font-family:var(--font);">저장</button>
    </div>
</form>

<!-- 페이지 이탈 경고 (독립 스크립트) -->
<script>
(function(){
    var f = document.getElementById('videoEditForm');
    if (!f) { alert('폼 못 찾음'); return; }
    var dirty = false;
    f.addEventListener('input', function(){ dirty = true; });
    f.addEventListener('change', function(){ dirty = true; });
    window.addEventListener('beforeunload', function(e){
        if (dirty) { e.preventDefault(); e.returnValue = '변경사항'; return '변경사항'; }
    });
    f.addEventListener('submit', function(){ dirty = false; });
})();
</script>

<script>

// === 인라인 에러 표시 유틸 ===
function showFieldError(inputEl, msg) {
    let errEl = inputEl.parentElement.querySelector('.field-error');
    if (!errEl) {
        errEl = document.createElement('div');
        errEl.className = 'field-error';
        errEl.style.cssText = 'font-size:16px; color:#e53e3e; margin-top:4px;';
        inputEl.parentElement.appendChild(errEl);
    }
    errEl.textContent = msg;
    errEl.style.display = '';
    inputEl.style.borderColor = '#e53e3e';
}
function clearFieldError(inputEl) {
    const errEl = inputEl.parentElement.querySelector('.field-error');
    if (errEl) errEl.style.display = 'none';
    inputEl.style.borderColor = '#a7a7a7';
}

// === 글자수 카운팅 ===
const titleInput = document.getElementById('inputTitle');
const descInput  = document.getElementById('inputDesc');
const titleCount = document.getElementById('titleCount');
const descCount  = document.getElementById('descCount');

titleInput.addEventListener('input', function() {
    if (this.value.length > 30) this.value = this.value.substring(0, 30);
    titleCount.textContent = this.value.length + '/30';
    if (this.value.trim()) clearFieldError(this);
});
descInput.addEventListener('input', function() {
    if (this.value.length > 150) this.value = this.value.substring(0, 150);
    descCount.textContent = this.value.length + '/150';
    if (this.value.trim()) clearFieldError(this);
});

// === 영상 URL 유효성 검사 + 썸네일 ===
const urlInput     = document.getElementById('inputUrl');
const urlError     = document.getElementById('urlError');
const thumbPreview = document.getElementById('thumbPreview');
const thumbImg     = document.getElementById('thumbImg');

function extractYoutubeId(url) {
    const m = url.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]{11})/);
    return m ? m[1] : null;
}

let urlDebounce;
urlInput.addEventListener('input', function() {
    clearFieldError(this);
    clearTimeout(urlDebounce);
    urlDebounce = setTimeout(() => {
        const val = this.value.trim();
        if (!val) {
            thumbPreview.style.display = 'none';
            urlError.style.display = 'none';
            return;
        }
        const vid = extractYoutubeId(val);
        if (vid) {
            urlError.style.display = 'none';
            thumbImg.src = 'https://img.youtube.com/vi/' + vid + '/maxresdefault.jpg';
            thumbPreview.style.display = '';
            thumbImg.onerror = function() {
                thumbImg.src = 'https://img.youtube.com/vi/' + vid + '/hqdefault.jpg';
            };
        } else {
            urlError.style.display = '';
            urlError.textContent = '올바른 영상 URL 주소를 입력해 주세요.';
            thumbPreview.style.display = 'none';
            this.style.borderColor = '#e53e3e';
        }
    }, 500);
});
// 초기값 썸네일
(function() {
    const v = urlInput.value.trim();
    if (v) {
        const vid = extractYoutubeId(v);
        if (vid) {
            thumbImg.src = 'https://img.youtube.com/vi/' + vid + '/maxresdefault.jpg';
            thumbPreview.style.display = '';
        }
    }
})();

// === 해시태그 관리 ===
const hashtagInput = document.getElementById('inputHashtag');
const hiddenTags   = document.getElementById('hiddenTags');
const tagList      = document.getElementById('tagList');
const tagCountEl   = document.getElementById('tagCount');
const tagMsg       = document.getElementById('tagMsg');
const MAX_TAGS = 5;
let tags = [];

function parseTags(str) {
    if (!str) return [];
    return str.split(/[\s,]+/).filter(t => t.startsWith('#')).map(t => t.trim()).filter(t => t.length > 1).slice(0, MAX_TAGS);
}

function showTagMsg(msg) {
    tagMsg.textContent = msg;
    tagMsg.style.display = '';
    clearTimeout(showTagMsg._t);
    showTagMsg._t = setTimeout(() => { tagMsg.style.display = 'none'; }, 2500);
}

function renderTags() {
    tagList.innerHTML = '';
    tags.forEach((tag, i) => {
        const el = document.createElement('div');
        el.className = 'tag';
        el.innerHTML = tag + ' <span style="cursor:pointer; margin-left:8px; font-weight:700;" data-idx="' + i + '">&times;</span>';
        tagList.appendChild(el);
    });
    hiddenTags.value = tags.join(' ');
    tagCountEl.textContent = tags.length + '/' + MAX_TAGS;
    hashtagInput.disabled = tags.length >= MAX_TAGS;
    if (tags.length >= MAX_TAGS) hashtagInput.placeholder = '최대 ' + MAX_TAGS + '개까지 등록 가능합니다.';
    else hashtagInput.placeholder = '해시태그를 입력해 주세요.';
}

function addTag(raw) {
    let val = raw.trim();
    if (!val) return;
    if (!val.startsWith('#')) val = '#' + val;
    if (val.length <= 1) return; // #만 있으면 무시
    if (tags.length >= MAX_TAGS) { showTagMsg('해시태그는 최대 ' + MAX_TAGS + '개까지 등록 가능합니다.'); return; }
    if (tags.includes(val)) {
        // 중복: 조용히 무시, 인풋만 초기화 (IME 조합 대기 후)
        setTimeout(() => { hashtagInput.value = ''; hashtagInput.focus(); }, 0);
        return;
    }
    tags.push(val);
    renderTags();
    setTimeout(() => { hashtagInput.value = ''; hashtagInput.focus(); }, 0);
    tagMsg.style.display = 'none';
}

// 삭제 이벤트
tagList.addEventListener('click', function(e) {
    if (e.target.dataset.idx !== undefined) {
        tags.splice(parseInt(e.target.dataset.idx), 1);
        renderTags();
    }
});

// 한글 IME 조합 상태 추적
let isComposing = false;
hashtagInput.addEventListener('compositionstart', () => { isComposing = true; });
hashtagInput.addEventListener('compositionend', function() {
    isComposing = false;
    // 조합 완료 후 스페이스/콤마 체크
    const val = this.value;
    if (val.endsWith(' ') || val.endsWith(',')) {
        addTag(val.replace(/[\s,]+$/, ''));
    }
});

// 엔터, 스페이스바, 콤마로 태그 생성
hashtagInput.addEventListener('keydown', function(e) {
    if (isComposing) return; // IME 조합 중이면 무시
    if (e.key === 'Enter') {
        e.preventDefault();
        addTag(this.value);
    } else if (e.key === ' ' || e.key === ',') {
        e.preventDefault();
        addTag(this.value);
    }
});
// 콤마 입력 감지 (keydown에서 못 잡히는 경우 대비)
hashtagInput.addEventListener('input', function() {
    if (this.value.includes(',')) {
        const parts = this.value.split(',');
        parts.forEach(p => { if (p.trim()) addTag(p); });
        this.value = '';
    }
});

// 초기 태그 로드
tags = parseTags(hiddenTags.value);
renderTags();

// === 저장 버튼 커스텀 Validation ===
document.getElementById('videoEditForm').addEventListener('submit', function(e) {
    // 기본 브라우저 validation 제거
    e.preventDefault();

    // 유효성 검사 전 Trim
    titleInput.value = titleInput.value.trim();
    descInput.value = descInput.value.trim();
    urlInput.value = urlInput.value.trim();

    let firstError = null;

    // 제목
    if (!titleInput.value) {
        showFieldError(titleInput, '제목을 입력해 주세요. (최대 30자)');
        if (!firstError) firstError = titleInput;
    } else { clearFieldError(titleInput); }

    // 설명
    if (!descInput.value) {
        showFieldError(descInput, '영상 상세 설명을 입력해 주세요. (최대 150자)');
        if (!firstError) firstError = descInput;
    } else { clearFieldError(descInput); }

    // 영상 URL
    if (!urlInput.value) {
        urlError.textContent = '영상 URL 주소를 입력해 주세요.';
        urlError.style.display = '';
        urlInput.style.borderColor = '#e53e3e';
        if (!firstError) firstError = urlInput;
    } else if (!extractYoutubeId(urlInput.value)) {
        urlError.textContent = '올바른 영상 URL 주소를 입력해 주세요.';
        urlError.style.display = '';
        urlInput.style.borderColor = '#e53e3e';
        if (!firstError) firstError = urlInput;
    } else {
        urlError.style.display = 'none';
        urlInput.style.borderColor = '#a7a7a7';
    }

    if (firstError) {
        firstError.focus();
        return;
    }

    // submit 실행
    this.submit();
});
</script>

