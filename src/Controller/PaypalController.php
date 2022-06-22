<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PaypalController extends AbstractController
{
    #[Route('/paypal', name: 'app_paypal')]
    public function index(): Response
    {
        return $this->render('paypal/index.html.twig', [
            'controller_name' => 'PaypalController',
        ]);
    }
}
