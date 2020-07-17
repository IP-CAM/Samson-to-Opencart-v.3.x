
<?PHP
require "config/prod.php"; // Обязательный запрос на получение данных поставщика.
require 'bd/connect.php'; // Обязательное подключение к базе данных .

$query1 = mysqli_query($connect_bd, "DELETE FROM `oc_manufacturer_to_store`");

$query1_string = "SELECT `manufacturer_id` FROM `oc_manufacturer`";
$query1 = mysqli_query($connect_bd, $query1_string);

//$arrayManufacturer_id = mysqli_fetch_assoc($query1);

$query2_string = "INSERT INTO `oc_manufacturer_to_store` (`manufacturer_id`, `store_id`) VALUES ";
while ($arrayManufacturer_id = mysqli_fetch_assoc($query1)) {
    $query2_string .= "(" . $arrayManufacturer_id["manufacturer_id"] . ", " . 0 . "),";

}

$query2_string1 = rtrim($query2_string, ",");
$query2_string1 .= " ON DUPLICATE KEY UPDATE `manufacturer_id` = VALUES(`manufacturer_id`), `store_id` = VALUES(`store_id`);";
$query2_string2 =
$query2 = mysqli_query($connect_bd, $query2_string1);
if (!$query2) {
    echo "Запрос: " . $query2_string . "<br/>" . "Сообщение ошибки:" . "<br/>" . mysqli_error($connect_bd);
} else {
    echo "Запрос к --oc_manufacturer_to_store-- успешно сформирован и отправлен. Ошибоки отсутствуют;" . "<br/>";
    printf("Затронутые строки (UPDATE): %d\n", mysqli_affected_rows($connect_bd));
}

//var_dump($query2_string);