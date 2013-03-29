<?
include 'include/config.php';
include 'tpl/head.htm';
$id= intval($_GET["id"]);
if ($id > 0) {
	$query = mysql_query("SELECT `name`, `price`, `desc`, `pic` FROM `catalog` WHERE `id`='".$id."'"); //запрос в БД о присутствии новости в БД
	if (mysql_num_rows($query) > 0) {
		$rw = mysql_fetch_assoc($query);
		echo '<div class="name">'.$rw["name"].'</div>'; //прикрепляем имя товара
		if (strlen($rw["pic"]) > 0) {
			$picj = json_decode($rw["pic"]);
			echo '<div class="pic"><img src="'.$picj[0].'" width="400"></div>'; //прикрепляем фото
		}
		echo '<div class="price">'.$rw["price"].' руб</div>'; //прикрепляем цену
		echo '<div>'.htmlspecialchars_decode($rw["desc"]).'</div>'; //прикрепляем описание
		echo '<div class="pagenav"><a href="/catalog.php?id='.($id-1).'">Предыдуший</a> <a href="/catalog.php">В каталог</a> <a href="/catalog.php?id='.($id+1).'">Следующий</a></div>';
	} else {
		echo "Товар не найден";
	}
} else {
	$query = mysql_query("SELECT `id`, `name`, `price`, `pic` FROM `catalog`");
	if (mysql_num_rows($query) > 0) {
		echo '<table width="100%">';
		echo '<tr><td>Название</td><td width="70">Цена в руб.</td><td width="100">Фото</td></tr>';
		while ($rw = mysql_fetch_assoc($query)) {
			if (strlen($rw["pic"]) > 0) {
				$picj = json_decode($rw["pic"]);
				if (is_array($picj)) {
					$pic = '<img src="'.$picj[0].'" border="0" width="50">';
				}
			}
			echo '<tr><td><a href="/catalog.php?id='.$rw["id"].'">'.$rw["name"].'</a></td><td>'.$rw["price"].'</td><td>'.$pic.'</td></tr>';
		}
		echo '</table>';
	} else {
		echo "Каталог пуст :(";
	}
}
include 'tpl/footer.htm';
?>