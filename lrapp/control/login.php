<?php
require_once '../inc/connection.php';
session_start();
$error = [];
function userValidation($user)
{
    global $error;
    if (!empty($user)) {

        if (preg_match('/^[a-z\d_]{2,20}$/i', $user) || filter_var($user, FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            $error['username'][0] = "Invalid Username OR Email ";
            return false;
        }
    } else {
        $error['username'][1] = "user name reqired ";
        return false;
    }
}
function passwordValidation($pass)
{
    global $error;
    if (!empty($pass)) {
        $pattern = "/[^a-zA-Z\d]/";
        if (preg_match($pattern, $pass)) {
            return true;
        } else {

            $error['pass'][0] = "Password incorrect";
            return false;
        }
    } else {
        $error['pass'][1] = "password reqired ";
        return false;
    }
}

if($_POST){
    if(userValidation($_POST['EmailOrUsername'])&&passwordValidation($_POST['password']) ){
        $stmt= $pdo->prepare('SELECT * FROM users WHERE email=:Username OR username =:Email');
        $stmt->execute([':Email'=>$_POST['EmailOrUsername'],':Username'=>$_POST['EmailOrUsername']]);
        if($stmt->rowCount()){
            $userinfo=$stmt->fetch();
        if(password_verify($_POST['password'],$userinfo['password'])){
            $_SESSION['userinfo']=$userinfo;
            $stmt=$pdo->prepare('UPDATE users SET last_login =:logintime WHERE email=:Username OR username =:Email');
            $stmt->execute([':logintime'=>date('y-m-d-h-i-s'),':Email'=>$_POST['EmailOrUsername'],':Username'=>$_POST['EmailOrUsername']]);
            header('location:../views/user_profile.php');
        }else{
            echo"password incorrect";
        }
        }else{
            echo "user unfound please enter correct email or user name";
        }
    }else{
        print_r($error);
    }
}