<?php
require_once "../inc/connection.php";

require_once "../inc/mailsetting.php";




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


if($_POST){
if(emailValidation($_POST['Email'])){
    $restToken=sha1(uniqid('',true)).sha1(time());
    $stmt=$pdo->prepare('UPDATE users SET reset_token = :token WHERE email = :email');
    $stmt->execute([
        ':token'=>$restToken,
        ':email'=>$_POST['Email'],
        
    ]);
    if($stmt->rowCount()){
     
        $mail->addAddress($_POST['Email']); 
        $mail->isHTML(true);   
        $mail->Subject = 'reset password'; 
        $link='http://localhost/study11-1-2022/lrapp/views/reset.php?email=' . $_POST['Email'] . '&token='.$restToken;
        $mail->Body = "<a href='$link'> please enter to restpassword</a>";  
        $mail->send();
        header('location:../views/login.php');die;
   
        
    }else{
        echo "user not found";
    }
}


}else{
    header('location:../views/login.php');
}




?>