<?php
/**
 * All Wet
 * 2018
 * 
 * API
 * Category
 * getCategories
 */
require_once("../../_system/keys.php");
require_once("../_boot.php");

$category = new AllWet\Category($mysqli);

$categories = $category->update(1, "Andresito", "de Guzman", "Haha");

/*if(empty($categories)){
    $categories = json_encode(array());
} else {
    $categories = json_encode($categories);
}
*/
echo $categories;

?>