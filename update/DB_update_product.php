<?PHP

//ini_set("memory_limit", "512M");
require "config/prod.php"; // Обязательный запрос на получение данных поставщика.
require 'bd/connect.php'; // Обязательное подключение к базе данных .

function oc_product(&$result, $connect_bd) // Получаем массив товаров и подключение к базе

{
    $data = json_decode($result, true);
    // Декодирование получаемых данных и преобразование в массив.
    echo "Данные декодированы;" . "<br/>";

    $url = ($data["meta"]["pagination"]["next"]);
    echo "Формирование запроса к базе данных" . "<br/>";
    echo "Новый URL получен" . $url . "<br/>";
    //Формирование строки к базе данных
    $query_new = "INSERT INTO `oc_product` (
    `product_id`,
    `sku`,
    `image`,
    `weight`,
    `subtract`,
    `date_available`,
    `date_added`,
    `date_modified`,
    `quantity`,
    `stock_status_id`,
    `price`,
    `minimum`,
    `manufacturer_id`,
    `status`) VALUES "; //Начинаем формировать в строчку товары
    foreach ($data as $k) {
        foreach ($k as $kq => $v) {
            if (($v["sku"]) !== null) {
                // Чтобы избавиться от пустых массивов

                $sku = ($v["sku"]);
                $name = mysqli_real_escape_string($connect_bd, ($v["name"])); // Экранируем название товаров
                $description = mysqli_real_escape_string($connect_bd, ($v["description"])); //Экранируем описание товаров
                $img_ex = ($v["photo_list"][0]); //Устанавливаем главное фото
                $img = ($img_ex !== null) ? "catalog/product_photo/" . (basename($img_ex)) : null; //Редактируем ссылку на фото
                $weight = ($v["weight"]);
                $subtract = 0;
                $date_str = (($v["sale_date"]) == null) ? "null" : str_replace(".", "-", ($v["sale_date"]));
                $date_create = date_create_from_format('j-m-Y', $date_str);
                $date_ava = ($date_create !== false) ? date_format($date_create, 'Y-m-d') : "NULL";
                $date_available = ($date_ava !== "NULL") ? "\"" . $date_ava . "\"" : "\"0000-00-00\"";
                $date_mod = "NOW()";
                $price = ($v["price_list"][1]["value"]); //0-стоковая цена, а 1-рекомендуемая
                $minimum = ($v["package_list"][0]["value"]);
                $status = 1;
                $brand = (mysqli_real_escape_string($connect_bd, ($v["brand"])));
                $manufacturer_id_string = "SELECT `manufacturer_id` FROM `oc_manufacturer` WHERE `name` = '" . $brand . "';";
                $manufacturer_id_query = mysqli_query($connect_bd, $manufacturer_id_string);
                $manufacturer_id = mysqli_fetch_assoc($manufacturer_id_query);
                $quantity = (($v["stock_list"][0]["value"]) == 0) ? ($v["stock_list"][2]["value"]) : ($v["stock_list"][0]["value"]);
                $stock_status_id = (($v["stock_list"][0]["value"]) == 0) ? 6 : 7;

                $query_new .= "("
                . $sku . ", " // ID товара
                 . $sku . ", '" //Код товара
                 . $img . "', " //Главное фото
                 . $weight . ", " // Вес
                 . $subtract . ", " //
                 . $date_available . ", " //Старт продаж
                 . $date_available . ", " // Дата поступления
                 . $date_mod . ", " //Дата изменения (Для отслеживания работы скрипта)
                 . $quantity . ", " // Количество
                 . $stock_status_id . ", " // Статус ожидания
                 . $price . ", " //Цена
                 . $minimum . ", "; // Минимальный заказ
                $query_new .= ($manufacturer_id["manufacturer_id"] == null) ? 872 : $manufacturer_id["manufacturer_id"];
                $query_new .= ", "
                    . $status . ")"; // Включен ли товар?

            }

            if ($kq + 1 < count($k)) {
                $query_new .= ", ";
            }
        }
    }
    $query_new .= " ON DUPLICATE KEY UPDATE `product_id` = VALUES(`product_id`),
    `sku` = VALUES(`sku`),
    `quantity` = VALUES(`quantity`),
    `stock_status_id` = VALUES(`stock_status_id`),
    `image` = VALUES(`image`),
    `price` = VALUES(`price`),
    `weight` = VALUES(`weight`),
    `minimum` = VALUES(`minimum`),
    `subtract` = VALUES(`subtract`),
    `date_available` = VALUES(`date_available`),
    `date_added` = VALUES(`date_added`),
    `date_modified` = VALUES(`date_modified`),
    `manufacturer_id` = VALUES(`manufacturer_id`),
    `status` = VALUES(`status`);";

    $res_query_new = mysqli_query($connect_bd, $query_new);
    if (!$res_query_new) {
        echo "Сообщение ошибки:" . $query_new . "<br/>" . mysqli_error($connect_bd);
    } else {
        echo "Запрос к --OC_PRODUCT-- успешно сформирован и отправлен. Ошибоки отсутствуют;" . "<br/>";
        printf("Затронутые строки (UPDATE): %d\n", mysqli_affected_rows($connect_bd));
    }

    //print_r($query_new);

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
    oc_product($result, $connect_bd);
}

mysqli_close($connect_bd);
