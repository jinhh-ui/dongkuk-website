<?php
class ReportController extends Controller
{
    function __construct() { parent::__construct(); $this->currentMenu = 'report'; }

    public function list()
    {
        require 'application/config/auth.php';
        $page    = max(1, intval($this->getParam('page', 1)));
        $keyword = $this->getParam('keyword', '');
        $status  = $this->getParam('status', '');
        $alertMessage = null;

        // 이메일 발송 실패 알림 (세션에서 가져오기)
        if (isset($_SESSION['report_alert'])) {
            $alertMessage = $_SESSION['report_alert'];
            unset($_SESSION['report_alert']);
        }

        // TODO: DB 연동 시 report 테이블에서 리스트 조회
        $allList = [
            ['id'=>12,'code'=>'ESG-2026-00132','title'=>'안전사고 관련 제보합니다.','status'=>'received',
                'report_type'=>'anonymous','name'=>null,'phone'=>null,'email'=>null,
                'content'=>'안전사고 관련 제보 내용입니다.','created_at'=>'2026-06-15 10:00:00','answered_at'=>null,
                'files'=>[]],
            ['id'=>11,'code'=>'ESG-2026-00131','title'=>'환경 오염 관련 제보','status'=>'received',
                'report_type'=>'named','name'=>'이영희','phone'=>'010-5555-6666','email'=>'lee@example.com',
                'content'=>'환경 오염 관련 제보입니다.','created_at'=>'2026-06-10 14:00:00','answered_at'=>null,
                'files'=>[['name'=>'사진자료.jpg','size'=>'3.1MB']]],
            ['id'=>10,'code'=>'ESG-2026-00130','title'=>'부정행위 제보합니다.','status'=>'review',
                'report_type'=>'anonymous','name'=>null,'phone'=>null,'email'=>null,
                'content'=>'부정행위 관련 내용입니다.','created_at'=>'2026-06-05 09:00:00','answered_at'=>null,
                'files'=>[]],
            ['id'=>9,'code'=>'ESG-2026-00129','title'=>'거래처 비리 제보','status'=>'review',
                'report_type'=>'named','name'=>'박영수','phone'=>'010-7777-8888','email'=>'park@example.com',
                'content'=>'거래처 비리 관련 제보입니다.','created_at'=>'2026-05-28 16:00:00','answered_at'=>null,
                'files'=>[['name'=>'증빙서류.pdf','size'=>'4.5MB']]],
            ['id'=>8,'code'=>'ESG-2026-00128','title'=>'내부 규정 위반 제보','status'=>'done',
                'report_type'=>'named','name'=>'최미나','phone'=>'010-3333-4444','email'=>'choi@example.com',
                'content'=>'내부 규정 위반 건입니다.','created_at'=>'2026-05-20 11:00:00','answered_at'=>'2026-05-25 14:00:00',
                'answer_content'=>'확인 후 조치 완료','answer_author'=>'관리자','files'=>[]],
            ['id'=>7,'code'=>'ESG-2026-00127','title'=>'직장 내 괴롭힘 제보','status'=>'done',
                'report_type'=>'anonymous','name'=>null,'phone'=>null,'email'=>null,
                'content'=>'직장 내 괴롭힘 관련 제보입니다.','created_at'=>'2026-05-15 13:00:00','answered_at'=>'2026-05-20 10:00:00',
                'answer_content'=>'확인 후 조치 완료','answer_author'=>'관리자','files'=>[]],
            ['id'=>6,'code'=>'ESG-2026-00126','title'=>'횡령 관련 제보','status'=>'done',
                'report_type'=>'named','name'=>'정수진','phone'=>'010-1111-2222','email'=>'jung@example.com',
                'content'=>'횡령 관련 제보입니다.','created_at'=>'2026-05-10 08:00:00','answered_at'=>'2026-05-15 17:00:00',
                'answer_content'=>'조사 완료','answer_author'=>'관리자','files'=>[['name'=>'증거.pdf','size'=>'2.0MB']]],
            ['id'=>5,'code'=>'ESG-2026-00125','title'=>'납품 비리 제보합니다.','status'=>'done',
                'report_type'=>'anonymous','name'=>null,'phone'=>null,'email'=>null,
                'content'=>'납품 관련 비리입니다.','created_at'=>'2026-04-25 15:00:00','answered_at'=>'2026-05-01 10:00:00',
                'answer_content'=>'조치 완료','answer_author'=>'관리자','files'=>[]],
            ['id'=>4,'code'=>'ESG-2026-00124','title'=>'시설 안전 위반 제보','status'=>'done',
                'report_type'=>'named','name'=>'오세훈','phone'=>'010-9999-0000','email'=>'oh@example.com',
                'content'=>'시설 안전 위반 관련 제보입니다.','created_at'=>'2026-04-15 10:00:00','answered_at'=>'2026-04-20 14:00:00',
                'answer_content'=>'점검 완료','answer_author'=>'관리자','files'=>[]],
            ['id'=>3,'code'=>'ESG-2026-00123','title'=>'제보합니다.','status'=>'done',
                'report_type'=>'anonymous','name'=>null,'phone'=>null,'email'=>null,
                'content'=>'익명 제보 내용입니다.','created_at'=>'2026-02-25 15:09:57','answered_at'=>'2026-03-01 09:00:00',
                'answer_content'=>'확인 완료','answer_author'=>'관리자','files'=>[['name'=>'증거자료.jpg','size'=>'2.7MB']]],
            ['id'=>2,'code'=>'ESG-2026-00122','title'=>'비리 관련 제보입니다.','status'=>'done',
                'report_type'=>'named','name'=>'홍길동','phone'=>'010-1234-5678','email'=>'test@example.com',
                'content'=>'실명 제보 내용입니다.','created_at'=>'2026-03-26 15:09:57','answered_at'=>'2026-04-01 11:00:00',
                'answer_content'=>'조치 완료','answer_author'=>'관리자','files'=>[['name'=>'첨부파일.pdf','size'=>'1.2MB']]],
            ['id'=>1,'code'=>'ESG-2026-00121','title'=>'환경 관련 제보합니다.','status'=>'done',
                'report_type'=>'named','name'=>'김철수','phone'=>'010-9876-5432','email'=>'kim@example.com',
                'content'=>'환경 관련 제보합니다.','created_at'=>'2026-03-26 15:09:57','answered_at'=>'2026-03-28 10:30:00',
                'answer_content'=>'제보 내용 확인 후 조치 완료하였습니다.','answer_author'=>'관리자','files'=>[]],
        ];

        if ($status) {
            $allList = array_values(array_filter($allList, fn($r) => $r['status'] === $status));
        }
        if ($keyword) {
            $allList = array_values(array_filter($allList, fn($r) => stripos($r['title'], $keyword) !== false || stripos($r['code'], $keyword) !== false));
        }
        $totalCount = count($allList);
        $list = array_slice($allList, ($page - 1) * 10, 10);
        $counts = ['total' => 12, 'received' => 2, 'review' => 2, 'done' => 8];

        $this->render('report/list', [
            'list' => $list, 'totalCount' => $totalCount,
            'totalPages' => max(1, ceil($totalCount / 10)),
            'page' => $page, 'keyword' => $keyword, 'status' => $status,
            'counts' => $counts, 'alertMessage' => $alertMessage,
        ]);
    }

    public function detail($num = '')
    {
        require 'application/config/auth.php';

        // TODO: DB 연동 시 report 테이블에서 단건 조회
        $report = $this->getDummyReport($num);
        if (!$report) $this->redirect('/report/list');

        // 상세 진입 시 접수 → 검토 중 자동 전환
        if (($report['status'] ?? '') === 'received') {
            // TODO: DB UPDATE report SET status='review' WHERE id=$num
            $report['status'] = 'review';

            // TODO: 실명 제보이고 이메일이 있으면 "검토진행 중" 이메일 자동발송
            if ($report['report_type'] === 'named' && !empty($report['email'])) {
                $this->sendEmail($report['email'], 'review', $report);
            }
        }

        $this->render('report/detail', ['report' => $report]);
    }

    /**
     * 답변 등록 — POST
     * 답변 저장 → 상태 '처리완료' 전환 → 이메일 발송
     */
    public function answer($num = '')
    {
        require 'application/config/auth.php';

        $answerContent = $_POST['answer_content'] ?? '';
        $answerAuthor  = $_POST['answer_author'] ?? '';

        if (!$answerContent || !$answerAuthor) {
            // validation 실패 → 상세로 리다이렉트
            $this->redirect('/report/detail/' . $num);
            return;
        }

        // TODO: DB UPDATE report SET
        //   status='done',
        //   answer_content=:answerContent,
        //   answer_author=:answerAuthor,
        //   answered_at=NOW()
        // WHERE id=:num

        // 이메일 발송 (실명이고 이메일 존재 시)
        $report = $this->getDummyReport($num);
        if ($report && $report['report_type'] === 'named' && !empty($report['email'])) {
            $sent = $this->sendEmail($report['email'], 'done', $report);
            if (!$sent) {
                // 이메일 발송 실패 → 알림 세션 저장
                $_SESSION['report_alert'] = [
                    'title' => '이메일 발송 실패',
                    'desc'  => '답변은 등록되었으나 이메일 발송에 실패했습니다. 제보자 이메일: ' . $report['email'],
                ];
            }
        }

        $this->redirect('/report/list');
    }

    /**
     * 삭제(파기) — POST
     * 파기자 명 필수 입력, 파기 이력 기록
     */
    public function delete($num = '')
    {
        require 'application/config/auth.php';

        $destroyerName = $_POST['destroyer_name'] ?? '';
        if (!$destroyerName) {
            $this->redirect('/report/detail/' . $num);
            return;
        }

        // TODO: DB — 파기 이력 INSERT
        // INSERT INTO report_destroy_history (
        //   report_id, report_code, destroyer_name, destroyed_at, destroy_reason
        // ) VALUES (
        //   :num, :code, :destroyerName, NOW(), '관리자 파기'
        // )

        // TODO: DB — 제보 데이터 완전 삭제 (개인정보 포함)
        // DELETE FROM report WHERE id = :num

        $this->redirect('/report/list');
    }

    /**
     * 이메일 발송 유틸
     * @param string $to 수신자 이메일
     * @param string $type 'received'|'review'|'done'
     * @param array $report 제보 데이터
     * @return bool 발송 성공 여부
     */
    private function sendEmail($to, $type, $report)
    {
        $subjects = [
            'received' => '[동국산업] 제보 접수 확인',
            'review'   => '[동국산업] 제보 검토 진행 안내',
            'done'     => '[동국산업] 제보 처리 결과 안내',
        ];
        $subject = $subjects[$type] ?? '[동국산업] 제보 안내';

        // TODO: 실제 이메일 발송 구현
        // $mailer = new Mailer();
        // $mailer->to($to);
        // $mailer->subject($subject);
        // $mailer->body($this->getEmailTemplate($type, $report));
        // return $mailer->send();

        // DEV_MODE: 발송 시뮬레이션 (항상 성공)
        if (DEV_MODE) {
            error_log("[EMAIL] To: {$to}, Subject: {$subject}, Type: {$type}, Code: {$report['code']}");
            return true;
        }

        return false;
    }

    /**
     * 더미 제보 데이터
     */
    private function getDummyReport($num)
    {
        $reports = [
            3 => ['id'=>3,'code'=>'ESG-2026-00123','title'=>'제보합니다.','status'=>'received',
                'report_type'=>'anonymous','name'=>null,'phone'=>null,'email'=>null,
                'content'=>'익명 제보 내용입니다. 해당 건에 대해 조사를 요청합니다.','created_at'=>'2026-02-25 15:09:57','answered_at'=>null,
                'answer_content'=>null,'answer_author'=>null,
                'files'=>[['name'=>'증거자료.jpg','size'=>'2.7MB']]],
            2 => ['id'=>2,'code'=>'ESG-2026-00122','title'=>'비리 관련 제보입니다.','status'=>'review',
                'report_type'=>'named','name'=>'홍길동','phone'=>'010-1234-5678','email'=>'test@example.com',
                'content'=>'실명 제보 내용입니다. 해당 건에 대해 조사를 요청합니다.','created_at'=>'2026-03-26 15:09:57','answered_at'=>null,
                'answer_content'=>null,'answer_author'=>null,
                'files'=>[['name'=>'첨부파일.pdf','size'=>'1.2MB']]],
            1 => ['id'=>1,'code'=>'ESG-2026-00121','title'=>'환경 관련 제보합니다.','status'=>'done',
                'report_type'=>'named','name'=>'김철수','phone'=>'010-9876-5432','email'=>'kim@example.com',
                'content'=>'환경 관련 제보합니다.','created_at'=>'2026-03-26 15:09:57','answered_at'=>'2026-03-28 10:30:00',
                'answer_content'=>'제보 내용 확인 후 조치 완료하였습니다. 감사합니다.','answer_author'=>'관리자',
                'files'=>[]],
        ];
        return $reports[intval($num)] ?? null;
    }
}
