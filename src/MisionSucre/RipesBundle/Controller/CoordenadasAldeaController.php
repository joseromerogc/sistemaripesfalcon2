<?php

namespace MisionSucre\RipesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MisionSucre\RipesBundle\Entity\CoordenadasAldea;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;

class CoordenadasAldeaController extends Controller
{
    
    public function newAction(Request $request,$id)
	{       
        
        $em = $this->getDoctrine()->getManager();
        
        $aldea =  $em->getRepository('MisionSucreRipesBundle:Aldea')->find($id);
        
        if(!$aldea){
            
            $request->getSession()->getFlashBag()->add(
                            'notice',
                            "Aldea con ID $id No esta Registrada"
                            );            
                    return $this->redirect($this->generateUrl('aldea_new'));
        }
        
        if($this->getUser()->getTipUsr()==8){
        $user = $this->getUser();
        $usreje = $em->getRepository('MisionSucreRipesBundle:CoordinadorEje')->findOneByUser($user->getId());
        if($usreje->getEje()->getId()!=$aldea->getParroquia()->getEje()->getId())
            {
            $request->getSession()->getFlashBag()->add(
                            'notice',
                            "Acceso Denegado al Eje"
                            );            
                    return $this->redirect($this->generateUrl('aldea_lista'));
            }
        }
        
	$em = $this->getDoctrine()->getManager();
        
        $coordenadas= new CoordenadasAldea();
        
        
        $form = $this->createFormBuilder($coordenadas)->
                add('norte', 'number',array('label' => 'Coordenada Norte (N)'))->
                add('oeste', 'number',array('label' => 'Coordenada Oeste (W)'))
                ->add('save', 'submit',array('label' => 'Registrar Coordenadas'))->getForm();
                
                $form->handleRequest($request);
                
		if ($form->isValid()) {
                            
                            $em = $this->getDoctrine()->getManager();    
                            
                            $coordenadas->setAldea($aldea);
                            $em->persist($coordenadas);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            ' Coordenadas Registradas con Éxito'
                            );      
                            
                    return $this->redirect($this->generateUrl('aldea_show',array('id'=>$aldea->getId())));
		}
		
        
        return $this->render('MisionSucreRipesBundle:CoordenadasAldea:new.html.twig', array(
		'form' => $form->createView(),'mensaje_heading'=>"Registro de Coordenadas",
                    'sub_heading'=>"Aldea {$aldea->getNombre()}"
		));
        
        }
        
        public function updateAction(Request $request,$id)
	{  
            
	$em = $this->getDoctrine()->getManager();
        
        $coordenadas= $em->getRepository('MisionSucreRipesBundle:CoordenadasAldea')->find($id);
        
        $aldea = $coordenadas->getAldea();
        
        if(!$aldea){
            
            $request->getSession()->getFlashBag()->add(
                            'notice',
                            "Aldea con ID $id No esta Registrada"
                            );            
                    return $this->redirect($this->generateUrl('aldea_new'));
        }
        
        if($this->getUser()->getTipUsr()==8){
        $user = $this->getUser();
        $usreje = $em->getRepository('MisionSucreRipesBundle:CoordinadorEje')->findOneByUser($user->getId());
        if($usreje->getEje()->getId()!=$aldea->getParroquia()->getEje()->getId())
            {
            $request->getSession()->getFlashBag()->add(
                            'notice',
                            "Acceso Denegado al Eje"
                            );            
                    return $this->redirect($this->generateUrl('aldea_lista'));
            }
        }
        
	$em = $this->getDoctrine()->getManager();
        
        $form = $this->createFormBuilder($coordenadas)->
                add('norte', 'number',array('label' => 'Coordenada Norte (N)'))->
                add('oeste', 'number',array('label' => 'Coordenada Oeste (W)'))
                ->add('save', 'submit',array('label' => 'Actualizar Coordenadas'))->getForm();
                
                $form->handleRequest($request);
                
		if ($form->isValid()) {
                            
                            $em = $this->getDoctrine()->getManager();    
                            
                            $coordenadas->setAldea($aldea);
                            $em->persist($coordenadas);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            ' Coordenadas Actualizadas con Éxito'
                            );      
                            
                    return $this->redirect($this->generateUrl('aldea_show',array('id'=>$aldea->getId())));
		}
		
        
        return $this->render('MisionSucreRipesBundle:CoordenadasAldea:new.html.twig', array(
		'form' => $form->createView(),'mensaje_heading'=>"Actualización de Coordenadas",
                    'sub_heading'=>"Aldea {$aldea->getNombre()}"
		));
        
        
        }

}