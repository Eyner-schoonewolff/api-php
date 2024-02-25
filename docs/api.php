<?php

require("../vendor/autoload.php");

$openapi = \OpenApi\Generator::scan([$_SERVER['DOCUMENT_ROOT'].'/controlador']);

header('Content-Type: application/x-json');
echo $openapi->toJSON();
