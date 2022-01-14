<?php


require __DIR__ . '/vendor/autoload.php';

use \App\Files\CSV;



$dados = CSV::lerArquivo(__DIR__ . '/files/Pasta1.csv', true, ',');


print_r($dados);