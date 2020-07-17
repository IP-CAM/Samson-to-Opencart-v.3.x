<?PHP
require "config/prod.php"; // Обязательный запрос на получение данных поставщика.
require 'bd/connect.php'; // Обязательное подключение к базе данных .

mysqli_query($connect_bd, "DELETE FROM `oc_product_image`");

function oc_product_image(&$result, $connect_bd)
{
    $data = json_decode($result, true);
    // Декодирование получаемых данных и преобразование в массив.
    echo "Данные декодированы;" . "<br/>";

    $url = ($data["meta"]["pagination"]["next"]);
    echo "Формирование запроса к базе данных" . "<br/>";
    echo "Новый URL получен" . $url . "<br/>";

    $query_new0 = "INSERT  INTO `oc_product_image` (`product_id`, `image`) VALUES";
    foreach ($data as $k) {
        foreach ($k as $kq => $v) {
            foreach (($v["photo_list"]) as $key => $url_img) {
                if (($v["sku"]) !== null) {

                    $sku = ($v["sku"]);
                    //$name = mysqli_real_escape_string($connect_bd, ($v["name"]));
                    //$description = mysqli_real_escape_string($connect_bd, ($v["description"]));
                    $img = "catalog/product_photo/" . (basename($url_img));
                    //$weight = ($v["weight"]);
                    //$subtract = 0;
                    //$date = "NOW()";
                    //$quantity = ($v["stock_list"][0]["value"]);
                    //$price = ($v["price_list"][1]["value"]); //0 - это стоковая цена, а 1 это рекомендуемая
                    //$minimum = ($v["package_list"][0]["value"]);

                    $query_new0 .= " (" . $sku . ", '" . $img . "'),";
                }
            }
        }
    }
    $query_new1 = rtrim($query_new0, ",");
    $query_new1 .= " ON DUPLICATE KEY UPDATE `product_id` = VALUES(`product_id`), `image` = VALUES(`image`);";

    //print_r($query_new1);

    $res_query_new = mysqli_query($connect_bd, $query_new1);
    if (!$res_query_new) {
        echo "Запрос: " . "<br/>" . "Сообщение ошибки:" . "<br/>" . mysqli_error($connect_bd);
    } else {
        echo "Запрос к --oc_product_image-- успешно сформирован и отправлен. Ошибоки отсутствуют;" . "<br/>";
        printf("Затронутые строки (UPDATE): %d\n", mysqli_affected_rows($connect_bd));
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
    oc_product_image($result, $connect_bd);
    ob_flush();
    flush();
    sleep(2);
}

mysqli_close($connect_bd);
