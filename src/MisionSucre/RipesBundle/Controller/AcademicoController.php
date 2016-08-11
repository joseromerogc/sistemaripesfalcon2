<?php

namespace MisionSucre\RipesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MisionSucre\RipesBundle\Form\Type\AcademicoType;
use MisionSucre\RipesBundle\Entity\Academico;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;

class AcademicoController extends Controller
{
    public function indexAction()
    {
        return $this->render('MisionSucreRipesBundle:Usuario:index.html.twig');
    }
	
	public function newAction(Request $request,$id)
	{       
                $em = $this->getDoctrine()->getManager();
                
                $academico = $em->getRepository('MisionSucreRipesBundle:Academico')->findByUser($id);
                $usuario = $em->getRepository('MisionSucreRipesBundle:User')->find($id);
                
                if($academico)
                {   
                    $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Datos Académicos ya Registrados'
                            );            
                    return $this->redirect($this->generateUrl('usuario_show',array('id'=>$id)));
                    
                }
                
                if(!$usuario)
                {   
                    $request->getSession()->getFlashBag()->add(
                            'notice',
                            "Usuario con ID: $id NO Registrado"
                            );            
                    return $this->redirect($this->generateUrl('usuario_show',array('id'=>$id)));
                    
                }
                
		// create a task and give it some dummy data for this example
                $academico= new Academico();
                        
		$form = $this->createForm(new AcademicoType(), $academico)
                        ->add('save', 'submit',array('label' => 'Registrar Datos Académicos'));
                        
                $form->handleRequest($request);
		if ($form->isValid()) {
                            
                            $academico->setUser($usuario);
                            
                            $em->persist($academico);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Datos Académicos Registrada con Éxito'
                            );            
                    return $this->redirect($this->generateUrl('usuario_show',array('id'=>$id)));
                            
		}
		
               
		return $this->render('MisionSucreRipesBundle:Academico:new.html.twig', array(
		'form' => $form->createView(),'mensaje_heading'=>'Registro de Datos Académicos',
                    'sub_heading'=>'Nuevos Datos'
		));
	}
    
        public function updateAction(Request $request,$idaca)
	{
                $em = $this->getDoctrine()->getManager();
                
                $academico = $em->getRepository('MisionSucreRipesBundle:Academico')->find($idaca);
                
                if(!$academico)
                {   
                    $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Datos Académicos No Registrados'
                            );            
                    return $this->redirect($this->generateUrl('usuario_show',array('id'=>$id)));
                }
                
		// create a task and give it some dummy data for this example
                        
		$form = $this->createForm(new AcademicoType(), $academico)
                        ->add('save', 'submit',array('label' => 'Actualizar Datos Académicos'));
                        
                $form->handleRequest($request);
		if ($form->isValid()) {
                            
                            $em->persist($academico);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Datos Académicos Registrada con Éxito'
                            );            
                    return $this->redirect($this->generateUrl('usuario_show',array('id'=>$academico->getUser()->getId())));
                            
		}
		
               
		return $this->render('MisionSucreRipesBundle:Academico:new.html.twig', array(
		'form' => $form->createView(),'mensaje_heading'=>'Actualización de Datos Académicos',
                    'sub_heading'=>'Renovar Datos'
		));
                
        }
}
