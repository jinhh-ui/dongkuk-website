<?php
class InquiryController extends Controller
{
    function __construct() { parent::__construct(); $this->currentMenu = 'inquiry'; }

    public function list()
    {
        require 'application/config/auth.php';
        $page   = max(1, intval($this->getParam('page', 1)));
        $search = $this->getParam('text', '');
        $status = $this->getParam('status', '');

        // TODO: DB 연동 시 inquiry 테이블에서 리스트 조회
        $allList = [
            ['id'=>12,'title'=>'냉연강판 견적 요청합니다.','category_name'=>'제품 및 영업 문의','status'=>'waiting',
                'created_at'=>'2026-06-15 10:00:00','answered_at'=>null],
            ['id'=>11,'title'=>'배터리 소재 관련 문의','category_name'=>'제품 및 영업 문의','status'=>'waiting',
                'created_at'=>'2026-06-10 14:00:00','answered_at'=>null],
            ['id'=>10,'title'=>'2026년 하반기 IR 일정 문의','category_name'=>'IR 및 투자 정보','status'=>'waiting',
                'created_at'=>'2026-06-05 09:00:00','answered_at'=>null],
            ['id'=>9,'title'=>'동국산업 채용 관련 문의','category_name'=>'대표 직접 문의','status'=>'done',
                'created_at'=>'2026-05-28 16:00:00','answered_at'=>'2026-06-01 10:00:00'],
            ['id'=>8,'title'=>'도금강판 납품 가능 여부 문의','category_name'=>'제품 및 영업 문의','status'=>'done',
                'created_at'=>'2026-05-20 11:00:00','answered_at'=>'2026-05-25 14:00:00'],
            ['id'=>7,'title'=>'니켈도금강판 샘플 요청','category_name'=>'제품 및 영업 문의','status'=>'done',
                'created_at'=>'2026-05-15 13:00:00','answered_at'=>'2026-05-20 10:00:00'],
            ['id'=>6,'title'=>'배당금 지급 일정 문의','category_name'=>'IR 및 투자 정보','status'=>'done',
                'created_at'=>'2026-05-10 08:00:00','answered_at'=>'2026-05-15 17:00:00'],
            ['id'=>5,'title'=>'공장 견학 가능 여부 문의','category_name'=>'대표 직접 문의','status'=>'done',
                'created_at'=>'2026-04-25 15:00:00','answered_at'=>'2026-05-01 10:00:00'],
            ['id'=>4,'title'=>'냉연강판 규격 관련 문의','category_name'=>'제품 및 영업 문의','status'=>'done',
                'created_at'=>'2026-04-15 10:00:00','answered_at'=>'2026-04-20 14:00:00'],
            ['id'=>3,'title'=>'제품 가격 문의합니다.','category_name'=>'제품 및 영업 문의','status'=>'done',
                'created_at'=>'2026-03-26 15:09:57','answered_at'=>'2026-04-01 09:00:00'],
            ['id'=>2,'title'=>'투자 관련 문의 드립니다.','category_name'=>'IR 및 투자 정보','status'=>'done',
                'created_at'=>'2026-03-26 15:09:57','answered_at'=>'2026-03-30 11:00:00'],
            ['id'=>1,'title'=>'대표이사님께 직접 문의합니다.','category_name'=>'대표 직접 문의','status'=>'done',
                'created_at'=>'2026-03-26 15:09:57','answered_at'=>'2026-03-26 15:09:57'],
        ];
        // 상태 필터
        if ($status) {
            $allList = array_values(array_filter($allList, fn($r) => $r['status'] === $status));
        }
        // 검색 필터
        if ($search) {
            $allList = array_values(array_filter($allList, fn($r) => stripos($r['title'], $search) !== false));
        }
        $totalCount = count($allList);
        $list = array_slice($allList, ($page - 1) * 10, 10);
        $counts = ['total'=>12, 'waiting'=>3, 'done'=>9];

        $this->render('inquiry/list', ['list'=>$list, 'totalCount'=>$totalCount,
            'totalPages'=>max(1, ceil($totalCount / 10)),
            'page'=>$page, 'search'=>$search, 'status'=>$status,
            'counts'=>$counts]);
    }

    public function detail($num = '')
    {
        require 'application/config/auth.php';
        // TODO: DB 연동 시 inquiry 테이블에서 단건 조회
        $item = ['id'=>$num,'title'=>'제품 가격 문의합니다.','category_name'=>'제품 및 영업 문의',
            'content'=>'안녕하세요. 니켈도금강판 제품 가격에 대해 문의 드립니다.','status'=>'waiting',
            'created_at'=>'2026-03-26 15:09:57',
            'writer_name'=>'홍길동','writer_email'=>'test@example.com','writer_phone'=>'010-1234-5678'];
        $this->render('inquiry/detail', ['item'=>$item]);
    }
}
