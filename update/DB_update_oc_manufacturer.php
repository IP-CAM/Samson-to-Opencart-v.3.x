<?PHP

/*
Берем данные о брендах.
В базе manufacturer_id даем автоинкремент
 */

require "config/prod.php";
require "bd/connect.php";
$query1 = mysqli_query($connect_bd, "DELETE FROM `oc_manufacturer`");
$arrayManufacturerd = array(); // Массив с брендами

function oc_manufacturer(&$result, $connect_bd)
{
    $data = json_decode($result, true);
    // Декодирование получаемых данных и преобразование в массив.
    $url = ($data["meta"]["pagination"]["next"]);

    global $arrayManufacturerd;
    foreach ($data as $k) {
        foreach ($k as $kq => $v) {
            if (($v["brand"]) !== null) {
                $manufacturerd = ($v["brand"]);
                $arrayManufacturerd[] = $manufacturerd; //Добавляем в массив Бренды

            }
        }
    }

    echo "Массив дополнен\n <br/>";

    if ($url) {
        //Переход на другую страницу

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
            echo "Новое подключение к новому URL состоялось\n <br/>";
        } else {
            echo "Новое подключение НЕ состоялось, но обработано <br/>";
        }
        return $result;
    } else {
        echo "<br/> Массив полностью собран\n";
        return ($result = false);
    }
}
while ($result) {
    oc_manufacturer($result, $connect_bd);
}

$finalArrayManufactured = array_unique($arrayManufacturerd); //Убирает из массива неуникальные значи,а так же ключи неуникальных значений
//print_r($);
echo "Уникализация массива\n <br/>";

array_pop($finalArrayManufactured);
echo "Редактирование массива\n <br/>";

$query = "INSERT INTO `oc_manufacturer` (`name`) VALUES ";
$i = 0;
foreach ($finalArrayManufactured as $finalManufactured) {
    if ($finalArrayManufactured) {

        $query .= "('" . mysqli_real_escape_string($connect_bd, $finalManufactured) . "')";

        $i++;
    }
    if ($i < count($finalArrayManufactured)) {
        $query .= ", ";

    }

}

$query .= " ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);";

$res_query = mysqli_query($connect_bd, $query);
if (!$res_query) {
    echo "Запрос: " . $query_new . "<br/>" . "Сообщение ошибки:" . "<br/>" . mysqli_error($connect_bd);
} else {
    echo "Запрос к --oc_manufacturer-- успешно сформирован и отправлен. Ошибоки отсутствуют;" . "<br/>";
    printf("Затронутые строки (UPDATE): %d\n", mysqli_affected_rows($connect_bd));
}
//var_dump($finalArrayManufactured);
