<?php

class Auth {
    public static function check() {
        Session::start();
        if (!Session::has('user_id')) {
            header('Location: /login');
            exit;
        }
    }

    public static function checkRole($role) {
        self::check();
        $userRole = Session::get('user_role');
        if ($userRole !== $role) {
            header('Location: /unauthorized');
            exit;
        }
    }

    public static function isLoggedIn() {
        Session::start();
        return Session::has('user_id');
    }

    public static function logout() {
        Session::start();
        Session::destroy();
        header('Location: /login');
        exit;
    }
}