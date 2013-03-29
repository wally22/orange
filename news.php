<?
include 'include/config.php';
include 'tpl/head.htm';
$id= intval($_GET["id"]);
if ($id > 0) {
	$query = mysql_query("SELECT `id`, DATE_FORMAT(`date`, '%d.%m.%Y') as time, `name`, `news` FROM `news` WHERE `id`='".$id."'"); //запрос в БД о присутствии новости в БД
	if (mysql_num_rows($query) > 0) {
		$rw = mysql_fetch_assoc($query);
		echo '<div class="name">'.$rw["name"].'</div>'; //прикрепляем имя новости
		echo '<div class="date">'.$rw["time"].'</div>'; //прикрепляем дату
		echo '<div>'.htmlspecialchars_decode($rw["news"]).'</div>'; //прикрепляем описание
	} else {
		echo "Товар не найден";
	}
} else {
	$query = mysql_query("SELECT `id`, DATE_FORMAT(`date`, '%d.%m.%Y') as time, `name`, `desc` FROM `news` ORDER BY `date` DESC");
	if (mysql_num_rows($query) > 0) {
		while ($rw = mysql_fetch_assoc($query)) {
			echo '<div class="news">';
			echo '<div style="text-decoration: left;font-size:18px;"><a href="news.php?id='.$rw[id].'">'.$rw["name"].'</a></div>';
			echo '<div class="date">'.$rw["time"].'</div>';
			echo '<div class="desc">'.htmlspecialchars_decode($rw["desc"]).'</div>';
			echo '</div>';		
		}
	} else {
		echo "Каталог пуст :(";
	}
}
include 'tpl/footer.htm';
?>