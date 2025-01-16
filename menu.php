<?php
$title = "Menu - Forno 800";
$description_content = "Contatta la nostra pizzeria, chiama ora o scrivici su WhatsApp! Siamo aperti tutti i giorni.";
$keyword_content = "ristorante, pizzeria, pizza, cibo, cibo a domicilio, cibo da asporto, pizza a domicilio, pizza da asporto, pizzeria a domicilio, pizzeria da asporto, pizzeria a taglio, pizzeria al taglio, pizzeria d'asporto, pizzeria d'asporto, pizzeria a domicilio";
$breadcum_content = "&nbsp;&sol;&nbsp;Menu";
$script = "menu.js";

$menu_content = '<ul id="menu-list">
    <li lang="en"><a href="index.php">HOME</a></li>
    <li id="currentLink">MENU</li>
    <li><a href="chisiamo.php">CHI SIAMO</a></li>
    <li><a href="contatti.php">CONTATTI</a></li>
    <li><a href="area-riservata.php">AREA RISERVATA</a></li>
    </ul>';

include "template/header.php";

$DOM = file_get_contents('html/menu.html');
echo($DOM);

include "template/footer.php";
?>