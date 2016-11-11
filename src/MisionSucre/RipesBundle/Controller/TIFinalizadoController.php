<?php

namespace MisionSucre\RipesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MisionSucre\RipesBundle\Entity\TIFinalizado;
use MisionSucre\RipesBundle\Form\Type\AmbienteType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\HttpFoundation\Request;

class TIFinalizadoController extends Controller
{

    public function finalizarTriunfadorAction(Request $request,$idtrf,$idperiodo)
	{       
		
                $em = $this->getDoctrine()->getManager();
                
                
                $trf = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Triunfador')
                ->find($idtrf);
                
                if(!$trf){
                $request->getSession()->getFlashBag()->add(
            'notice',
            'Triunfador con ID: '.$idtrf.' no registrada'
                );
                return $this->redirect($this->generateUrl('usuario_show',array('id'=>$usr->getId())));
                }
                
                $ambiente = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Ambiente')
                ->find($trf->getAmbiente()->getId());
                
                $usr = $this->getUser();
                
                if(!$ambiente){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Ambiente con ID: '.$ambiente.' no registrada'
                );
                return $this->redirect($this->generateUrl('usuario_show',array('id'=>$usr->getId())));
                }
                
                if($ambiente->getPnf()->getModalidad()!="TI")
                {
                    $request->getSession()->getFlashBag()->add(
            'notice',
            'Ambiente no es de Trayecto Inicial'
                );
                return $this->redirect($this->generateUrl('ambiente_show',array('idamb'=>$ambiente->getId())));
                }
                
                $aldea=$ambiente->getAldea();
                        
                if($usr->getTipUsr()==8){
                    
                  $ceje = $this->getDoctrine()
                            ->getRepository('MisionSucreRipesBundle:CoordinadorEje')
                            ->findOneByUser($usr->getId());

                            if( $ceje->getEje()->getId() != $aldea->getParroquia()->getEje()->getId()) {   
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Accesso Denegado a este Eje'
                            );
                            return $this->redirect($this->generateUrl('usuario_show',array('id'=>$usr->getId())));
                            }  
                }
                
                if($usr->getTipUsr()==5){
                
                $caldea = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:CoordinadorAldea')
                ->findOneByUser($usr->getId());
                
                if($caldea->getAldea()->getId()!=$idaldea){

                $request->getSession()->getFlashBag()->add(
                'notice',
                'Usted no es Coordinador de Esta Aldea'
                );
                return $this->redirect($this->generateUrl('usuario_show',array('id'=>$usr->getId())));
                }
               
                }
                
                     $user=$trf->getUser();
                     
                     $periodoacademico = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:PeriodoAcademico')
                ->find($idperiodo);
                     
                     if(!$periodoacademico){
                     $request->getSession()->getFlashBag()->add(
                'notice',
                ' Error en el Periodo Académico'
                );
                return $this->redirect($this->generateUrl('aldea_show',array('id'=>$aldea->getId())));
                }
                     
                     $tifinalizado = new TIFinalizado();
                     $tifinalizado->setAmbiente($ambiente);
                     $tifinalizado->setPeriodoAcademico($periodoacademico);
                     $tifinalizado->setUser($user);
                     $em->persist($tifinalizado);
                     $em->remove($trf);
                  
                  $em->flush();
                  $em->clear();
           $request->getSession()->getFlashBag()->add(
                'notice',
                'Triunfador Finalizado con Éxito'
                );
                return $this->redirect($this->generateUrl('ambiente_show',array('idamb'=>$ambiente->getId())));
	}
        
}
