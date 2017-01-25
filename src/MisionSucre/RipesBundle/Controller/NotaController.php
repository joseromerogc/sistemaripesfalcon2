<?php

namespace MisionSucre\RipesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;
use MisionSucre\RipesBundle\Entity\Nota;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class NotaController extends Controller{
    
    public function cargarAction(Request $request,$idan)
	{       
        $em = $this->getDoctrine()->getManager();
        
        $actanota = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:ActaNota')
                ->find($idan);
                
                if(!$actanota){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            "Acta de Nota con ID $idan no Existe"
                );
                return $this->redirect($this->generateUrl('ambiente'));
                }
        if(strtoupper($actanota->getValidada())=='SI'){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            "Acta de Nota Bloqueada"
                );
                return $this->redirect($this->generateUrl('acta_nota_show'),  array('idan',$actanota->getId()));
                } 
                
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')){        
        $error = $validar->ValidarCarga($idan,$request);
                if($error)
                    return $this->redirect($this->generateUrl($error['url'],array($error['param']=>$error['valueparam'])));        
        }
        
                $periodoacademicoambiente = $actanota->getPeriodoacademicoambiente();
                $idpamb=$periodoacademicoambiente->getId();
                $ambiente = $periodoacademicoambiente->getAmbiente();
                $periodoacademico = $periodoacademicoambiente->getPeriodoAcademico();
                $periodopnf = $periodoacademicoambiente->getPeriodoPnf();
                $malla_pnf = $actanota->getMalla();
                $docente = $actanota->getDocente();
                $triunfadores= $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Nota')
                ->TriunfadoresVinculadosSinNotas($idan);
                $docente= $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Persona')
                ->findOneByUser($docente->getUser()->getId());
                
                return $this->render('MisionSucreRipesBundle:Notas:carga.html.twig', array(
		'periodoacademico'=>$periodoacademico,'periodoambiente'=>$periodoacademicoambiente,
                            'periodopnf'=>$periodopnf,'ambiente'=>$ambiente,'malla'=>$malla_pnf,
                'triunfadores'=> $triunfadores,'docente'=> $docente,'actanota'=>$actanota
		));                    
                
    }
    
    public function registrarNotasAction(Request $request,$notas){       
        
                $em = $this->getDoctrine()->getManager();
                
                $datos=json_decode($notas,true);
                
                $error= "";
                $idan=$datos[3];
                
                foreach($datos[0] as $i=>$id) {
                    
               $periodotriunfador = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:PeriodoTriunfador')
                ->find($id); 
               
               $actanota = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:ActaNota')
                ->find($idan); 
                
               if($periodotriunfador && $actanota){
                
                $esPeriodoAmbiente= $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:ActaNota')
                ->Triunfador($idan,$id);
                   
                $tienenota = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Nota')
                ->BuscarTriundadorActa($idan,$id);
                
                
                
                                   if(!$esPeriodoAmbiente){
                                       
                                       $error.= "ID:$id Triunfador no Vinculado a este PeriodoAcademico-Ambiente / ";
                                   }
                else {
                    
                    if($tienenota){
                        $error.= "ID:$id Triunfador ya con Notas / ";
                    }else{
                        try{
                        $nota = new Nota();
                        $nota->setTriunfador($periodotriunfador);
                        $nota->setActanota($actanota);
                        $nota->setValor($datos[2][$i]);
                        $nota->setAsistencia($datos[1][$i]);
                
                        $em->persist($nota);
                        }
                        catch (\Doctrine\ORM\EntityNotFoundException $ex) {
                            $error.="Problemas a Registrar a ID:$id  / ";
                        }
                }
               }
               
                        }
                else{
                    if(!$actanota){
                    $error.="Acta de Nota con ID:$idan No Registrado";
                    }
                    else{
                        $error.="Triunfador con ese ID no Registrado en ese PeriodoAcademico";    
                    }
                }
                    
                }
                $em->flush();
               
                if($error){
                return new Response("Se han Encontrado los siguientes Errores:<br/>:".$error);
                }
                else
                {
                    return new Response("good"); //si se ha registrado
                }
                }
                
    public function actualizarNotasAction(Request $request,$notas){       
            
                $em = $this->getDoctrine()->getManager();
                
                $datos=json_decode($notas,true);
                
                $error= "";
                $idan=$datos[3];
                
                foreach($datos[0] as $i=>$id) {
                    
               $periodotriunfador = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:PeriodoTriunfador')
                ->find($id); 
               
               $actanota = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:ActaNota')
                ->find($idan); 
                
               if($periodotriunfador && $actanota){
                
                $esPeriodoAmbiente= $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:ActaNota')
                ->Triunfador($idan,$id);
                   
                $nota = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Nota')
                ->BuscarTriundadorActa($idan,$id);
                
                                   if(!$esPeriodoAmbiente){
                                       
                                       $error.= "ID:$id Triunfador no Vinculado a este PeriodoAcademico-Ambiente / ";
                                   }
                else {
                    
                    if(!$nota){
                        $error.= "ID:$id Triunfador No tiene Nota Carga / ";
                    }else{
                        try{
                        $nota->setTriunfador($periodotriunfador);
                        $nota->setActanota($actanota);
                        $nota->setValor($datos[2][$i]);
                        $nota->setAsistencia($datos[1][$i]);
                
                        $em->persist($nota);
                        }
                        catch (\Doctrine\ORM\EntityNotFoundException $ex) {
                            $error.="Problemas a Registrar a ID:$id  / ";
                        }
                }
               }
               
                        }
                else{
                    if(!$actanota){
                    $error.="Acta de Nota con ID:$idan No Registrado";
                    }
                    else{
                        $error.="Triunfador con ese ID no Registrado en ese PeriodoAcademico";    
                    }
                }
                    
                }
                $em->flush();
               
                if($error){
                return new Response("Se han Encontrado los siguientes Errores:<br/>:".$error);
                }
                else
                {
                    return new Response("good"); //si se ha registrado
                }
}
    public function updateAction(Request $request,$idan)
	{   
        
        $em = $this->getDoctrine()->getManager();
        
        $actanota = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:ActaNota')
                ->find($idan);
                
                if(!$actanota){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            "Acta de Nota con ID $idan no Existe"
                );
                return $this->redirect($this->generateUrl('ambiente'));
                }
                
        if(strtoupper($actanota->getValidada())=='SI'){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            "Acta de Nota Bloqueada"
                );
                return $this->redirect($this->generateUrl('acta_nota_show'),  array('idan',$actanota->getId()));
                } 
                
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')){        
        $error = $validar->ValidarCarga($idan,$request);
                if($error)
                    return $this->redirect($this->generateUrl($error['url'],array($error['param']=>$error['valueparam'])));        
        }
        
                $periodoacademicoambiente = $actanota->getPeriodoacademicoambiente();
                $idpamb=$periodoacademicoambiente->getId();
                $ambiente = $periodoacademicoambiente->getAmbiente();
                $periodoacademico = $periodoacademicoambiente->getPeriodoAcademico();
                $periodopnf = $periodoacademicoambiente->getPeriodoPnf();
                $malla_pnf = $actanota->getMalla();
                $docente = $actanota->getDocente();
                $notas= $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Nota')
                ->TriunfadoresVinculadosConNotas($actanota->getId());
                $docente= $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Persona')
                ->findOneByUser($docente->getUser()->getId());
                
                return $this->render('MisionSucreRipesBundle:Notas:update.html.twig', array(
		'periodoacademico'=>$periodoacademico,'periodoambiente'=>$periodoacademicoambiente,
                            'periodopnf'=>$periodopnf,'ambiente'=>$ambiente,'malla'=>$malla_pnf,
                'notas'=> $notas,'docente'=> $docente,'actanota'=>$actanota
		));                        
    }
                
}