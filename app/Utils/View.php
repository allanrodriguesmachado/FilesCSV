<?php

namespace App\Utils;

class View
{
    private static $vars = [];

    /**
     * Método responsavel por definir os dados inicias da classe
     */
    public static function init($vars = [])
    {
        self::$vars = $vars;
    }

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

        $vars = array_merge(self::$vars, $vars);

        //Chaves do array
        $keys = array_keys($vars);
        $keys = array_map(function ($item) {
            return '{{' . $item . '}}';
        }, $keys);

        return str_replace($keys, array_values($vars), $contentView);
    }
}