<?php

namespace StareUp\Main\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction()
    {
	$path = realpath($this->get('kernel')->getRootDir() . '/../web/css/main.css');
        return $this->render('StareUpMainBundle:Default:index.html.twig', array('path'=>$path));
    }
}
