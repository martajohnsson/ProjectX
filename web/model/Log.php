<?php

class Log {

    public static function siteError($msg) {
        $site_error_dir  = $_SERVER['DOCUMENT_ROOT'] . "site_error.log";
        $date = date('d.m.Y H:i:s'); 
        $log = $msg."   |  Date:  " . $date . "\n"; 
        error_log($log, 3, $site_error_dir); 
    }

    public static function userError($msg, $username) {
        $user_error_dir = $_SERVER['DOCUMENT_ROOT'] . "user_error.log";
        $date = date('d.m.Y H:i:s'); 
        $log = $msg."   |  Date:  " . $date . "  |  User:  " . $username . "\n"; 
        error_log($log, 3, $user_error_dir);
    }

    public static function apiError($msg, $url) {
        $api_error_dir = $_SERVER['DOCUMENT_ROOT'] . "api_error.log";
        $date = date('d.m.Y H:i:s'); 
        $log = $msg."   |  Date:  " . $date . "  |  url:  " . $url . "\n"; 
        error_log($log, 3, $api_error_dir);
    }

}