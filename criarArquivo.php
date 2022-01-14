<?php


require  __DIR__ . '/vendor/autoload.php';

use \App\Files\CSV;

$dados = [
    [
        'ID',
        'Nome',
        'Descrição'
    ],
    [
        1,
        'Produto teste',
        'Produto de teste integração'
    ],
    [
        2,
        'Produto amostra',
        'Produto de amostra integração'
    ]
];

$sucesso = CSV::criarArquivo(__DIR__ . '/files/arquivo_escrita.csv', $dados, ',');


echo "<pre>";
print_r($sucesso);
echo "<pre>", exit;