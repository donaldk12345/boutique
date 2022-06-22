<?php

namespace App\Controller;

use DateTimeImmutable;
use App\Entity\Purchase;
use App\Entity\PurchaseCart;
use App\Form\PurchaseFormType;
use App\Services\Panier\ShoppingCart;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PurchaseController extends AbstractController
{

 
    /**
     * 
     *@Route("/confirmation", name="confirmation")
     *
     */
    public function index(Request $request,EntityManagerInterface $entityManagerInterface,ShoppingCart $shoppingCart,Security $security)
    {

        $form = $this->createForm(PurchaseFormType::class);
        $form->handleRequest($request);

        if (!$form->isSubmitted() && $form->isValid()) {

            
            return $this->redirectToRoute('card_add');
        }

        $cart = $shoppingCart->getDetailCartItems();
        if (count($cart) === 0) {
          
            return $this->redirectToRoute('card_add');
        }

        $purchase = new Purchase();

        /** @var Purchase */
        $purchase = $form->getData();

        $purchase->setUserPurchase($security->getUser())
        ->setTotal($shoppingCart->getTotal())
        ->setCreatedAt(new DateTimeImmutable());



       $entityManagerInterface->persist($purchase);
       $entityManagerInterface->flush();

      foreach ($shoppingCart->getDetailCartItems() as $cartItem) {
         $purchaseCart = new PurchaseCart;
         $purchaseCart->setProduit($cartItem->produit)
           ->setPurchase($purchase)
           ->setNomProduit($cartItem->produit->getNom())
          ->setPrixProduit($cartItem->produit->getPrix())
          ->setQuantiteProduit($cartItem->quantite)
          ->setTotal($cartItem->getTotal());
          //dd($purchaseItem);
          
          $entityManagerInterface->persist($purchaseCart);
       }

        $entityManagerInterface->flush();
        return $this->redirectToRoute('payement', [
            'id' => $purchase->getId()
        ]); 
        
    }
    
    /**
     * @Route("/commande", name="app_commande")
     * 
     */
    public function commandeList(): Response
    {
        /** @var User */
        $user = $this->getUser();
        return $this->render('purchase/commande.html.twig', [
            'purchases' => $user->getPurchases()
        ]);
    }
  
  
}
