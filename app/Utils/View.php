<?php

namespace App\Utils;

class View
{

    /**
     * Método responsavel por retornar o conteúdo de uma view
     */
    private static function getContentView($view)
    {
        $file = __DIR__ . '/../../public/view/' . $view . '.html';
        return file_exists($file) ? file_get_contents($file) : '';
    }


    /**
     * Método responsavel por retornar o conteudo renderizado de uma view
     */
    public static function render($view, $vars = [])
    {
        $contentView = self::getContentView($view);

        //Chaves do array
        $keys = array_keys($vars);
        $keys = array_map(function ($item){
            return '{{' . $item . '}}';
        }, $keys);

        return str_replace($keys, array_values($vars), $contentView);
    }
}