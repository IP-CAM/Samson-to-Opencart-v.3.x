<?PHP
	require 'config/cat.php';                                           // Обязательный запрос на получение данных поставщика. 
	require 'bd/connect.php';                                           // Обязательное подключение к базе данных .
	
	if($data = json_decode($result, true)){                             // Декодирование получаемых данных и преобразование в массив.
		echo "<br/>" . "Данные декодированы;" . "<hr/>";
//----------------------Формирование запроса к базе данных:---------------------------------

		
		$query_new = "INSERT INTO `oc_testtest` (`category_id`, `name`, `parent_id`) VALUES ";
		foreach ($data as $k){
			foreach ($k as $kq => $v){
				if(($v["id"]) !== NULL || ($v["name"]) !== NULL || ($v["parent_id"]) !== NULL){
					$query_new .= "(" . ($v["id"]) . ", " . "'" .  ($v["name"]) . "'" . ", " . ($v["parent_id"]) . ")";
				}
				if ($kq+1 < count($k)) { 
					$query_new .= ", ";
				}
			}
		}
		$query_new .= " ON DUPLICATE KEY UPDATE `category_id` = VALUES(`category_id`), `name` = VALUES(`name`), `parent_id` = VALUES(`parent_id`);";
		// $res_query_new = mysqli_query($connect_bd, $query_new);
			

		print_r($query_new);
	} 
 
?> 		
