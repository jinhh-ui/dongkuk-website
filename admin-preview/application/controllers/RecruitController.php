<?php
class RecruitController extends Controller
{
    function __construct() { parent::__construct(); $this->currentMenu = 'recruit'; }

    public function list()
    {
        require 'application/config/auth.php';
        $page    = max(1, intval($this->getParam('page', 1)));
        $keyword = $this->getParam('keyword', '');
        $status  = $this->getParam('status', '');

        // TODO: DB 연동 시 recruit 테이블에서 리스트 조회
        $allList = [
            ['id'=>12,'title'=>'동국산업㈜ 2026년 하반기 채용 공고(생산기술)','job_group'=>'생산',
                'recruit_info'=>'공채 · 신입 · 경기 시흥','status'=>'waiting',
                'start_date'=>'2026-06-01','end_date'=>'2026-06-30','d_day'=>'D-14',
                'external_url'=>'https://www.saramin.co.kr'],
            ['id'=>11,'title'=>'동국산업㈜ 2026년 상반기 채용 공고(경인영업그룹)','job_group'=>'영업',
                'recruit_info'=>'공채 · 신입 · 경기 시흥','status'=>'waiting',
                'start_date'=>'2026-05-11','end_date'=>'2026-06-05','d_day'=>'D-30',
                'external_url'=>'https://www.saramin.co.kr'],
            ['id'=>10,'title'=>'동국산업㈜ 2026년 상반기 채용 공고(품질관리)','job_group'=>'품질',
                'recruit_info'=>'수시 · 경력 · 부산','status'=>'active',
                'start_date'=>'2026-05-01','end_date'=>'2026-05-26','d_day'=>'D-20',
                'external_url'=>'https://www.saramin.co.kr'],
            ['id'=>9,'title'=>'동국산업㈜ 2026년 R&D 연구원 채용','job_group'=>'연구',
                'recruit_info'=>'수시 · 경력 · 서울','status'=>'active',
                'start_date'=>'2026-04-15','end_date'=>'2026-05-15','d_day'=>'D-10',
                'external_url'=>'https://www.saramin.co.kr'],
            ['id'=>8,'title'=>'동국산업㈜ 경영지원 채용 공고','job_group'=>'경영',
                'recruit_info'=>'공채 · 신입 · 서울','status'=>'closed',
                'start_date'=>'2026-04-01','end_date'=>'2026-04-20','d_day'=>'마감',
                'external_url'=>'https://www.saramin.co.kr'],
            ['id'=>7,'title'=>'동국산업㈜ IT인프라 엔지니어 채용','job_group'=>'IT',
                'recruit_info'=>'수시 · 경력 · 경기 시흥','status'=>'closed',
                'start_date'=>'2026-03-20','end_date'=>'2026-04-10','d_day'=>'마감',
                'external_url'=>'https://www.saramin.co.kr'],
            ['id'=>6,'title'=>'동국산업㈜ 마케팅 담당자 채용','job_group'=>'마케팅',
                'recruit_info'=>'공채 · 신입 · 서울','status'=>'closed',
                'start_date'=>'2026-03-11','end_date'=>'2026-04-05','d_day'=>'마감',
                'external_url'=>'https://www.saramin.co.kr'],
            ['id'=>5,'title'=>'동국산업㈜ 물류관리 채용 공고','job_group'=>'물류',
                'recruit_info'=>'수시 · 경력 · 부산','status'=>'closed',
                'start_date'=>'2026-03-01','end_date'=>'2026-03-26','d_day'=>'마감',
                'external_url'=>'https://www.saramin.co.kr'],
            ['id'=>4,'title'=>'동국산업㈜ 2026년 상하반기 채용 공고(경인영업그룹)','job_group'=>'영업',
                'recruit_info'=>'공채 · 신입 · 경기 시흥','status'=>'closed',
                'start_date'=>'2026-02-15','end_date'=>'2026-03-15','d_day'=>'마감',
                'external_url'=>'https://www.saramin.co.kr'],
            ['id'=>3,'title'=>'동국산업㈜ 안전관리 담당자 채용','job_group'=>'안전',
                'recruit_info'=>'수시 · 경력 · 경기 시흥','status'=>'closed',
                'start_date'=>'2026-02-01','end_date'=>'2026-02-26','d_day'=>'마감',
                'external_url'=>'https://www.saramin.co.kr'],
            ['id'=>2,'title'=>'동국산업㈜ 재무회계 채용 공고','job_group'=>'재무',
                'recruit_info'=>'공채 · 경력 · 서울','status'=>'closed',
                'start_date'=>'2026-01-15','end_date'=>'2026-02-10','d_day'=>'마감',
                'external_url'=>'https://www.saramin.co.kr'],
            ['id'=>1,'title'=>'동국산업㈜ 인사관리 채용 공고','job_group'=>'인사',
                'recruit_info'=>'공채 · 신입 · 서울','status'=>'closed',
                'start_date'=>'2026-01-01','end_date'=>'2026-01-31','d_day'=>'마감',
                'external_url'=>'https://www.saramin.co.kr'],
        ];
        if ($status) {
            $allList = array_values(array_filter($allList, fn($r) => $r['status'] === $status));
        }
        if ($keyword) {
            $allList = array_values(array_filter($allList, fn($r) => stripos($r['title'], $keyword) !== false));
        }
        $totalCount = count($allList);
        $list = array_slice($allList, ($page - 1) * 10, 10);
        $counts = ['total' => 12, 'waiting' => 2, 'active' => 2, 'closed' => 8];

        $this->render('recruit/list', [
            'list' => $list, 'totalCount' => $totalCount,
            'totalPages' => max(1, ceil($totalCount / 10)),
            'page' => $page, 'keyword' => $keyword, 'status' => $status,
            'counts' => $counts,
        ]);
    }

    public function create() { $this->post(); }

    public function post()
    {
        require 'application/config/auth.php';
        $this->render('recruit/form', ['recruit' => null]);
    }

    public function edit($num = '')
    {
        require 'application/config/auth.php';
        $recruit = [
            'id' => $num,
            'title' => '동국산업㈜ 2026년 상하반기 채용 공고(경인영업그룹)',
            'job_group' => '영업',
            'recruit_info' => '공채 · 신입 · 경기 시흥',
            'status' => 'active',
            'start_date' => '2026-03-01',
            'end_date' => '2026-03-26',
            'external_url' => 'https://www.saramin.co.kr',
        ];
        $this->render('recruit/form', ['recruit' => $recruit]);
    }

    public function detail($num = '')
    {
        require 'application/config/auth.php';
        $recruit = [
            'id' => $num,
            'title' => '동국산업㈜ 2026년 상하반기 채용 공고(경인영업그룹)',
            'job_group' => '영업',
            'recruit_info' => '공채 · 신입 · 경기 시흥',
            'status' => 'active',
            'start_date' => '2026-03-01',
            'end_date' => '2026-03-26',
            'external_url' => 'https://www.saramin.co.kr',
        ];
        $this->render('recruit/form', ['recruit' => $recruit, 'isDetail' => true]);
    }
}
