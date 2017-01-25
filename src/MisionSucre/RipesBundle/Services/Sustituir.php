<?php

namespace MisionSucre\RipesBundle\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\HttpFoundation\Request;

class Sustituir {
 
    protected $em;
    protected $router;
    protected $user;
 
    public function __construct(EntityManager $entityManager, Router $router, TokenStorage $tokenStorage) {
        $this->em = $entityManager;
        $this->router = $router;
        $this->user = $tokenStorage->getToken()->getUser();
    }
 
    public function Meses() {
        
        return array(
            1 =>'Enero',
            2 =>'Febrero',
            3 =>'Marzo',
            4 =>'Abril',
            5 =>'Mayo',
            6 =>'Junio',
            7 =>'Julio',
            8 =>'Agosto',
            9 =>'Septiembre',
            10 =>'Octubre',
            11 =>'Noviembre',
            12 =>'Diciembre'
        );
        
    }
}
