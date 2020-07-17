<?PHP
ini_set("memory_limit", "512M");
require "config/prod.php"; // Обязательный запрос на получение данных поставщика.
require 'bd/connect.php'; // Обязательное подключение к базе данных .

$query0 = mysqli_query($connect_bd, "DELETE FROM `oc_manufacturer_description`");

$query1_string = "SELECT * FROM `oc_manufacturer`";
$query1 = mysqli_query($connect_bd, $query1_string);

$query2_string = "INSERT INTO `oc_manufacturer_description` (
`manufacturer_id`,
`language_id`,
`meta_title`,
`meta_h1`) VALUES ";
while ($arrayManufacturer_id = mysqli_fetch_assoc($query1)) {
    $manufacturer_id = $arrayManufacturer_id["manufacturer_id"];
    $rusLanguage_id = 1;
    $enLanguage_id = 2;
    $meta_title = mysqli_real_escape_string($connect_bd, ($arrayManufacturer_id["name"]));
    $meta_h1 = mysqli_real_escape_string($connect_bd, ($arrayManufacturer_id["name"]));

    $query2_string .= " ({$manufacturer_id}, {$rusLanguage_id},'{$meta_title}','{$meta_h1}'),
    ({$manufacturer_id}, {$enLanguage_id}, NULL, NULL),";

}

$query2_string1 = rtrim($query2_string, ",");
$query2_string1 .= " ON DUPLICATE KEY UPDATE
`manufacturer_id` = VALUES(`manufacturer_id`),
`language_id` = VALUES(`language_id`),
`meta_title` = VALUES(`meta_title`),
`meta_h1` = VALUES(`meta_h1`);";

$query2 = mysqli_query($connect_bd, $query2_string1);
if (!$query2) {
    echo "Запрос: " . $query2_string . "<br/>" . "Сообщение ошибки:" . "<br/>" . mysqli_error($connect_bd);
} else {
    echo "Запрос к --oc_manufacturer_to_store-- успешно сформирован и отправлен. Ошибоки отсутствуют;" . "<br/>";
    printf("Затронутые строки (UPDATE): %d\n", mysqli_affected_rows($connect_bd));
}

//var_dump($query2_string);
