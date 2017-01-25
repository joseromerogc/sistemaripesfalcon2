<?php

namespace MisionSucre\RipesBundle\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\HttpFoundation\Request;

/*VALIDAR */
//                $validar = $this->get('servicios.validar');
//                
//                $error = $validar->ValidarAldea($aldea->getId(), $request);
//                if($error)
//                    return $this->redirect($this->generateUrl($error['url'],array($error['param']=>$error['valueparam'])));
                /*FIN VALIDAR*/

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
            'denegadoaldea'=>'Acceso Denegado a esta Aldea',
            'denegadoeje'=>'Acceso Denegado a este Eje',
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
            return $this->redirectUsuario(NULL,$msg);
                }
        $turnoambiente = $ambiente->getTurno();
        $aldea = $ambiente->getAldea();
                
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
                            return $this->redirectUsuario(NULL,$this->msg['denegadoaldea']);
                            }
                            if( !$this->em->getRepository('MisionSucreRipesBundle:CoordinadorTurno')->CoordinadorTurno($turnoambiente,$caldea->getId())) {   
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Acesso denegado a este Turno'
                            );
                            return $this->redirectUsuario(NULL,'Acesso denegado a este Turno');
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
                            return $this->redirectUsuario(NULL,$msg);
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

public function ValidarUsuarioID($idusr,$role,Request $request) {

    $user = $this->em
                ->getRepository('MisionSucreRipesBundle:User')
                ->find($idusr);
    return $this->ValidarUsuario($user,$role,$idusr,$request);
}

public function ValidarIdUsuario($id,Request $request,$extra=false) {
    /*
     * Funcion para validar un ID entre los diferentes ROLES
     * @param id de usuario
     * @extra para darle unos permiso extras a ciertos roles
     */
    
    if($this->existeUsuario($id,$request)){
            return $this->existeUsuario($id,$request);
    }
    
    switch($this->user->getTipUsr()){
                
                    case 5:
                        return $this->ValidarIdUsuarioCoordinadorTurno($id,$request);
                        break;
                    case 8:
                        return $this->ValidarIdUsuarioCoordinadorEje($id,$request);
                        break;
                    case 2;
                    case 10;
                        if(!$extra){
                           if(!$this->AutoUsuario($idusr))
                            return $this->redirectUsuario(NULL,'Permiso Denegado a este Usuario');
                        }
                }
    
}

private function AutoUsuario($id) {
    /*
     * funcion para validar si el id pasado es de el suyo
     */    
    if($this->user->getId()==$id)
        return true;
    else
        return false;                
}

public function existeUsuario($idusr,Request $request) {
    
    $user = $this->em
                ->getRepository('MisionSucreRipesBundle:User')
                ->find($idusr);
    
    if(!$user)
                {   
                    $msg = "Usuario con ID: $id No Registrado";
                    $request->getSession()->getFlashBag()->add(
                            'notice',
                            $msg
                            );            
                return $this->redirectUsuario(NULL,$msg);    
                }
}

public function ValidarIdUsuarioCoordinadorTurno($idusr,Request $request) {
    /*
    Valida si el ID de un usuario es del turno de un coordinador de aldea especifico
     *      */
    $user = $this->em
                ->getRepository('MisionSucreRipesBundle:User')
                ->find($idusr);
    
    $caldea = $this->em
                            ->getRepository('MisionSucreRipesBundle:CoordinadorAldea')
                            ->findOneByUser($this->user->getId());
    if(!$caldea)
                {   
                    $msg = "Coordinador de aldea no Vinculado";
                    $request->getSession()->getFlashBag()->add(
                            'notice',
                            $msg
                            );            
                return $this->redirectUsuario(NULL,$msg);    
                }
    
    switch($user->getTipUsr()){
                
                    case 5:
                        if(!$this->AutoUsuario($idusr))
                            return $this->redirectUsuario(NULL,'Permiso Denegado a este Usuario');
                    break;
                    case 6:
                        if($this->ValidarTriunfador($idusr,$request)){
                       return $this->ValidarTriunfador($idusr,$request);
                            }
                        $triunfador = $this->em
                            ->getRepository('MisionSucreRipesBundle:Triunfador')
                            ->findOneByUser($idusr);                            
                        //Se verifica si el ambiente pertenece a su turno correspondiente
                        return $this->ValidarAmbienteCoordinador($triunfador->getAmbiente(),$caldea,$request);
                    break;
                    case 7:
                        return $this->ValidarDocenteAldea($idusr,$caldea->getAldea()->getId(),$request);
                    break;
                    case 9:
                        return $this->ValidarOperarioAldea($idusr,$caldea->getAldea()->getId(),$request,$caldea->getId());
                    break;
                    default :
                        return $this->redirectUsuario(NULL,'Usuario Denegado');
                
                }
}

public function ValidarIdUsuarioCoordinadorEje($idusr,Request $request) {
    /*
    Valida si el ID de un usuario es del turno de un coordinador de eje especifico
     *      */
    $user = $this->em
                ->getRepository('MisionSucreRipesBundle:User')
                ->find($idusr);
    
    $ceje = $this->em
                            ->getRepository('MisionSucreRipesBundle:CoordinadorEje')
                            ->findOneByUser($this->user->getId());
    if(!$ceje)
                {   
                    $msg = "Coordinador de aldea no Vinculado";
                    $request->getSession()->getFlashBag()->add(
                            'notice',
                            $msg
                            );            
                return $this->redirectUsuario(NULL,$msg);    
                }
    
    switch($user->getTipUsr()){
                
                    case 5:
                        if($this->ValidarCoordinadorAldea($idusr,$request))
                            return ValidarCoordinadorAldea($idusr,$request);
                        $caldea = $this->em
                            ->getRepository('MisionSucreRipesBundle:CoordinadorAldea')
                            ->findOneByUser($idusr);
                        return $this->ValidarAldeaEje($caldea->getAldea(),$ceje,$request);
                    break;
                    case 8:
                        if(!$this->AutoUsuario($idusr))
                            return $this->redirectUsuario(NULL,'Permiso Denegado a este Usuario');
                    break;
                    case 6:
                        if($this->ValidarTriunfador($idusr,$request)){
                       return $this->ValidarTriunfador($idusr,$request);
                            }
                        $triunfador = $this->em
                            ->getRepository('MisionSucreRipesBundle:Triunfador')
                            ->findOneByUser($idusr);                            
                        //Se verifica si el ambiente pertenece a su turno correspondiente
                        return $this->ValidarAldeaEje($triunfador->getAmbiente()->getAldea(),$ceje,$request);
                    break;
                    case 7:
                        if($this->ValidarDocente($idusr,$request))
                            return ValidarDocente($idusr,$request);
                        $docente = $this->em
                            ->getRepository('MisionSucreRipesBundle:Docente')
                            ->findOneByUser($idusr);
                        return $this->ValidarAldeaEje($docente->getAldea(),$ceje,$request);
                    break;
                    case 9:
                        if($this->ValidarOperario($idusr,$request))
                            return ValidarOperario($idusr,$request);
                        $operario = $this->em
                            ->getRepository('MisionSucreRipesBundle:Operario')
                            ->findOneByUser($idusr);
                        return $this->ValidarAldeaEje($operario->getAldea(),$ceje,$request);
                    break;
                    default :
                        return $this->redirectUsuario(NULL,'Usuario Denegado');
                
                }
}

public function ValidarAmbienteCoordinador($ambiente,$caldea,Request $request) {
    /*
     * Validad si un ambiente pertenece a un turno del coordinador
     */
    
    $turnoambiente = $ambiente->getTurno();
    $idaldea = $ambiente->getAldea()->getId();
    
    if( $caldea->getAldea()->getId() != $idaldea) {   
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            $this->msg['denegadoaldea']
                            );
                            return $this->redirectUsuario(NULL,$this->msg['denegadoaldea']);
                            }
                
                if( !$this->em->getRepository('MisionSucreRipesBundle:CoordinadorTurno')->
                        CoordinadorTurno($turnoambiente,$caldea->getId())) {   
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Acceso Denegado a este Turno'
                            );
                            return $this->redirectUsuario(NULL,$this->msg['denegadoaldea']);
                            }
}

public function ValidarTriunfadorAmbiente($idusr,Request $request,$idamb) {
    
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
                
                $this->ValidarAldea($idaldea,$request);
}


public function ValidarDocente($idusr,Request $request) {
    /*
    Valida si un usuario es de tipo Docente y si este Vinculado
     *      */
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
        return array('url'=>'docente_asignar','param'=>NULL,'valueparam'=>NULL,$this->msg['docentenovinculado']);
    }
}
public function ValidarOperario($idusr,Request $request) {
    /*
    Valida si un usuario es de tipo Docente y si este Vinculado
     *      */
    $user = $this->em
                ->getRepository('MisionSucreRipesBundle:User')
                ->find($idusr);
    
    if($this->ValidarUsuario($user,9,$idusr,$request)){
            return $this->ValidarUsuario($user,9,$idusr,$request);
    }
    
    $operario = $this->em
                ->getRepository('MisionSucreRipesBundle:Operario')
                ->findOneByUser($idusr);
    
    if(!$operario){
        $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Operario no Vinculado'
                            );
        return array('url'=>'usuario_lista','param'=>NULL,'valueparam'=>NULL,'Operario no Vinculado');
    }
}

public function ValidarTriunfador($idusr,Request $request) {
    
    $user = $this->em
                ->getRepository('MisionSucreRipesBundle:User')
                ->find($idusr);
    
    if($this->ValidarUsuario($user,6,$idusr,$request)){
            return $this->ValidarUsuario($user,6,$idusr,$request);
    }
    
    $triunfador = $this->em
                ->getRepository('MisionSucreRipesBundle:Triunfador')
                ->findOneByUser($idusr);
    
    if(!$triunfador){
        $request->getSession()->getFlashBag()->add(
                            'notice',
                            $this->msg['triunfadornovinculado']
                            );
        return array('url'=>'vincular_triunfador','param'=>NULL,'valueparam'=>NULL,$this->msg['triunfadornovinculado']);
    }
}                

public function ValidarCoordinadorAldea($idusr,Request $request) {
    
    $user = $this->em
                ->getRepository('MisionSucreRipesBundle:User')
                ->find($idusr);
    
    if($this->ValidarUsuario($user,5,$idusr,$request)){
            return $this->ValidarUsuario($user,5,$idusr,$request);
    }
    
    $caldea = $this->em
                ->getRepository('MisionSucreRipesBundle:CoordinadorAldea')
                ->findOneByUser($idusr);
    
    if(!$caldea){
        $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Coordinador de Aldea No Vinculado'
                            );
        return array('url'=>'aldea_coordinador_new','param'=>'id','valueparam'=>$idusr,'Coordinador de Aldea No Vinculado');
    }
}                

public function ValidarAldeaEje($aldea,$ceje, Request $request) {
    
    if($aldea->getParroquia()->getEje()->getId()!=$ceje->getEje()->getId()){
        $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Aldea No es del Eje'
                            );
        return array('url'=>'usuario_lista','param'=>NULL,'valueparam'=>NULL,'Aldea No es del Eje');
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

public function ValidarOperarioAldea($idusr,$idaldea,Request $request,$idcoordinador=NULL) {
    
    if($this->ValidarOperario($idusr,$request))
        return $this->ValidarDocente($idusr,$request);
    if($this->ValidarAldea($idaldea,$request))
        return $this->ValidarAldea($idaldea,$request);
    
    $operario = $this->em
        ->getRepository('MisionSucreRipesBundle:Operario')
        ->findOneByUser($idusr);
    
    if(!$operario->getAldea()->getId()==$idaldea ){
        $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Operario No es de esta Aldea'
                            );
        return $this->redirectUsuario($this->user->getId(),'Operario No es de esta Aldea');
        }
        
    if($idcoordinador){
      
        
        if(!$this->em->getRepository('MisionSucreRipesBundle:CoordinadorTurno')->CoordinadorTurno($operario->getTurno(),$idcoordinador))
                {
        $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Operario No es de este Turno'
                            );
        return $this->redirectUsuario($this->user->getId(),'Operario No es de este Turno');
        }
    }    
}
public function ValidarTriunfadorAldea($idusr,$idaldea,Request $request,$idcoordinador=NULL) {
    /*
     * valida el triunfador de una aldea y de coordinador
     */
    if($this->ValidarTriunfador($idusr,$request))
        return $this->ValidarTriunfador($idusr,$request);
    if($this->ValidarAldea($idaldea,$request))
        return $this->ValidarAldea($idaldea,$request);
    
    $triunfador = $this->em
        ->getRepository('MisionSucreRipesBundle:Triunfador')
        ->findOneByUser($idusr);
    $ambiente = $triunfador->getAmbiente();
    
    if(!$ambiente->getAldea()->getId()==$idaldea ){
        $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Triundador No es de esta Aldea'
                            );
        return $this->redirectUsuario($this->user->getId(),'Triunfador No es de esta Aldea');
        }
        
    if($idcoordinador){
      
        
        if(!$this->em->getRepository('MisionSucreRipesBundle:CoordinadorTurno')->CoordinadorTurno($ambiente->getTurno(),$idcoordinador))
                {
        $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Triunfador No es de esta Aldea'
                            );
        return $this->redirectUsuario($this->user->getId(),'Triunfador No es de esta Aldea');
        }
    }    
}

public function ValidarAldea($idaldea,Request $request) {
/*
 * Valida si una aldea pertenece a ese usuario
 */    
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

public function ValidarCargar($idan,Request $request) {
    
    $idusr = $this->user->getId();
    
    if($this->ValidarDocente($idusr,$request))
        return $this->ValidarDocente($idusr,$request);
    
    if(!$this->em
        ->getRepository('MisionSucreRipesBundle:ActaNota')
        ->Docente($idusr,$idan) ){
        $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Docente no puede Cargar este Ambiente'
                            );
        return $this->redirectUsuario($this->user->getId(),'Docente No puede Cargar este Ambiente');
        }
}
}
