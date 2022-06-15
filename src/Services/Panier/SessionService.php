<?php
namespace App\Services\Panier;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;

class SessionService{

    public $session;
    public function __construct()
    {
        $this->session= new Session(new NativeSessionStorage(), new AttributeBag());
    }

 
}