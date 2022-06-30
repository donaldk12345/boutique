<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(ProduitRepository $produitRepository): Response
    {
       
        return $this->render('home/index.html.twig', [
            'produits'=>$produitRepository->findByMin()
        
        ]);
    }

    /**
     * 
     * @Route("/boutiques", name="boutiques")
     */
    public function insertProduit(Request $request, EntityManagerInterface $manager): Response
    {

        $produit =new Produit();
        $form=$this->CreateForm(ProduitType::class ,$produit);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $produit->setCreatedAt(new \DateTimeImmutable());
            $manager->persist($produit);
            $manager->flush();
            return $this->redirectToRoute('home');
        }
        

        return $this->render('home/produit.html.twig', [
            'form' =>$form->createView()
        ]);
           
    }
}
