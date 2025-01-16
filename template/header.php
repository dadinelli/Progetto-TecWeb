<?php
$DOM = file_get_contents('html/header.html');

//head
$DOM = str_replace('<title></title>', '<title>'.$title.'</title>', $DOM);
$DOM = str_replace('description_content', "Scopri la nostra pizzeria: pizze cotte a legna, ingredienti freschi e di qualità. Ordina online o vieni a trovarci per gustare l'autentica pizza italiana in un'atmosfera accogliente!" , $DOM);

//menu
$DOM = str_replace('<ul id="menu-list"></ul>', $menu_content, $DOM);

//breadcrumb
$DOM = str_replace('breadcrumb_content', $breadcrumb_content, $DOM);

//script
$DOM = str_replace('js_file', $script, $DOM);

echo $DOM;
?>


