<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Model\Entity\Organization;

/**
 * Método responsável por retornar o contúdo (VIEW) da HOME
 */
class Home extends Page
{
    public static function getHome()
    {
        $obOrganization = new Organization();


        $content = View::render('pages/home', [
            'name' => $obOrganization->name,
            'description' => $obOrganization->description,
            'site' => $obOrganization->site
        ]);
        return parent::getPage('Portal - Teste', $content);
    }
}