<?php
class IrController extends Controller
{
    function __construct() { parent::__construct(); $this->currentMenu = 'ir'; }

    public function list()
    {
        require 'application/config/auth.php';
        $page     = max(1, intval($this->getParam('page', 1)));
        $keyword  = $this->getParam('keyword', '');
        $category = $this->getParam('category', '');

        $catMap = ['letter' => 'IR레터', 'book' => 'IR북'];
        $allList = [];
        $titles = ['동국산업 IR레터 2026 02호','2026년 4월 IR북','2026년 3월 IR북',
            '2026년 2월 IR북','2026년 1월 IR북','동국산업 IR레터 2026 01호',
            '2025년 12월 IR북','2025년 11월 IR북','동국산업 IR레터 2025 04호',
            '2025년 10월 IR북','2025년 9월 IR북','동국산업 IR레터 2025 03호'];
        $types  = ['letter','book','book','book','book','letter',
            'book','book','letter','book','book','letter'];
        foreach ($titles as $i => $t) {
            $allList[] = [
                'id' => 50 - $i,
                'title' => $t,
                'category' => $types[$i],
                'category_name' => $catMap[$types[$i]],
                'created_at' => '2026-03-26 15:09:57',
                'updated_at' => '2026-03-26 15:09:57',
                'file_name' => ($i % 2 === 0) ? 'IR자료.pdf' : null,
            ];
        }
        if ($category && isset($catMap[$category])) {
            $allList = array_values(array_filter($allList, fn($r) => $r['category'] === $category));
        }
        if ($keyword) {
            $allList = array_values(array_filter($allList, fn($r) => stripos($r['title'], $keyword) !== false));
        }
        $totalCount = count($allList);
        $list = array_slice($allList, ($page - 1) * 10, 10);
        $counts = ['total' => 12, 'letter' => 4, 'book' => 8];

        $this->render('ir/list', [
            'list' => $list, 'totalCount' => $totalCount,
            'totalPages' => max(1, ceil($totalCount / 10)),
            'page' => $page, 'keyword' => $keyword, 'category' => $category,
            'counts' => $counts,
        ]);
    }

    public function create() { $this->post(); }

    public function post()
    {
        require 'application/config/auth.php';
        $this->render('ir/form', ['ir' => null, 'mode' => 'create']);
    }

    public function edit($num = '')
    {
        require 'application/config/auth.php';
        $ir = [
            'id' => $num,
            'title' => '동국산업 IR레터 2026 02호',
            'category' => 'letter',
            'category_name' => 'IR레터',
            'publish_date' => '2026-03-26',
            'publish_time' => '15:09',
            'published_at' => '2026-03-26 15:09:00',
            'file_name' => 'IR자료.pdf',
        ];
        $this->render('ir/form', ['ir' => $ir, 'mode' => 'edit']);
    }

    public function detail($num = '')
    {
        require 'application/config/auth.php';
        $ir = [
            'id' => $num,
            'title' => '동국산업 IR레터 2026 02호',
            'category' => 'letter',
            'category_name' => 'IR레터',
            'publish_date' => '2026-03-26',
            'publish_time' => '15:09',
            'published_at' => '2026-03-26 15:09:00',
            'file_name' => 'IR자료.pdf',
        ];
        $this->render('ir/detail', ['ir' => $ir]);
    }
}
