<?php

namespace Bp\CodeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{
    
    public function indexAction($name)
    {
        return $this->render('BpCodeBundle:Default:index.html.twig', array('name' => $name));
    }
}
