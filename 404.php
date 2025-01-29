<?php
http_response_code(404);

$DOM = file_get_contents('html/404.html');
echo($DOM);
?>