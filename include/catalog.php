<?
	switch ($_GET['p']) {
		case 'add':
			if (isset($_POST['name'])) {
				$error = array(); //массив ошибок
				$file=$_FILES["file"];
				if (is_array($file["type"]) && strlen($file["type"][0]) > 0) {
					foreach ($file["type"] as $kft => $ftv) {
						if ($ftv != "image/jpeg") {
							$error[] = "Файл #".($kft+1)." Не соответствует формату JPEG"; 
						}
					}
				}
				if (strlen($_POST['name']) < 5) { //проверка на количество символов в имени
					$error[] = 'Слишком короткое имя'; 
				}
				if (strlen($_POST['desc']) < 5) {
					$error[] = 'Слишком короткое описание';
				}
				if (!preg_match("/^\d+$/", $_POST['price']) && !preg_match("/^\d+\.\d+$/", $_POST['price'])) {
					$error[] = 'Некорректная цена';
				}
				if (count($error) == 0) {
					$files = array();
					$droot = $_SERVER["DOCUMENT_ROOT"];
					if (is_array($file["tmp_name"]) && strlen($file["tmp_name"][0]) > 0) {
						foreach ($file["tmp_name"] as $value) {
							$files[] = "/img/".time().rand(5,20).".jpg";
							move_uploaded_file($value, $droot.$files[(count($files)-1)]); //загрузка файла
						}
					}
					$JSON_file = json_encode($files);
					$query = mysql_query("	INSERT INTO 
												`catalog` (`name`, `price`, `desc`, `pic`) 
											VALUES 
											(	
												'".htmlspecialchars($_POST['name'])."', 
												'".htmlspecialchars($_POST['price'])."', 
												'".htmlspecialchars($_POST['desc'])."',
												'".$JSON_file."'
											)
											"); //запись товара в БД
					if ($query) {
						echo "Ваш товар успешно добавлен.";
					} else {
						echo "Ваш товар некорректен";
					}
				} else {
					foreach ($error as $val) { //отображение ошибок
						echo $val."<BR>";
					}
				}
			} else {
				$fname="Добавить";
				include 'tpl/catalogform.htm';
			}
			break;
		case 'edit':
			$id = intval($_GET['id']);
			$error = array();
			if ($id > 0) {
				$query = mysql_query("SELECT `name`, `price`, `desc`, `pic` FROM `catalog` WHERE `id`='".$id."'"); //запрос в БД о присутствии новости в БД
				if (mysql_num_rows($query) > 0) {
					$rw = mysql_fetch_assoc($query);
					if(isset($_POST['name'])) {
						$file=$_FILES["file"];
						if (is_array($file["type"]) && strlen($file["type"][0]) > 0) {
							foreach ($file["type"] as $kft => $ftv) {
								if ($ftv != "image/jpeg") {
									$error[] = "Файл #".($kft+1)." Не соответствует формату JPEG"; 
								}
							}
						}
						if (strlen($_POST['name']) < 5) { //проверка на количество символов в имени
							$error[] = 'Слишком короткое имя'; 
						}
						if (strlen($_POST['desc']) < 5) {
							$error[] = 'Слишком короткое описание';
						}
						if (!preg_match("/^\d+$/", $_POST['price']) && !preg_match("/^\d+\.\d+$/", $_POST['price'])) {
							$error[] = 'Некорректная цена';
						}
						if (count($error) == 0) {
							//echo"<pre>";print_r($_POST);echo"</pre>";
							$files = array();
							$droot = $_SERVER["DOCUMENT_ROOT"];
							if (is_array($file["tmp_name"]) && strlen($file["tmp_name"][0]) > 0) {
								foreach ($file["tmp_name"] as $value) {
									$files[] = "/img/".time().rand(5,20).".jpg";
									move_uploaded_file($value, $droot.$files[(count($files)-1)]); //загрузка файла
								}
							}
							if (is_array($_POST['files']) && count($_POST['files']) > 0) {
								$files = array_merge($files, $_POST['files']);
							}
							if (count($files) > 0) {
								$pic = ", `pic`='".json_encode($files)."' ";
							}
							//echo"<pre>";print_r($files);echo"</pre>";
							$query = mysql_query("
								UPDATE 
									`catalog` 
								SET 
									`name`='".htmlspecialchars($_POST['name'])."', `price`='".$_POST['price']."', `desc`='".htmlspecialchars($_POST['desc'])."' ".$pic."
								WHERE
									`id`='".$id."'
								");
							if ($query) {
								echo "Товар изменен.";
							} else {
								$error[] = 'Ошибка при изменении товара.';
							}
						}
					} else {
						$name = $rw["name"];
						$price = $rw["price"];
						$desc = $rw["desc"]; //выводит в поле имя, описание и сам товар
						$files = json_decode($rw["pic"]);
						$fname = "Изменить";
						include 'tpl/catalogform.htm';
					}
				} else {
					$error[] = "Неверный id товара";
				}
				if(count($error) > 0){
					foreach ($error as $val) { //отображение ошибок
						echo $val."<BR>";
					}
				}
			} else {
				echo "Неверный id товара";
			}
			break;
		case 'delete':
			$id = intval($_GET['id']);
				if ($id > 0) {
					$query = mysql_query("DELETE FROM `catalog` WHERE id='".$id."'");
					if ($query) {
						echo "Удалено.";
					} else {
						echo "Ошибка Базы Данных.";
					}
				} else {
					echo "Неверный id.";
				}
			break;
		default:
			echo "
				<script>
					function dlt(t){
						if (confirm(\"Вы точно хотите удалить товар?\")) {
			 				location.href = t.href;
						}
					}
				</script>
				";
				echo '<a href="/admin.php?a=catalog&p=add">Добавить товар</a><br>';
				$query = mysql_query("SELECT `id`, `name`, `price`, `pic` FROM `catalog`");
				if (mysql_num_rows($query) > 0) {
					echo '<table width="100%">';
					echo '<tr><td>Название</td><td width="70">Цена</td><td width="100">Фото</td><td width="150">Действие</td></tr>';
					while ($rw = mysql_fetch_assoc($query)) {
						if (strlen($rw["pic"]) > 0) {
							$picj = json_decode($rw["pic"]);
							if (is_array($picj)) {
								$pic = '<img src="'.$picj[0].'" border="0" width="50">';
							}
						}
						echo '<tr><td>'.$rw["name"].'</td><td>'.$rw["price"].'</td><td>'.$pic.'</td><td><a href="/admin.php?a=catalog&p=edit&id='.$rw["id"].'">Изменить</a> <a href="/admin.php?a=catalog&p=delete&id='.$rw["id"].'" onclick="dlt(this); return false;">Удалить</a></td></tr>';
					}
					echo '</table>';
				} else {
					echo "Каталог пуст :(";
				}
			break;
	}
?>