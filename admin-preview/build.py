#!/usr/bin/env python3
"""
admin-preview 빌드: admin/ 원본 구조 그대로 + PHP → static HTML
baseUrl depth 자동 계산, 모든 라우트 자동 생성
"""
import subprocess, os, json, re

BASE = os.path.dirname(os.path.abspath(__file__))
VIEWS = os.path.join(BASE, 'application', 'views')

# ═══════════════════════════════════════════
# 더미 데이터
# ═══════════════════════════════════════════
D_NEWS_LIST = [
    {'id':str(i+1),'title':t,'thumbnail':f'public/img/news_thumb_{i+1:02d}.png','created_at':d,'updated_at':d}
    for i,(t,d) in enumerate([
        ('동국산업, 2025년 상반기 매출 역대 최고 달성','2025.06.15'),
        ('글로벌 자동차 OEM 3사와 신규 공급 계약 체결','2025.06.10'),
        ('Q/T 열처리강판 신제품 라인업 확대 발표','2025.05.28'),
        ('친환경 수소 열처리 설비 가동 1주년 기념 행사','2025.05.15'),
        ('산업통상자원부 장관 표창 수상 — 소재 혁신 부문','2025.04.30'),
        ('2025 국제 철강 전시회 참가 및 DiKel 브랜드 소개','2025.04.15'),
        ('동국산업, ESG 경영 우수기업 선정','2025.03.28'),
        ('부산 본사 제2공장 증설 착공식 개최','2025.03.15'),
        ('냉연강판 생산량 월간 최고 기록 경신','2025.02.28'),
        ('2025년 신년사 — 도전과 혁신으로 미래를 열다','2025.01.02'),
    ])
]
D_NEWS_ITEM = {'id':'1','title':'동국산업, 2025년 상반기 매출 역대 최고 달성','content':'<p>동국산업이 2025년 상반기 매출에서 역대 최고치를 기록했습니다.</p><p>특히 니켈도금강판과 Q/T 열처리강판 부문에서 전년 동기 대비 23% 성장을 달성하며 시장 점유율을 확대했습니다.</p>','source_name':'한국경제','source_url':'https://example.com','created_at':'2025.06.15','updated_at':'2025.06.15','thumbnail':'public/img/news_thumb_01.png','files':[]}

D_VIDEO_LIST = [
    {'id':str(i+1),'title':t,'thumbnail':'public/img/thumbnail_video.png','youtube_id':y,'is_main':str(int(i==0)),'created_at':d,'updated_at':d}
    for i,(t,y,d) in enumerate([
        ('동국산업 홍보 영상 2025','dQw4w9WgXcQ','2025.06.01'),
        ('DiKel 니켈도금강판 제품 소개','abc123','2025.05.15'),
        ('Q/T 열처리강판 공정과정','def456','2025.04.20'),
        ('2024년 동국산업 연간 하이라이트','ghi789','2025.03.10'),
        ('부산 본사 공장 투어 영상','jkl012','2025.02.20'),
    ])
]

D_NOTICE_LIST = [
    {'id':str(i+1),'title':t,'category':c,'created_at':d,'updated_at':d}
    for i,(t,c,d) in enumerate([
        ('2025년 제2회 임시주주총회 소집 공고','주주총회','2025.06.10'),
        ('사외이사 후보 추천 공고','이사회','2025.05.25'),
        ('2025년 정기주주총회 결과 공고','주주총회','2025.03.28'),
        ('배당 결정 공고','배당','2025.03.20'),
        ('2024년 사업보고서 공시','공시','2025.03.15'),
        ('감사보고서 제출 공고','감사','2025.03.10'),
        ('2025년 정기주주총회 소집 공고','주주총회','2025.02.28'),
        ('이사회 결의 사항 공고','이사회','2025.02.15'),
        ('주요사항 보고서 공시','공시','2025.01.31'),
        ('2024년 4분기 실적 공시','공시','2025.01.15'),
    ])
]
D_NOTICE_ITEM = {'id':'1','title':'2025년 제2회 임시주주총회 소집 공고','content':'<p>주주 여러분께 알려드립니다.</p><p>2025년 제2회 임시주주총회를 아래와 같이 소집합니다.</p>','category':'주주총회','category_name':'주주총회','published_at':'2025.06.10 09:00','publish_date':'2025-06-10','publish_time':'09:00','created_at':'2025.06.10','updated_at':'2025.06.10','file_name':'임시주주총회_안건.pdf','file_url':'#','files':[{'name':'임시주주총회_안건.pdf','url':'#','size':'2.4MB'}]}

D_IR_LIST = [
    {'id':str(i+1),'title':t,'category':c,'created_at':d,'updated_at':d}
    for i,(t,c,d) in enumerate([
        ('2025년 1분기 실적 발표 자료','실적발표','2025.05.15'),
        ('2024년 연간 사업보고서','사업보고서','2025.03.31'),
        ('2024년 4분기 실적 발표 자료','실적발표','2025.02.14'),
        ('2024년 3분기 실적 발표 자료','실적발표','2024.11.15'),
        ('2024년 반기 사업보고서','사업보고서','2024.08.14'),
        ('2024년 2분기 실적 발표 자료','실적발표','2024.08.10'),
        ('2024년 1분기 실적 발표 자료','실적발표','2024.05.15'),
        ('2023년 연간 사업보고서','사업보고서','2024.03.31'),
        ('2023년 4분기 실적 발표 자료','실적발표','2024.02.14'),
        ('2023년 3분기 실적 발표 자료','실적발표','2023.11.15'),
    ])
]
D_IR_ITEM = {'id':'1','title':'2025년 1분기 실적 발표 자료','content':'<p>2025년 1분기 실적 발표 자료입니다.</p>','category':'실적발표','category_name':'실적발표','published_at':'2025.05.15 10:00','publish_date':'2025-05-15','publish_time':'10:00','created_at':'2025.05.15','updated_at':'2025.05.15','file_name':'1Q2025_실적발표.pdf','file_url':'#','files':[{'name':'1Q2025_실적발표.pdf','url':'#','size':'5.1MB'}]}

D_RECRUIT_LIST = [
    {'id':str(i+1),'title':t,'job_group':g,'recruit_info':ri,'status':s,'d_day':dd,'start_date':sd,'end_date':ed,'external_url':eu}
    for i,(t,g,ri,s,dd,sd,ed,eu) in enumerate([
        ('2025년 상반기 생산기술직 신입사원 모집','생산기술','정규직 · 부산','active','D-14','2025.06.01','2025.06.30','https://saramin.co.kr'),
        ('품질관리 경력사원 채용','품질관리','정규직 · 부산','active','D-7','2025.06.10','2025.06.25',''),
        ('연구개발 석·박사급 연구원 채용','R&D','정규직 · 부산','active','D-21','2025.06.01','2025.07.10','https://saramin.co.kr'),
        ('경영지원 사무직 채용','경영지원','정규직 · 서울','active','D-10','2025.06.05','2025.06.28',''),
        ('환경안전 담당자 모집','환경안전','정규직 · 부산','active','D-5','2025.06.10','2025.06.23',''),
        ('IT 시스템 운영 담당자 모집','IT','정규직 · 서울','closed','마감','2025.05.01','2025.05.31',''),
        ('해외영업 경력사원 채용','영업','정규직 · 서울','closed','마감','2025.04.15','2025.05.15',''),
        ('설비보전 기술직 채용','설비보전','정규직 · 부산','closed','마감','2025.04.01','2025.04.30',''),
        ('구매/자재관리 경력사원','구매','정규직 · 부산','closed','마감','2025.03.15','2025.04.15',''),
        ('2024년 하반기 인턴십 프로그램','전체','인턴 · 부산/서울','closed','마감','2024.11.01','2024.11.30','https://saramin.co.kr'),
    ])
]

D_REPORT_LIST = [
    {'id':str(i+1),'title':t,'code':c,'status':s,'created_at':ca,'answered_at':aa}
    for i,(t,c,s,ca,aa) in enumerate([
        ('내부 비리 관련 제보합니다','RPT-2025-001','received','2025.06.14','-'),
        ('협력업체 부당 요구 건','RPT-2025-002','review','2025.06.10','-'),
        ('안전 규정 위반 사항 보고','RPT-2025-003','done','2025.05.20','2025.06.01'),
        ('원자재 품질 이상 발견 건','RPT-2025-004','done','2025.05.15','2025.05.28'),
        ('하청업체 근로기준법 위반 의심','RPT-2025-005','received','2025.05.10','-'),
        ('공정 데이터 조작 의혹','RPT-2025-006','review','2025.05.05','-'),
        ('납품 단가 부당 인하 요구','RPT-2025-007','done','2025.04.28','2025.05.10'),
        ('작업장 안전장비 미비 건','RPT-2025-008','received','2025.04.20','-'),
        ('환경오염물질 무단 배출 의심','RPT-2025-009','review','2025.04.15','-'),
        ('회계 부정 관련 제보','RPT-2025-010','done','2025.04.10','2025.04.25'),
    ])
]
D_REPORT_ITEM = {'id':'1','title':'내부 비리 관련 제보합니다','content':'제보 내용이 여기에 표시됩니다. 내부 비리 관련 사실을 발견하여 제보합니다.','code':'RPT-2025-001','status':'received','report_type':'anonymous','reporter_name':'익명','reporter_contact':'','created_at':'2025.06.14','answered_at':'-','answer_content':'','answer_author':'','files':[]}

D_INQUIRY_LIST = [
    {'id':str(i+1),'title':t,'subject':t,'category_name':c,'status':s,'created_at':ca,'answered_at':aa}
    for i,(t,c,s,ca,aa) in enumerate([
        ('DiKel 니켈도금강판 샘플 요청','제품문의','waiting','2025.06.15','-'),
        ('Q/T 열처리강판 납기 문의','납기문의','waiting','2025.06.12','-'),
        ('냉연강판 규격 관련 문의드립니다','제품문의','done','2025.06.05','2025.06.08'),
        ('대리점 계약 관련 상담 요청','기타','done','2025.05.28','2025.06.02'),
        ('해외 수출 견적 요청','견적문의','done','2025.05.20','2025.05.25'),
        ('열처리강판 두께별 가격 문의','견적문의','waiting','2025.05.18','-'),
        ('배터리 케이스용 소재 상담','제품문의','done','2025.05.10','2025.05.15'),
        ('공장 견학 일정 문의','기타','waiting','2025.05.05','-'),
        ('도금 두께 커스터마이징 가능 여부','제품문의','done','2025.04.28','2025.05.03'),
        ('MOQ 및 리드타임 확인 요청','납기문의','done','2025.04.20','2025.04.25'),
    ])
]
D_INQUIRY_ITEM = {'id':'1','title':'DiKel 니켈도금강판 샘플 요청','subject':'DiKel 니켈도금강판 샘플 요청','content':'<p>DiKel 니켈도금강판 샘플을 요청드립니다. 현재 자동차 배터리 케이스용으로 검토 중입니다.</p>','category_name':'제품문의','company':'(주)샘플전자','name':'김담당','email':'sample@example.com','phone':'010-1234-5678','status':'waiting','created_at':'2025.06.15','answered_at':'-','answer_content':'','files':[]}

D_DELETELOG_LIST = [
    {'id':str(i+1),'destroyed_at':d,'menu_name':m,'count':str(c),'type':tp,'admin_name':a}
    for i,(d,m,c,tp,a) in enumerate([
        ('2025.06.15','제보하기',3,'auto','SYSTEM'),
        ('2025.06.10','문의하기',5,'auto','SYSTEM'),
        ('2025.06.08','뉴스 기사',1,'manual','관리자'),
        ('2025.05.30','제보하기',7,'auto','SYSTEM'),
        ('2025.05.15','IR자료실',2,'manual','관리자'),
        ('2025.05.01','문의하기',12,'auto','SYSTEM'),
        ('2025.04.15','채용공고',3,'manual','관리자'),
        ('2025.04.01','제보하기',8,'auto','SYSTEM'),
        ('2025.03.15','공고',4,'manual','관리자'),
        ('2025.03.01','문의하기',15,'auto','SYSTEM'),
    ])
]

DUMMY = {
    'news':           {'list': D_NEWS_LIST},
    'news_detail':    {'item': D_NEWS_ITEM},
    'video':          {'list': D_VIDEO_LIST, 'video': {'id':'1','title':'철의 역사를 만들어가는 동국산업','desc':'동국산업의 최신 홍보영상입니다. 글로벌 철강 소재 기업으로서 혁신과 도전의 역사를 소개합니다.','url':'https://www.youtube.com/watch?v=dQw4w9WgXcQ','thumbnail':'public/img/thumbnail_video.png','tags':'#동국산업 #홍보영상 #냉연강판','updated_at':'2025.06.05'}},
    'notice':         {'list': D_NOTICE_LIST},
    'notice_detail':  {'item': D_NOTICE_ITEM},
    'ir':             {'list': D_IR_LIST},
    'ir_detail':      {'item': D_IR_ITEM},
    'recruit':        {'tabs':{'':{'label':'전체','count':'18'},'active':{'label':'게시 중','count':'5'},'closed':{'label':'마감','count':'13'}},'list':D_RECRUIT_LIST},
    'report':         {'tabs':{'':{'label':'전체','count':'30'},'received':{'label':'제보접수','count':'8'},'review':{'label':'검토 중','count':'7'},'done':{'label':'처리완료','count':'15'}},'list':D_REPORT_LIST},
    'report_detail':  {'item': D_REPORT_ITEM},
    'inquiry':        {'tabs':{'':{'label':'전체','count':'35'},'waiting':{'label':'답변대기','count':'12'},'done':{'label':'답변완료','count':'23'}},'list':D_INQUIRY_LIST},
    'inquiry_detail': {'item': D_INQUIRY_ITEM},
    'deletelog':      {'list': D_DELETELOG_LIST},
}

# ═══════════════════════════════════════════
# 모든 라우트 (URL경로, 뷰파일, 제목, 활성메뉴, 데이터키)
# baseUrl은 depth에서 자동 계산
# ═══════════════════════════════════════════
PAGES = [
    # 뉴스
    ('news/list',       'news/list.php',     '뉴스 기사 관리', 'news-article', 'news'),
    ('news/detail',     'news/detail.php',   '뉴스 기사 상세', 'news-article', 'news_detail'),
    ('news/create',     'news/form.php',     '뉴스 기사 등록', 'news-article', 'news_detail'),
    ('news/edit',       'news/form.php',     '뉴스 기사 수정', 'news-article', 'news_detail'),
    # 영상
    ('video/list',      'video/list.php',    '대표 영상 관리', 'news-video',   'video'),
    ('video/create',    'video/form.php',    '대표 영상 등록', 'news-video',   'video'),
    ('video/edit',      'video/form.php',    '대표 영상 수정', 'news-video',   'video'),
    # 공고
    ('notice/list',     'notice/list.php',   '공고 관리',     'notice',       'notice'),
    ('notice/detail',   'notice/detail.php', '공고 상세',     'notice',       'notice_detail'),
    ('notice/create',   'notice/form.php',   '공고 등록',     'notice',       'notice_detail'),
    ('notice/edit',     'notice/form.php',   '공고 수정',     'notice',       'notice_detail'),
    # IR
    ('ir/list',         'ir/list.php',       'IR자료실 관리', 'ir',           'ir'),
    ('ir/detail',       'ir/detail.php',     'IR자료 상세',   'ir',           'ir_detail'),
    ('ir/create',       'ir/form.php',       'IR자료 등록',   'ir',           'ir_detail'),
    ('ir/edit',         'ir/form.php',       'IR자료 수정',   'ir',           'ir_detail'),
    # 채용
    ('recruit/list',    'recruit/list.php',  '채용공고 관리', 'recruit',      'recruit'),
    ('recruit/create',  'recruit/form.php',  '채용공고 등록', 'recruit',      'recruit'),
    ('recruit/edit',    'recruit/form.php',  '채용공고 수정', 'recruit',      'recruit'),
    # 제보
    ('report/list',     'report/list.php',   '제보하기 관리', 'report',       'report'),
    ('report/detail',   'report/detail.php', '제보 상세',     'report',       'report_detail'),
    # 문의
    ('inquiry/list',    'inquiry/list.php',  '문의하기 관리', 'inquiry',      'inquiry'),
    ('inquiry/detail',  'inquiry/detail.php','문의 상세',     'inquiry',      'inquiry_detail'),
    # 파기이력
    ('deletelog/list',  'deletelog/list.php','파기 이력',     'deletelog',    'deletelog'),
]

DEFAULT_ITEM = "['id'=>'1','title'=>'샘플','content'=>'<p>내용</p>','created_at'=>'2025.06.15','updated_at'=>'2025.06.15','category'=>'','source_name'=>'','source_url'=>'','thumbnail'=>'','files'=>[],'code'=>'','status'=>'','reporter_name'=>'','reporter_contact'=>'','answer_content'=>'','answered_at'=>'-','company'=>'','name'=>'','email'=>'','phone'=>'','subject'=>'','category_name'=>'','youtube_id'=>'','is_main'=>'0']"

ROUTE_SEGMENTS = {'news','video','notice','ir','recruit','report','inquiry','deletelog','login','logout'}


def get_base_url(url_path):
    """URL 경로 depth에서 baseUrl 자동 계산"""
    depth = len(url_path.split('/'))
    return '/'.join(['..'] * depth)


def fix_links(html):
    """
    모든 내부 링크를 정적 HTML용으로 변환:
    - /news/detail/3 → /news/detail/index.html?id=3
    - /news/edit/5   → /news/edit/index.html?id=5
    - /news/list     → /news/list/index.html
    """
    def replacer(m):
        attr, path, q = m.group(1), m.group(2), m.group(3)
        if not path or path.startswith(('http','javascript:','#','mailto:')):
            return m.group(0)
        if '/public/' in path or path.endswith(('.css','.js','.svg','.png','.jpg','.pdf','.webp')):
            return m.group(0)
        qs = ''
        if '?' in path:
            path, qs = path.split('?', 1)
            qs = '?' + qs
        if path.endswith('.html'):
            return m.group(0)
        
        segs = [s for s in path.split('/') if s and s != '..']
        if not segs or segs[0] not in ROUTE_SEGMENTS:
            return m.group(0)
        
        # /controller/action/숫자 패턴 → /controller/action/index.html?id=숫자
        # 예: ../../news/detail/3 → ../../news/detail/index.html?id=3
        dots = path[:path.index(segs[0])] if segs[0] in path else ''
        if len(segs) >= 3 and segs[-1].isdigit():
            num = segs[-1]
            base_path = '/'.join(segs[:-1])
            id_qs = f'id={num}'
            if qs:
                id_qs = id_qs + '&' + qs.lstrip('?')
            return f'{attr}{dots}{base_path}/index.html?{id_qs}{q}'
        
        # 일반 내부 링크 → /index.html 추가
        path = path.rstrip('/') + '/index.html'
        return f'{attr}{path}{qs}{q}'
    return re.sub(r'((?:href|action)=")([^"]*?)(")', replacer, html)


def render(url_path, view_file, title, menu, data_key):
    base_url = get_base_url(url_path)
    dummy = DUMMY.get(data_key, {})
    dummy_json = json.dumps(dummy, ensure_ascii=False).replace("'", "\\'")

    header = os.path.join(VIEWS, '_layout', 'header.php')
    sidebar = os.path.join(VIEWS, '_layout', 'sidebar.php')
    footer = os.path.join(VIEWS, '_layout', 'footer.php')
    view = os.path.join(VIEWS, view_file)

    tmp = os.path.join(BASE, '_tmp.php')
    with open(tmp, 'w', encoding='utf-8') as f:
        f.write(f'''<?php
$baseUrl = "{base_url}";
$pageTitle = "{title}";
$currentMenu = "{menu}";
$_d = json_decode('{dummy_json}', true);
// thumbnail 상대경로에 baseUrl prefix
if (isset($_d['list'])) {{
    foreach ($_d['list'] as &$_r) {{
        if (!empty($_r['thumbnail']) && strpos($_r['thumbnail'], 'http') !== 0)
            $_r['thumbnail'] = $baseUrl . '/' . $_r['thumbnail'];
    }}
    unset($_r);
}}
if (isset($_d['item']['thumbnail']) && !empty($_d['item']['thumbnail']) && strpos($_d['item']['thumbnail'], 'http') !== 0)
    $_d['item']['thumbnail'] = $baseUrl . '/' . $_d['item']['thumbnail'];
$list = $_d['list'] ?? [];
$item = $_d['item'] ?? {DEFAULT_ITEM};
$row = $item;

// 각 뷰에서 사용하는 변수명에 맞게 데이터 할당
$notice = $item;
$ir = $item;
$recruit = $item;
$report = $item;
$inquiry = $item;

// video 페이지용
if (isset($_d['video'])) {{
    $video = $_d['video'];
    if (!empty($video['thumbnail']) && strpos($video['thumbnail'], 'http') !== 0)
        $video['thumbnail'] = $baseUrl . '/' . $video['thumbnail'];
}} else {{
    $video = null;
}}
$tabs = $_d['tabs'] ?? ['' => ['label'=>'전체','count'=>strval(count($list))]];
$currentStatus = '';
$keyword = '';
$page = 1;
$totalPages = 10;
include '{header}';
include '{sidebar}';
include '{view}';
include '{footer}';
''')

    r = subprocess.run(['php', tmp], capture_output=True, text=True, cwd=BASE)
    os.remove(tmp)
    if r.returncode != 0:
        print(f'ERR: {r.stderr[:200]}')
        return None
    return fix_links(r.stdout)


def render_login():
    base_url = '..'
    view = os.path.join(VIEWS, 'login/index.php')
    tmp = os.path.join(BASE, '_tmp.php')
    with open(tmp, 'w', encoding='utf-8') as f:
        f.write(f'''<?php $baseUrl = "{base_url}"; ?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>로그인</title>
    <link rel="stylesheet" href="{base_url}/public/css/admin.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/orioncactus/pretendard@v1.3.9/dist/web/variable/pretendardvariable-dynamic-subset.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
</head>
<body>
<?php include '{view}'; ?>
</body>
</html>''')
    r = subprocess.run(['php', tmp], capture_output=True, text=True, cwd=BASE)
    os.remove(tmp)
    return fix_links(r.stdout) if r.returncode == 0 else None


# ═══════════════════════════════════════════
# 빌드
# ═══════════════════════════════════════════
print('admin-preview 빌드 중...\n')

ok = 0
for url_path, view_file, title, menu, data_key in PAGES:
    out_dir = os.path.join(BASE, url_path)
    os.makedirs(out_dir, exist_ok=True)
    print(f'  /{url_path}/ ...', end=' ', flush=True)
    html = render(url_path, view_file, title, menu, data_key)
    if html:
        with open(os.path.join(out_dir, 'index.html'), 'w', encoding='utf-8') as f:
            f.write(html)
        print('✅')
        ok += 1
    else:
        print('❌')

# 로그인
os.makedirs(os.path.join(BASE, 'login'), exist_ok=True)
print('  /login/ ...', end=' ', flush=True)
html = render_login()
if html:
    with open(os.path.join(BASE, 'login/index.html'), 'w', encoding='utf-8') as f:
        f.write(html)
    print('✅'); ok += 1
else:
    print('❌')

# 로그아웃 redirect
os.makedirs(os.path.join(BASE, 'logout'), exist_ok=True)
with open(os.path.join(BASE, 'logout/index.html'), 'w') as f:
    f.write('<!DOCTYPE html><html><head><meta http-equiv="refresh" content="0;url=../login/index.html"></head></html>')
print('  /logout/ ✅ (→ login)')

# 루트 redirect
with open(os.path.join(BASE, 'index.html'), 'w') as f:
    f.write('<!DOCTYPE html><html><head><meta http-equiv="refresh" content="0;url=news/list/index.html"></head></html>')
print('  / ✅ (→ news/list)')

print(f'\n🎉 {ok+2}개 페이지 생성 완료!')
