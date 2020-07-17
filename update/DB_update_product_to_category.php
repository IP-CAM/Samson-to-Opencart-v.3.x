<?PHP

require 'config/prod.php'; // Обязательный запрос на получение данных поставщика.
require 'bd/connect.php'; // Обязательное подключение к базе данных .

function oc_product_to_category(&$result, $connect_bd)
{
    $data = json_decode($result, true);
    // Декодирование получаемых данных и преобразование в массив.
    echo "Данные декодированы;" . "<br/>";

    $url = ($data["meta"]["pagination"]["next"]);
    echo "Формирование запроса к базе данных" . "<br/>";
    echo "Новый URL получен" . $url . "<br/>";

    $query_new = "INSERT INTO `oc_product_to_category` (`product_id`, `category_id`) VALUES ";

    foreach ($data as $k) {
        foreach ($k as $kq => $v) {
            $sku = ($v["sku"]);

            foreach (($v["category_list"]) as $key => $category) {
                if (iconv_strlen($category) !== 0) {
                    $query_new .= "(" . $sku . ", " . $category . "),";
                }
            }
        }
    }
    $query_new0 = rtrim($query_new, ",");
    $query_new0 .= " ON DUPLICATE KEY UPDATE `product_id` = VALUES(`product_id`), `category_id` = VALUES(`category_id`);";

    $res_query_new = mysqli_query($connect_bd, $query_new0);
    if (!$res_query_new) {
        //echo "Запрос: " . $query_new0 . "<br/>" . "Сообщение ошибки:" . "<br/>" . mysqli_error($connect_bd);
    } else {
        echo "Запрос к --OC_PRODUCT_TO_CATEGORY-- успешно сформирован и отправлен. Ошибоки отсутствуют;" . "<br/>";
        printf("Затронутые строки (UPDATE): %d\n", mysqli_affected_rows($connect_bd));
    }

    // print_r($quantity);

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
    oc_product_to_category($result, $connect_bd);
}

mysqli_close($connect_bd);
