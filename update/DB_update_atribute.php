<?PHP
ini_set("memory_limit", "512M");

	require "config/prod.php";  // Обязательный запрос на получение данных поставщика. 
	require 'bd/connect.php';   // Обязательное подключение к базе данных .

function parser(&$result, $connect_bd){
		if($data = json_decode($result, true)){  // Декодирование получаемых данных и преобразование в массив.
			echo "Данные декодированы;" . "<br/>";

			$url=($data["meta"]["pagination"]["next"]);
			echo "Формирование запроса к базе данных" . "<br/>";
			echo "Новый URL получен" . $url . "<br/>";
		 
		 	$arrayAttributeName = array();
			foreach ($data as $k){
				foreach ($k as $kq => $v){
					foreach(($v["facet_list"]) as $atribute_name){
							$arrayAttributeName[] = $atribute_name["name"];

					}		
				}
			}
		}
		$finalyArrayAttributeName = array_unique($arrayAttributeName); 

		$query = "INSERT INTO `oc_attribute_description` (`name`) VALUES ";
		$i = 0;
		foreach($finalyArrayAttributeName as $finalyAttributeName){
			if($finalyAttributeName){

				$query .= "('" .  mysqli_real_escape_string($connect_bd, $finalyAttributeName) . "')" ; 
				
				$i++ ;
			}
			if($i < count($finalyArrayAttributeName)){
					$query .= ", ";

			}

		}


	$query .= " ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);";

	$res_query = mysqli_query($connect_bd, $query);
		if (!$res_query) {
		 echo "Запрос: " . $query_new . "<br/>" . "Сообщение ошибки:" . "<br/>" . mysqli_error($connect_bd);
		} else{ 
				echo "Запрос к --oc_manufacturer-- успешно сформирован и отправлен. Ошибоки отсутствуют;" . "<br/>";
				printf("Затронутые строки (UPDATE): %d\n", mysqli_affected_rows($connect_bd));
			}
				if($url){	
						
						$url_p = curl_init();
						$arHeaderList = array();
						$arHeaderList[] = 'Accept: application/json';
						$arHeaderList[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36 OPR/65.0.3467.78';

						curl_setopt_array($url_p, array(
							CURLOPT_URL => $url,
							CURLOPT_HTTPHEADER => $arHeaderList,
							CURLOPT_FOLLOWLOCATION => true,
							CURLOPT_RETURNTRANSFER => true,
							CURLOPT_FRESH_CONNECT => true
						));
						$result = curl_exec($url_p);
						curl_close($url_p); 
							if($result){
								echo "Новое подключение к новому URL состоялось <br/>";
							} else {
								echo "Новое подключение НЕ состоялось, но обработано <br/>";
							}
						return $result;
					} else{ echo "<br/> Все данные от поставщика получены";
							return ($result = false);		
					}		
}

		
		while($result){
		parser($result, $connect_bd);
		}

			

	mysqli_close($connect_bd);

?>