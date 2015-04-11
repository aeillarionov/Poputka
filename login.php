<?php
    include "sessions.php";
    session_start();

        if (empty($_POST['email'])||empty($_POST['password'])){
            exit("Заполните оба поля");
        }
        $db_des = new mysqli("localhost","root","root","game");
        if(mysqli_connect_errno()) {
            $response = array('status' => "Не удалось подключиться к базе данных", 'stakes' => 0);
			echo json_encode($response);
        }
        $i=0;
        $auctions = array();
        $bests = array();
        $email = $_POST["email"];
        $entered_password = $_POST["password"];
        $email = stripslashes($email);
        $email = htmlspecialchars($email);
        $email = trim($email);
        $entered_password = stripslashes($entered_password);
        $entered_password = htmlspecialchars($entered_password);
        $entered_password = trim($entered_password);
        $data = $db_des->query("SELECT `password`,`id`,`stakes`,`auctions`,`best` FROM `players` WHERE `email` = \"$email\"");
        
        if($data) {
            $row = $data->fetch_assoc();
            if (empty($row)) {
                $response = array('status' => "Вы ввели неверный email или пароль. Пожалуйста, попробуйте заново", 'stakes' => 0);
				echo json_encode($response);
            }
            $db_password = $row['password'];
        } else {
        	$response = array('status' => "Что-то пошло не так ;(", 'stakes' => 0);
			echo json_encode($response);
        }
        
        if($db_password === crypt($entered_password, $db_password)) {
            $_SESSION['id'] = $row['id'];
            $auctions = explode(";",$row['auctions']);
            $bests = explode(";",$row['best']);
            $stakes = $row['stakes'];
            $_SESSION['stakes'] = $stakes;
            if($_POST['rememberMe'] == 1){
                setcookie("sess_id", session_id(), time()+9999999);
            }
            $response = array('status' => 0, 'stakes' => $stakes);
			echo json_encode($response);
        } else {
            $response = array('status' => "Вы ввели неверный email или пароль. Пожалуйста, попробуйте заново", 'stakes' => 0);
			echo json_encode($response);
        }
    
?>