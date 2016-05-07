<?php

namespace StareUp\Main\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BuyerController extends Controller
{
    public function indexAction()
    {
        return $this->render('StareUpMainBundle:Buyer:index.html.twig');
    }
}
