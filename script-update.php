<?php
ini_set('error_reporting', 0);
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
ini_set("memory_limit", "512M");
set_time_limit(1000);
$arrayUpdate = array();
include "update/DB_update_atribute_oc_attribute.php";
include "update/DB_update_atribute_oc_attribute_description.php";
include "update/DB_update_atribute_oc_product_attribute.php";

include "update/DB_update_oc_manufacturer.php";
include "update/DB_update_category.php";
include "update/DB_update_product.php";
include "update/DB_update_product_description.php";

include "update/oc_manufacturer_to_store.php";
include "update/DB_update_product_to_category.php";
include "update/DB_update_product_to_store.php";
include "update/DB_update_product_img.php";


include "update/DB_update_category_description.php";
include "update/DB_update_category_path.php";
include "update/DB_update_category_store.php";



// /var/www/astgsnru/data/www/tipografiya-pechat.ru
