<?PHP
/*
|---------------------------------------------------------|
|    Фомирует атрибуты для товаров.                       |
|    Берет данные, уникализирует и добавляет в таблицу.   |
|    В базе Нужно сделать attribute_id автоинкрементным   |
|---------------------------------------------------------|
 */
//ini_set('error_reporting', 0);
//ini_set('display_errors', 0);
//ini_set('display_startup_errors', 0);
ini_set("memory_limit", "512M");
require "config/prod.php"; // Обязательный запрос на получение данных поставщика.
require 'bd/connect.php'; // Обязательное подключение к базе данных .

$query1 = mysqli_query($connect_bd, "DELETE FROM `oc_attribute_description`"); //Стирание данных
/*Нужно сначала собрать массив, а потом его уникализировать*/
$arrayAttributeName = array();
function oc_attribute_description(&$result, $connect_bd)
{
    $data = json_decode($result, true);
    // Декодирование получаемых данных и преобразование в массив.
    echo "Данные декодированы;" . "<br/>";
    $url = ($data["meta"]["pagination"]["next"]);
    echo "Формирование запроса к базе данных" . "<br/>";
    echo "Новый URL получен" . $url . "<br/>";
    global $arrayAttributeName;
    foreach ($data as $k) {
        foreach ($k as $kq => $v) {
            foreach (($v["facet_list"]) as $attribute_name) {
                $upgradeAtribute_name = rtrim($attribute_name["name"], ' ');
                $arrayAttributeName[] = $upgradeAtribute_name;
            }
        }
    }
    if ($url) {
        $url_p = curl_init();
        $arHeaderList = array();
        $arHeaderList[] = 'Accept: application/json';
        $arHeaderList[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36 OPR/65.0.3467.78';

        curl_setopt_array($url_p, array(
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => $arHeaderList,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FRESH_CONNECT => true,
        ));
        $result = curl_exec($url_p);
        curl_close($url_p);
        if ($result) {
            echo "Новое подключение к новому URL состоялось <br/>";
        } else {
            echo "Новое подключение НЕ состоялось, но обработано <br/>";
        }
        return $result;
    } else {
        echo "<br/> Все данные от поставщика получены";
        return ($result = false);
    }
}
while ($result) {
    oc_attribute_description($result, $connect_bd);
}

$finalyArrayAttributeName = array_unique($arrayAttributeName);
/*var_dump($finalyArrayAttributeName);*/
$query = "INSERT INTO `oc_attribute_description` (`name`, `language_id`) VALUES";
$i = 0;
$language_id = 1;
foreach ($finalyArrayAttributeName as $finalyAttributeName) {
    if ($finalyAttributeName) {
        $escapeAttributeName = mysqli_real_escape_string($connect_bd, $finalyAttributeName);
        $query .= " ('{$escapeAttributeName}', {$language_id})";
        $i++;
    }
    if ($i < count($finalyArrayAttributeName)) {
        $query .= ", ";
    }
}
$query .= " ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `language_id` = VALUES(`language_id`) ;";
$res_query = mysqli_query($connect_bd, $query);
if (!$res_query) {
    echo "Запрос: " . $query_new . "<br/>" . "Сообщение ошибки:" . "<br/>" . mysqli_error($connect_bd);
} else {
    echo "Запрос к --oc_attribute_description-- успешно сформирован и отправлен. Ошибоки отсутствуют;" . "<br/>";
    printf("Затронутые строки (UPDATE): %d\n", mysqli_affected_rows($connect_bd));
}
mysqli_close($connect_bd);
