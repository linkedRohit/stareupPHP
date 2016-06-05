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
class SellerController extends BaseController
{
    /**
     * @config\Route("/profile", name="seller_profile")
     * detail - This will return the seller profile
     * author - Rohit Sharma
     * date - 28 May 2016
     */
    public function index()
    {
        return $this->render('StareUpMainBundle:Seller:index.html.twig');
    }

    /**
     * @config\Route("", name="get_post_form")
     * detail - action that helps you post assets for the rest of Startup World
     * author - Rohit Sharma
     * date - 28 May 2016
     */
    public function postForm()
    {
	     return $this->render('StareUpMainBundle:Seller:post.html.twig');
    }

    /**
     * @config\Route("/post", name="post_asset")
     * detail - action that helps you post assets for the rest of Startup World
     * author - Rohit Sharma
     * date - 28 May 2016
     */
    public function post()
    {
        $res = array();
        $token = $this->getPostRequestParam('_token');
        if(!$isCsrfTokenValid) {
          $res['status'] = false;
          $res['code'] = 401;
          $res['mesg'] = 'Not authorized for this.';
        }
        $sellingObj = $this->getPostRequestParam('sellingObj');
        $sellingArray = json_decode($sellingObj, true);
        if($sellingArray) {

        } else {
            $res['status'] = false;
            $res['code'] = 406;
            $res['mesg'] = 'Not a valid Object';
        }
	      return $this->render('StareUpMainBundle:Seller:post.html.twig');
    }
}

?>
