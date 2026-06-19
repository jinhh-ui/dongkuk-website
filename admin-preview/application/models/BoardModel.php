<?php

class BoardModel
{
    private $db;

    function __construct($db)
    {
        $this->db = $db;
    }

    /* ── 리스트 ── */
    public function getList($boardId, $page = 1, $limit = 10, $search = '')
    {
        if (DEV_MODE) return self::dummyList();

        $offset = ($page - 1) * $limit;
        $sql = "SELECT d.BOARD_NUM, d.BOARD_TITLE,
                       d.BOARD_START_DATE, d.BOARD_MODIFY_DATE,
                       f.FILE_PATH AS THUMB_PATH
                  FROM tb_board_detail d
                  LEFT JOIN (SELECT BOARD_ID, BOARD_NUM, MIN(FILE_PATH) AS FILE_PATH
                               FROM tb_file_master GROUP BY BOARD_ID, BOARD_NUM
                            ) f USING (BOARD_ID, BOARD_NUM)
                 WHERE d.BOARD_ID = ?";
        $params = [$boardId];

        if ($search) {
            $sql .= " AND (d.BOARD_TITLE LIKE ? OR d.BOARD_CONTENT LIKE ?)";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }

        $sql .= " ORDER BY d.BOARD_START_DATE DESC, d.BOARD_NUM DESC LIMIT ?, ?";
        $params[] = $offset;
        $params[] = $limit;

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getCount($boardId, $search = '')
    {
        if (DEV_MODE) return 48;

        $sql = "SELECT COUNT(*) AS cnt FROM tb_board_detail WHERE BOARD_ID = ?";
        $params = [$boardId];
        if ($search) {
            $sql .= " AND (BOARD_TITLE LIKE ? OR BOARD_CONTENT LIKE ?)";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    /* ── 상세 ── */
    public function getOne($boardId, $boardNum)
    {
        if (DEV_MODE) return self::dummyOne($boardNum);

        $stmt = $this->db->prepare(
            "SELECT * FROM tb_board_detail WHERE BOARD_ID = ? AND BOARD_NUM = ?"
        );
        $stmt->execute([$boardId, $boardNum]);
        return $stmt->fetch();
    }

    /* ── 저장 ── */
    public function save($boardId, $data, $userId)
    {
        if (DEV_MODE) return true;

        $postDate = $data['start_date'] . ' ' . $data['start_time'] . ':00';

        if (!empty($data['boardnum'])) {
            $stmt = $this->db->prepare(
                "UPDATE tb_board_detail
                    SET BOARD_TITLE = ?, BOARD_CONTENT = ?,
                        BOARD_START_DATE = ?, BOARD_MODIFY_DATE = NOW()
                  WHERE BOARD_ID = ? AND BOARD_NUM = ?"
            );
            return $stmt->execute([
                $data['title'], $data['content'], $postDate,
                $boardId, $data['boardnum']
            ]);
        }

        $stmt = $this->db->prepare(
            "INSERT INTO tb_board_detail
             (BOARD_ID, BOARD_TITLE, BOARD_CONTENT, BOARD_CREATE_USER,
              BOARD_CREATE_DATE, BOARD_MODIFY_DATE, BOARD_START_DATE)
             VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        return $stmt->execute([
            $boardId, $data['title'], $data['content'], $userId,
            $postDate, $postDate, $postDate
        ]);
    }

    /* ── 삭제 ── */
    public function delete($boardId, array $nums)
    {
        if (DEV_MODE) return true;

        $in = implode(',', array_fill(0, count($nums), '?'));
        $stmt = $this->db->prepare(
            "DELETE FROM tb_board_detail WHERE BOARD_ID = ? AND BOARD_NUM IN ({$in})"
        );
        return $stmt->execute(array_merge([$boardId], $nums));
    }

    /* ── 첨부파일 ── */
    public function getFiles($boardId, $boardNum)
    {
        if (DEV_MODE) return [];

        $stmt = $this->db->prepare(
            "SELECT * FROM tb_file_master WHERE BOARD_ID = ? AND BOARD_NUM = ?"
        );
        $stmt->execute([$boardId, $boardNum]);
        return $stmt->fetchAll();
    }

    /* ══════════════════════════════════════
       더미 데이터 (DEV_MODE 전용)
       ══════════════════════════════════════ */
    private static function dummyList()
    {
        $titles = [
            '[창간기획7] 동국산업 김동철 공장장..."가격은 더 저렴하게, 품질은 더 우수하게"⑦',
            '니켈도금강판 게임체인저 동국산업 \'디켈(DIKEL)\', 배터리 시장 정조준',
            '동국산업, \'46시리즈\' 수혜 노린다…니켈도금강판 양산 시동',
            '경북도 투자유치대상 시상식...동국산업 등 기업 7곳 시상',
            '\'포항 원팀\'으로 이차전지 산업 글로벌 경쟁력 높인다',
            '\'오직 품질과 기술력으로 승부수\'···동국산업 니켈도금강판 공장에 가다',
            '"니켈도금강판 신사업으로 제2의 도약 나설 것" -동국산업 정일영 상무',
            '동국산업, 니켈도금강판 공장 준공…"미래 향한 도약"',
            '"좋은 재료와 설비가 좋은 품질로"…동국산업 니켈도금강판 공장',
            '"무조건 할 수밖에 없다"…\'제 2의 도약\' 사활 건 동국산업',
        ];
        $items = [];
        foreach ($titles as $i => $t) {
            $items[] = (object)[
                'BOARD_NUM'       => 100 - $i,
                'BOARD_TITLE'     => $t,
                'BOARD_START_DATE'=> '2026-03-26 15:09:00',
                'BOARD_MODIFY_DATE'=> '2026-03-26 15:09:57',
                'THUMB_PATH'      => null,
            ];
        }
        return $items;
    }

    private static function dummyOne($num)
    {
        return (object)[
            'BOARD_NUM'        => $num,
            'BOARD_TITLE'      => '더미 뉴스 기사 #' . $num,
            'BOARD_CONTENT'    => '<p>본문 내용입니다.</p>',
            'BOARD_START_DATE' => '2026-03-26 15:09:00',
            'BOARD_MODIFY_DATE'=> '2026-03-26 15:09:57',
        ];
    }
}
