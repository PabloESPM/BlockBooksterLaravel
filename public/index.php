<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determina si la aplicacion esta en modo mantenimiento
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Registra el autocargador de Composer...
require __DIR__.'/../vendor/autoload.php';

// Inicializa Laravel y maneja la solicitud...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
