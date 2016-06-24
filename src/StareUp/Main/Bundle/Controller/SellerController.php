<?php

namespace StareUp\Main\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as config;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use StareUp\Main\Bundle\Entity\Item;

/**
 * @config\Route("/seller")
 */
class SellerController extends BaseController
{

    /** @DI\Inject("selling.service") */
    public $sellingService;

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
        $isCsrfTokenValid = true;
        if(!$isCsrfTokenValid) {
          $res['status'] = false;
          $res['code'] = 401;
          $res['data'] = false;
          $res['mesg'] = 'Not authorized for this.';
        }
        $sellingObj = $this->getPostRequestParam('sellingObj');
        $sellingArray = json_decode($sellingObj, true);
        if($sellingArray) {
            $sellingArray['userId'] = 1231;
            //$item = $this->getItemObjectFromArray($sellingArray);
            /*$em = $this->getDoctrine()->getManager();
            $array['id'] = $item->getId();
            // tells Doctrine you want to (eventually) save the Product (no queries yet)
            $em->persist($item);
            // actually executes the queries (i.e. the INSERT query)
            $em->flush();*/
            $items = $this->sellingService->saveItem($sellingArray);
            if($items) {
              $res['status'] = true;
              $res['code'] = 200;
              $res['item'] = json_encode($array);
              $res['mesg'] = "Your item is posted successfully.";
            } else {
              $res['status'] = false;
              $res['code'] = 400;
              $res['item'] = false;
              $res['mesg'] = "Unable to save, try again!";
            }
        } else {
            $res['status'] = false;
            $res['code'] = 406;
            $res['item'] = false;
            $res['mesg'] = 'Not a valid Object';
        }

        return new Response(json_encode($res));
    }

    //This will be moved to seller repository.
    private function getItemObjectFromArray($array) {

        $itemObj = new Item();
        $itemObj->setTitle($array['title']);
        $itemObj->setDescription($array['description']);
        $itemObj->setUserId($array['userId']);
        $itemObj->setType($array['type']);
        $itemObj->setCategory($array['category']);
        $itemObj->setLocation($array['location']);
        $itemObj->setLattitude($array['lattitude']);
        $itemObj->setLongitude($array['longitude']);
        $itemObj->setQuantity($array['quantity']);
        $itemObj->setDuration($array['duration']);
        $itemObj->setPrice($array['price']);
        $itemObj->setCurrency($array['currency']);
        $itemObj->setNegotiable($array['negotiable']);
        $itemObj->setImages($array['images']);
        $itemObj->setPostedOn(new \DateTime('now'));

        return $itemObj;
    }
}

?>
