<?php

namespace Netgen\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('NetgenTestBundle::index.html.twig');
    }
}
