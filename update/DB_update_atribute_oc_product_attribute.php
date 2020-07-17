<?PHP
ini_set("memory_limit", "512M");
/*
Присваивает атрибуты к товарам.
Берет данные из таблиц oc_attribute_description и oc_attribute.

В базе Нужно  language_id  поставить по умолачание "1"
 */

require "config/prod.php"; // Обязательный запрос на получение данных поставщика.
require 'bd/connect.php'; // Обязательное подключение к базе данных .

function oc_product_attribute(&$result, $connect_bd)
{
    $data = json_decode($result, true);
    // Декодирование получаемых данных и преобразование в массив.
    echo "Данные декодированы;" . "<br/>";

    $url = ($data["meta"]["pagination"]["next"]);
    echo "Формирование запроса к базе данных" . "<br/>";
    echo "Новый URL получен" . $url . "<br/>";

    $query_new = "INSERT INTO `oc_product_attribute` (`product_id`, `attribute_id`, `text`) VALUES";
    foreach ($data as $k) {
        foreach ($k as $kq => $v) {

            foreach (($v["facet_list"]) as $atribute_text) {
                $att = ($atribute_text["value"]);
                $sku = ($v["sku"]);
                $atribute_text_id_string = "SELECT `attribute_id` FROM `oc_attribute_description` WHERE `name` = '" . $atribute_text["name"] . "';";
                $atribute_text_id_query = mysqli_query($connect_bd, $atribute_text_id_string);
                $arrayAtribute_text_id = mysqli_fetch_all($atribute_text_id_query, MYSQLI_ASSOC);
                foreach ($arrayAtribute_text_id as $Atribute_text_id) {
                    $query_new .= " (" . $sku . ", " . ($Atribute_text_id["attribute_id"]) . ", '" . mysqli_real_escape_string($connect_bd, $att) . "'),";

                }

            }
        }
    }

    $query_new1 = rtrim($query_new, ",");
    $query_new1 .= " ON DUPLICATE KEY UPDATE `product_id` = VALUES(`product_id`), `attribute_id` = VALUES(`attribute_id`), `text` = VALUES(`text`);";

    $res_query_new = mysqli_query($connect_bd, $query_new1);
    if (!$res_query_new) {
        echo "Запрос: " . $query_new1 . "<br/>" . "Сообщение ошибки:" . "<br/>" . mysqli_error($connect_bd);
    } else {
        echo "Запрос к --OC_PRODUCT-- успешно сформирован и отправлен. Ошибоки отсутствуют;" . "<br/>";
        printf("Затронутые 00строки (UPDATE): %d\n", mysqli_affected_rows($connect_bd));

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
    oc_product_attribute($result, $connect_bd);
}

mysqli_close($connect_bd);
