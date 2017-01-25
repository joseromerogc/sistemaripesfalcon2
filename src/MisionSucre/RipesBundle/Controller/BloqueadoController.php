<?php

namespace MisionSucre\RipesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MisionSucre\RipesBundle\Entity\Bloqueado;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;

class BloqueadoController extends Controller
{
    public function indexAction()
    {
        return $this->render('MisionSucreRipesBundle:Usuario:index.html.twig');
    }
	
	public function newAction(Request $request,$cedula)
	{       
            $em = $this->getDoctrine()->getManager();
            
            $datospersonales = $this->getDoctrine()
            ->getRepository('MisionSucreRipesBundle:Persona')
            ->findOneByCedPer($cedula);
            
            $b = new Bloqueado();
            
            $form = $this->createFormBuilder($b)->
                    add('motivo', 'text',array('label' => 'Motivo de Bloqueo'))
                        ->add('save', 'submit',array('label' => 'Registrar Bloqueo'))->getForm();
            
            $form->handleRequest($request);
            
            if ($form->isValid()) {
                    
                            $b->setCedulas($datospersonales->getCedPer());
                            
                            $em->persist($b);
                            
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Persona Bloqueado con Éxito'
                            );            
                    return $this->redirect($this->generateUrl('usuario_lista'));
		}
            
            return $this->render('MisionSucreRipesBundle:Bloqueado:new.html.twig', array(
		'form' => $form->createView(),'datospersonales'=> $datospersonales,'mensaje_heading'=>'Bloquear Cédula',
                    'sub_heading'=>'Ingresar Datos'
		));
            
        }
    public function listaAction(Request $request)
	{   
         $em = $this->getDoctrine()->getManager();
         
            $bloqueos = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Bloqueado')
                ->findAllDatos();;
                
		if(!$bloqueos){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Ningún Bloqueo Registrado'
            );
            return $this->redirect($this->generateUrl('usuario_lista'));
            }
            
		return $this->render(
			'MisionSucreRipesBundle:Bloqueado:lista.html.twig',
                array('bloqueos' => $bloqueos)
		);	
	}    
    public function deleteAction(Request $request,$id)
	{   
         $em = $this->getDoctrine()->getManager();
         
            $bloqueo = $atenciones = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Bloqueado')
                ->find($id);;
                
		if(!$bloqueo){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Bloque con ID:$id No existe'
            );
            return $this->redirect($this->generateUrl('usuario_lista'));
            }
            
            $datospersonales = $this->getDoctrine()
            ->getRepository('MisionSucreRipesBundle:Persona')
            ->findOneByCedPer($bloqueo->getCedulas());
            
            $em->remove($bloqueo);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Usuario Desbloqueado con Éxito'
                            );            
                return $this->redirect($this->generateUrl('usuario_show',array('id'=>$datospersonales->getUser()->getId())));    
	}    
}
