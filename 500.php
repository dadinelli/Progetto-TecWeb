<?php

http_response_code(500);

$DOM = file_get_contents('html/500.html');
echo($DOM);

?>