<?php

use \App\Http\Response;
use \App\Controller\Pages;

/**
 * ROTA HOME
 */
$obRouter->get('/', [
    function(){
        return new Response(400,Pages\Home::getHome());
    }
]);


/**
 * ROTA SOBRE
 */
$obRouter->get('/sobre', [
    function(){
        return new Response(400,Pages\About::getAbout());
    }
]);


/**
 * ROTA DINAMICA
 */
$obRouter->get('/pagina/{idPagina}/{$acao}', [
    function($idPagina, $acao){
        return new Response(400,'Página ' . $idPagina . ' - ' . $acao);
    }
]);
