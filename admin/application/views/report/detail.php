<?php
/* 제보하기 상세 — 피그마 744:6835 100% 매칭
 * Contents: p-60 gap-60 w-full
 * Title: gap-8 justify-end
 *   제목: 36px Bold black line-1.5
 *   삭제 btn: bg-white border-#003592 w-121 rounded-8 px-24 py-14, 16px Bold #003592
 * 본문: gap-100
 *   좌(flex-1 gap-20):
 *     "제보 내용": 18px Medium #7a7a7a line-1.6
 *     내용: gap-24, Title(w-160 h-58) 24px Bold #363636 line-1.5, con(flex-1) 22px Regular black line-1.6
 *     첨부파일: gap-24, Title(w-160 py-12) 24px Bold #363636, 파일(bg-#f8f9fb gap-10 px-24 py-20 rounded-10, ic_File 24x24, 20px Regular black, 20px Regular #4e4e4e)
 *   우(shrink-0 gap-12 pt-50):
 *     상태카드: bg-#e0eaf9 rounded-20 p-32 w-440
 *       w-200: dot(12px) gap-6 18px SemiBold #222 + 상태변경btn(border-#d3d3d3 rounded-52 px-12 py-6 14px Regular black)
 *     정보카드: bg-white border-#d3d3d3 rounded-20 p-32 w-440 gap-40
 *       제보자 정보: 18px Bold #363636 gap-30 → rows 20px Medium gap-24 (label w-100 #7a7a7a / val #1b1b1b)
 *       구분선: h-0 (1px line)
 *       접수 정보: 동일
 * 답변: bg-white rounded-20 px-24 py-30 w-1480 gap-24 items-end
 *   "* 필수": 18px Medium #7a7a7a
 *   제보결과 작성: label(w-160 py-12 gap-4) 24px Bold #363636 + * 20px Regular rgba(255,0,0,0.8) / textarea(flex-1 h-302 border-#d3d3d3 rounded-10 px-24 py-12 22px Regular #a7a7a7 placeholder)
 *   작성자 명: 동일 / input(flex-1 border-#d3d3d3 rounded-10 px-24 py-12 22px)
 *   등록 btn: bg-#d3d3d3 h-68 rounded-10 px-40 py-20 20px Bold #7a7a7a
 * 목록 btn: py-60 center → border-#003592 rounded-10 w-346 h-80 px-40 py-15 24px Bold #003592
 */
$title = isset($report['title']) ? htmlspecialchars($report['title']) : '제보합니다.';
$isDone = ($report['status'] ?? '') === 'done';
$isAnonymous = ($report['report_type'] ?? '') === 'anonymous';

$statusLabel = '제보접수'; $dotImg = 'icon_dot_report_received.svg';
if (($report['status'] ?? '') === 'review') { $statusLabel = '검토 중'; $dotImg = 'icon_dot_report_review.svg'; }
elseif ($isDone) { $statusLabel = '처리완료'; $dotImg = 'icon_dot_report_done.svg'; }
?>
<!-- Title: gap-8 -->
<div style="display:flex; flex-direction:column; gap:8px; align-items:flex-start; justify-content:flex-end; width:100%;">
    <div style="display:flex; justify-content:space-between; align-items:center; width:100%;">
        <!-- 36px Bold black line-1.5 -->
        <p style="font-size:36px; font-weight:700; color:#000; margin:0; line-height:1.5;"><?= $title ?></p>
        <!-- 삭제 btn: bg-white border-#003592 w-121 rounded-8 px-24 py-14 16px Bold #003592 -->
        <button type="button" id="openDeleteModal" style="display:flex; align-items:center; justify-content:center; width:121px; padding:14px 24px; border:1px solid #003592; border-radius:8px; background:#fff; font-size:16px; font-weight:700; color:#003592; line-height:1.6; cursor:pointer; font-family:var(--font); box-sizing:border-box;">삭제</button>
    </div>
</div>

<!-- 본문: gap-100 -->
<div style="display:flex; flex-direction:column; gap:100px; width:100%;">
    <!-- 좌+우: gap-20 -->
    <div style="display:flex; gap:20px; align-items:flex-start; width:100%;">
        <!-- 좌: flex-1 gap-20 -->
        <div style="flex:1; min-width:0; display:flex; flex-direction:column; gap:20px;">
            <!-- "제보 내용": 18px Medium #7a7a7a line-1.6 -->
            <p style="font-size:18px; font-weight:500; color:#7a7a7a; line-height:1.6; margin:0;">제보 내용</p>
            <!-- 내용: gap-24 -->
            <div style="display:flex; gap:24px; align-items:flex-start; width:100%;">
                <!-- Title: w-160 h-58 center -->
                <div style="width:160px; flex-shrink:0; height:58px; display:flex; align-items:center;">
                    <p style="font-size:24px; font-weight:700; color:#363636; line-height:1.5; margin:0;">내용</p>
                </div>
                <!-- con: flex-1 22px Regular black line-1.6 -->
                <div style="flex:1; min-width:0;">
                    <p style="font-size:22px; font-weight:400; color:#000; line-height:1.6; margin:0;"><?= nl2br(htmlspecialchars($report['content'] ?? '')) ?></p>
                </div>
            </div>
            <!-- 첨부파일: gap-24 -->
            <div style="display:flex; gap:24px; align-items:flex-start; width:100%;">
                <!-- Title: w-160 py-12 -->
                <div style="width:160px; flex-shrink:0; padding:12px 0; display:flex; align-items:center;">
                    <p style="font-size:24px; font-weight:700; color:#363636; line-height:1.5; margin:0;">첨부파일</p>
                </div>
                <!-- 파일 목록: flex-1 gap-16 -->
                <div style="flex:1; min-width:0; display:flex; flex-direction:column; gap:16px;">
                    <?php if (!empty($report['files'])): ?>
                    <?php foreach ($report['files'] as $file): ?>
                    <!-- bg-#f8f9fb gap-10 px-24 py-20 rounded-10 -->
                    <div style="display:flex; gap:10px; align-items:center; background:#f8f9fb; border-radius:10px; padding:20px 24px;">
                        <!-- ic_File 24x24 -->
                        <img src="<?= $baseUrl ?>/public/img/icon_file_attach.svg" alt="" style="width:16px; height:20px; flex-shrink:0;">
                        <!-- 20px Regular black -->
                        <span style="font-size:20px; font-weight:400; color:#000; line-height:1.6;"><?= htmlspecialchars($file['name'] ?? '') ?></span>
                        <!-- 20px Regular #4e4e4e -->
                        <span style="font-size:20px; font-weight:400; color:#4e4e4e; line-height:1.6;">(<?= $file['size'] ?? '' ?>)</span>
                    </div>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <span style="font-size:20px; font-weight:400; color:#7a7a7a; line-height:1.6;">첨부파일이 없습니다.</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- 우: shrink-0 gap-12 pt-50 -->
        <div style="flex-shrink:0; padding-top:50px; display:flex; flex-direction:column; gap:12px;">
            <!-- 상태카드: bg-#e0eaf9 rounded-20 p-32 w-440 -->
            <div style="background:#e0eaf9; border-radius:20px; padding:32px; width:440px; box-sizing:border-box; display:flex; align-items:center;">
                <!-- w-200: dot+label gap-6, 상태변경btn gap-8 -->
                <div style="display:flex; gap:8px; align-items:center; width:200px;">
                    <div style="display:flex; gap:6px; align-items:center;">
                        <img src="<?= $baseUrl ?>/public/img/<?= $dotImg ?>" alt="" style="width:12px; height:12px; flex-shrink:0;">
                        <span style="font-size:18px; font-weight:600; color:#222; line-height:normal; white-space:nowrap;"><?= $statusLabel ?></span>
                    </div>
                    <?php if (!$isDone): ?>
                    <!-- 상태변경: bg-white border-#d3d3d3 rounded-52 px-12 py-6 14px Regular black -->
                    <button type="button" id="openStatusModal" style="display:inline-flex; align-items:center; justify-content:center; padding:6px 12px; border:1px solid #d3d3d3; border-radius:52px; background:#fff; font-size:14px; font-weight:400; color:#000; line-height:1.5; cursor:pointer; font-family:var(--font); white-space:nowrap;">상태변경</button>
                    <?php endif; ?>
                </div>
            </div>
            <!-- 정보카드: bg-white border-#d3d3d3 rounded-20 p-32 w-440 gap-40 -->
            <div style="background:#fff; border:1px solid #d3d3d3; border-radius:20px; padding:32px; width:440px; box-sizing:border-box; display:flex; flex-direction:column; gap:40px;">
                <!-- 제보자 정보: gap-30 line-1.6 -->
                <div style="display:flex; flex-direction:column; gap:30px; line-height:1.6; width:100%;">
                    <p style="font-size:18px; font-weight:700; color:#363636; margin:0;">제보자 정보</p>
                    <!-- rows: 20px Medium gap-24, label w-100 #7a7a7a, val #1b1b1b -->
                    <div style="display:flex; flex-direction:column; gap:24px; font-size:20px; font-weight:500; width:100%;">
                        <div style="display:flex; gap:10px; align-items:center; width:100%;"><span style="color:#7a7a7a; width:100px; flex-shrink:0;">제보방식</span><span style="color:#1b1b1b;"><?= $isAnonymous ? '익명' : '실명' ?></span></div>
                        <div style="display:flex; gap:10px; align-items:center; width:100%;"><span style="color:#7a7a7a; width:100px; flex-shrink:0;">이름</span><span style="color:#1b1b1b;"><?= $isAnonymous ? '-' : htmlspecialchars($report['name'] ?? '-') ?></span></div>
                        <div style="display:flex; gap:10px; align-items:center; width:100%;"><span style="color:#7a7a7a; width:100px; flex-shrink:0;">연락처</span><span style="color:#1b1b1b;"><?= $isAnonymous ? '-' : htmlspecialchars($report['phone'] ?? '-') ?></span></div>
                        <div style="display:flex; gap:10px; align-items:center; width:100%;"><span style="color:#7a7a7a; width:100px; flex-shrink:0;">이메일</span><span style="color:#1b1b1b;"><?= $isAnonymous ? '-' : htmlspecialchars($report['email'] ?? '-') ?></span></div>
                    </div>
                </div>
                <!-- 구분선: h-0 1px -->
                <div style="width:100%; height:1px; background:#d3d3d3;"></div>
                <!-- 접수 정보: gap-30 line-1.6 -->
                <div style="display:flex; flex-direction:column; gap:30px; line-height:1.6; width:100%;">
                    <p style="font-size:18px; font-weight:700; color:#363636; margin:0;">접수 정보</p>
                    <div style="display:flex; flex-direction:column; gap:24px; font-size:20px; font-weight:500; width:100%;">
                        <div style="display:flex; gap:10px; align-items:center; width:100%;"><span style="color:#7a7a7a; width:100px; flex-shrink:0;">접수 일시</span><span style="color:#1b1b1b;"><?= htmlspecialchars($report['created_at'] ?? '-') ?></span></div>
                        <div style="display:flex; gap:10px; align-items:center; width:100%;"><span style="color:#7a7a7a; width:100px; flex-shrink:0;">접수 번호</span><span style="color:#1b1b1b;"><?= htmlspecialchars($report['code'] ?? '-') ?></span></div>
                        <?php if ($isDone && !empty($report['answered_at'])): ?>
                        <div style="display:flex; gap:10px; align-items:center; width:100%;"><span style="color:#7a7a7a; width:100px; flex-shrink:0;">답변 일시</span><span style="color:#1b1b1b;"><?= htmlspecialchars($report['answered_at']) ?></span></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 답변 영역 -->
    <?php if ($isDone): ?>
    <!-- 처리완료: 읽기 전용 bg-white rounded-20 px-24 py-30 gap-24 -->
    <div style="background:#fff; border-radius:20px; padding:30px 24px; display:flex; flex-direction:column; gap:24px; width:100%;">
        <p style="font-size:18px; font-weight:500; color:#7a7a7a; line-height:1.6; margin:0; align-self:flex-end;">제보결과</p>
        <div style="display:flex; gap:24px; align-items:flex-start; width:100%;">
            <div style="width:160px; flex-shrink:0; padding:12px 0; display:flex; gap:4px; align-items:center;">
                <p style="font-size:24px; font-weight:700; color:#363636; line-height:1.5; margin:0;">답변 내용</p>
            </div>
            <div style="flex:1; min-width:0; padding:12px 0;">
                <p style="font-size:22px; font-weight:400; color:#000; line-height:1.6; margin:0;"><?= nl2br(htmlspecialchars($report['answer_content'] ?? '')) ?></p>
            </div>
        </div>
        <div style="display:flex; gap:24px; align-items:center; width:100%;">
            <div style="width:160px; flex-shrink:0; padding:12px 0;">
                <p style="font-size:24px; font-weight:700; color:#363636; line-height:1.5; margin:0;">작성자 명</p>
            </div>
            <p style="font-size:22px; font-weight:400; color:#000; line-height:1.6; margin:0;"><?= htmlspecialchars($report['answer_author'] ?? '-') ?></p>
        </div>
    </div>
    <?php else: ?>
    <!-- 미완료: 답변 입력 bg-white rounded-20 px-24 py-30 gap-24 items-end -->
    <form method="post" action="<?= $baseUrl ?>/report/answer/<?= $report['id'] ?? '' ?>" id="answerForm">
        <div style="background:#fff; border-radius:20px; padding:30px 24px; display:flex; flex-direction:column; gap:24px; align-items:flex-end;">
            <!-- "*는 필수 입력 항목입니다.": 18px Medium #7a7a7a -->
            <p style="font-size:18px; font-weight:500; color:#7a7a7a; line-height:1.6; margin:0;"><span style="color:red; line-height:1.6;">*</span><span style="line-height:1.6;">는 필수 입력 항목입니다.</span></p>
            <!-- 제보결과 작성: gap-24 h-302 -->
            <div style="display:flex; gap:24px; align-items:flex-start; width:100%; height:302px;">
                <!-- label: w-160 py-12 gap-4 -->
                <div style="width:160px; flex-shrink:0; display:flex; gap:4px; align-items:center; padding:12px 0;">
                    <p style="font-size:24px; font-weight:700; color:#363636; line-height:1.5; margin:0;">제보결과 작성</p>
                    <p style="font-size:20px; font-weight:400; color:rgba(255,0,0,0.8); line-height:32px; margin:0;">*</p>
                </div>
                <!-- textarea: flex-1 h-302 border-#d3d3d3 rounded-10 px-24 py-12 22px Regular placeholder #a7a7a7 -->
                <textarea name="answer_content" id="answerContent" placeholder="내용을 입력해 주세요." style="flex:1; height:302px; border:1px solid #d3d3d3; border-radius:10px; padding:12px 24px; font-size:22px; font-weight:400; color:#000; line-height:1.6; font-family:var(--font); resize:vertical; box-sizing:border-box; background:#fff;" required></textarea>
            </div>
            <!-- 작성자 명: gap-24 -->
            <div style="display:flex; gap:24px; align-items:flex-start; width:100%;">
                <div style="width:160px; flex-shrink:0; display:flex; gap:4px; align-items:center; padding:12px 0;">
                    <p style="font-size:24px; font-weight:700; color:#363636; line-height:1.5; margin:0;">작성자 명</p>
                    <p style="font-size:20px; font-weight:400; color:rgba(255,0,0,0.8); line-height:32px; margin:0;">*</p>
                </div>
                <!-- input: flex-1 border-#d3d3d3 rounded-10 px-24 py-12 22px Regular placeholder #a7a7a7 -->
                <input type="text" name="answer_author" id="answerAuthor" placeholder="작성자 명을 입력해 주세요." style="flex:1; border:1px solid #d3d3d3; border-radius:10px; padding:12px 24px; font-size:22px; font-weight:400; color:#000; line-height:1.6; font-family:var(--font); box-sizing:border-box; background:#fff;" required>
            </div>
            <!-- 등록 btn: bg-#d3d3d3 h-68 rounded-10 px-40 py-20 20px Bold #7a7a7a -->
            <button type="submit" id="reportSubmitBtn" style="display:flex; align-items:center; justify-content:center; padding:20px 40px; height:68px; background:#d3d3d3; border-radius:10px; border:none; font-size:20px; font-weight:700; color:#7a7a7a; cursor:pointer; font-family:var(--font); line-height:1.5;">등록</button>
        </div>
    </form>
    <?php endif; ?>
</div>

<!-- 목록 btn 영역: py-60 center -->
<div style="display:flex; flex-direction:column; align-items:center; justify-content:center; padding:60px 0; width:100%;">
    <!-- bg-white → border-#003592 rounded-10 w-346 h-80 px-40 py-15 24px Bold #003592 -->
    <a href="<?= $baseUrl ?>/report/list" style="display:flex; align-items:center; justify-content:center; width:346px; height:80px; border:1px solid #003592; border-radius:10px; padding:15px 40px; box-sizing:border-box; text-decoration:none; background:#fff;">
        <span style="font-size:24px; font-weight:700; color:#003592; line-height:1.5;">목록</span>
    </a>
</div>

<!-- ========== 삭제(파기) 팝업 모달 ========== -->
<div id="deleteModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:20px; width:732px; box-shadow:0 4px 40px rgba(0,0,0,0.15);">
        <div style="padding:40px 40px 0 40px;">
            <h2 style="font-size:28px; font-weight:700; color:#000; margin:0; line-height:1.5;">삭제 확인</h2>
        </div>
        <div style="padding:24px 40px 0 40px;">
            <div style="background:#f8f9fb; border-radius:10px; padding:12px;">
                <p style="font-size:18px; font-weight:400; color:#363636; line-height:1.6; margin:0;">삭제 시 개인정보를 포함한 모든 데이터가 즉시 파기되며 복구가 불가합니다.<br>관리자 명을 입력 후 삭제를 진행해 주세요. <span style="color:red;">*</span>는 필수 입력 항목입니다.</p>
            </div>
        </div>
        <form method="post" action="<?= $baseUrl ?>/report/delete/<?= $report['id'] ?? '' ?>" id="deleteForm">
            <div style="padding:24px 40px;">
                <div style="display:flex; gap:12px; align-items:center;">
                    <label style="font-size:20px; font-weight:700; color:#363636; white-space:nowrap;">파기자 명 <span style="color:red;">*</span></label>
                    <input type="text" name="destroyer_name" id="destroyerName" placeholder="파기자 명을 입력해 주세요." style="flex:1; height:52px; border:1px solid #d3d3d3; border-radius:10px; padding:0 20px; font-size:18px; font-weight:400; color:#000; font-family:var(--font); box-sizing:border-box;">
                </div>
                <div id="destroyerError" style="display:none; margin-top:8px; font-size:16px; color:#e53e3e;">파기자 명을 입력해 주세요.</div>
            </div>
            <div style="display:flex; gap:12px; justify-content:flex-end; padding:0 40px 40px 40px;">
                <button type="button" id="cancelDelete" style="display:flex; align-items:center; justify-content:center; width:120px; height:52px; background:#fff; border:1px solid rgba(34,34,34,0.2); border-radius:10px; font-size:18px; font-weight:600; color:#000; cursor:pointer; font-family:var(--font);">취소</button>
                <button type="submit" style="display:flex; align-items:center; justify-content:center; width:120px; height:52px; background:#e53e3e; border:none; border-radius:10px; font-size:18px; font-weight:600; color:#fff; cursor:pointer; font-family:var(--font);">삭제</button>
            </div>
        </form>
    </div>
</div>

<?php if (!$isDone): ?>
<!-- ========== 상태변경 팝업 모달 ========== -->
<div id="statusModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:20px; width:480px; box-shadow:0 4px 40px rgba(0,0,0,0.15);">
        <div style="padding:40px 40px 0 40px;">
            <h2 style="font-size:28px; font-weight:700; color:#000; margin:0; line-height:1.5;">상태 변경</h2>
        </div>
        <form method="post" id="statusChangeForm">
            <div style="padding:24px 40px;">
                <p style="font-size:18px; font-weight:400; color:#363636; line-height:1.6; margin:0 0 24px 0;">변경할 상태를 선택해 주세요.</p>
                <div style="display:flex; flex-direction:column; gap:12px;" id="statusOptions">
                    <label style="display:flex; gap:12px; align-items:center; padding:16px 20px; border:1px solid #d3d3d3; border-radius:10px; cursor:pointer;" data-value="received">
                        <input type="radio" name="new_status" value="received" <?= ($report['status'] ?? '') === 'received' ? 'checked' : '' ?> style="width:20px; height:20px;">
                        <img src="<?= $baseUrl ?>/public/img/icon_dot_report_received.svg" alt="" style="width:12px; height:12px;">
                        <span style="font-size:18px; font-weight:500; color:#363636;">제보접수</span>
                    </label>
                    <label style="display:flex; gap:12px; align-items:center; padding:16px 20px; border:1px solid #d3d3d3; border-radius:10px; cursor:pointer;" data-value="review">
                        <input type="radio" name="new_status" value="review" <?= ($report['status'] ?? '') === 'review' ? 'checked' : '' ?> style="width:20px; height:20px;">
                        <img src="<?= $baseUrl ?>/public/img/icon_dot_report_review.svg" alt="" style="width:12px; height:12px;">
                        <span style="font-size:18px; font-weight:500; color:#363636;">검토 중</span>
                    </label>
                    <label style="display:flex; gap:12px; align-items:center; padding:16px 20px; border:1px solid #d3d3d3; border-radius:10px; cursor:pointer;" data-value="done">
                        <input type="radio" name="new_status" value="done" style="width:20px; height:20px;">
                        <img src="<?= $baseUrl ?>/public/img/icon_dot_report_done.svg" alt="" style="width:12px; height:12px;">
                        <span style="font-size:18px; font-weight:500; color:#363636;">처리완료</span>
                    </label>
                </div>
            </div>
            <div style="display:flex; gap:12px; justify-content:flex-end; padding:0 40px 40px 40px;">
                <button type="button" id="cancelStatusModal" style="display:flex; align-items:center; justify-content:center; width:120px; height:52px; background:#fff; border:1px solid rgba(34,34,34,0.2); border-radius:10px; font-size:18px; font-weight:600; color:#000; cursor:pointer; font-family:var(--font);">취소</button>
                <button type="submit" style="display:flex; align-items:center; justify-content:center; width:120px; height:52px; background:#003592; border:none; border-radius:10px; font-size:18px; font-weight:600; color:#fff; cursor:pointer; font-family:var(--font);">변경</button>
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
                var btn = document.getElementById('reportSubmitBtn');
                if (contentEl.value.trim() && authorEl.value.trim()) { btn.style.background = '#003592'; btn.style.color = '#fff'; }
                else { btn.style.background = '#d3d3d3'; btn.style.color = '#7a7a7a'; }
            });
        });
    }

    // 삭제 모달
    var deleteModal = document.getElementById('deleteModal');
    document.getElementById('openDeleteModal').addEventListener('click', function() { deleteModal.style.display = 'flex'; });
    document.getElementById('cancelDelete').addEventListener('click', function() { deleteModal.style.display = 'none'; });
    deleteModal.addEventListener('click', function(e) { if (e.target === deleteModal) deleteModal.style.display = 'none'; });
    document.getElementById('deleteForm').addEventListener('submit', function(e) {
        var name = document.getElementById('destroyerName').value.trim();
        if (!name) { e.preventDefault(); document.getElementById('destroyerError').style.display = ''; document.getElementById('destroyerName').style.borderColor = '#e53e3e'; document.getElementById('destroyerName').focus(); return; }
        document.getElementById('destroyerError').style.display = 'none';
    });

    // 상태변경 모달
    var statusModal = document.getElementById('statusModal');
    var statusBtn = document.getElementById('openStatusModal');
    if (statusModal && statusBtn) {
        statusBtn.addEventListener('click', function() {
            var current = '<?= $report['status'] ?? 'received' ?>';
            document.querySelectorAll('#statusOptions label').forEach(function(label) {
                label.style.borderColor = (label.dataset.value === current) ? '#003592' : '#d3d3d3';
                label.style.background = (label.dataset.value === current) ? '#f0f4ff' : '#fff';
            });
            statusModal.style.display = 'flex';
        });
        document.getElementById('cancelStatusModal').addEventListener('click', function() { statusModal.style.display = 'none'; });
        statusModal.addEventListener('click', function(e) { if (e.target === statusModal) statusModal.style.display = 'none'; });
        document.querySelectorAll('#statusOptions input[name="new_status"]').forEach(function(radio) {
            radio.addEventListener('change', function() {
                document.querySelectorAll('#statusOptions label').forEach(function(l) { l.style.borderColor = '#d3d3d3'; l.style.background = '#fff'; });
                this.closest('label').style.borderColor = '#003592'; this.closest('label').style.background = '#f0f4ff';
            });
        });
        document.getElementById('statusChangeForm').addEventListener('submit', function(e) {
            e.preventDefault();
            var selected = this.querySelector('input[name="new_status"]:checked');
            if (!selected) return;
            // TODO: AJAX POST /report/changeStatus/<?= $report['id'] ?? '' ?>
            alert('상태가 변경되었습니다.');
            statusModal.style.display = 'none'; location.reload();
        });
    }
})();
</script>
