<?php
namespace App\Services\Panier;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use App\Services\Panier\SessionService;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ShoppingCart{

    protected $session;
    protected $produitRepository;
    public  function __construct(SessionService $session,ProduitRepository $produitRepository)
    {

        $this->session=$session->session;
        $this->produitRepository = $produitRepository;   
    }
    protected function getCart(): array
    {
        return $this->session->get('cart', []);
    }

  
    protected function saveCart(array $cart)
    {
        $this->session->set('cart', $cart);
    }
    public function empty()
    {
        $this->saveCart([]);
    }

    public function addToCart(int $id){
        $cart = $this->getCart();

        if (!array_key_exists($id, $cart)) {
            $cart[$id] = 0;
        }
        $cart[$id]++;

        $this->saveCart($cart);
    }

    public function removeToCart(int $id)
    {
        $cart = $this->getCart();
        unset($cart[$id]);

        $this->saveCart($cart);
    }
    public function getTotal(): int
    {
        $total = 0;

        $cart = $this->getCart();

        foreach ($cart as $id => $quantite) {
            $produit = $this->produitRepository->find($id);

            if (!$produit) {
                continue;
            }

            $total += ($produit->getPrix() * $quantite);
        }

        return $total;
    }

    /**
     * Undocumented function
     *
     * @return array<CartItem>
     */
    public function getDetailCartItems(): array
    {
        $detailedCart = []; // à la base c'est un tableau qui est vide

        $cart = $this->getCart();

        foreach ($cart as $id => $quantite) {
            $produit = $this->produitRepository->find($id);

            if (!$produit) {
                continue;
            }

            $detailedCart[] = new CartItem($produit, $quantite);
        }
        return $detailedCart;
    }
}