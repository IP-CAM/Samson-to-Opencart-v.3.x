<?PHP
	ini_set("memory_limit", "512M");
	require 'config/prod.php';                                           // Обязательный запрос на получение данных поставщика. 
	require 'bd/connect.php';                                           // Обязательное подключение к базе данных .
	
	if($data = json_decode($result, true)){                             // Декодирование получаемых данных и преобразование в массив.
		echo "Данные декодированы;" . "<br/>";
		echo "Формирование запроса к базе данных" . "<br/>";
//----------------------Формирование запроса к базе данных:---------------------------------

		
		$query_new = "INSERT INTO `oc_attribute_description` (`product_id`, `name`) VALUES ";
		
		foreach ($data as $k){
			foreach ($k as $kq => $v){
				if(($v["sku"]) !== NULL) {
		
					$attribute_name_0 = ($v["facet_list"][0]["name"]) ; 
					$attribute_name_1 = ($v["facet_list"][1]["name"]) ;
					$attribute_name_2 = ($v["facet_list"][2]["name"]) ;
					$attribute_name_3 = ($v["facet_list"][3]["name"]) ;
					$attribute_name_4 = ($v["facet_list"][4]["name"]) ;
					$attribute_name_5 = ($v["facet_list"][5]["name"]) ;
					$attribute_name_6 = ($v["facet_list"][6]["name"]) ;
					$attribute_name_7 = ($v["facet_list"][7]["name"]) ;
					$attribute_name_8 = ($v["facet_list"][8]["name"]) ;
					$attribute_name_9 = ($v["facet_list"][9]["name"]) ;
					
					// $attribute_value_0 = ($v["facet_list"][0]["value"]) ;
					// $attribute_value_1 = ($v["facet_list"][1]["value"]) ;
					// $attribute_value_2 = ($v["facet_list"][2]["value"]) ;
					// $attribute_value_3 = ($v["facet_list"][3]["value"]) ;
					// $attribute_value_4 = ($v["facet_list"][4]["value"]) ;
					// $attribute_value_5 = ($v["facet_list"][5]["value"]) ;
					// $attribute_value_6 = ($v["facet_list"][6]["value"]) ;
					// $attribute_value_7 = ($v["facet_list"][7]["value"]) ;
					// $attribute_value_8 = ($v["facet_list"][8]["value"]) ;
					// $attribute_value_9 = ($v["facet_list"][9]["value"]) ;
					
					$name = mysqli_real_escape_string($connect_bd, ($v["name"]));
					$sku = ($v["sku"]);
					
					// $description = mysqli_real_escape_string($connect_bd, ($v["description"]));
					// $img = ($v["photo_list"][0]);
					// $weight = ($v["weight"]);
					// $subtract = 0;
					// $date = "NOW()";
					// $quantity = ($v["stock_list"][0]["value"]);
					// $price = ($v["price_list"][1]["value"]);
					// $minimum = ($v["package_list"][0]["value"]);
					
					//добавить допонительные категории в строку черезе if если в facet_list пусто то не добавлять
						  $query_new .= "(" . $sku  . ", '" . $attribute_name_0 . "')";
					
					if($attribute_name_1 !== NULL){
						$query_new .= ", (" . $sku  . ", '" . $attribute_name_1 . "')";
					}
					if($attribute_name_2 !== NULL){
						$query_new .= ", (" . $sku  . ", '" . $attribute_name_2 . "')";
					}
					if($attribute_name_3 !== NULL){
						$query_new .= ", (" . $sku  . ", '" . $attribute_name_3 . "')";
					}
					if($attribute_name_4 !== NULL){
						$query_new .= ", (" . $sku  . ", '" . $attribute_name_4 . "')";
					}
					if($attribute_name_5 !== NULL){
						$query_new .= ", (" . $sku  . ", '" . $attribute_name_5 . "')";
					}
					if($attribute_name_6 !== NULL){
						$query_new .= ", (" . $sku  . ", '" . $attribute_name_6 . "')";
					}
					if($attribute_name_7 !== NULL){
						$query_new .= ", (" . $sku  . ", '" . $attribute_name_7 . "')";
					}
					if($attribute_name_8 !== NULL){
						$query_new .= ", (" . $sku  . ", '" . $attribute_name_8 . "')";
					}
					if($attribute_name_9 !== NULL){
						$query_new .= ", (" . $sku  . ", '" . $attribute_name_9 . "')";
					}
					/* foreach($v as $kq => $v1){
						foreach($v1 as $kq1 => $v2){
							foreach($v2 as $kq2 => $v3){
							$quantity .= "<br/>" . $kq . " = " . $kq1 . " = " . $kq2 . " = " . $v3;
							$price = ($v1["price_list"]);
							$minimum = ($v1["package_list"]);
							$query_new .= $quantity . ", " . $price . ", " .  $minimum . ")";
							}
							
						}
						
					} */
				}
				if ($kq+1 < count($k)) { 
					$query_new .= ", ";
				}
			}
		}
		$query_new .= " ON DUPLICATE KEY UPDATE `product_id` = VALUES(`product_id`), `name` = VALUES(`name`);";
		
		$res_query_new = mysqli_query($connect_bd, $query_new);
			if (!$res_query_new) {
			 echo "Запрос: " . $query_new . "<br/>" . "Сообщение ошибки:" . "<br/>" . mysqli_error($connect_bd);
			} else{ 
					echo "Запрос к --OC_PRODUCT_TO_CATEGORY-- успешно сформирован и отправлен. Ошибоки отсутствуют;" . "<br/>";
					printf("Затронутые строки (UPDATE): %d\n", mysqli_affected_rows($connect_bd));
			}

		// print_r($quantity);
	
	} 
	
	mysqli_close($connect_bd);

?>