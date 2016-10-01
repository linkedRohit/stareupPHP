<?php

namespace StareUp\Main\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as config;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use StareUp\Main\Bundle\Entity\Item;

/**
 * @config\Route("/demo")
 */
class TestController extends BaseController
{

    /**
     * @config\Route("", name="seller_profile")
     * detail - This will return the seller profile
     * author - Rohit Sharma
     * date - 28 May 2016
     */
    public function index()
    {
        return $this->render('StareUpMainBundle:Demo:index.html.twig', array(
            'msg' => 'testing',
	));
    }
}

