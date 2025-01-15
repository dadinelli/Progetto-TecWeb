<?php
$DOM = file_get_contents('html/header.html');

//head
str_replace('<title></title>', '<title>'.$title.'</title>', $DOM);
str_replace('description_content', $description_content, $DOM);

//menu
str_replace('<ul id="menu-list"></ul>', $menu_content, $DOM);

//breadcrumb
str_replace('breadcrumb_content', $title, $DOM);

echo $DOM;
?>


