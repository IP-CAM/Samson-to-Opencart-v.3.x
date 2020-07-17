<?PHP
ini_set("memory_limit", "512M");
	require 'config/prod.php';                                           // Обязательный запрос на получение данных поставщика. 
	require 'bd/connect.php';                                           // Обязательное подключение к базе данных .
	if($data = json_decode($result, true)){                             // Декодирование получаемых данных и преобразование в массив.
		echo "Данные декодированы;" . "<br/>";
//----------------------Формирование запроса к базе данных:---------------------------------

		function parser(){
			
			echo "Формирование запроса к базе данных" . "<br/>";
		/* 	$query_new = "INSERT INTO `oc_product` (`product_id`, `sku`, `image`, `weight`, `subtract`, `date_modified`, `quantity`, `price`, `minimum`) VALUES ";
			foreach ($data as $k){
				foreach ($k as $kq => $v){
					if(($v["sku"]) !== NULL) {
						
					$sku = ($v["sku"]);
						$name = mysqli_real_escape_string($connect_bd, ($v["name"]));
						$description = mysqli_real_escape_string($connect_bd, ($v["description"]));
						$img = ($v["photo_list"][0]);
						$weight = ($v["weight"]);
						$subtract = 0;
						$date = "NOW()";
						$quantity = ($v["stock_list"][0]["value"]);
						$price = ($v["price_list"][1]["value"]); //0 - это стоковая цена, а 1 это рекомендуемая
						$minimum = ($v["package_list"][0]["value"]);
						
						$query_new .= "(" . $sku . ", " . $sku . ", '"  . $img . "', " . $weight . ", " .  $subtract . ", " . $date . ", " . $quantity . ", " . $price . ", " .  $minimum . ")";
						
						// foreach($v as $kq => $v1){
							// foreach($v1 as $kq1 => $v2){
								// foreach($v2 as $kq2 => $v3){
								// $quantity .= "<br/>" . $kq . " = " . $kq1 . " = " . $kq2 . " = " . $v3;
								// $price = ($v1["price_list"]);
								// $minimum = ($v1["package_list"]);
								// $query_new .= $quantity . ", " . $price . ", " .  $minimum . ")";
								// }
								
							// }
							
						// }
					}
					if ($kq+1 < count($k)) { 
						$query_new .= ", ";
					}
				}
			}
			$query_new .= " ON DUPLICATE KEY UPDATE `product_id` = VALUES(`product_id`), `sku` = VALUES(`sku`), `quantity` = VALUES(`quantity`), `image` = VALUES(`image`), `price` = VALUES(`price`), `weight` = VALUES(`weight`),`minimum` = VALUES(`minimum`), `subtract` = VALUES(`subtract`), `date_modified` = VALUES(`date_modified`);";
			
			$res_query_new = mysqli_query($connect_bd, $query_new);
				if (!$res_query_new) {
				 echo "Запрос: " . $query_new . "<br/>" . "Сообщение ошибки:" . "<br/>" . mysqli_error($connect_bd);
				} else{ 
						echo "Запрос к --OC_PRODUCT-- успешно сформирован и отправлен. Ошибоки отсутствуют;" . "<br/>";
						printf("Затронутые строки (UPDATE): %d\n", mysqli_affected_rows($connect_bd));
				} */

			print_r($query_new);
		
		}
		
	}

	parser();
	printf($data["meta"]["pagination"]["next"]);
	mysqli_close($connect_bd);
?>