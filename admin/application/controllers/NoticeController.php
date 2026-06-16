<?php

class NoticeController extends Controller
{
    private $model;
    private $boardId = '557fd98101d4c';

    function __construct()
    {
        parent::__construct();
        $this->currentMenu = 'notice';
        $this->model = $this->loadModel('BoardModel');
    }

    public function list()
    {
        require 'application/config/auth.php';
        $page     = max(1, intval($this->getParam('page', 1)));
        $search   = $this->getParam('text', '');
        $category = $this->getParam('category', '');

        if (DEV_MODE) {
            // TODO: DB 연동 시 notice 테이블에서 리스트 조회
            $catMap = ['settlement'=>'결산공고','shareholder'=>'주주총회','etc'=>'기타'];
            $allList = [];
            $titles = [
                '제 55기 결산공시(연결 · 별도)','제 55기 사업보고서 (2025.12)',
                '제 55기 주주총회소집공고','제 54기 주주총회소집공고',
                '제 54기 주주총회결과공고','제 53기 주주총회소집공고',
                '제 53기 주주총회결과공고','제 52기 주주총회결과공고',
                '제 52기 주주총회소집공고','제 51기 주주총회소집공고',
                '제 51기 결산공시','제 50기 사업보고서',
            ];
            $catKeys = array_keys($catMap);
            foreach ($titles as $i => $t) {
                $allList[] = [
                    'id'=> 60 - $i, 'title'=> $t,
                    'category_key'  => $catKeys[$i % 3],
                    'category_name' => $catMap[$catKeys[$i % 3]],
                    'published_at'=>'2026-03-26 15:09:00',
                    'file_name'=> ($i % 3 === 0) ? '첨부파일.pdf' : null,
                ];
            }
            // 카테고리 필터
            if ($category && isset($catMap[$category])) {
                $allList = array_values(array_filter($allList, fn($r) => $r['category_key'] === $category));
            }
            // 검색 필터
            if ($search) {
                $allList = array_values(array_filter($allList, fn($r) => stripos($r['title'], $search) !== false));
            }
            $totalCount = count($allList);
            $list = array_slice($allList, ($page - 1) * 10, 10);
            $counts = ['total'=>48, 'settlement'=>20, 'shareholder'=>18, 'etc'=>10];
        } else {
            $list = $this->model->getList($this->boardId, $page, 10, $search);
            $totalCount = $this->model->getCount($this->boardId, $search);
            $counts = ['total'=>$totalCount, 'settlement'=>0, 'shareholder'=>0, 'etc'=>0];
        }

        $this->render('notice/list', [
            'list'=>$list, 'totalCount'=>$totalCount,
            'totalPages'=>max(1, ceil($totalCount / 10)),
            'page'=>$page, 'search'=>$search, 'category'=>$category,
            'counts'=>$counts,
        ]);
    }

    public function create() { $this->post(); }

    public function post()
    {
        require 'application/config/auth.php';
        $this->render('notice/form', ['item'=>null,'files'=>[],'mode'=>'create']);
    }

    public function edit($num = '')
    {
        require 'application/config/auth.php';
        $notice = $num && !DEV_MODE ? $this->model->getOne($this->boardId, $num) : null;
        if (DEV_MODE && $num) {
            $notice = [
                'id' => $num,
                'title' => '더미 공고 #'.$num,
                'category' => 'settlement',
                'category_name' => '결산공고',
                'publish_date' => '2026-03-26',
                'publish_time' => '15:09',
                'published_at' => '2026-03-26 15:09:00',
                'file_name' => '첨부파일_샘플.pdf',
            ];
        }
        if (!$notice) $this->redirect('/notice/list');
        $this->render('notice/form', ['notice' => $notice, 'mode' => 'edit']);
    }

    public function detail($num = '')
    {
        require 'application/config/auth.php';
        $notice = null;
        if (DEV_MODE && $num) {
            $notice = [
                'id' => $num,
                'title' => '더미 공고 #'.$num,
                'category' => 'settlement',
                'category_name' => '결산공고',
                'publish_date' => '2026-03-26',
                'publish_time' => '15:09',
                'published_at' => '2026-03-26 15:09:00',
                'file_name' => '첨부파일_샘플.pdf',
            ];
        } elseif ($num) {
            $notice = $this->model->getOne($this->boardId, $num);
        }
        if (!$notice) $this->redirect('/notice/list');
        $this->render('notice/detail', ['notice' => $notice]);
    }
}
