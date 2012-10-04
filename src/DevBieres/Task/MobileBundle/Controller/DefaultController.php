<?php

namespace DevBieres\Task\MobileBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('DevBieresTaskMobileBundle:Default:index.html.twig');
    }

    public function aproposAction() {
   	    return $this->render('DevBieresTaskMobileBundle:Default:apropos.html.twig');
    }
}
