<?php 
session_start();
$connect = mysql_connect("127.0.0.1:3306", "root", "");
mysql_select_db("ISHOP", $connect);
$row = mysql_query("SELECT `title_categorie`, `link` FROM `Categories`");
$titleData = fetch($row);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Catalog</title>
</head>
<body>
	<div class="catalog_title">
		<div class="col-md-12">
			<?php // Функционалиные кнопки ?>
			<form action="/catalog" method="POST">
				<button name="add">Добавить категорию</button>
				<?php if (isset($_POST['add'])): ?>
					<input type="text" placeholder="Название категории" name="new_cat">
					<button name="send_new">ОК</button>
				<?php endif ?>
				<button name="delete">Удалить категорию</button>
				<?php if (isset($_POST['delete'])): ?>
					<input type="text" placeholder="Название категории" name="del_cat">
					<button name="send_del">ОК</button>
				<?php endif ?>
				<button name="change">Изменить категорию</button>
				<?php if (isset($_POST['change'])): ?>
					<input type="text" placeholder="Старое название" name="old_title">
					<input type="text" placeholder="Новое название" name="new_title">
					<button name="send_title">ОК</button>
				<?php endif ?>
			</form>

			<h2>Категории товаров<h2>
			</div>
			<div class="categ">
				<div class="col-md-12">
					<?php // Вывожу все названия категорий с БД ?>
					<?php foreach ($titleData as $item): ?>
						<a href="<?php echo $item[link] ?>"><?php echo $item[title_categorie] ?></a>
					<?php endforeach ?>
				</div>
			</div>
		</div>
	</body>
	</html>
	<?php 
	// Добавление новой категории
	if (isset($_POST['send_new'])) {
		// Проверка на занятость
		$row = mysql_fetch_assoc(mysql_query("SELECT `title_categorie` FROM `Categories` WHERE `title_categorie` LIKE"." '".$_POST['new_cat']."'"));
		if ($row['title_categorie']) {
			exit("<script>alert(\"Категория " . $_POST['new_cat'] . " уже существует. \");</script>");
		}else{
			$link = translit($_POST['new_cat']);
			$link = '/catalog/' . $link;
			mysql_query("INSERT INTO `Categories` VALUES (null, \"$_POST[new_cat]\", \"$link\")", $connect);
			exit("<meta http-equiv='refresh' content='0; url=/catalog'>");
		}
	}
	// Удаление категории
	if (isset($_POST['send_del'])) {
		$row = mysql_fetch_assoc(mysql_query("SELECT `title_categorie` FROM `Categories` WHERE `title_categorie` LIKE"." '".$_POST['del_cat']."'"));
		if (!$row['title_categorie']) {
			exit("<script>alert(\"Категории " . $_POST['del_cat'] . " не существует. \");</script>");
		}else{
			mysql_query("DELETE FROM `Categories` WHERE `title_categorie` = '$_POST[del_cat]'", $connect);
			exit("<meta http-equiv='refresh' content='0; url=/catalog'>");
		}
	}
	// Изменине названия категории
	if (isset($_POST['send_title'])) {
		$link = translit($_POST['new_title']);
		$link = '/catalog/' . $link;
		$rowOld = mysql_fetch_assoc(mysql_query("SELECT `title_categorie` FROM `Categories` WHERE `title_categorie` LIKE"." '".$_POST['old_title']."'"));
		$rowNew = mysql_fetch_assoc(mysql_query("SELECT `title_categorie` FROM `Categories` WHERE `title_categorie` LIKE"." '".$_POST['new_title']."'"));
		if (!$rowOld['title_categorie']) {
			exit("<script>alert(\"Категории " . $_POST['old_title'] . " не существует. \");</script>");
		}elseif ($rowNew['title_categorie']) {
			exit("<script>alert(\"Категория " . $_POST['new_title'] . " уже существует. \");</script>");
		}else{
			mysql_query("UPDATE `Categories` SET `title_categorie`= '$_POST[new_title]',`link`='$link' WHERE `title_categorie` = '$_POST[old_title]'", $connect);
			exit("<meta http-equiv='refresh' content='0; url=/catalog'>");
		}
	}
	?>
