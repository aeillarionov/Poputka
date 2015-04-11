<?php
    include "sessions.php";
    session_start();
    if (empty($_SESSION['id'])) {
        exit("Войдите или зарегистрируйтесь");
    }
    unset($_SESSION['id']);
    if(isset($_COOKIE['sess_id'])){
        unset($_COOKIE['sess_id']);
        setcookie('sess_id','',time()-3600);
    }
    session_destroy();
    echo 0;
?>