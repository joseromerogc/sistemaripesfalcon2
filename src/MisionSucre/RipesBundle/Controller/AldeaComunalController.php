<?php

namespace MisionSucre\RipesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MisionSucre\RipesBundle\Entity\AldeaComunal;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;

class AldeaComunalController extends Controller
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
        
        /*VALIDAR */
                $validar = $this->get('servicios.validar');
                
                $error = $validar->ValidarAldea($aldea->getId(), $request);
                if($error)
                    return $this->redirect($this->generateUrl($error['url'],array($error['param']=>$error['valueparam'])));
                /*FIN VALIDAR*/
        
	$em = $this->getDoctrine()->getManager();
        
        $aldeacomunal= new AldeaComunal();
        
        
        $form = $this->createFormBuilder($aldeacomunal)->
                add('nombre', 'text',array('label' => 'Nombre del Ambiente Comunal'))
                        ->add('sector', 'choice', array(
        'choices' => $this->Sectores($aldea->getParroquia()->getId()),'mapped' =>false,
            'placeholder'=>"Seleccione una",'label' => 'Sector del Ambiente Comunal'
            ))
            ->add('direccion', 'textarea',array('label' => 'Dirrección del Ambiente Comunal'))
        ->add('save', 'submit',array('label' => 'Registrar Espacio Comunal'))->getForm();
            
                $form->handleRequest($request);
                
		if ($form->isValid()) {
                            
                            $em = $this->getDoctrine()->getManager();    
                            
                            $idsct = $form->get('sector')->getData();
                                
                            $sector = $em->getRepository('MisionSucreRipesBundle:Sector')->find($idsct);
                            
                            $aldeacomunal->setSector($sector);
                            $aldeacomunal->setAldea($aldea);
                            
                            $em->persist($aldeacomunal);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Ambiente Comunal Creada con Éxito'
                            );            
                    return $this->redirect($this->generateUrl('aldea_show',array('id'=>$aldea->getId())));
		}
		
        
        return $this->render('MisionSucreRipesBundle:AldeaComunal:new.html.twig', array(
		'form' => $form->createView(),'mensaje_heading'=>"Registro </br>Ambiente Comunal",
                    'sub_heading'=>"Aldea {$aldea->getNombre()}"
		));
        
        }
        
        public function updateAction(Request $request,$id)
	{       
	$em = $this->getDoctrine()->getManager();
                
        $aldeacomunal =  $em->getRepository('MisionSucreRipesBundle:AldeaComunal')->find($id);
        
        if(!$aldeacomunal){
            
            $request->getSession()->getFlashBag()->add(
                            'notice',
                            "Ambiente Comunal con ID $id No esta Registrada"
                            );            
                    return $this->redirect($this->generateUrl('aldea_new'));
        }
        
        /*VALIDAR */
                $validar = $this->get('servicios.validar');
                
                $error = $validar->ValidarAldea($aldea->getId(), $request);
                if($error)
                    return $this->redirect($this->generateUrl($error['url'],array($error['param']=>$error['valueparam'])));
                /*FIN VALIDAR*/
        
        $form = $this->createFormBuilder($aldeacomunal)->
                add('nombre', 'text',array('label' => 'Nombre del Ambiente Comunal'))
                        ->add('sector', 'choice', array(
        'choices' => $this->Sectores($aldeacomunal->getSector()->getParroquia()->getId()),'mapped' =>false,
            'placeholder'=>"Seleccione una",'label' => 'Sector del Ambiente Comunal'
            ))
            ->add('direccion', 'textarea',array('label' => 'Dirrección del Ambiente Comunal'))
        ->add('save', 'submit',array('label' => 'Actualizar Aldea'))->getForm();
            
                $form->handleRequest($request);
                
		if ($form->isValid()) {
                            
                            $em = $this->getDoctrine()->getManager();    
                            
                            $idsct = $form->get('sector')->getData();
                                
                            $sector = $em->getRepository('MisionSucreRipesBundle:Sector')->find($idsct);
                            
                            $aldeacomunal->setSector($sector);
                            
                            $em->persist($aldeacomunal);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Ambiente Comunal Actualizado con Éxito'
                            );            
                    return $this->redirect($this->generateUrl('aldea_show',array('id'=>$aldeacomunal->getAldea()->getId())));
		}
                return $this->render('MisionSucreRipesBundle:AldeaComunal:new.html.twig', array(
		'form' => $form->createView(),'mensaje_heading'=>"Actualización </br>Ambiente Comunal",
                    'sub_heading'=>"Aldea {$aldeacomunal->getAldea()->getNombre()}"
		));
        
        }
	
        public function deleteAction(Request $request,$id)
	{       
        $em = $this->getDoctrine()->getManager();
	$aldeacomunal =  $em->getRepository('MisionSucreRipesBundle:AldeaComunal')->find($id);
        
        if(!$aldeacomunal){
            
            $request->getSession()->getFlashBag()->add(
                            'notice',
                            "Ambiente Comunal con ID $id No esta Registrada"
                            );            
                    return $this->redirect($this->generateUrl('aldea_new'));
        }
        
        /*VALIDAR */
                $validar = $this->get('servicios.validar');
                
                $error = $validar->ValidarAldea($aldea->getId(), $request);
                if($error)
                    return $this->redirect($this->generateUrl($error['url'],array($error['param']=>$error['valueparam'])));
                /*FIN VALIDAR*/
                
                            $em->remove($aldeacomunal);
                            $idaldea = $aldeacomunal->getAldea()->getId();
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Aldea Comunal Borrada con  con Éxito'
                            );            
                    return $this->redirect($this->generateUrl('aldea_show',array('id'=>$idaldea)    ));
        
        }
    
/*
**FUNCIONES EXTRAS
 *  */
        
    protected function Sectores($id) {
        
    $choices = array();
    
    $em = $this->getDoctrine()->getManager();
    $sectores = $em->getRepository('MisionSucreRipesBundle:Sector')->findByParroquia($id);
        
        foreach ($sectores as $s) {
        $choices[$s->getId()] =$s->getNombre();
    }        
    return $choices;
    }
}