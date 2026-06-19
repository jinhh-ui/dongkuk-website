<?php

class LoginModel
{
    private $db;

    function __construct($db) { $this->db = $db; }

    public function authenticate($id, $pw)
    {
        if (DEV_MODE) {
            return (object)['USER_ID' => 'dev', 'USER_NAME' => '개발자', 'LEVEL_CODE' => '0'];
        }

        $stmt = $this->db->prepare(
            "SELECT USER_ID, USER_NAME, LEVEL_CODE
               FROM tb_user_master
              WHERE USER_ID = ? AND USER_PW = ?"
        );
        $stmt->execute([$id, $pw]);
        return $stmt->fetch();
    }
}
