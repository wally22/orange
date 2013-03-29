<?
	include 'include/config.php';
	$auth = false;																//переменная аутх-авторизован ли пользователь
	$ses = (isset($_COOKIE['ses']))?htmlspecialchars($_COOKIE['ses']):false;	//переменная сессия из куки
	$user = (isset($_POST['user']))?htmlspecialchars($_POST['user']):false;		//переменная email из формы авторизации
	$pass = (isset($_POST['pass']))?htmlspecialchars($_POST['pass']):false;		//переменная пароль из формы авторизации
	$remember = ($_POST['remember'] == 1)?(time()+60*60*24*365):(time()+60*60);	//если переменная запомнить=1, то нынешнее время+год, если нет+час
	if ($ses OR ($user AND $pass)) {											//проверка наличия сессии или попытка авторизации пользователя
		if ($ses) {
			$query = mysql_query("SELECT `id` FROM `users` WHERE `ses`='".$ses."'"); //запрос БД на наличие сессии
			if (mysql_num_rows($query) > 0) {
				$auth = true;
			}
		} else {
			$query = mysql_query("SELECT `id` FROM `users` WHERE `email`='".$user."' AND `pass`=MD5(".$pass.")");
			if (mysql_num_rows($query) > 0) {
				$row = mysql_fetch_row($query);
				$ses = md5($row[0].time());
				mysql_query("UPDATE `users` SET `ses`='".$ses."' WHERE `id`='".$row[0]."'"); //обновление БД
				setcookie('ses', $ses, $remember);								//устанавливаем пользователю куки
				$auth = true;
			} else {
				$error = true;
			}
		}
	}
	if ($_GET['a'] == "exit") {				//выход
		setcookie('ses','',time()-60*60);	//удаление куки
		$auth = false;						//вывод в меню авторизации
	}
	include 'tpl/head.htm';
	if (!$auth) {
		include 'tpl/loginform.htm';
	} else {
		include 'tpl/adminmenu.htm';
		switch ($_GET['a']) {
			case 'catalog':
				include 'include/catalog.php';
				break;
			case 'news':
				include 'include/news.php';
				break;
			
			default:
				echo '<p class="zagal">Добро пожаловать в панель администрирования.</p>';
				break;
		}
	}
	include 'tpl/footer.htm';
?>