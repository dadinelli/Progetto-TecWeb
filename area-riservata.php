<?php
$title = "Area riservata - Forno 800";
$description_content = "Scopri la nostra pizzeria: pizze cotte a legna, ingredienti freschi e di qualità. Ordina online o vieni a trovarci per gustare l'autentica pizza italiana in un'atmosfera accogliente!";
$keyword_content = "ristorante, pizzeria, pizza, cibo, cibo a domicilio, cibo da asporto, pizza a domicilio, pizza da asporto, pizzeria a domicilio, pizzeria da asporto, pizzeria a taglio, pizzeria al taglio, pizzeria d'asporto, pizzeria d'asporto, pizzeria a domicilio";
$breadcrumb_content = "&nbsp;&sol;&nbsp;Area riservata";
$script = "menu.js";

$menu_content = '<ul id="menu-list">
    <li lang="en"><a href="index.php">HOME</a></li>
    <li><a href="menu.php">MENU</a></li>
    <li><a href="chisiamo.php">CHI SIAMO</a></li>
    <li><a href="contatti.php">CONTATTI</a></li>
    <li id="currentLink">AREA RISERVATA</li>
    </ul>';

include "template/header.php";

$DOM = file_get_contents('html/area-riservata.html');
echo($DOM);

include "template/footer.php";
?>