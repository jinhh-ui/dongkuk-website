<?php
/* IR자료 등록/수정 폼 — 공고 등록(notice/form.php)과 동일 구조
 * 피그마 744:6640 기반: 탭 카테고리 = IR레터/IR북
 * label: 24px Bold #363636 w-160 py-12
 * input: h-60 border #a7a7a7 rounded-10 px-24 22px Regular
 * radio: 36x36 SVG + 22px Regular #000 gap-16
 * file: 파일선택 btn(bg #003592 h-52 rounded-10 px-20 18px Medium white) + dropzone(h-200)
 * 하단: 취소(border #003592 w-346 h-80 rounded-10) + 등록(bg #003592 w-346 h-80)
 */
$isEdit = isset($ir) && !empty($ir);
$formTitle = $isEdit ? 'IR자료 수정' : 'IR자료 등록';
$submitLabel = $isEdit ? '수정' : '등록';
$formAction = $isEdit ? ($baseUrl . '/ir/update/' . $ir['id']) : ($baseUrl . '/ir/store');

$title = $isEdit ? htmlspecialchars($ir['title']) : '';
$publishDate = $isEdit ? (isset($ir['publish_date']) ? $ir['publish_date'] : date('Y-m-d')) : date('Y-m-d');
$publishTime = $isEdit ? (isset($ir['publish_time']) ? $ir['publish_time'] : '00:00') : '00:00';
$currentCat = $isEdit ? (isset($ir['category']) ? $ir['category'] : 'letter') : 'letter';
?>
<!-- 헤더 -->
<div class="content-header">
    <h1 class="content-title"><?= $formTitle ?></h1>
</div>

<!-- 폼 -->
<form method="post" action="<?= $formAction ?>" id="irForm" enctype="multipart/form-data">
    <?php if ($isEdit): ?><input type="hidden" name="id" value="<?= $ir['id'] ?>"><?php endif; ?>

    <div style="display:flex; flex-direction:column; gap:40px;">
        <!-- 필수 표시 -->
        <div style="display:flex; justify-content:flex-end;">
            <span style="font-size:18px; font-weight:500; color:#000; line-height:1.6;"><span style="color:red;">*</span>는 필수 입력 항목입니다.</span>
        </div>

        <!-- 자료명 -->
        <div style="display:flex; gap:24px; align-items:center; height:60px;">
            <div style="width:160px; flex-shrink:0; padding:12px 0; display:flex; gap:4px; align-items:center;">
                <span style="font-size:24px; font-weight:700; color:#363636; line-height:1.5;">자료명</span>
                <span style="font-size:20px; font-weight:400; color:rgba(255,0,0,0.8); line-height:32px;">*</span>
            </div>
            <div style="flex:1; display:flex; flex-direction:column; gap:8px;">
                <input type="text" name="title" id="inputTitle" value="<?= $title ?>" placeholder="IR자료명을 입력해주세요." style="width:100%; height:60px; border:1px solid #a7a7a7; border-radius:10px; padding:0 24px; font-size:22px; font-weight:400; color:#000; line-height:1.6; font-family:var(--font); box-sizing:border-box;">
                <div id="titleError" style="display:none; font-size:16px; color:#e53e3e;"></div>
            </div>
        </div>

        <!-- 게시 일시 -->
        <div style="display:flex; gap:24px; align-items:center;">
            <div style="width:160px; flex-shrink:0; padding:12px 0; display:flex; gap:4px; align-items:center;">
                <span style="font-size:24px; font-weight:700; color:#363636; line-height:1.5;">게시 일시</span>
                <span style="font-size:20px; font-weight:400; color:rgba(255,0,0,0.8); line-height:32px;">*</span>
            </div>
            <div style="flex:1; display:flex; gap:16px; align-items:center;">
                <div style="flex:1; position:relative;">
                    <input type="text" name="publish_date" id="irDate" value="<?= $publishDate ?>" class="picker-input" placeholder="YYYY-MM-DD" readonly>
                    <svg style="position:absolute; right:20px; top:50%; transform:translateY(-50%); pointer-events:none;" width="20" height="20" viewBox="0 0 20 20" fill="none"><rect x="2" y="4" width="16" height="14" rx="2" stroke="#999" stroke-width="1.5"/><path d="M2 8h16" stroke="#999" stroke-width="1.5"/><path d="M6 2v4M14 2v4" stroke="#999" stroke-width="1.5" stroke-linecap="round"/></svg>
                </div>
                <div style="flex:1; position:relative;">
                    <input type="text" name="publish_time" id="irTime" value="<?= $publishTime ?>" class="picker-input" readonly>
                    <svg style="position:absolute; right:20px; top:50%; transform:translateY(-50%); pointer-events:none;" width="20" height="20" viewBox="0 0 20 20" fill="none"><circle cx="10" cy="10" r="8" stroke="#999" stroke-width="1.5"/><path d="M10 6v4l3 2" stroke="#999" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
            </div>
        </div>

        <!-- 구분: IR레터 / IR북 -->
        <div style="display:flex; gap:24px; align-items:center; height:60px;">
            <div style="width:160px; flex-shrink:0; padding:12px 0; display:flex; gap:4px; align-items:center;">
                <span style="font-size:24px; font-weight:700; color:#363636; line-height:1.5;">구분</span>
                <span style="font-size:20px; font-weight:400; color:rgba(255,0,0,0.8); line-height:32px;">*</span>
            </div>
            <div style="display:flex; gap:24px; align-items:center;">
                <?php
                $categories = ['letter' => 'IR레터', 'book' => 'IR북'];
                foreach ($categories as $val => $label):
                ?>
                <label style="display:flex; gap:16px; align-items:center; width:172px; cursor:pointer;">
                    <input type="radio" name="category" value="<?= $val ?>" <?= $currentCat === $val ? 'checked' : '' ?> style="display:none;" class="ir-radio">
                    <img src="<?= $baseUrl ?>/public/img/icon_radio_<?= $currentCat === $val ? 'on' : 'off' ?>.svg" alt="" style="width:36px; height:36px;" class="radio-img">
                    <span style="font-size:22px; font-weight:400; color:#000; line-height:1.6;"><?= $label ?></span>
                </label>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- 첨부파일 -->
        <div style="display:flex; gap:24px; align-items:flex-start;">
            <div style="width:160px; flex-shrink:0; padding:12px 0;">
                <span style="font-size:24px; font-weight:700; color:#363636; line-height:1.5;">첨부파일</span>
            </div>
            <div style="flex:1; display:flex; flex-direction:column; gap:24px;">
                <div style="padding-top:4px;">
                    <label for="irFile" style="display:inline-flex; align-items:center; justify-content:center; height:52px; padding:0 20px; background:#003592; border-radius:10px; font-size:18px; font-weight:500; color:#fff; cursor:pointer; line-height:1.6;">
                        파일선택
                    </label>
                    <input type="file" name="file" id="irFile" style="display:none;" accept=".jpg,.gif,.png,.xls,.xlsx,.doc,.docx,.ppt,.pdf,.txt,.zip">
                    <span id="fileNameDisplay" style="margin-left:12px; font-size:18px; color:#363636;"></span>
                </div>
                <div id="irDropzone" style="width:100%; height:200px; border:1px dashed #a7a7a7; border-radius:10px; background:#f8f9fb; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:10px; padding:13px 24px;">
                    <img src="<?= $baseUrl ?>/public/img/icon_file_cog.svg" alt="" style="width:36px; height:36px;">
                    <span style="font-size:20px; font-weight:400; color:#4e4e4e; line-height:1.6;">첨부할 파일을 여기에 끌어다 놓거나, 파일선택 버튼을 눌러 직접 선택해 주세요.</span>
                </div>
                <div style="display:flex; flex-direction:column; gap:10px;">
                    <div style="display:flex; gap:6px; align-items:flex-start;">
                        <div style="display:flex; align-items:center; height:32px; padding:0 5px;"><span style="display:inline-block; width:6px; height:6px; background:#7a7a7a; border-radius:50%;"></span></div>
                        <span style="font-size:20px; font-weight:400; color:#7a7a7a; line-height:1.6;">jpg, gif, png, xls, xlsx, doc, docx, ppt, pdf, txt, zip 파일만 업로드가 가능합니다.</span>
                    </div>
                    <div style="display:flex; gap:6px; align-items:flex-start;">
                        <div style="display:flex; align-items:center; height:32px; padding:0 5px;"><span style="display:inline-block; width:6px; height:6px; background:#7a7a7a; border-radius:50%;"></span></div>
                        <span style="font-size:20px; font-weight:400; color:#7a7a7a; line-height:1.6;">파일은 최대 20MB,  1개까지 등록 가능합니다.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 하단 버튼: gap-16, center, py-60 -->
    <div class="form-actions" style="display:flex; gap:16px; align-items:center; justify-content:center; padding:60px 0;">
        <a href="<?= $baseUrl ?>/ir/list" id="cancelBtn" style="display:flex; align-items:center; justify-content:center; width:346px; height:80px; border:1px solid #003592; border-radius:10px; font-size:24px; font-weight:700; color:#003592; line-height:1.5;">
            취소
        </a>
        <button type="submit" style="display:flex; align-items:center; justify-content:center; width:346px; height:80px; background:#003592; border:1px solid #003592; border-radius:10px; font-size:24px; font-weight:700; color:#fff; line-height:1.5; cursor:pointer; font-family:var(--font);">
            <?= $submitLabel ?>
        </button>
    </div>
</form>

<!-- 외부 라이브러리 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="<?= $baseUrl ?>/public/css/datepicker.css">
<link rel="stylesheet" href="<?= $baseUrl ?>/public/css/timepicker.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ko.js"></script>
<script src="<?= $baseUrl ?>/public/js/timepicker.js"></script>

<script>
(function(){
    var dirty = false;
    var form = document.getElementById('irForm');

    // 날짜 피커
    var dateInput = document.getElementById('irDate');
    flatpickr(dateInput, {
        locale: 'ko', dateFormat: 'Y-m-d',
        defaultDate: dateInput.value || 'today',
        disableMobile: true, static: true,
        appendTo: dateInput.parentElement,
        onChange: function() { dirty = true; }
    });

    // 시간 피커
    new TimePicker('#irTime', { onChange: function() { dirty = true; } });

    // 라디오 이미지 토글
    document.querySelectorAll('.ir-radio').forEach(function(radio) {
        radio.addEventListener('change', function() {
            document.querySelectorAll('.radio-img').forEach(function(img) {
                img.src = '<?= $baseUrl ?>/public/img/icon_radio_off.svg';
            });
            this.parentElement.querySelector('.radio-img').src = '<?= $baseUrl ?>/public/img/icon_radio_on.svg';
            dirty = true;
        });
    });

    // 파일 드롭존
    var dz = document.getElementById('irDropzone');
    var fileInput = document.getElementById('irFile');
    var fileDisplay = document.getElementById('fileNameDisplay');

    dz.addEventListener('dragover', function(e) { e.preventDefault(); this.style.borderColor = '#003592'; });
    dz.addEventListener('dragleave', function() { this.style.borderColor = '#a7a7a7'; });
    dz.addEventListener('drop', function(e) {
        e.preventDefault(); this.style.borderColor = '#a7a7a7';
        fileInput.files = e.dataTransfer.files;
        showFileName(e.dataTransfer.files); dirty = true;
    });
    fileInput.addEventListener('change', function() { showFileName(this.files); dirty = true; });
    function showFileName(files) {
        if (!files || files.length === 0) return;
        var file = files[0];
        var sizeMB = (file.size / (1024 * 1024)).toFixed(1);
        var maxMB = 20;

        dz.style.height = 'auto';
        dz.style.alignItems = 'flex-start';
        dz.style.justifyContent = 'flex-start';
        dz.style.gap = '20px';
        dz.style.padding = '24px';
        dz.innerHTML = '' +
            '<div style="display:flex; gap:16px; align-items:center; width:100%;">' +
                '<div style="display:flex; gap:16px; align-items:center; white-space:nowrap; line-height:1.6;">' +
                    '<span style="font-size:18px; font-weight:500; color:#4e4e4e;">파일용량</span>' +
                    '<div style="display:flex; gap:4px; align-items:center;">' +
                        '<span style="font-size:18px; font-weight:500; color:#003592;">' + sizeMB + 'MB</span>' +
                        '<span style="font-size:20px; font-weight:400; color:#4e4e4e;">/</span>' +
                        '<span style="font-size:18px; font-weight:500; color:#4e4e4e;">' + maxMB + 'MB</span>' +
                    '</div>' +
                '</div>' +
                '<div style="width:0; height:16px; display:flex; align-items:center; justify-content:center;">' +
                    '<div style="width:16px; height:0; border-top:1px solid #d3d3d3; transform:rotate(90deg);"></div>' +
                '</div>' +
                '<div style="display:flex; gap:16px; align-items:center; white-space:nowrap; line-height:1.6;">' +
                    '<span style="font-size:18px; font-weight:500; color:#4e4e4e;">첨부파일</span>' +
                    '<div style="display:flex; gap:4px; align-items:center;">' +
                        '<span style="font-size:18px; font-weight:500; color:#003592;">1개</span>' +
                        '<span style="font-size:20px; font-weight:400; color:#4e4e4e;">/</span>' +
                        '<span style="font-size:18px; font-weight:500; color:#4e4e4e;">1개</span>' +
                    '</div>' +
                '</div>' +
            '</div>' +
            '<div style="display:flex; flex-direction:column; gap:8px; width:100%;">' +
                '<div style="display:flex; gap:24px; align-items:center; padding:12px 15px; background:#fff; border-radius:8px;">' +
                    '<span class="file-remove-btn" style="display:flex; align-items:center; justify-content:center; width:24px; height:24px; cursor:pointer; font-size:18px; color:#a7a7a7; font-weight:400; flex-shrink:0;">✕</span>' +
                    '<div style="display:flex; gap:16px; align-items:center; line-height:1.6;">' +
                        '<span style="font-size:20px; font-weight:400; color:#4e4e4e; white-space:nowrap;">' + file.name + '</span>' +
                        '<span style="font-size:16px; font-weight:400; color:#a7a7a7; line-height:1.6;">(' + sizeMB + 'MB)</span>' +
                    '</div>' +
                '</div>' +
            '</div>';

        dz.querySelector('.file-remove-btn').addEventListener('click', function() {
            fileInput.value = '';
            fileDisplay.textContent = '';
            resetDropzone();
        });
    }

    function resetDropzone() {
        dz.style.height = '200px';
        dz.style.alignItems = 'center';
        dz.style.justifyContent = 'center';
        dz.style.gap = '10px';
        dz.style.padding = '13px 24px';
        dz.innerHTML = '<img src="<?= $baseUrl ?>/public/img/icon_file_cog.svg" alt="" style="width:36px; height:36px;">' +
            '<span style="font-size:20px; font-weight:400; color:#4e4e4e; line-height:1.6;">첨부할 파일을 여기에 끌어다 놓거나, 파일선택 버튼을 눌러 직접 선택해 주세요.</span>';
    }

    // dirty
    form.addEventListener('input', function(){ dirty = true; });
    form.addEventListener('change', function(){ dirty = true; });

    // 이탈 경고
    window.addEventListener('beforeunload', function(e) { if (dirty) { e.preventDefault(); e.returnValue = ''; return ''; } });

    // 취소
    document.getElementById('cancelBtn').addEventListener('click', function(e) {
        if (dirty) {
            e.preventDefault();
            if (confirm('변경사항이 저장되지 않았습니다.\n이 페이지를 벗어나시겠습니까?')) { dirty = false; location.href = this.href; }
        }
    });

    // 폼 제출
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        var titleInput = document.getElementById('inputTitle');
        var titleError = document.getElementById('titleError');
        if (!titleInput.value.trim()) {
            titleError.textContent = 'IR자료명을 입력해 주세요.';
            titleError.style.display = ''; titleInput.style.borderColor = '#e53e3e'; titleInput.focus(); return;
        }
        titleError.style.display = 'none'; titleInput.style.borderColor = '#a7a7a7';
        dirty = false; form.submit();
    });
})();
</script>

<style>
.picker-input {
    width: 100%; height: 60px;
    border: 1px solid #a7a7a7; border-radius: 10px;
    padding: 0 50px 0 24px; font-size: 22px;
    font-family: var(--font); color: #000;
    box-sizing: border-box; background: #fff;
    cursor: pointer; line-height: 1.6;
}
.picker-input:focus { border-color: #003592; outline: none; }
</style>
