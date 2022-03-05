<?php
session_start();
require_once '../inc/connection.php';

$error = [];
function userValidation($user)
{
    global $error;
    if (!empty($user)) {

        if (preg_match('/^[a-z\d_]{2,20}$/i', $user)) {
            return true;
        } else {
            $error['username'][0] = "Invalid Name ";
            return false;
        }
    } else {
        $error['username'][1] = "user name reqired ";
        return false;
    }
}

function emailValidation($mail)
{
    global $error;
    if (!empty($mail)) {
        if (filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            $error['email'][0] = "Invalid email ";
            return false;
        }
    } else {
        $error['email'][1] = "email reqired ";
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

            $error['pass'][0] = "Password must include one uppercase letter, one lowercase letter, one number, and one special character such as $ or %.";
            return false;
        }
    } else {
        $error['pass'][1] = "password reqired ";
        return false;
    }
}
function repasswordvalidation($pass, $repass)
{
    global $error;
    if (!empty($repass)) {
        if ($pass == $repass) {
            return true;
        } else {
            $error['repassword'][0] = "re-password not matching ";
            return false;
        }
    } else {
        $error['repassword'][1] = "re-password reqired ";
        return false;
    }
}





if ($_POST) {
    if (userValidation($_POST['username']) && emailValidation($_POST['email']) && passwordValidation($_POST['password']) && repasswordvalidation($_POST['password'], $_POST['password_confirm'])) {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username= ? OR  email = ?');
        $stmt->execute([$_POST['username'], $_POST['email']]);
        if ($stmt->rowCount()) {
            die('Username Or Email is already taken , please pick up another one ');
        } else {
            $stmt = $pdo->prepare('INSERT INTO users (username,password,email) VALUE(?,?,?)');
            $stmt->execute([
                $_POST['username'],
                password_hash($_POST['password'], PASSWORD_DEFAULT, ['cost' => 11]),
                $_POST['email']
            ]);
            if ($stmt->rowCount()) {
                echo ' thanks for registering, please go to activate your account';
            }
        }
    } else {
        print_r($error);
    }
}
