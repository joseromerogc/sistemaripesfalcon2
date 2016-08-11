<?php

namespace MisionSucre\RipesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MisionSucre\RipesBundle\Entity\CoordinadorTurno;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;

class CoordinadorTurnoController extends Controller
{
    public function indexAction()
    {
        return $this->render('MisionSucreRipesBundle:Usuario:index.html.twig');
    }
	
	public function newAction(Request $request,$id)
	{       
                $em = $this->getDoctrine()->getManager();
                
		$turnocoordinador = new CoordinadorTurno();
                
                 $caldea = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:CoordinadorAldea')
                ->find($id);    
                 
                 if(!$caldea){

                $request->getSession()->getFlashBag()->add(
                'notice',
                'Coordinador no Registrado(a)'
                );
                return $this->redirect($this->generateUrl('usuario_show',array('id'=>$id)));
                }  
                
                $aldea = $caldea->getAldea();
                
                $per = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Persona')
                ->findOneByUser($caldea->getUser());    
                
                if($this->getUser()->getTipUsr()==8){
                $user = $this->getUser();
                $usreje = $em->getRepository('MisionSucreRipesBundle:CoordinadorEje')->findOneByUser($user->getId());
                if($usreje->getEje()->getId()!=$aldea->getParroquia()->getEje()->getId())
                    {
                    $request->getSession()->getFlashBag()->add(
                                    'notice',
                                    "Acceso Denegado al Eje"
                                    );            
                            return $this->redirect($this->generateUrl('aldea_new'));
                    }
                }
                
                        
		$form = $this->createFormBuilder($turnocoordinador)->
        add('turno', 'choice', array(
        'choices' => $this->choicesTurno($aldea),
            'placeholder'=>"Seleccione una",'label' => 'Turno'
            ))                           
        ->add('save', 'submit',array('label' => 'Registrar Turno Coordinador de Aldea'))->getForm();
            
                $form->handleRequest($request);
                
                
		if ($form->isValid()) {
                    
                    
                    
                    if($em->getRepository('MisionSucreRipesBundle:CoordinadorTurno')->ExisteTurno($turnocoordinador->getTurno(),$aldea)){
                        $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Turno ya Registrado en esta Aldea'
                            );            
                    }
                    else{
                            $em = $this->getDoctrine()->getManager();    
                            $turnocoordinador->setCoordinador($caldea);        
                            $em->persist($turnocoordinador);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Turno Creado con Éxito'
                            );            
                            return $this->redirect($this->generateUrl('aldea_show',array('id'=>$aldea->getId())));
                    }
		}
		
		return $this->render('MisionSucreRipesBundle:CoordinadorAldeaTurno:new.html.twig', array(
		'form' => $form->createView(),'mensaje_heading'=>'Nuevo Turno',
                    'sub_heading'=>"Aldea Universitaria ".$aldea->getNombre(),'usr'=>$per->getUser(),'per' => $per,"aldea"=>$aldea
		));
	}
        
    public function updateAction(Request $request,$idturno)
	{       
                $em = $this->getDoctrine()->getManager();
                
                 $turnocoordinador = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:CoordinadorTurno')
                ->find($idturno);    
                 
                 if(!$turnocoordinador){

                $request->getSession()->getFlashBag()->add(
                'notice',
                'Turno no Registrado(a)'
                );
                return $this->redirect($this->generateUrl('home'));
                }  
                
                $caldea=$turnocoordinador->getCoordinador();
                
                $aldea = $caldea->getAldea();
                
                $per = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Persona')
                ->findOneByUser($caldea->getUser());    
                
                if($this->getUser()->getTipUsr()==8){
                $user = $this->getUser();
                $usreje = $em->getRepository('MisionSucreRipesBundle:CoordinadorEje')->findOneByUser($user->getId());
                if($usreje->getEje()->getId()!=$aldea->getParroquia()->getEje()->getId())
                    {
                    $request->getSession()->getFlashBag()->add(
                                    'notice',
                                    "Acceso Denegado al Eje"
                                    );            
                            return $this->redirect($this->generateUrl('aldea_new'));
                    }
                }
                
                        
		$form = $this->createFormBuilder($turnocoordinador)->
        add('turno', 'choice', array(
        'choices' => $this->choicesTurno($aldea),
            'placeholder'=>"Seleccione una",'label' => 'Turno'
            ))                           
        ->add('save', 'submit',array('label' => 'Actualizar Turno Coordinador de Aldea'))->getForm();
            
                $form->handleRequest($request);
                
                
		if ($form->isValid()) {
                    
                    
                    
                    if($em->getRepository('MisionSucreRipesBundle:CoordinadorTurno')->ExisteTurno($turnocoordinador->getTurno(),$aldea)){
                        $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Turno ya Registrado en esta Aldea'
                            );            
                    }
                    else{
                            $em = $this->getDoctrine()->getManager();    
                            $turnocoordinador->setCoordinador($caldea);        
                            $em->persist($turnocoordinador);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Turno Cambiado con Éxito'
                            );            
                            return $this->redirect($this->generateUrl('usuario_show',array('id'=>$caldea->getUser()->getId())));
                    }
		}
		
		return $this->render('MisionSucreRipesBundle:CoordinadorAldeaTurno:new.html.twig', array(
		'form' => $form->createView(),'mensaje_heading'=>'Cambiar Turno',
                    'sub_heading'=>"Aldea Universitaria ".$aldea->getNombre(),'usr'=>$per->getUser(),'per' => $per,"aldea"=>$aldea
		));
	}
    
     public function deleteAction(Request $request,$idturno)
	{       
                $em = $this->getDoctrine()->getManager();
                
                 $turnocoordinador = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:CoordinadorTurno')
                ->find($idturno);    
                 
                 if(!$turnocoordinador){

                $request->getSession()->getFlashBag()->add(
                'notice',
                'Turno no Registrado(a)'
                );
                return $this->redirect($this->generateUrl('home'));
                }  
                
                $caldea=$turnocoordinador->getCoordinador();
                
                $aldea = $caldea->getAldea();
                
                $per = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Persona')
                ->findOneByUser($caldea->getUser());    
                
                if($this->getUser()->getTipUsr()==8){
                $user = $this->getUser();
                $usreje = $em->getRepository('MisionSucreRipesBundle:CoordinadorEje')->findOneByUser($user->getId());
                if($usreje->getEje()->getId()!=$aldea->getParroquia()->getEje()->getId())
                    {
                    $request->getSession()->getFlashBag()->add(
                                    'notice',
                                    "Acceso Denegado al Eje"
                                    );            
                            return $this->redirect($this->generateUrl('aldea_new'));
                    }
                }
                
               $em->remove($turnocoordinador);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Turno Borrado con Éxito'
                            );

                    return $this->redirect($this->generateUrl('usuario_show',array('id'=>$caldea->getUser()->getId())));
	}
        
        public function choicesTurno($aldea) {
        
            $em = $this->getDoctrine()->getManager();
    $turnosdisponibles = array("Nocturno"=>"Nocturno",
        "Viernes y Sábado"=>"Viernes y Sábado",
        "Fines de Semana"=>"Fines de Semana",
        "Vespertino"=>"Vespertino"
        );
   
    
    $turnosaldeas=$em->getRepository('MisionSucreRipesBundle:CoordinadorTurno')->TurnosAldea($aldea);
    
    $turnosregistrados=  array();
    
    foreach ($turnosaldeas as $key => $value) {
        $v=$value['turno'];
       $turnosregistrados[$v]=$v;
        
    }
    
    
    if($turnosregistrados){
    $turnos= array_diff($turnosdisponibles,$turnosregistrados);
    }
    else{
        $turnos=$turnosdisponibles;
    }

    return $turnos;
    
        }
 
 }

