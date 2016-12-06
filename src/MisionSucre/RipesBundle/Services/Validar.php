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
    protected $msg;
 
    public function __construct(EntityManager $entityManager, Router $router, TokenStorage $tokenStorage) {
        $this->em = $entityManager;
        $this->router = $router;
        $this->user = $tokenStorage->getToken()->getUser();
        $this->msg = array(
            'denegadoaldea'=>'Accesso Denegado a esta Aldea',
            'denegadoeje'=>'Accesso Denegado a este Eje',
            'noambiente'=>'Triunfador no Vinculado a este Ambiente',
            'triunfadornovinculado' => 'Triunfador no Vinculado a un Ambiente',
            'docentenovinculado' => 'Docente no Vinculado a una Aldea',
            'docentenoaldea' => 'Este Docente no es de esta Aldea'
        );
    }
 
    public function ValidarAmbiente($ambiente,$idaldea,Request $request) {
       
        if(!$ambiente){
            
            $msg='Ambiente con ID: '.$idambiente.' no registrado';
            
            $request->getSession()->getFlashBag()->add(
            'notice',
            $msg
                );
            return $this->redirectUsuario($msg);
                }
                
        switch($this->user->getTipUsr()){
                
                    case 5:
                        $caldea = $this->em
                            ->getRepository('MisionSucreRipesBundle:CoordinadorAldea')
                            ->findOneByUser($this->user->getId());
                            
                            if( $caldea->getAldea()->getId() != $idaldea) {   
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            $this->msg['denegadoaldea']
                            );
                            return $this->redirectUsuario($this->msg['denegadoaldea']);
                            }
                        break;
                    case 8:
                        $ceje = $this->em
                            ->getRepository('MisionSucreRipesBundle:CoordinadorEje')
                            ->findOneByUser($this->user->getId());
                            
                            if( $ceje->getEje()->getId() != $aldea->getParroquia()->getEje()->getId()) {   
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            $this->msg['denegadoeje']
                            );
                            return $this->redirectUsuario($msg);
                            }
                        break;
                }
    }
public function redirectUsuario($idusr=NULL,$msg="")    {
    
    $url='usuario_show';
            $param='id';
            if($idusr){
            $valueparam=$idusr;
            }
            else{
            $valueparam=$this->user->getId();}
                return array('url'=>$url,'param'=>$param,'valueparam'=>$valueparam,'msg'=>$msg);
}

public function redirectAmbiente($idamb,$msg="")    {
    
    $url='ambiente_show';
            $param='idamb';
            $valueparam=$idamb;
                return array('url'=>$url,'param'=>$param,'valueparam'=>$valueparam,'msg'=>$msg);
}

public function ValidarUsuario($user,$role,$id,Request $request) {

    if(!$user){
            $msg='Usuario con ID: '.$id.' no registrado';
            $request->getSession()->getFlashBag()->add(
            'notice',
            $msg
                );
                return $this->redirectUsuario($id,$msg);
                }
                if($user->getTipUsr()!=$role){
                     $msg='Usuario No es de Tipo Especificado';   
                $request->getSession()->getFlashBag()->add(
                'notice',
                $msg
                );
                return $this->redirectUsuario($id,$msg);
                }
}

public function ValidarTriunfador($idusr,Request $request,$idamb) {
    
    $usertriunfador = $this->em
                ->getRepository('MisionSucreRipesBundle:User')
                ->find($idusr);
    
    if($this->ValidarUsuario($usertriunfador,6,$idusr,$request)){
            return $this->ValidarUsuario($usertriunfador,6,$idusr,$request);
    }
    
    $triunfador = $this->em
                ->getRepository('MisionSucreRipesBundle:Triunfador')
                ->findOneByUser($idusr);
    
    if(!$triunfador){
        $msg='Triunfador no Vinculado a un Ambiente';   
        $request->getSession()->getFlashBag()->add(
                            'notice',
                            $this->msg['triunfadornovinculado']
                            );
        return $this->redirectAmbiente($idamb,$this->msg['triunfadornovinculado']);
    }
    
    $ambiente = $triunfador->getAmbiente();   
    
    if($ambiente->getId()!=$idamb)
    {   
        $request->getSession()->getFlashBag()->add(
                            'notice',
                            $this->msg['noambiente']
                            );
        return $this->redirectAmbiente($idamb,$this->msg['noambiente']);
    }
                $idaldea = $ambiente->getAldea()->getId();
                
                $aldea = $ambiente->getAldea();
                
                $this->ValidarAldea($idaldea);
}

public function ValidarDocente($idusr,Request $request) {
    
    $user = $this->em
                ->getRepository('MisionSucreRipesBundle:User')
                ->find($idusr);
    
    if($this->ValidarUsuario($user,7,$idusr,$request)){
            return $this->ValidarUsuario($user,7,$idusr,$request);
    }
    
    $docente = $this->em
                ->getRepository('MisionSucreRipesBundle:Docente')
                ->findOneByUser($idusr);
    
    if(!$docente){
        $request->getSession()->getFlashBag()->add(
                            'notice',
                            $this->msg['docentenovinculado']
                            );
        return $this->redirectUsuario($idusr,$this->msg['docentenovinculado']);
    }
}                

public function ValidarDocenteAldea($idusr,$idaldea,Request $request) {
    
    if($this->ValidarDocente($idusr,$request))
        return $this->ValidarDocente($idusr,$request);
    if($this->ValidarAldea($idaldea,$request))
        return $this->ValidarAldea($idaldea,$request);
    
    if(!$this->em
        ->getRepository('MisionSucreRipesBundle:Docente')
        ->findDocenteByUserAndId($idaldea,$idusr) ){
        $request->getSession()->getFlashBag()->add(
                            'notice',
                            $this->msg['docentenoaldea']
                            );
        return $this->redirectUsuario($this->user->getId(),$this->msg['docentenoaldea']);
        }
                 
}

public function ValidarAldea($idaldea,Request $request) {
    
    $aldea = $this->em
                ->getRepository('MisionSucreRipesBundle:Aldea')
                ->find($idaldea);
    
    if(!$aldea){
        $request->getSession()->getFlashBag()->add(
                            'notice',
                            "Aldea con ID:".$idaldea."No Existe"
                            );
        return $this->redirectUsuario($this->user->getId(),"Aldea con ID:".$idaldea."No Existe");
    }
    
                    switch($this->user->getTipUsr()){
                
                    case 5:
                        $caldea = $this->em
                            ->getRepository('MisionSucreRipesBundle:CoordinadorAldea')
                            ->findOneByUser($this->user->getId());

                            if( $caldea->getAldea()->getId() != $idaldea) {   
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            $this->msg['denegadoaldea']
                            );
                            return $this->redirectUsuario($id,$this->msg['denegadoaldea']);
                            }
                        break;
                    case 8:
                        $ceje = $this->em
                            ->getRepository('MisionSucreRipesBundle:CoordinadorEje')
                            ->findOneByUser($this->user->getId());

                            if( $ceje->getEje()->getId() != $aldea->getParroquia()->getEje()->getId()) { 
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            $this->msg['denegadoeje']
                            );
                            return $this->redirectUsuario($id,$this->msg['denegadoeje']);
                            }
                        break;
                }
}                

}
