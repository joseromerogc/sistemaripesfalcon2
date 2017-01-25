<?php

namespace MisionSucre\RipesBundle\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\HttpFoundation\Request;

class Datos {
 
    protected $em;
    protected $router;
    protected $user;
 
    public function __construct(EntityManager $entityManager, Router $router, TokenStorage $tokenStorage) {
        $this->em = $entityManager;
        $this->router = $router;
        $this->user = $tokenStorage->getToken()->getUser();
    }
 
    public function Municipios() {
        
    $choices = array();
    
    $municipios = $this->em
            ->getRepository('MisionSucreRipesBundle:Municipio')
            ->findAll();
    
    foreach ($municipios as $m) {
        $choices[$m->getId()] =$m->getMunicipio();
    }
    return $choices;
    }
}
