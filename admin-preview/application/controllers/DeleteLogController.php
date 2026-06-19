<?php
class DeleteLogController extends Controller
{
    function __construct() { parent::__construct(); $this->currentMenu = 'deletelog'; }

    public function list()
    {
        require 'application/config/auth.php';

        // 필터 파라미터
        $filterMenu = isset($_GET['menu']) ? trim($_GET['menu']) : '';
        $filterType = isset($_GET['type']) ? trim($_GET['type']) : '';
        $startDate = isset($_GET['start_date']) ? trim($_GET['start_date']) : '';
        $endDate = isset($_GET['end_date']) ? trim($_GET['end_date']) : '';
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $perPage = 10;

        // 더미 데이터
        $allItems = [
            ['menu_name'=>'제보하기','type'=>'manual','count'=>1,
                'destroyed_at'=>'2026-06-11 15:09:57','admin_name'=>'홍길동'],
            ['menu_name'=>'문의하기','type'=>'auto','count'=>4,
                'destroyed_at'=>'2026-06-11 00:00:00','admin_name'=>'SYSTEM'],
            ['menu_name'=>'문의하기','type'=>'auto','count'=>5,
                'destroyed_at'=>'2026-06-10 00:00:00','admin_name'=>'SYSTEM'],
            ['menu_name'=>'문의하기','type'=>'auto','count'=>5,
                'destroyed_at'=>'2026-06-09 00:00:00','admin_name'=>'SYSTEM'],
            ['menu_name'=>'제보하기','type'=>'auto','count'=>5,
                'destroyed_at'=>'2026-06-08 00:00:00','admin_name'=>'SYSTEM'],
            ['menu_name'=>'제보하기','type'=>'auto','count'=>3,
                'destroyed_at'=>'2026-06-07 00:00:00','admin_name'=>'SYSTEM'],
            ['menu_name'=>'문의하기','type'=>'auto','count'=>2,
                'destroyed_at'=>'2026-06-06 00:00:00','admin_name'=>'SYSTEM'],
            ['menu_name'=>'제보하기','type'=>'auto','count'=>1,
                'destroyed_at'=>'2026-06-05 00:00:00','admin_name'=>'SYSTEM'],
            ['menu_name'=>'문의하기','type'=>'auto','count'=>6,
                'destroyed_at'=>'2026-06-04 00:00:00','admin_name'=>'SYSTEM'],
            ['menu_name'=>'제보하기','type'=>'auto','count'=>2,
                'destroyed_at'=>'2026-06-03 00:00:00','admin_name'=>'SYSTEM'],
            ['menu_name'=>'문의하기','type'=>'auto','count'=>1,
                'destroyed_at'=>'2026-06-02 00:00:00','admin_name'=>'SYSTEM'],
            ['menu_name'=>'제보하기','type'=>'auto','count'=>4,
                'destroyed_at'=>'2026-06-01 00:00:00','admin_name'=>'SYSTEM'],
        ];

        // 필터링
        $filtered = $allItems;
        if ($filterMenu) {
            $filtered = array_filter($filtered, function($item) use ($filterMenu) {
                $menuMap = ['report'=>'제보하기', 'inquiry'=>'문의하기'];
                return ($menuMap[$filterMenu] ?? '') === $item['menu_name'];
            });
        }
        if ($filterType) {
            $filtered = array_filter($filtered, function($item) use ($filterType) {
                return $item['type'] === $filterType;
            });
        }
        if ($startDate) {
            $filtered = array_filter($filtered, function($item) use ($startDate) {
                return substr($item['destroyed_at'], 0, 10) >= $startDate;
            });
        }
        if ($endDate) {
            $filtered = array_filter($filtered, function($item) use ($endDate) {
                return substr($item['destroyed_at'], 0, 10) <= $endDate;
            });
        }
        $filtered = array_values($filtered);

        // 통계
        $totalCount = count($allItems);
        $autoCount = count(array_filter($allItems, fn($i) => $i['type'] === 'auto'));
        $manualCount = count(array_filter($allItems, fn($i) => $i['type'] === 'manual'));

        // 페이지네이션
        $totalFiltered = count($filtered);
        $totalPages = max(1, ceil($totalFiltered / $perPage));
        $page = min($page, $totalPages);
        $offset = ($page - 1) * $perPage;
        $list = array_slice($filtered, $offset, $perPage);

        $this->render('deletelog/list', [
            'list' => $list,
            'stats' => ['total' => $totalCount, 'auto' => $autoCount, 'manual' => $manualCount],
            'filterMenu' => $filterMenu,
            'filterType' => $filterType,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'page' => $page,
            'totalPages' => $totalPages,
        ]);
    }
}
