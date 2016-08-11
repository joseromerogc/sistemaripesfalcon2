<?php

namespace MisionSucre\RipesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MisionSucre\RipesBundle\Entity\Universidad;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;

class UniversidadController extends Controller
{
    public function indexAction()
    {
        return $this->render('MisionSucreRipesBundle:Universidad:index.html.twig');
    }
	
	public function newAction(Request $request)
	{       
                $em = $this->getDoctrine()->getManager();
                
                $u = new Universidad();
                        
		$form = $this->createFormBuilder($u)->add('nombre','text',array('label'=>'Nombre de la Universidad'))
            ->add('direccion','text',array('label'=>'Dirección'))
                        ->add('save', 'submit',array('label' => 'Registrar'))->getForm();
                        
                $form->handleRequest($request);
		if ($form->isValid()) {
                            
                
                            
                            $em->persist($u);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Universidad Registrada con Éxito'
                            );            
                    return $this->redirect($this->generateUrl('universidad_show',array('id'=>$id)));
                           
		}
		
               
		return $this->render('MisionSucreRipesBundle:Universidad:new.html.twig', array(
		'form' => $form->createView(),'mensaje_heading'=>'Universidad',
                    'sub_heading'=>'Nuevos Datos'
		));
	}
        
        public function updateAction(Request $request,$id)
	{       
                 $em = $this->getDoctrine()->getManager();
                
                 $u = $this->getDoctrine()
                        ->getRepository('MisionSucreRipesBundle:Universidad')
                        ->find($id);
                        
		$form = $this->createFormBuilder($u)->add('nombre','text',array('label'=>'Nombre de la Universidad'))
            ->add('direccion','text',array('label'=>'Dirección'))
                        ->add('save', 'submit',array('label' => 'Registrar'))->getForm();
                        
                $form->handleRequest($request);
		if ($form->isValid()) {
                            
                
                            
                            $em->persist($u);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Universidad Actualizada con Éxito'
                            );            
                    return $this->redirect($this->generateUrl('universidad_show',array('id'=>$id)));
                           
		}
		
               
		return $this->render('MisionSucreRipesBundle:Universidad:new.html.twig', array(
		'form' => $form->createView(),'mensaje_heading'=>'Universidad',
                    'sub_heading'=>'Actualizar Datos'
		));
	}
    
        public function listaAction(Request $request)
	{       
                $em = $this->getDoctrine()->getManager();
                
                $usr = $this->getUser();
                $unis = $this->getDoctrine()
                        ->getRepository('MisionSucreRipesBundle:Universidad')
                        ->findAll();
                
		return $this->render(
			'MisionSucreRipesBundle:Universidad:lista.html.twig',
                array('unis' => $unis)
		);	
	}
        
        public function showAction(Request $request,$id){
                
                $em = $this->getDoctrine()->getManager();
                
                $u =  $em->getRepository('MisionSucreRipesBundle:Universidad')->find($id);
                
                $pnfs = $em->getRepository('MisionSucreRipesBundle:Pnf')->findByUniversidad($id);
                
                if(!$u){
                    
                    $request->getSession()->getFlashBag()->add(
                    'notice',
                    'Universidad con ID: '.$id.' no registrada'
                    );    
                    return $this->redirect($this->generateUrl('pnf_lista'));
                }
                
                return $this->render(
			'MisionSucreRipesBundle:Universidad:show.html.twig',
                        array('u'=>$u,'pnfs'=>$pnfs)
		);
        }
             
}
