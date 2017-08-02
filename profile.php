<?php 
$connect = mysql_connect("127.0.0.1:3306", "root", "");
mysql_select_db("ISHOP", $connect);
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Profile</title>
</head>
<body>
	<div class="main_page no_margin"><h3>Информация о пользователе</h3></div>
	<div class="info">
		<form class="profile" action="/profile" method="POST">
			<h3>Логин: </h3> 
			<b><?php echo $_SESSION['USER_LOGIN']; ?></b> <button name="change_log">Изменить</button>
			<?php if (isset($_POST['change_log'])):?>
				<input class="chng" type="text" placeholder="Введите новый логин" name="new_log">
				<input class = "btn" type="submit" name="send_log" value="Отправить">
			<?php endif ?>

			<h3>Имя: </h3> 
			<b><?php echo $_SESSION['USER_NAME']; ?></b> </b> <button name="change_name">Изменить</button>
			<?php if(isset($_POST['change_name'])): ?>
				<input class="chng" type="text" placeholder="Введите новое имя" name="new_name">
				<input class = "btn" type="submit" name="send_name" value="Отправить">
			<?php endif ?>

			<h3>Е-mail адрес:	</h3> 
			<b><?php echo $_SESSION['USER_EMAIL']; ?></b> <button name="change_email">Изменить</button>
			<?php if(isset($_POST['change_email'])): ?>
				<input class="chng" type="text" placeholder="Введите новый email" name="new_email">
				<input class = "btn" type="submit" name="send_email" value="Отправить">
			<?php endif ?>

			<div class="date">
				<h3>Дата регистрации:	</h3> 
				<b><?php echo $_SESSION['USER_DATE']; ?></b> 
				<p>Вы с нами уже: <span><?php 
					$d1 = strtotime($_SESSION['USER_DATE']);
					$d2 = strtotime(date("d-m-Y"));
					$diff = $d2 - $d1;
					$diff = floor($diff/(60*60*24));
					echo  $diff?>  дней(я)</span></p>
				</div>
			</form>
		</div>
	</body>
	</html>
	<?php
	if (isset($_POST['send_log'])) {
		// Проверка на занятость
		$row = mysql_fetch_assoc(mysql_query("SELECT `login` FROM `Users` WHERE `login` LIKE"." '".$_POST['new_log']."'"));
		if ($row['login']) {
			exit("<script>alert(\"Логин " . $_POST['new_log'] . " уже занят. \");</script>");
		} else {
			mysql_query("UPDATE `Users` SET `login`=". "'" .$_POST['new_log']."'"." WHERE `login`="."'".$_SESSION['USER_LOGIN']."'", $connect);
			$_SESSION['USER_LOGIN'] = $_POST['new_log'];
	// Перезагружаю страницу что бы отобразились новые данные из сессии| header() - не работает
			exit("<meta http-equiv='refresh' content='0; url=/profile'>");
		}
	}
	if (isset($_POST['send_name'])) {
		mysql_query("UPDATE `Users` SET `name`=". "'" .$_POST['new_name']."'"." WHERE `name`="."'".$_SESSION['USER_NAME']."'", $connect);
		$_SESSION['USER_NAME'] = $_POST['new_name'];
		exit("<meta http-equiv='refresh' content='0; url=/profile'>");
	}
	if (isset($_POST['send_email'])) {
		// Проверка на занятость
		$row = mysql_fetch_assoc(mysql_query("SELECT `email` FROM `Users` WHERE `email` LIKE"." '".$_POST['new_email']."'"));
		if ($row['email']) {
			exit("<script>alert(\"Email " . $_POST['new_email'] . " уже занят. \");</script>");
		}else{
			mysql_query("UPDATE `Users` SET `email`=". "'" .$_POST['new_email']."'"." WHERE `email`="."'".$_SESSION['USER_EMAIL']."'", $connect);
			$_SESSION['USER_EMAIL'] = $_POST['new_email'];
			exit("<meta http-equiv='refresh' content='0; url=/profile'>");
		}
	}
	?>