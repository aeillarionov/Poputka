<?php
	include "sessions.php";
	session_start();
    function cleanInput($input){
        $input = stripslashes($input);
        $input = htmlspecialchars($input);
        $input = trim($input);
        return $input;
    }
    
    $email = $_POST['email'];
    $email = cleanInput($email);
    $entered_password = $_POST['password'];
    $phone = $_POST['phone'];
    
    $db_des = new mysqli("localhost","root","root","game");
    if (mysqli_connect_errno()) {
        $response = array('status' => "Не удалось подключиться к базе данных", 'stakes' => 0);
		echo json_encode($response);
    }
    
    $data = $db_des->query("SELECT `id` FROM `players` WHERE `email`=\"$email\"");
    if($data){
    	$row = $data->fetch_assoc();
    	if($row['id']){
    		$response = array('status' => "На электронный адрес: ".$email." уже регистрировались", 'stakes' => 0);
			echo json_encode($response);
    	} else {
    		$data = $db_des->query("SELECT `id` FROM `players`");
			$i=0;
			if ($data){
				while ($row = $data->fetch_assoc()) {
					$ids[$i] = $row['id'];
					$i++;
				}
			} else {
				
			}
			$maxid = max($ids);
			$id = ++$maxid;
			
			$salt = "";
			$salt_chars = array_merge(range('A','Z'), range('a','z'), range(0,9));
			for($i=0; $i < 22; $i++) {
			  $salt .= $salt_chars[array_rand($salt_chars)];
			}
			$hashed_password = crypt($entered_password, sprintf('$2a$%02d$', 7) . $salt);

			$result = $db_des->query("INSERT INTO `game`.`players` (`id` , `email`, `password`, `phone`) VALUES (\"$id\", \"$email\", \"$hashed_password\", \"$phone\")");
			if ($result == 'TRUE') {
				$_SESSION['id'] = $id;
				$_SESSION['stakes'] = 0;
				setcookie("sess_id", session_id(), time()+9999999);
				$response = array('status' => 0, 'stakes' => 0);
				echo json_encode($response);
			} else {
				$response = array('status' => "При регистрации произошла ошибка. Пожалуйста, попробуйте заново.", 'stakes' => 0);
				echo json_encode($response);
			}
    	}
    }
?>