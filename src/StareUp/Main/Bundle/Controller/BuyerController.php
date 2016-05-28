<?php

namespace StareUp\Main\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as config;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * @config\Route("/buyer")
 */
class BuyerController extends Controller
{
     /**
     * @config\Route("", name="buyer_profile")
     * detail - This will return the buyer profile.
     * author - Rohit Sharma
     * date - 28 May 2016
     */
    public function indexAction()
    {
        return $this->render('StareUpMainBundle:Buyer:index.html.twig');
    }

    /**
     * @config\Route("/post", name="post_asset")
     * detail - action that helps you post assets for the rest of Startup World
     * author - Rohit Sharma
     * date - 28 May 2016
     */
    public function postAction()
    {
        return new Response('itemsold');
    }
}

