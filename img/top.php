<?PHP
	set_time_limit(0);
	$curl = curl_init();
	$arHeaderList = array();
	$arHeaderList[] = 'Accept: application/json';
	$arHeaderList[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36 OPR/65.0.3467.78';


		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://api.samsonopt.ru/v1/assortment/?api_key=0e875fd887c442755f6d06f04301562f',
			CURLOPT_HTTPHEADER => $arHeaderList,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_FORBID_REUSE => true
			));
		  
		$result = curl_exec($curl);
		curl_close($curl);
			if($result){
					echo "<hr/>" . "Стартовое подключение <br/>" . "Данные от поставщика получены;" . "<br/>";
			} else { echo "<hr/>" . "Данные от поставщика не получены;" . "<br/>";
			}
		
	function img_download(&$result){
	
		echo "Начало выполнения функции <br/>";

		if($data = json_decode($result, true)){     // Декодирование получаемых данных и преобразование в массив.
			$url=($data["meta"]["pagination"]["next"]);
			echo "Новый URL получен" . $url . "<br/>";
			
			foreach ($data as $k){
				foreach ($k as $kq => $v){
					foreach(($v["photo_list"]) as $key => $url_img){
						
						$name = (basename($url_img));
						$path = "product_photo/" . $name;
						
						$ch = curl_init($url_img);
						curl_setopt($ch, CURLOPT_HTTPHEADER, 0);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						$data = curl_exec($ch);
						curl_close($ch);
						
						if(!file_exists($path)){
							$file = fopen($path, "w+");
							fwrite($file, $data);
							fclose($file);
						}


					}
						

				}
			} 
		}
		
		if($url){	
			
			$url_pagin = curl_init();
			$arHeaderList = array();
			$arHeaderList[] = 'Accept: application/json';
			$arHeaderList[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36 OPR/65.0.3467.78';

			curl_setopt_array($url_pagin, array(
				CURLOPT_URL => $url,
				CURLOPT_HTTPHEADER => $arHeaderList,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_FRESH_CONNECT => true
			));
			$result = curl_exec($url_pagin);
			curl_close($url_pagin); 
				if($result){
					echo "Новое подключение к новому URL состоялось <br/>";
				} else {
					echo "Новое подключение НЕ состоялось, но обработано <br/>";
				}
			return $result;
			} else{ echo "<br/> Все данные от поставщика получены <br/>";
					return ($result = false);		
			}
			
	}		
while($result){
img_download($result);
}
echo "Выгрузка изображений закончена <br/>";	
?> 		
