<?php

class LogoutController extends Controller
{
    public function index()
    {
        session_destroy();
        header('Location:' . BASE_URL . '/login');
        exit;
    }
}
