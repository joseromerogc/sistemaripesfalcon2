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
/**
 * newAction
 *
 * registra los datos academicos de los Docentes, triunfadores, coordinadores y operarios
 *
 * @param id es el ID de Usuario
 * 
 */       
                       
                $em = $this->getDoctrine()->getManager();
                
                $acad = $em->getRepository('MisionSucreRipesBundle:Academico')->findByUser($id);
                $usuario = $em->getRepository('MisionSucreRipesBundle:User')->find($id);
                
                if($acad)
                {   
                    $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Datos Académicos ya Registrados'
                            );            
                    return $this->redirect($this->generateUrl('usuario_show',array('id'=>$id)));
                    
                }
                
                
                /*VALIDAR */
                $validar = $this->get('servicios.validar');
                $error = $validar->ValidarIdUsuario($academico->getUser()->getId(), $request);
                if($error)
                    return $this->redirect($this->generateUrl($error['url'],array($error['param']=>$error['valueparam'])));
                /*FIN VALIDAR*/
                
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
                            'Datos Académicos Registrados con Éxito'
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
                    return $this->redirect($this->generateUrl('usuario_show',array('id'=>$idaca)));
                }
                
                /*VALIDAR */
                
                $error = $validar->ValidarIdUsuario($academico->getUser()->getId(), $request);
                if($error)
                    return $this->redirect($this->generateUrl($error['url'],array($error['param']=>$error['valueparam'])));
                /*FIN VALIDAR*/
                        
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
    public function listaAction(Request $request)
	{   
        /*
         * Muestra la lista de todos los usuarios con datos academicos
         */
            $em = $this->getDoctrine()->getManager();
            $academicos = $em->getRepository('MisionSucreRipesBundle:Academico')->lista();
                
		if(!$academicos){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Ningun Dato Académico Registrado'
            );
            return $this->redirect($this->generateUrl('aldea_lista'));
            }
            
		return $this->render(
			'MisionSucreRipesBundle:Academico:lista.html.twig',
                array('academicos' => $academicos)
		);	
	}
  public function resumenAction(Request $request)
	{  /*
         * Resumen Estadistico de los Datos Académico
         */     
                $em = $this->getDoctrine()->getManager();
                
                        $cantidad = $em->createQuery(
                        "SELECT a.titulouniversitario, COUNT (a.titulouniversitario) as total FROM MisionSucreRipesBundle:Academico a
                            GROUP BY a.titulouniversitario
                        ")
                        ->getResult();
                        
                    return $this->render(
			'MisionSucreRipesBundle:Academico:resumen.html.twig',
                        array(
                            'cantidad'=>$cantidad,
                        ));
                
        }
       
}
