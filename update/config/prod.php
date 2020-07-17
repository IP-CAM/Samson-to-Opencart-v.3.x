<?php
// Отправляем запрос на получение товаров каталогоа в JSON

$curl = curl_init();
$arHeaderList = array();
$arHeaderList[] = 'Accept: application/json';
$arHeaderList[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36 OPR/65.0.3467.78';

curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://api.samsonopt.ru/v1/assortment/?api_key=0e875fd887c442755f6d06f04301562f',
    CURLOPT_HTTPHEADER => $arHeaderList,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FORBID_REUSE => true,
));

$result = curl_exec($curl);
curl_close($curl);
if ($result) {
    echo "<hr/> Данные от поставщика получены\n <br/>";
} else {
    echo "<hr/> Данные от поставщика не получены\n <br/>";
}
return $result;
