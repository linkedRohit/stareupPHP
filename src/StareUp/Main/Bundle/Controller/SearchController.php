<?php

namespace StareUp\Main\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as config;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use StareUp\Main\Bundle\Util\CommonValidator;
use StareUp\Main\Bundle\Repository\ItemRepository;

/**
 * @config\Route("/search")
 */
class SearchController extends BaseController
{

    /** @DI\Inject ("selling.service") */
    protected $sellingService;

    /**
     * @config\Route("/items", name="search_items")
     * detail - This will search the items for keyword and location
     * author - Rohit Sharma
     * date - 11 June 2016
     */
    public function search()
    {
        $limit = 20;
        $params = $this->getRequest()->query->all();
        $items = $this->sellingService->getItem(1);
        //Will implement elastic here.
        //Right now just mysql search
        /*$em = $this->getDoctrine()->getManager();
        $itemsEm = $em->getRepository('ItemRepository')->findAll();
        $query = $em->createQuery('select title from items where id = 1');
        $query->setMaxResults(20);
        $items = $query->getArrayResult();
        var_dump($itemsEm);die;
        $count = $itemRepository->findBy(array('title'=>$params['keyword']));
        var_dump($count);die;*/
        //$em->persist($item);
        //$em->flush();
	return $this->render("StareUpMainBundle:Search:search.html.twig", array('items'=> $items));
        //return new Response(json_encode($items));
    }
}
