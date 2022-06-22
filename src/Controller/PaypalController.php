<?php

namespace App\Controller;

use App\Services\Panier\ShoppingCart;
use App\Services\Paypal\Paypal;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PaypalController extends AbstractController
{
   

    public function createPaiement(ShoppingCart $shoppingCart,Paypal $paypal){

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
       

    }
  


}
