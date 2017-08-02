<?php 
session_start();
$connect = mysql_connect("127.0.0.1:3306", "root", "");
mysql_select_db("ISHOP", $connect);
$row = mysql_query("SELECT `id_product`, `title`, `description`, `title_categorie` FROM `Products` WHERE `link_categorie` = '$_SERVER[REQUEST_URI]'");
$data = fetch($row);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<link rel="shortcut icon" href="favicon.png" />
	<link rel="stylesheet" href="../libs/bootstrap/bootstrap-grid-3.3.1.min.css" />
	<link rel="stylesheet" href="../css/fonts.css" />
	<link rel="stylesheet" href="../css/main.css" />
	<title>Document</title>
</head>
<body>
	<?php 
	ini_set('display_errors','On');
	error_reporting('E_ALL');
	?>
	<div class="catalog_title product">
		<?php foreach ($data as $item) {
			$title_categorie = $item[title_categorie];
			$goods_id[] = $item[id_product];
			$goods[] = $item[title];
			$goods_description[] = $item[description];
		} ?>
		<form action="<?php echo $_SERVER['REQUEST_URI'];?>" method	= "POST">
			<button name="add_tov">Добавить товар</button>
			<?php if (isset($_POST['add_tov'])): ?>
				<input type="text" placeholder="Название товара" name="new_tov">
				<input type="text" placeholder="Описание товара" name="new_desc">
				<button name="send_new_tov">ОК</button>
			<?php endif ?>

			<button name="del_tov">Удалить товар</button>
			<?php if (isset($_POST['del_tov'])): ?>
				<input type="text" placeholder="Название товара" name="delete">
				<button name="del_tov_new_tov">ОК</button>
			<?php endif ?>

			<button name="chng_tov">Редактировать товар</button>
			<?php if (isset($_POST['chng_tov'])): ?>
				<input type="text" placeholder="Старое название" name="chng_old_title">
				<input type="text" placeholder="Новое название" name="chng_new_title">
				<input type="text" placeholder="Новое описание" name="chng_new_desc">
				<button name="chng_tov_send">ОК</button>
			<?php endif ?>

		</form>
		<h2>Товары категории " <?php echo $title_categorie; ?> "</h2>
	</div>
	<div class="items">
		<div class="col-md-3">
			<h2>ID товара</h2>
			<div class="products_item">
				<?php foreach ($data as $item): ?>
					<h2><?php echo $item[id_product]; ?></h2>
				<?php endforeach ?>
			</div>
		</div>
		<div class="col-md-3">
			<h2>Название товара</h2>
			<div class="products_item">
				<?php foreach ($data as $item): ?>
					<h2><?php echo $item[title]; ?></h2>
				<?php endforeach ?>
			</div>
		</div>
		<div class="col-md-3">
			<h2>Описание товара</h2>
			<div class="products_item">
				<?php foreach ($data as $item): ?>
					<h2><?php echo $item[description]; ?></h2>
				<?php endforeach ?>
			</div>
		</div>
		<div class="col-md-3">
			<h2>Категория</h2>
			<div class="products_item">
				<?php foreach ($data as $item): ?>
					<h2><?php echo $item[title_categorie]; ?></h2>
				<?php endforeach ?>
			</div>
		</div>
	</div>
</body>
</html>
<?php 
ini_set('display_errors','Off');
?>
<?php 
//Добавление нового товара
if (isset($_POST['send_new_tov'])) {
		// Проверка на занятость
	$row = mysql_fetch_assoc(mysql_query("SELECT `title` FROM `Products` WHERE `title` LIKE"." '".$_POST['new_tov']."'"));
	if ($row['title']) {
		exit("<script>alert(\"Товар " . $_POST['new_tov'] . " уже существует. \");</script>");
	}else{
		$link = translit($_POST['new_tov']);
		$link = $_SERVER['REQUEST_URI'] . '/' . $link;
		mysql_query("INSERT INTO `Products` VALUES (null, null, \"$_POST[new_tov]\", \"$_POST[new_desc]\", \"$link\", \"$title_categorie\", \"$_SERVER[REQUEST_URI]\")", $connect);
		exit("<meta http-equiv='refresh' content='0; url=$_SERVER[REQUEST_URI]'>");
	}
}
//Удаление товара
if (isset($_POST['del_tov_new_tov'])) {
	$row = mysql_fetch_assoc(mysql_query("SELECT `title` FROM `Products` WHERE `title` LIKE"." '".$_POST['delete']."'"));
	if (!$row['title']) {
		exit("<script>alert(\"Товара " . $_POST['delete'] . " не существует. \");</script>");
	}else{
		mysql_query("DELETE FROM `Products` WHERE `title` = '$_POST[delete]'", $connect);
		exit("<meta http-equiv='refresh' content='0; url=$_SERVER[REQUEST_URI]'>");
	}
}
//Редактирование товара
if (isset($_POST['chng_tov_send'])) {
		$link = translit($_POST['new_tov']);
		$link = $_SERVER['REQUEST_URI'] . '/' . $link;
		$rowOld = mysql_fetch_assoc(mysql_query("SELECT `title` FROM `Products` WHERE `title` LIKE"." '".$_POST['chng_old_title']."'"));
		$rowNew = mysql_fetch_assoc(mysql_query("SELECT `title` FROM `Products` WHERE `title` LIKE"." '".$_POST['chng_new_title']."'"));
		if (!$rowOld['title']) {
			exit("<script>alert(\"Товар " . $_POST['chng_old_title'] . " не существует. \");</script>");
		}elseif ($rowNew['title']) {
			exit("<script>alert(\"Товар " . $_POST['chng_new_title'] . " уже существует. \");</script>");
		}else{
			mysql_query("UPDATE `Products` SET `title`= '$_POST[chng_new_title]',`link`='$link', `description`='$_POST[chng_new_desc]' WHERE `title` = '$_POST[chng_old_title]'", $connect);
			exit("<meta http-equiv='refresh' content='0; url=$_SERVER[REQUEST_URI]'>");
		}
	}
?>