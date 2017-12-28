<?php

namespace INSSET\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('INSSETBlogBundle:Default:index.html.twig');
    }
}
