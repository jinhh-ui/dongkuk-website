<?php
if (!isset($_SESSION["userinfo"])) {
    header("Location:" . BASE_URL . "/login");
    exit;
}
