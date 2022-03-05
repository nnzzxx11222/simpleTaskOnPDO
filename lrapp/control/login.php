<?php
require_once '../inc/connection.php';

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
        $stmt= $pdo->prepare('SELECT * FROM users WHERE email=:EmailOrUsername OR username =:EmailOrUsername');
        $stmt->execute([':EmailOrUsername'=>$_POST['EmailOrUsername']]);
        if($stmt->rowCount()){
        if(password_verify($_POST['password'],$stmt->fetch()['password'])){
            print_r($stmt->fetchAll());
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