<?
if (isset($_GET['add'])) {
	if (isset($_POST['name'])) {
		$error = array(); //массив ошибок
		if (strlen($_POST['name']) < 5) { //проверка на количество символов в имени
			$error[] = 'Слишком короткое имя'; 
		}
		if (strlen($_POST['desc']) < 5) {
			$error[] = 'Слишком короткое описание';
		}
		if (strlen($_POST['news']) < 5) {
			$error[] = 'Слишком короткая новость';
		}
		if(count($error) > 0) { //проверка на присутствие ошибки
			foreach ($error as $val) { //отображение ошибок
				echo $val."<BR>";
			}
		} else {	//добавление в БД новой новости
			$query = mysql_query("INSERT INTO `news` (`name`, `desc`, `news`) VALUES ('".htmlspecialchars($_POST['name'])."', '".htmlspecialchars($_POST['desc'])."', '".htmlspecialchars($_POST['news'])."') "); //запись новости в БД
			if ($query) {
				echo "Ваша новость успешно добавлена.";
			} else {
				echo "Ваша новость некорректна";
			}
		}
	} else {
		$fname="Добавить";
		include 'tpl/newsform.htm';
	}
} elseif (isset($_GET['edit'])) {
	$id = intval($_GET['edit']);
	$error = array();
	if ($id > 0) {
		$query = mysql_query("SELECT `name`, `desc`, `news` FROM `news` WHERE `id`='".$id."'"); //запрос в БД о присутствии новости в БД
		if (mysql_num_rows($query) > 0) {
			$rw = mysql_fetch_assoc($query);
			if(isset($_POST['name'])) {
				if (strlen($_POST['name']) < 5) { //проверка на количество символов в имени
					$error[] = 'Слишком короткое имя'; 
				}
				if (strlen($_POST['desc']) < 5) {
					$error[] = 'Слишком короткое описание';
				}
				if (strlen($_POST['news']) < 5) {
					$error[] = 'Слишком короткая новость';
				}
				if (count($error) == 0) {
					$query = mysql_query("
						UPDATE 
							`news` 
						SET 
							`name`='".$_POST['name']."', `desc`='".$_POST['desc']."', `news`='".$_POST['news']."'
						WHERE
							`id`='".$id."'
						");
					if ($query) {
						echo "Новость изменена.";
					} else {
						$error[] = 'Ошибка при изменении новости.';
					}
				}
			} else {
				$name = $rw["name"];
				$desc = $rw["desc"];
				$news = $rw["news"]; //выводит в поле имя, описание и саму новость
				$fname = "Изменить";
				include 'tpl/newsform.htm';
			}
		} else {
			$error[] = "Неверный id новости";
		}
		if(count($error) > 0){
			foreach ($error as $val) { //отображение ошибок
				echo $val."<BR>";
			}
		}
	} else {
		echo "Неверный id новости";
	}
} elseif (isset($_GET['delete'])) {
	$id = intval($_GET['delete']);
	if ($id > 0) {
		$query = mysql_query("DELETE FROM `news` WHERE id='".$id."'");
		if ($query) {
			echo "Удалено.";
		} else {
			echo "Ошибка Базы Данных.";
		}
	} else {
		echo "Неверный id.";
	}
} else {
	echo "
	<script>
		function dlt(t){
			if (confirm(\"Вы точно хотите удалить новость?\")) {
 				location.href = t.href;
			}
		}
	</script>
	";
	echo '<a href="/admin.php?a=news&add">Добавить новость</a><br>';
	$query = mysql_query("SELECT `id`, `date`, `name` FROM `news`");
	if (mysql_num_rows($query) > 0) {
		echo '<table width="100%">';
		echo '<tr><td width="170">Дата</td><td>Название</td><td width="150">Действие</td></tr>';
		while ($rw = mysql_fetch_row($query)) {
			echo '<tr><td>'.$rw[1].'</td><td>'.$rw[2].'</td><td><a href="/admin.php?a=news&edit='.$rw[0].'">Изменить</a> <a href="/admin.php?a=news&delete='.$rw[0].'" onclick="dlt(this); return false;">Удалить</a></td></tr>';
		}
		echo '</table>';
	} else {
		echo "Новостей нет";
	}
}
?>