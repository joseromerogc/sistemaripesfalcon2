<?php

namespace MisionSucre\RipesBundle\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\HttpFoundation\Request;

class Ambiente {
 
    protected $em;
    protected $router;
    protected $user;
 
    public function __construct(EntityManager $entityManager, Router $router, TokenStorage $tokenStorage) {
        $this->em = $entityManager;
        $this->router = $router;
        $this->user = $tokenStorage->getToken()->getUser();
    }
 
    public function AnexoModalidad($anexo) {
    /*
     * Muestra los ambientes con periodos y sin periodos academico de un respectivo anexo
     */
    $ambientes = array();
    
    if($this->user->getTipUsr()==5)    
    {    
        $caldea = $this->em
                            ->getRepository('MisionSucreRipesBundle:CoordinadorAldea')
                            ->findOneByUser($this->user->getId());
//        ID COORDINADOR
        $idc = $caldea->getId();
    /*
     * TRIMESTRALES
     */            
        $ambientes['CTA']['conperiodos'] =  $this->em->getRepository('MisionSucreRipesBundle:AnexoAldea')->AmbientesConPeriodoAcademicosCoordinador($anexo,$idc,'TRIMESTRAL');
        $ambientes['CTA']['sinperiodos'] =  $this->em->getRepository('MisionSucreRipesBundle:AnexoAldea')->AmbientesSinPeriodoAcademicosCoordinador($anexo,$idc,'TRIMESTRAL');
    /*
     * SEMESTRALES
     */            
        //        $ambientesubv = array();                               
                $ambientes['UBV']['conperiodos'] =  $this->em->getRepository('MisionSucreRipesBundle:AnexoAldea')->AmbientesConPeriodoAcademicosCoordinador($anexo,$idc,'SEMESTRAL');               
                $ambientes['UBV']['sinperiodos'] =  $this->em->getRepository('MisionSucreRipesBundle:AnexoAldea')->AmbientesSinPeriodoAcademicosCoordinador($anexo,$idc,'SEMESTRAL');
    /*
     * TI
     */                            
                $ambientes['TI']['conperiodos']=  $this->em->getRepository('MisionSucreRipesBundle:AnexoAldea')->AmbientesConPeriodoAcademicosCoordinador($anexo,$idc,'TI');
                $ambientes['TI']['sinperiodos']=  $this->em->getRepository('MisionSucreRipesBundle:AnexoAldea')->AmbientesSinPeriodoAcademicosCoordinador($anexo,$idc,'TI');
                
    }           
    else{
    /*
     * TRIMESTRALES
     */            
        $ambientes['CTA']['conperiodos'] =  $this->em->getRepository('MisionSucreRipesBundle:AnexoAldea')->AmbientesConPeriodoAcademicos($anexo,'TRIMESTRAL');
        $ambientes['CTA']['sinperiodos'] =  $this->em->getRepository('MisionSucreRipesBundle:AnexoAldea')->AmbientesSinPeriodoAcademicos($anexo,'TRIMESTRAL');
    /*
     * SEMESTRALES
     */            
        //        $ambientesubv = array();                               
                $ambientes['UBV']['conperiodos'] =  $this->em->getRepository('MisionSucreRipesBundle:AnexoAldea')->AmbientesConPeriodoAcademicos($anexo,'SEMESTRAL');               
                $ambientes['UBV']['sinperiodos'] =  $this->em->getRepository('MisionSucreRipesBundle:AnexoAldea')->AmbientesSinPeriodoAcademicos($anexo,'SEMESTRAL');
                $ambientes['TI']['conperiodos']=  $this->em->getRepository('MisionSucreRipesBundle:AnexoAldea')->AmbientesSinPeriodoAcademicosCoordinador($anexo,'TI');
                $ambientes['TI']['sinperiodos']=  $this->em->getRepository('MisionSucreRipesBundle:AnexoAldea')->AmbientesSinPeriodoAcademicos($anexo,'TI');
                
    }            
        return $ambientes;
    }
    
    public function TurnosAldea($aldea) {
    /*
     * Muestra los turnos de una aldea y especifica los turnos si es un coordinador
     */
    
    if($this->user->getTipUsr()==5)    
    {   /*
     * Previamente Validado
     */
        $caldea = $this->em
                            ->getRepository('MisionSucreRipesBundle:CoordinadorAldea')
                            ->findOneByUser($this->user->getId());
//        ID COORDINADOR
        $idc = $caldea->getId();
    
    $arrayturnos = $this->em
                            ->getRepository('MisionSucreRipesBundle:CoordinadorTurno')
                            ->findAllByCoordinador($idc);
    
    foreach ($arrayturnos as $value) {
                    $turnos[$value['turno']]=$value['turno'];
                    }    
    
    }           
    else{
    $arrayturnos = $this->em
                            ->getRepository('MisionSucreRipesBundle:CoordinadorTurno')
                            ->TurnosAldea($aldea);
     
    foreach ($arrayturnos as $value) {
                    $turnos[$value['turno']]=$value['turno'];
                    } 
    }            
        return $turnos;
    }
    
    public function AldeaModalidad($aldea) {
    /*
     * Muestra los ambientes con periodos y sin periodos academico de un respectivo aldea
     */
    $ambientes = array();
    
    if($this->user->getTipUsr()==5)    
    {    
        $caldea = $this->em
                            ->getRepository('MisionSucreRipesBundle:CoordinadorAldea')
                            ->findOneByUser($this->user->getId());
//        ID COORDINADOR
        $idc = $caldea->getId();
    /*
     * TRIMESTRALES
     */            
        $ambientes['CTA']['conperiodos'] =  $this->em->getRepository('MisionSucreRipesBundle:Ambiente')->PorAldeaModalidadConPeriodoCoordinador($aldea,$idc,'TRIMESTRAL');
        $ambientes['CTA']['sinperiodos'] =  $this->em->getRepository('MisionSucreRipesBundle:Ambiente')->PorAldeaModalidadSinPeriodoCoordinador($aldea,$idc,'TRIMESTRAL');
    /*
     * SEMESTRALES
     */            
        //        $ambientesubv = array();                               
                $ambientes['UBV']['conperiodos'] =  $this->em->getRepository('MisionSucreRipesBundle:Ambiente')->PorAldeaModalidadConPeriodoCoordinador($aldea,$idc,'SEMESTRAL');               
                $ambientes['UBV']['sinperiodos'] =  $this->em->getRepository('MisionSucreRipesBundle:Ambiente')->PorAldeaModalidadSinPeriodoCoordinador($aldea,$idc,'SEMESTRAL');
    /*
     * TI
     */                            
                $ambientes['TI']['conperiodos']=  $this->em->getRepository('MisionSucreRipesBundle:Ambiente')->PorAldeaModalidadConPeriodoCoordinador($aldea,$idc,'TI');
                $ambientes['TI']['sinperiodos']=  $this->em->getRepository('MisionSucreRipesBundle:Ambiente')->PorAldeaModalidadSinPeriodoCoordinador($aldea,$idc,'TI');
                
    }           
    else{
    /*
     * TRIMESTRALES
     */            
        $ambientes['CTA']['conperiodos'] =  $this->em->getRepository('MisionSucreRipesBundle:Ambiente')->PorAldeaModalidadConPeriodo($aldea,'TRIMESTRAL');
        $ambientes['CTA']['sinperiodos'] =  $this->em->getRepository('MisionSucreRipesBundle:Ambiente')->PorAldeaModalidadSinPeriodo($aldea,'TRIMESTRAL');
    /*
     * SEMESTRALES
     */            
        //        $ambientesubv = array();                               
                $ambientes['UBV']['conperiodos'] =  $this->em->getRepository('MisionSucreRipesBundle:Ambiente')->PorAldeaModalidadConPeriodo($aldea,'SEMESTRAL');               
                $ambientes['UBV']['sinperiodos'] =  $this->em->getRepository('MisionSucreRipesBundle:Ambiente')->PorAldeaModalidadSinPeriodo($aldea,'SEMESTRAL');
                $ambientes['TI']['conperiodos']=  $this->em->getRepository('MisionSucreRipesBundle:Ambiente')->PorAldeaModalidadConPeriodo($aldea,'TI');
                $ambientes['TI']['sinperiodos']=  $this->em->getRepository('MisionSucreRipesBundle:Ambiente')->PorAldeaModalidadSinPeriodo($aldea,'TI');
                
    }            
        return $ambientes;
    }

    public function Lista() {
    /*
     * Muestra los ambientes con periodos y sin periodos academico de un respectivo aldea
     */
    $ambientes = array();
    
    $user = $this->user;
        
        switch($user->getTipUsr()){
        
        case 5:
            $coordinador = $this->em
                            ->getRepository('MisionSucreRipesBundle:CoordinadorAldea')
                            ->findOneByUser($this->user->getId());
            $ambientes['conperiodos'] = $this->em->getRepository('MisionSucreRipesBundle:CoordinadorTurno')->AmbientesTurnos($coordinador->getId());
            $ambientes['sinperiodos'] = $this->em->getRepository('MisionSucreRipesBundle:CoordinadorTurno')->AmbientesTurnosSinPeriodoAcademico($coordinador->getId());
        
            break;
        case 8:    
        
        $usreje = $this->em->getRepository('MisionSucreRipesBundle:CoordinadorEje')->findOneByUser($user->getId());
        
        $ambientes['conperiodos'] = $this->em->getRepository('MisionSucreRipesBundle:Ambiente')->findAllOrderedByAmbienteAndEje($usreje->getEje()->getId());
        $ambientes['sinperiodos'] = $this->em->getRepository('MisionSucreRipesBundle:Ambiente')->findAllOrderedByPeriodoAcademicoAndEje($usreje->getEje()->getId());
        
        break;
    
        case 1:
        $ambientes['conperiodos'] = $this->em->getRepository('MisionSucreRipesBundle:Ambiente')->findAllOrderedByAmbiente();
        $ambientes['sinperiodos'] = $this->em->getRepository('MisionSucreRipesBundle:Ambiente')->findAllOrderedByPeriodoAcademico();
        break;
        }
    
        return $ambientes;
    }
}
