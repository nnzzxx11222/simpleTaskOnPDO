<?php
if($_SESSION['userinfo']){
    return true;
}else{

    header('location:login.php');exit;

}

?>
