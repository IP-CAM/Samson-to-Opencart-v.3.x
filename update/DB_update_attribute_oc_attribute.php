<?PHP
ini_set("memory_limit", "512M");
require "config/prod.php"; // Обязательный запрос на получение данных поставщика.
require 'bd/connect.php'; // Обязательное подключение к базе данных .
//Убираем автоинкремент в базе
$query0 = mysqli_query($connect_bd, "DELETE FROM `oc_attribute`"); //Стирание данных

$query1 = mysqli_query($connect_bd, "SELECT `attribute_id` FROM `oc_attribute_description`");

$query2_string = "INSERT INTO `oc_attribute` (`attribute_id`, `attribute_group_id`, `sort_order`) VALUES ";
while ($arrayAttribute_id = mysqli_fetch_assoc($query1)) {
    $query2_string .= "(" . $arrayAttribute_id["attribute_id"] . ", " . 3 . ", " . 0 . "),";

}

$query2_string1 = rtrim($query2_string, ",");
$query2_string1 .= " ON DUPLICATE KEY UPDATE
`attribute_id`       = VALUES(`attribute_id`),
`attribute_group_id` = VALUES(`attribute_group_id`),
`sort_order`         = VALUES(`sort_order`);";

$query2 = mysqli_query($connect_bd, $query2_string1);
if (!$query2) {
    echo "Запрос: " . $query2_string . "<br/>" . "Сообщение ошибки:" . "<br/>" . mysqli_error($connect_bd);
} else {
    echo "Запрос к --oc_attribute-- успешно сформирован и отправлен. Ошибоки отсутствуют;" . "<br/>";
    printf("Затронутые строки (UPDATE): %d\n", mysqli_affected_rows($connect_bd));
}

mysqli_close($connect_bd);
