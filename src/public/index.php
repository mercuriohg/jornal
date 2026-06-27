<?php

date_default_timezone_set('America/Sao_Paulo');

require_once __DIR__ . '/../controller/RouterController.php';

$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

RouterController::route($uri);