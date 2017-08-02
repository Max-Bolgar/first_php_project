<?php 
// Используя ранее спроектированную базу
// данных для интернет магазина реализовать
// добавление , редактирование, удаление
// категорий, производителей, товаров.
// Спроектировать таблицу для хранения
// заказов
// Спроектировать личный кабинет
// пользователя
session_start();
$connect = mysql_connect("127.0.0.1:3306", "root", "");
mysql_select_db("ISHOP", $connect);
$row = mysql_query("SELECT `link` FROM `Categories`");
$titleLink = fetch($row);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>IShop</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="shortcut icon" href="favicon.png" />
	<link rel="stylesheet" href="libs/bootstrap/bootstrap-grid-3.3.1.min.css" />
	<link rel="stylesheet" href="css/fonts.css" />
	<link rel="stylesheet" href="css/main.css" />
</head>
<body>
	<div class="header">
		<div class="col-md-6"><a class="to_main" href="/">На главную</a><a href="/"><h1><span>I</span>Shop</h1></a></div>
		<div class="col-md-6">
			<?php 
			// Если нажали кнопку "Вход"
			if (isset($_POST['log'])) {
				// Проверка на ввод данныъ
				if ((!$_POST['login'] || !$_POST['password'])) {
					echo "<script>alert(\"Введите данные в форму\");</script>";
					// Если не верен то флаг - 0
					$_SESSION['USER_LOG_IN'] = 0;
				}
				// Проверка на валидность ввода данныъ
				$row = mysql_fetch_assoc(mysql_query("SELECT `password` FROM `Users` WHERE `login` LIKE"." '".$_POST['login']."'"));
				if ($row['password'] !== $_POST['password']) {
					echo "<script>alert(\"Неверный логин или пароль\");</script>";
					$_SESSION['USER_LOG_IN'] = 0;
				} else {
					// Если все ок то беру данные из БД и записываю их в сессии
					$row = mysql_fetch_assoc(mysql_query("SELECT `id`, `name`, `email`, `login`, `date` FROM `Users` WHERE `login` LIKE"." '".$_POST['login']."'"));
					$_SESSION['USER_ID'] = $row['id'];
					$_SESSION['USER_NAME'] = $row['name'];
					$_SESSION['USER_EMAIL'] = $row['email'];
					$_SESSION['USER_LOGIN'] = $row['login'];
					$_SESSION['USER_DATE'] = $row['date'];
					// Ставлю флаг - 1
					$_SESSION['USER_LOG_IN'] = 1;
				}
			}
			?>
			<?php // Если вход выполнен то вывожу форму с инфой ?>
			<?php if ($_SESSION['USER_LOG_IN'] == 1): ?>
				<form action="index.php" method="POST">
					<h2><?php  echo "Здравствуйте, " . $_SESSION['USER_NAME']; ?></h2><br>
					<a href="/profile">Личный кабинет</a><br><br>
					<button name="exit">EXIT</button>
					<?php if (isset($_POST['exit'])) {
						session_destroy();
						exit("<meta http-equiv='refresh' content='0; url=$_SERVER[PHP_SELF]'>");
					} ?>
				</form>
			<?php else: // Если вход не выполнен то отображается форма фхода?>
				<form action="index.php" method="POST">
					<h2>Вход</h2>
					<input type = "text" name = "login" placeholder = "Логин">
					<input type = "password" name = "password" placeholder = "Пароль">
					<input type="submit" value="Вход" name="log" class="spec">
					<a href="/registration">Регистрация</a>
				</form>
			<?php endif ?>
		</div>
	</div>
	<?php 
	foreach ($titleLink as $item) {
		if($_SERVER['REQUEST_URI'] == $item[link]) {
			include_once 'products.php';
			exit();
		}
	}
	?>
	<?php if($_SERVER['REQUEST_URI'] == '/'  || $_SERVER['REQUEST_URI'] == '/index.php'): ?>
		<div class="main_page">
			<a href="/catalog"><h3>Каталог товаров</h3></a>
		</div>
	<?php elseif($_SERVER['REQUEST_URI'] == '/catalog'): include_once 'catalog.php';?>
	<?php elseif($_SERVER['REQUEST_URI'] == '/profile'): include_once 'profile.php';?>
	<?php elseif($_SERVER['REQUEST_URI'] == '/registration'): include_once 'registration_form.php';?>
	<?php else: echo "404 NOT FOUND";?>
	<?php endif; ?>
	<div id="clear"></div>
	<div id="rasporka"></div>
	<footer>
		<p>Designed by Bolgar Max | August 2017</p>
	</footer>
</body>
</html>
<?php 
function fetch($res){
	while($row=mysql_fetch_assoc($res)){$data[] = $row;}
	return $data;
}
	function translit($s) {
  $s = (string) $s; // преобразуем в строковое значение
  $s = strip_tags($s); // убираем HTML-теги
  $s = str_replace(array("\n", "\r"), " ", $s); // убираем перевод каретки
  $s = preg_replace("/\s+/", ' ', $s); // удаляем повторяющие пробелы
  $s = trim($s); // убираем пробелы в начале и конце строки
  $s = function_exists('mb_strtolower') ? mb_strtolower($s) : strtolower($s); // переводим строку в нижний регистр (иногда надо задать локаль)
  $s = strtr($s, array('а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'e','ж'=>'j','з'=>'z','и'=>'i','й'=>'y','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'h','ц'=>'c','ч'=>'ch','ш'=>'sh','щ'=>'shch','ы'=>'y','э'=>'e','ю'=>'yu','я'=>'ya','ъ'=>'','ь'=>''));
  $s = preg_replace("/[^0-9a-z-_ ]/i", "", $s); // очищаем строку от недопустимых символов
  $s = str_replace(" ", "_", $s); // заменяем пробелы знаком минус
  return $s; // возвращаем результат

}
?>
