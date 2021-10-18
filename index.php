<?php
require __DIR__ . "/vendor/autoload.php";


use App\Http\Router;
use App\Utils\View;


define('URL', 'http://localhost:8000');

View::init([
    'URL' => URL,
]);

$obRouter = new Router(URL);

include __DIR__ . '/routes/pages.php';

$obRouter->run()->sendResponse();


//echo '<pre>';
//print_r($obRouter);
//echo '</pre>';
