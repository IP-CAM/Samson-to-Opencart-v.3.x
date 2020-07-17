<?php
// Отправляем запрос на получение товаров каталогоа в JSON
$curl = curl_init('https://api.samsonopt.ru/v1/assortment/?api_key=cbc874c6031c9bc71d36aac0b6000492');
$arHeaderList = array();
	$arHeaderList[] = 'Accept: application/json';
	$arHeaderList[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36 OPR/65.0.3467.78';
curl_setopt($curl, CURLOPT_HTTPHEADER, $arHeaderList);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
$result = curl_exec($curl);
curl_close($curl);
	if($result){
			echo "<hr/>" . "Данные получены;" . "<br/>";
	} else { echo "<hr/>" . "Данные не получены;" . "<br/>";
	}	
	return $result;

///////////////////





?>