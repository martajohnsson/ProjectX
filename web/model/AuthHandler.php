<?php

class AuthHandler
{
    public static $instance;

    public static function login($user)
    {
        $_SESSION['user'] = $user;
    }

    public static function getRole()
    {
        if(self::isLoggedIn()) {
            $role = self::getUser()->getRole();
            return $role;
        }
        return null;
    }

    public static function getApiKey() {
        if(self::isLoggedIn()) {
            return self::getUser()->getApiKey();
        }
        return false;
    }

    public static function getUser()
    {
        if (isset($_SESSION['user'])) {
            return $_SESSION['user'];
        } else {
            return null;
        }
    }

    public static function isLoggedIn()
    {
        if (isset($_SESSION['user'])) {
            return true;
        } else {
            return false;
        }
    }

    public static function logout()
    {
        unset($_SESSION['user']);
        session_destroy();
    }

    public static function isOwner($email)
    {
        if (self::isLoggedIn()) {
            if ($_SESSION['user']->getEmail() == $email) {
                return true;
            }
        }
        return false;
    }

    public static function isOwnerByID($id) {
        if (self::isLoggedIn()) {
            if ($_SESSION['user']->getId() == $id  || self::isAdmin()) {
                return true;
            }
        }
        return false;
    }

    public static function isAdmin() {
        if(self::getRole() == 2){
            return true;
        }
        return false;
    }

}