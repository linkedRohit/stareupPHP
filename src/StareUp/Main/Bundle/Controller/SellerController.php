<?php

namespace StareUp\Main\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SellerController extends Controller
{
    public function indexAction()
    {
        return $this->render('StareUpMainBundle:Seller:index.html.twig');
    }

    public function postAction()
    {
	return 'itemsold';
    }
}
