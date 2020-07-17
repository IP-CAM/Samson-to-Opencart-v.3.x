<?PHP

$connect_bd = mysqli_connect("localhost", "astgsnru_tp", "xenonhammer331772", "astgsnru_tp");
if ($connect_bd) {
    echo "Подключение к Базе данных 'astgsnru_tp' успешно осуществлено\n <br/>";
} else {
    echo "Не удалось подключиться к Базе данных 'astgsnru_tp'\n <br/>";
}
