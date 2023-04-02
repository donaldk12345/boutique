<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\ProduitRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfileController extends AbstractController
{
    /**
     *@Route("/profile",name="profile")
     *@Security("is_granted('ROLE_USER')") 
     */
    public function index(ProduitRepository $produitRepository): Response
    {

        $produits = $produitRepository->findAll();

        return $this->render('profile/index.html.twig', [
            'produits' => $produits
        ]);
    }
}
