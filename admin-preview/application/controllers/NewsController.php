<?php

class NewsController extends Controller
{
    private $model;
    private $boardId = '557fd952cfd32'; // TODO: 실제 BOARD_ID 확인 후 교체

    function __construct()
    {
        parent::__construct();
        $this->currentMenu = 'news-article';
        $this->model = $this->loadModel('BoardModel');
    }

    public function list()
    {
        require 'application/config/auth.php';

        $page    = max(1, intval($this->getParam('page', 1)));
        $keyword = $this->getParam('keyword', '');
        $limit   = 10;

        if (DEV_MODE) {
            // 더미 데이터 — 정렬: 1차 게시일시 역순, 2차 ID 역순
            $allData = [
                ['id'=>50, 'title'=>'동국산업 IR레터 2026 02호', 'thumbnail'=>BASE_URL.'/public/img/news_thumb_01.png', 'created_at'=>'2026-03-26 15:00:00', 'updated_at'=>'2026-03-26 15:09:57'],
                ['id'=>49, 'title'=>'니켈도금강판 게임체인저 동국산업 \'디켈(DIKEL)\', 배터리 시장 정조준', 'thumbnail'=>BASE_URL.'/public/img/news_thumb_02.png', 'created_at'=>'2026-03-26 15:00:00', 'updated_at'=>'2026-03-26 15:09:57'],
                ['id'=>48, 'title'=>'동국산업, \'46시리즈\' 수혜 노린다…니켈도금강판 양산 시동', 'thumbnail'=>BASE_URL.'/public/img/news_thumb_03.png', 'created_at'=>'2026-03-18 10:00:00', 'updated_at'=>'2026-03-18 10:00:00'],
                ['id'=>47, 'title'=>'경북도 투자유치대상 시상식...동국산업 등 기업 7곳 시상', 'thumbnail'=>BASE_URL.'/public/img/news_thumb_04.png', 'created_at'=>'2026-03-16 09:00:00', 'updated_at'=>'2026-03-16 10:30:00'],
                ['id'=>46, 'title'=>'\'포항 원팀\'으로 이차전지 산업 글로벌 경쟁력 높인다', 'thumbnail'=>BASE_URL.'/public/img/news_thumb_05.png', 'created_at'=>'2026-03-15 09:00:00', 'updated_at'=>'2026-03-15 09:00:00'],
                ['id'=>45, 'title'=>'\'오직 품질과 기술력으로 승부수\'···동국산업 니켈도금강판 공장에 가다', 'thumbnail'=>BASE_URL.'/public/img/news_thumb_06.png', 'created_at'=>'2026-03-10 14:00:00', 'updated_at'=>'2026-03-10 15:00:00'],
                ['id'=>44, 'title'=>'"니켈도금강판 신사업으로 제2의 도약 나설 것" -동국산업 정일영 상무', 'thumbnail'=>BASE_URL.'/public/img/news_thumb_07.png', 'created_at'=>'2026-03-08 11:00:00', 'updated_at'=>'2026-03-08 11:00:00'],
                ['id'=>43, 'title'=>'동국산업, 니켈도금강판 공장 준공…"미래 향한 도약"', 'thumbnail'=>BASE_URL.'/public/img/news_thumb_08.png', 'created_at'=>'2026-03-05 16:00:00', 'updated_at'=>'2026-03-05 16:00:00'],
                ['id'=>42, 'title'=>'"좋은 재료와 설비가 좋은 품질로"…동국산업 니켈도금강판 공장', 'thumbnail'=>BASE_URL.'/public/img/news_thumb_09.png', 'created_at'=>'2026-03-01 08:00:00', 'updated_at'=>'2026-03-01 09:30:00'],
                ['id'=>41, 'title'=>'무조건 할 수밖에 없다"…\'제 2의 도약\' 사활 건 동국산업', 'thumbnail'=>BASE_URL.'/public/img/news_thumb_10.png', 'created_at'=>'2026-02-28 10:00:00', 'updated_at'=>'2026-02-28 10:00:00'],
                ['id'=>40, 'title'=>'동국산업, 2025년 4분기 실적 발표…매출 전년比 12% 증가', 'thumbnail'=>BASE_URL.'/public/img/news_thumb_01.png', 'created_at'=>'2026-02-25 09:00:00', 'updated_at'=>'2026-02-25 09:00:00'],
                ['id'=>39, 'title'=>'동국산업 포항공장 스마트팩토리 전환 가속화', 'thumbnail'=>BASE_URL.'/public/img/news_thumb_02.png', 'created_at'=>'2026-02-20 14:00:00', 'updated_at'=>'2026-02-20 14:00:00'],
            ];
            // 검색 필터
            if ($keyword) {
                $allData = array_filter($allData, function($row) use ($keyword) {
                    return stripos($row['title'], $keyword) !== false;
                });
                $allData = array_values($allData);
            }
            $totalCount = count($allData);
            $totalPages = max(1, ceil($totalCount / $limit));
            $list = array_slice($allData, ($page - 1) * $limit, $limit);
        } else {
            $list       = $this->model->getList($this->boardId, $page, $limit, $keyword);
            $totalCount = $this->model->getCount($this->boardId, $keyword);
            $totalPages = max(1, ceil($totalCount / $limit));
        }

        $this->render('news/list', compact(
            'list', 'totalCount', 'totalPages', 'page', 'keyword'
        ) + ['boardId' => $this->boardId]);
    }

    public function detail($boardNum = '')
    {
        require 'application/config/auth.php';
        $item = null;
        if (DEV_MODE && $boardNum) {
            $item = [
                'id'=>$boardNum,
                'title'=>'[창간기획7] 동국산업 김동철 공장장..."가격은 더 저렴하게, 품질은 더 우수하게"⑦',
                'content'=>'<p>지난 4월, 포항을 찾은 취재진이 마주한 동국산업은 변혁의 한복판에 있었다. 1967년 설립된 이 회사는 반세기 넘게 냉연강판·도금강판 분야에서 묵묵히 길을 걸어왔다.</p><p>"가격은 더 저렴하게, 품질은 더 우수하게." 동국산업 김동철 공장장이 취재진에게 전한 이 한 마디에는 수십 년간 축적된 현장의 철학이 담겨있다.</p><p>동국산업이 최근 가장 심혈을 기울이고 있는 분야는 니켈도금강판이다. 이차전지 배터리 캔 소재로 주목받는 니켈도금강판은 기존 수입에만 의존하던 시장 구조를 바꿀 수 있는 게임체인저로 평가받고 있다.</p>',
                'thumbnail'=> BASE_URL . '/public/img/news_thumb_01.png',
                'created_at'=>'2026-03-26 15:00:00',
                'updated_at'=>'2026-03-26 15:09:57'
            ];
        } elseif ($boardNum) {
            $item = $this->model->getOne($this->boardId, $boardNum);
        }
        if (!$item) $this->redirect('/news/list');
        $this->render('news/detail', ['item' => $item]);
    }

    public function create() { $this->post(); }

    public function post()
    {
        require 'application/config/auth.php';
        $this->render('news/form', [
            'item' => null, 'files' => [],
            'boardId' => $this->boardId, 'mode' => 'create',
        ]);
    }

    public function edit($boardNum = '')
    {
        require 'application/config/auth.php';
        $item = null; $files = [];
        if (DEV_MODE && $boardNum) {
            $item = [
                'id'=>$boardNum,
                'title'=>'[창간기획7] 동국산업 김동철 공장장..."가격은 더 저렴하게, 품질은 더 우수하게"⑦',
                'content'=>'<p>지난 4월, 포항을 찾은 취재진이 마주한 동국산업은 변혁의 한복판에 있었다. 1967년 설립된 이 회사는 반세기 넘게 냉연강판·도금강판 분야에서 묵묵히 길을 걸어왔다.</p><p>"가격은 더 저렴하게, 품질은 더 우수하게." 동국산업 김동철 공장장이 취재진에게 전한 이 한 마디에는 수십 년간 축적된 현장의 철학이 담겨있다.</p>',
                'thumbnail'=> BASE_URL . '/public/img/news_thumb_01.png',
                'created_at'=>'2026-03-26 15:00:00',
                'updated_at'=>'2026-03-26 15:09:57'
            ];
        } elseif ($boardNum) {
            $item  = $this->model->getOne($this->boardId, $boardNum);
            $files = $this->model->getFiles($this->boardId, $boardNum);
        }
        if (!$item) $this->redirect('/news/list');

        $this->render('news/form', [
            'item' => $item, 'files' => $files,
            'boardId' => $this->boardId, 'mode' => 'edit',
        ]);
    }

    public function save()
    {
        require 'application/config/auth.php';
        if (!$this->isPost()) $this->redirect('/news/list');

        if (DEV_MODE) {
            $mode = $this->postParam('mode', 'create');
            $id = $this->postParam('id', '');
            if ($mode === 'edit' && $id) {
                $msg = '수정되었습니다.';
                $url = BASE_URL . '/news/detail/' . $id;
            } else {
                $msg = '등록되었습니다.';
                $url = BASE_URL . '/news/list';
            }
            echo "<script>alert('" . $msg . "');location.href='" . $url . "';</script>";
            return;
        }

        $ok = $this->model->save($this->boardId, $_POST, $_SESSION['userinfo']->USER_ID);

        if ($ok) {
            $this->redirect('/news/list');
        } else {
            echo "<script>alert('저장에 실패하였습니다.');history.back();</script>";
        }
    }

    public function del()
    {
        require 'application/config/auth.php';
        if (!$this->isPost()) return;

        $nums = array_filter(explode(',', $this->postParam('del_nums')));
        $ok   = $nums ? $this->model->delete($this->boardId, $nums) : false;

        header('Content-Type: application/json');
        echo json_encode(['success' => $ok]);
    }
}
