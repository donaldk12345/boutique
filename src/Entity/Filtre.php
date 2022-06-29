<?php

namespace App\Entity;
use App\Entity\Category;


class Filtre
{

    /**
     * 
     *@var int
     */
    public $page=1;
    /**
     * @var string
     */
    public $val='';

    /** 
     * @var string
     */
    public $string;

    /**
     * @var Category[]
     */
    public $categories = [];

    /**
     * @var null|integer
     */
    public $prixMax;

    /**
     * @var null|integer
     */
    public $prixMin;
    /**
     * 
     * @var boolean 
     */
    public $promo= false;

    

  
}
