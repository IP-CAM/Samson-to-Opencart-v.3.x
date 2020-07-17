<?PHP
/*
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
					echo "<hr/>" . "Данные от поставщика получены;" . "<br/>";
			} else { echo "<hr/>" . "Данные от поставщика не получены;" . "<br/>";
			}
		
		echo "Начало выполнения функции <br/>";
		if($data = json_decode($result, true)){     // Декодирование получаемых данных и преобразование в массив.
		
			foreach ($data as $k){
				foreach ($k as $kq => $v){
					foreach(($v["photo_list"]) as $key => $url){
						$name = (basename($url));
						$path = "/" . $name ;

						$ch = curl_init($url);
						curl_setopt($ch, CURLOPT_HTTPHEADER, 0);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						$data = curl_exec($ch);
						curl_close($ch);
						
						if(!file_exists($name)){
							$file = fopen($path, "w+");
							fwrite($file, $data);
							fclose($file);
						}


					}
						

				}
			}
		}
		*/
$wurl="https://api.samsonopt.ru/goods/100008/be38ddab59d7fba9a59a630296003d3b_x.jpg";
$name = (basename($wurl));
$path = "/" . $name ;
						$data = "2";
						
					
							$file = fopen($name, "w");
							fwrite($file, $data);
							fclose($file);
						

							

						
?>

						
	