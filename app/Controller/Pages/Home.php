<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Model\Entity\Organization;

/**
 * Method responsible for returning the content (VIEW) of the HOME
 */
class Home extends Page
{
    public static function getHome()
    {
        $obOrganization = new Organization();
        $content = View::render('pages/home', [
            'name' => $obOrganization->name,
        ]);

        return parent::getPage('Home - Portal', $content);
    }
}