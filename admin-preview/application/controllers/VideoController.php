<?php
class VideoController extends Controller
{
    function __construct() { parent::__construct(); $this->currentMenu = 'news-video'; }

    public function list()
    {
        require 'application/config/auth.php';
        // TODO: DB 연동 시 video 테이블에서 단건 조회
        $item = [
            'id'        => 1,
            'title'     => '철의 역사를 만들어가는 동국산업',
            'desc'      => '동국산업은 1967년 설립되어 철의 역사를 만들어가고 있습니다. 세계 최고 냉연특수강을 제작하여 세계로 뻗어나가겠습니다.',
            'url'       => 'https://youtu.be/wmEtOcmF6Mk?si=ZKR44RacM_gWa8kU',
            'thumbnail' => BASE_URL . '/public/img/thumbnail_video.png',
            'tags'      => '#동국산업 #홍보영상 #냉연강판',
            'updated_at'=> '2026-03-26 17:45:12',
        ];
        $this->render('video/list', ['video' => $item]);
    }

    public function edit()
    {
        require 'application/config/auth.php';
        // TODO: DB 연동 시 video 테이블에서 단건 조회
        $item = [
            'id'        => 1,
            'title'     => '철의 역사를 만들어가는 동국산업',
            'desc'      => '동국산업은 1967년 설립되어 철의 역사를 만들어가고 있습니다. 세계 최고 냉연특수강을 제작하여 세계로 뻗어나가겠습니다.',
            'url'       => 'https://youtu.be/wmEtOcmF6Mk?si=ZKR44RacM_gWa8kU',
            'thumbnail' => BASE_URL . '/public/img/thumbnail_video.png',
            'tags'      => '#동국산업 #홍보영상 #냉연강판',
            'updated_at'=> '2026-03-26 17:45:12',
        ];
        $this->render('video/form', ['video' => $item]);
    }

    public function save()
    {
        require 'application/config/auth.php';
        if (!$this->isPost()) $this->redirect('/video/list');

        if (DEV_MODE) {
            // TODO: DB 연동 시 video 테이블에 INSERT/UPDATE
            // $_POST['title'], $_POST['desc'], $_POST['url'], $_POST['tags'] 저장
            echo "<script>alert('저장이 완료되었습니다.');location.href='" . BASE_URL . "/video/list';</script>";
            return;
        }

        // 실서버: DB 저장 로직
        // $this->model->save($_POST);
        $this->redirect('/video/list');
    }
}
