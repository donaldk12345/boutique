<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\PurchaseFormType;
use App\Repository\ProduitRepository;
use App\Services\Panier\ShoppingCart;
use App\Services\Panier\SessionService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{

   

    
    #[Route('/panier', name: 'cart_index')]
    public function index(ShoppingCart $shoppingCart): Response
    {
       
       $details=$shoppingCart->getDetailCartItems();
       
       $total=$shoppingCart->getTotal();
      
     
       
        return $this->render('cart/index.html.twig',[
            'items' => $details,
            'total' => $total

        ]);
    }

    /**
     *@Route("/cart-count-read", name="cart-count-read")
     */
    public function cartIndex(ShoppingCart $shoppingCart){
        return new JsonResponse([
            'count' => $shoppingCart->getDetailCartItems()
        ]);
       
    }

    /**
     * 
     * @Route("/panier/add/{id}" , name= "card_add")
     */
    public function addToCart(ProduitRepository $produitRepository,ShoppingCart $cart,$id,Request $request){
        $produit = $produitRepository->find($id);
        if (!$produit) {
            throw $this->createNotFoundException("Le produit $id n'existe pas !");
        }

        $cart->addToCart($id);
   
        if ($request->query->get('returnToCart')) {
            return $this->redirectToRoute("card_add");
        }
        return $this->redirectToRoute('cart_index');

    }
      /**
       * 
       * @Route("/panier/remove/{id}", name="cart-remove")
       */
      public function remove($id ,ProduitRepository $produitRepository, ShoppingCart $cart){
        $produit = $produitRepository->find($id);

        if (!$produit) {
            throw $this->createNotFoundException("Impossible de supprimer le produit $id, car ce produit n'existe pas !");
        }

        $cart->removeToCart($id);
       return $this->redirectToRoute("cart_index");
    }
    /**
     * 
     * @Route("/checkout", name="checkout")
     * @Security("is_granted('ROLE_USER')") 
     */
    public function checkoutShop(Request $request){
        $form = $this->createForm(PurchaseFormType::class);
        $form->handleRequest($request);
   
      

    return $this->render('cart/checkout.html.twig',[
           
            'form' => $form->createView(),
        ]);

    }

    /**
     * 
     * @Route("/payement", name="payement")
     * @Security("is_granted('ROLE_USER')") 
     */
    public function payementCart(ShoppingCart $shoppingCart){

        $details=$shoppingCart->getDetailCartItems();
       
        $total=$shoppingCart->getTotal();

       
        return $this->render('cart/payement.html.twig',[
            'items' => $details,
            'total' => json_encode($total)

        ]);

    }

}
