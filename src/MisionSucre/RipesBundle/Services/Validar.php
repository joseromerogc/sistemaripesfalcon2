<?php

namespace MisionSucre\RipesBundle\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\HttpFoundation\Request;

class Validar {
 
    protected $em;
    protected $router;
    protected $user;
 
    public function __construct(EntityManager $entityManager, Router $router, TokenStorage $tokenStorage) {
        $this->em = $entityManager;
        $this->router = $router;
        $this->user = $tokenStorage->getToken()->getUser();
    }
 
    public function ValidarAmbiente($ambiente,$idaldea,Request $request) {
       
        if(!$ambiente){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Ambiente con ID: '.$idambiente.' no registrado'
                );
            return $this->redirectUsuario();
                }
                
        switch($this->user->getTipUsr()){
                
                    case 5:
                        $caldea = $this->em
                            ->getRepository('MisionSucreRipesBundle:CoordinadorAldea')
                            ->findOneByUser($this->user->getId());

                            if( $caldea->getAldea()->getId() != $idaldea) {   
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Accesso Denegado a esta Aldea'
                            );
                            return $this->redirectUsuario();
                            }
                        break;
                    case 8:
                        $ceje = $this->em->getDoctrine()->getManager()
                            ->getRepository('MisionSucreRipesBundle:CoordinadorEje')
                            ->findOneByUser($this->user->getId());

                            if( $ceje->getEje()->getId() != $aldea->getParroquia()->getEje()->getId()) {   
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Accesso Denegado a este Eje'
                            );
                            return $this->redirectUsuario();
                            }
                        break;
                }
    }
public function redirectUsuario()    {
    $url='usuario_show';
            $param='id';
            $valueparam=$this->user->getId();
                return array('url'=>$url,'param'=>$param,'valueparam'=>$valueparam);
}
 
}
