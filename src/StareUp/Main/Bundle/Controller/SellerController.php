<?php

namespace StareUp\Main\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as config;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * @config\Route("/seller")
 */
class SellerController extends Controller
{
    /**
     * @config\Route("", name="seller_profile")
     * detail - This will return the seller profile
     * author - Rohit Sharma
     * date - 28 May 2016
     */
    public function indexAction()
    {
        return $this->render('StareUpMainBundle:Seller:index.html.twig');
    }

    /**
     * @config\Route("/post", name="post_asset")
     * detail - action that helps you post assets for the rest of Startup World
     * author - Rohit Sharma
     * date - 28 May 2016
     */
    public function postAction()
    {
	return $this->render('StareUpMainBundle:Seller:post.html.twig');
    }
}
