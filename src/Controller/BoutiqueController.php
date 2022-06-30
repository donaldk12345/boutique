<?php

namespace App\Controller;

use App\Entity\Filtre;
use App\Entity\Produit;
use App\Form\FiltreType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BoutiqueController extends AbstractController
{

  
    /**
     * 
     * @Route("/boutique", name="boutique")
     */
  public function index(Request $request,ProduitRepository $produitRepository)
    {
        $data= new Filtre(); 
        $data->page=$request->get('page',1);     
        $form = $this->createForm(FiltreType::class, $data);
        $form->handleRequest($request);
        $produits=$produitRepository->findWithFiltre($data);
    
        return $this->render('boutique/index.html.twig', [
            'produits' => $produits,
            'form' => $form->createView()
        ]);
    }

     /**
      * @Route("/details/{slug}",name="details")
      * @param Produit $produit
      * @return Response
      * 
      */
      public function view(ProduitRepository $produitRepository,$slug){
        $produits=$produitRepository->findOneBy(['slug'=> $slug]);
      
     
        $produits=$produitRepository->findOneBySlug($slug);
  
        if (!$produits) {
            throw $this->createNotFoundException(
                'La video  demandé n\'existe pas !'.$slug
            );
        }
       
        return $this->render('boutique/details.html.twig', [
          'produits' => $produits
                
      ]);
  
      }
  

}
