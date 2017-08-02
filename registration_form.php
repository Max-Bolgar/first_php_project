<?php 
$connect = mysql_connect("127.0.0.1:3306", "root", "");
mysql_select_db("ISHOP", $connect);
if (isset($_POST['send'])) {
	// Если пароли совпадают
	if ($_POST['password'] == $_POST['confirm_password']) {
		$login = $_POST['login'];
		$pass = $_POST['password'];
		$name = $_POST['name'];
		$email = $_POST['email'];
		$date = date("d-m-Y");
		$row = mysql_fetch_assoc(mysql_query("SELECT `login` FROM `Users` WHERE `login` LIKE"." '".$_POST['login']."'"));
		// Проверка свободен ли логин
		if ($row['login']) {
			exit("Логин <b>" . $_POST['login'] . "</b> уже используется. <br> <a href = \"/registration\"> Назад </a>");
		}
		$row = mysql_fetch_assoc(mysql_query("SELECT `email` FROM `Users` WHERE `email` LIKE"." '".$_POST['email']."'"));
		// Проверка свободен ли пароль
		if ($row['email']) {
			exit("Email <b>" . $_POST['email'] . "</b> уже используется. <br> <a href = \"/registration\"> Назад </a>");
		}
		// Если все ок то регистрирую нового пользователся
		mysql_query("INSERT INTO Users VALUES (null, \"$login\", \"$pass\", \"$name\", \"$email\", \"$date\")", $connect);
		echo "<script>alert(\"Успешно зарегестрировались\");</script>";
		exit("<meta http-equiv='refresh' content='0; url=index.php'>");
	}else {
		echo "<script>alert(\"Пароли должны совпадать\");</script>";
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Регистрация</title>
</head>
<body>
	<div class="registration">
		<a href = "index.php"> На главную </a>
		<form action="/registration" method="POST" class="reg">
			<h2>Форма регистрации</h2>
			<input type="text" name="name" placeholder="Ваше имя" required>
			<input type="text" name="login" placeholder="Ваш логин" required>
			<input type="email" name="email" placeholder="Ваша почта" required>
			<input type="password" name="password" placeholder="Ваш пароль" required>
			<input type="password" name="confirm_password" placeholder="Повторите пароль" required>
			<input type="submit" name="send" value="Отправить" >
		</form>
	</div>
</body>
</html>
<?php 