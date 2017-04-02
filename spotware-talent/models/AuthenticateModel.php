<?php


class AuthenticateModel {
    public static function isLogin(){
        return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == SECURE_STRING;
    }
    
    public static function login($secureString){
        if($secureString == SECURE_STRING){
            $_SESSION['is_admin'] = $secureString;
            return true;
        }
        
        return false;
    }
    
    public static function logout(){
        unset($_SESSION['is_admin']);
        return true;
    }
}
