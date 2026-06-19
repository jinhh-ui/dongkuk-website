<?php
/* 뉴스 기사 등록/수정 폼 — 기획서 P_01.02.04 / P_01.02.03 기반 */
$isEdit = isset($item) && !empty($item);
$formTitle = $isEdit ? '뉴스 기사 수정' : '뉴스 기사 등록';
$submitLabel = $isEdit ? '수정 완료' : '등록';
$formAction = $baseUrl . '/news/save';

$title = $isEdit ? htmlspecialchars($item['title']) : '';
$postDate = $isEdit ? (isset($item['created_at']) ? substr($item['created_at'], 0, 10) : date('Y-m-d')) : date('Y-m-d');
$postTime = $isEdit ? (isset($item['created_at']) ? substr($item['created_at'], 11, 5) : '00:00') : '00:00';
$content = $isEdit ? (isset($item['content']) ? $item['content'] : '') : '';
$itemId = $isEdit ? $item['id'] : '';
?>
<div class="content-header"><h1 class="content-title"><?= $formTitle ?></h1></div>

<form method="post" action="<?= $formAction ?>" id="newsForm" enctype="multipart/form-data">
    <?php if ($isEdit): ?><input type="hidden" name="id" value="<?= $itemId ?>"><?php endif; ?>
    <input type="hidden" name="mode" value="<?= $isEdit ? 'edit' : 'create' ?>">
    <input type="hidden" name="content" id="hiddenContent">
    <input type="hidden" name="thumbnail" id="hiddenThumbnail">

    <div style="display:flex; flex-direction:column; gap:40px;">
        <!-- 필수 안내 -->
        <div style="display:flex; justify-content:flex-end;">
            <span style="font-size:18px; font-weight:500; color:#000;"><span style="color:red;">*</span>는 필수 입력 항목입니다.</span>
        </div>

        <!-- 뉴스 제목 (필수) -->
        <div style="display:flex; gap:24px; align-items:flex-start;">
            <div style="width:160px; flex-shrink:0; display:flex; gap:4px; align-items:center; padding:18px 0;">
                <span style="font-size:24px; font-weight:700; color:#363636;">뉴스 제목</span><span style="font-size:20px; color:rgba(255,0,0,0.8);">*</span>
            </div>
            <div style="flex:1; display:flex; flex-direction:column; gap:8px;">
                <input type="text" name="title" id="inputTitle" value="<?= $title ?>"
                       placeholder="뉴스 제목을 입력해주세요."
                       style="width:100%; height:60px; border:1px solid #a7a7a7; border-radius:10px; padding:0 24px; font-size:22px; font-family:var(--font); color:#111; box-sizing:border-box;">
                <div id="titleError" style="display:none; font-size:16px; color:#e53e3e;"></div>
            </div>
        </div>

        <!-- 게시일시 (필수) — 날짜 + 시간 50:50 -->
        <div style="display:flex; gap:24px; align-items:flex-start;">
            <div style="width:160px; flex-shrink:0; display:flex; gap:4px; align-items:center; padding:18px 0;">
                <span style="font-size:24px; font-weight:700; color:#363636;">게시일시</span><span style="font-size:20px; color:rgba(255,0,0,0.8);">*</span>
            </div>
            <div style="flex:1; display:flex; gap:16px; align-items:center;">
                <!-- 날짜 -->
                <div style="flex:1; position:relative;">
                    <input type="text" name="post_date" id="inputDate" value="<?= $postDate ?>"
                           class="picker-input" placeholder="YYYY-MM-DD" readonly>
                    <svg style="position:absolute; right:20px; top:50%; transform:translateY(-50%); pointer-events:none;" width="20" height="20" viewBox="0 0 20 20" fill="none"><rect x="2" y="4" width="16" height="14" rx="2" stroke="#999" stroke-width="1.5"/><path d="M2 8h16" stroke="#999" stroke-width="1.5"/><path d="M6 2v4M14 2v4" stroke="#999" stroke-width="1.5" stroke-linecap="round"/></svg>
                </div>
                <!-- 시간 -->
                <div style="flex:1; position:relative;">
                    <input type="text" name="post_time" id="inputTime" value="<?= $postTime ?>"
                           class="picker-input" readonly>
                    <svg style="position:absolute; right:20px; top:50%; transform:translateY(-50%); pointer-events:none;" width="20" height="20" viewBox="0 0 20 20" fill="none"><circle cx="10" cy="10" r="8" stroke="#999" stroke-width="1.5"/><path d="M10 6v4l3 2" stroke="#999" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
            </div>
        </div>

        <!-- 본문 에디터 (WYSIWYG) -->
        <div style="display:flex; flex-direction:column; gap:0;">
            <div id="editor" style="min-height:500px;"><?= $content ?></div>
        </div>
    </div>

    <!-- 버튼 -->
    <div class="form-actions" style="display:flex; justify-content:center; gap:20px; margin-top:60px;">
        <a href="<?= $baseUrl ?>/news/list" id="cancelBtn" class="btn-outline">취소</a>
        <button type="submit" class="btn-primary"><?= $submitLabel ?></button>
    </div>
</form>

<!-- 외부 라이브러리 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="<?= $baseUrl ?>/public/css/datepicker.css">
<link rel="stylesheet" href="<?= $baseUrl ?>/public/css/timepicker.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ko.js"></script>
<script src="<?= $baseUrl ?>/public/js/timepicker.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>

<script>
(function(){
    var dirty = false;
    var form = document.getElementById('newsForm');
    var editorInstance = null;

    // 날짜 피커 (flatpickr — 인풋 너비에 맞춤)
    var dateInput = document.getElementById('inputDate');
    flatpickr(dateInput, {
        locale: 'ko',
        dateFormat: 'Y-m-d',
        defaultDate: dateInput.value || 'today',
        disableMobile: true,
        static: true,
        appendTo: dateInput.parentElement,
        onChange: function() { dirty = true; }
    });

    // 시간 피커 (커스텀)
    new TimePicker('#inputTime', {
        onChange: function() { dirty = true; }
    });

    // dirty 플래그
    form.addEventListener('input', function(){ dirty = true; });
    form.addEventListener('change', function(){ dirty = true; });

    // 페이지 이탈 경고
    window.addEventListener('beforeunload', function(e){
        if (dirty) { e.preventDefault(); e.returnValue = ''; return ''; }
    });

    // 취소 버튼
    document.getElementById('cancelBtn').addEventListener('click', function(e){
        if (dirty) {
            e.preventDefault();
            if (confirm('변경사항이 저장되지 않았습니다.\n이 페이지를 벗어나시겠습니까?')) {
                dirty = false;
                location.href = this.href;
            }
        }
    });

    // CKEditor 초기화
    ClassicEditor
        .create(document.querySelector('#editor'), {
            toolbar: ['heading', '|', 'bold', 'italic', 'underline', 'strikethrough', '|',
                      'fontSize', 'fontColor', 'fontBackgroundColor', '|',
                      'alignment', '|', 'bulletedList', 'numberedList', '|',
                      'outdent', 'indent', '|',
                      'link', 'imageUpload', 'blockQuote', 'insertTable', '|',
                      'undo', 'redo', '|', 'sourceEditing'],
            language: 'ko',
            placeholder: '뉴스 기사 내용을 입력해주세요.'
        })
        .then(function(editor) {
            editorInstance = editor;
            editor.model.document.on('change:data', function() { dirty = true; });
        })
        .catch(function(error) { console.error('CKEditor 초기화 실패:', error); });

    // 폼 제출
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        var contentHtml = editorInstance ? editorInstance.getData() : '';
        document.getElementById('hiddenContent').value = contentHtml;

        // Validation
        var titleInput = document.getElementById('inputTitle');
        var titleError = document.getElementById('titleError');
        if (!titleInput.value.trim()) {
            titleError.textContent = '뉴스 제목을 입력해 주세요.';
            titleError.style.display = '';
            titleInput.style.borderColor = '#e53e3e';
            titleInput.focus();
            return;
        }
        titleError.style.display = 'none';
        titleInput.style.borderColor = '#a7a7a7';

        // 썸네일 자동 추출 (본문 첫 이미지)
        var tmp = document.createElement('div');
        tmp.innerHTML = contentHtml;
        var firstImg = tmp.querySelector('img');
        document.getElementById('hiddenThumbnail').value = firstImg ? firstImg.getAttribute('src') : '';

        dirty = false;
        form.submit();
    });
})();
</script>

<style>
.picker-input {
    width: 100%;
    height: 60px;
    border: 1px solid #a7a7a7;
    border-radius: 10px;
    padding: 0 50px 0 24px;
    font-size: 22px;
    font-family: var(--font);
    color: #111;
    box-sizing: border-box;
    background: #fff;
    cursor: pointer;
}
.picker-input:focus { border-color: #003592; outline: none; }


/* CKEditor */
.ck-editor__editable {
    min-height: 500px !important;
    font-size: 18px !important;
    font-family: var(--font) !important;
    line-height: 1.8 !important;
    border: none !important;
    box-shadow: none !important;
    outline: none !important;
}
.ck.ck-editor { border: 1px solid #a7a7a7 !important; border-radius: 10px !important; overflow: hidden; }
.ck.ck-editor__main > .ck-editor__editable { border: none !important; }
.ck.ck-toolbar { border: none !important; border-bottom: 1px solid #e9e9e9 !important; }
.ck.ck-editor__top .ck-sticky-panel .ck-sticky-panel__content { border: none !important; }
</style>
