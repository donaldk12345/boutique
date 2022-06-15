<?php 

namespace App\Services\Paypal;

use App\Services\Panier\SessionService;
use App\Services\Panier\ShoppingCart;
use PayPal\Api\{Payer,Item,ItemList,Details,Amount,Transaction,RedirectUrls,Payment};
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Paypal{
    public function __construct(SessionService $session, UrlGeneratorInterface $router)
    {
       $this->session=$session->session;
       $this->router=$router; 
      

    }

    public function createPayment(ShoppingCart $shoppingCart){

        $payer= new Payer();
        foreach($shoppingCart->getDetailCartItems() as $produit)
        {
            $items[] = $item = new Item();
            $item->setName($produit['nom'])
                ->setCurrency('USD')
                ->setQuantity($produit['quantite'])
                ->setPrice($produit['prix']);
        }
        $itemList = new ItemList();
        $itemList->setItems($items);

        $details = new Details();
        $details->setShipping(0)
            ->setTax(0)
            ->setSubtotal($shoppingCart->getTotal());

        $amount = new Amount();
        $amount->setCurrency("USD")
            ->setTotal($shoppingCart->getTotal())
            ->setDetails($details);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription("Payment description")
            ->setInvoiceNumber(uniqid());

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl($this->router->generate('execute-payment',[],UrlGeneratorInterface::ABSOLUTE_URL))
            ->setCancelUrl($this->router->generate('cart',[],UrlGeneratorInterface::ABSOLUTE_URL));

        $payment = new Payment();
        $payment->setIntent("sale")
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions(array($transaction));

        $apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                $_ENV['PAYPAL_CLIENT_ID'],     // ClientID
                $_ENV['PAYPAL_CLIENT_SECRET']     // ClientSecret
            )
        );
        $payment->create($apiContext);
        $approvalUrl = $payment->getApprovalLink();
        return $approvalUrl;

    }
}