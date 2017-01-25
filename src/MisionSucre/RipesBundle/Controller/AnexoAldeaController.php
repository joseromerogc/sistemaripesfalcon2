<?php

namespace MisionSucre\RipesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MisionSucre\RipesBundle\Entity\AnexoAldea;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;

class AnexoAldeaController extends Controller
{
    
    
    public function newAction(Request $request,$id)
	{ 
    /*
     * Crea un nuevo ambiente comunal(o anexo)
     */      
        
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
        
        
        $anexo= new AnexoAldea();
        
        $form = $this->createFormBuilder($anexo)->
                add('nombre', 'text',array('label' => 'Nombre del Ambiente Comunal'))
                ->add('parroquia', 'choice', array(
        'choices' => $this->Parroquias($aldea->getParroquia()->getMunicipio()->getId()),'mapped' =>false,
            'placeholder'=>"Seleccione una",'label' => 'Parroquia del Ambiente Comunal'
            ))->add('sector', 'hidden',array('mapped' => false))
            ->add('direccion', 'textarea',array('label' => 'Dirrección del Ambiente Comunal'))
        ->add('save', 'submit',array('label' => 'Registrar Ambiente Comunal'))->getForm();
            
                $form->handleRequest($request);
                
		if ($form->isValid()) {
                            
                            $em = $this->getDoctrine()->getManager();    
                            
                            $idsct = $form->get('sector')->getData();
                                
                            $sector = $em->getRepository('MisionSucreRipesBundle:Sector')->find($idsct);
                            
                            $anexo->setSector($sector);
                            $anexo->setAldea($aldea);
                            
                            $em->persist($anexo);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Ambiente Comunal Creado con Éxito'
                            );            
                    return $this->redirect($this->generateUrl('aldea_show',array('id'=>$aldea->getId())));
		}
		
        
        return $this->render('MisionSucreRipesBundle:Anexo:new.html.twig', array(
		'form' => $form->createView(),'mensaje_heading'=>"Registro </br>Ambiente Comunal",
                    'sub_heading'=>"Aldea {$aldea->getNombre()}"
		));
        
        }
        
        public function updateAction(Request $request,$id)
	{       
         /*
          * Actualiza los datos del ambiente comunal(Anexo)
          */   
	$em = $this->getDoctrine()->getManager();
                
        $anexo =  $em->getRepository('MisionSucreRipesBundle:AnexoAldea')->find($id);
        
        if(!$anexo){
            
            $request->getSession()->getFlashBag()->add(
                            'notice',
                            "Anexo con ID $id No esta Registrada"
                            );            
                    return $this->redirect($this->generateUrl('usuario_info'));
        }
        
        $aldea =  $em->getRepository('MisionSucreRipesBundle:Aldea')->find($anexo->getAldea()->getId());
        
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
        
        $form = $this->createFormBuilder($anexo)->
                add('nombre', 'text',array('label' => 'Nombre del Ambiente Comunal'))
                ->add('parroquia', 'choice', array(
        'choices' => $this->Parroquias($aldea->getParroquia()->getMunicipio()->getId()),'mapped' =>false,
            'placeholder'=>"Seleccione una",'label' => 'Parroquia del Ambiente Comunal'
            ))->add('sector', 'hidden',array('mapped' => false))
            ->add('direccion', 'textarea',array('label' => 'Dirrección del Ambiente Comunal'))
        ->add('save', 'submit',array('label' => 'Actualizar Ambiente Comunal'))->getForm();
            
                $form->handleRequest($request);
                
		if ($form->isValid()) {
                            
                            $em = $this->getDoctrine()->getManager();    
                            
                            $idsct = $form->get('sector')->getData();
                                
                            $sector = $em->getRepository('MisionSucreRipesBundle:Sector')->find($idsct);
                            
                            $anexo->setSector($sector);
                            $anexo->setAldea($aldea);
                            
                            $em->persist($anexo);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Ambiente Comunal Actualizado con Éxito'
                            );            
                    return $this->redirect($this->generateUrl('aldea_show',array('id'=>$aldea->getId())));
		}
		
        
        return $this->render('MisionSucreRipesBundle:Anexo:new.html.twig', array(
		'form' => $form->createView(),'mensaje_heading'=>"Actualizar </br>Ambiente Comunal",
                    'sub_heading'=>"Aldea {$aldea->getNombre()}"
		));
        
        }
	
        public function deleteAction(Request $request,$id)
	{       
        /*
         * Elimina el ambiente comunal. Este no debe poseer datos.
         */
        $em = $this->getDoctrine()->getManager();
	$anexo =  $em->getRepository('MisionSucreRipesBundle:AnexoAldea')->find($id);
        
        if(!$anexo){
            
            $request->getSession()->getFlashBag()->add(
                            'notice',
                            "Ambiente Comunal con ID $id No esta Registrada"
                            );            
                    return $this->redirect($this->generateUrl('aldea_new'));
        }
        
        /*VALIDAR */
                $validar = $this->get('servicios.validar');
                
                $error = $validar->ValidarAldea($anexo->getAldea()->getId(), $request);
                if($error)
                    return $this->redirect($this->generateUrl($error['url'],array($error['param']=>$error['valueparam'])));
        /*FIN VALIDAR*/
        $idaldea = $anexo->getAldea()->getId();
        
        $ambientes = $em->getRepository('MisionSucreRipesBundle:Ambiente')->findByAnexo($id);
        if($ambientes){
            $request->getSession()->getFlashBag()->add(
                            'notice',
                            "No puede Eliminar un Ambiente Comunal que contiene Información"
                            );            
                    return $this->redirect($this->generateUrl('aldea_show',array('id'=>$idaldea)));
        }
                            $em->remove($anexo);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Anexo Borrado con Éxito'
                            );            
                    return $this->redirect($this->generateUrl('aldea_show',array('id'=>$idaldea)));
        
        }
    public function showAction(Request $request,$id)
	{  
        /*
         * Muestra los ambientes que se encuentran activos en ese anexo o ambiente comunal
         */
                $em = $this->getDoctrine()->getManager();
                
                $anexo =  $em->getRepository('MisionSucreRipesBundle:AnexoAldea')->find($id);
                
                if(!$anexo){
                    
                    $request->getSession()->getFlashBag()->add(
                    'notice',
                    'Anexo con ID: '.$id.' no registrada'
                    );    
                    return $this->redirect($this->generateUrl('aldea_new'));
                }
                
                $aldea = $anexo->getAldea();
                
                /*VALIDAR */
                $validar = $this->get('servicios.validar');
                
                $error = $validar->ValidarAldea($aldea->getId(), $request);
                if($error)
                    return $this->redirect($this->generateUrl($error['url'],array($error['param']=>$error['valueparam'])));
                /*FIN VALIDAR*/
                
                //LLAMANDO AL SERVICIO DE AMBIENTES
                $containerambientes = $this->get('servicios.ambiente');
                
                $ambientes = $containerambientes->AnexoModalidad($id);
                
		return $this->render(
			'MisionSucreRipesBundle:Anexo:show.html.twig',
			array('aldea'=>$aldea,'ambientescta'=>$ambientes['CTA'],'anexo'=>$anexo,
                            'ambientesubv'=>$ambientes['UBV'],'ambientesti'=>$ambientes['TI']
                    
                        ));
	}
public function listaAction(Request $request)
	{   
        /*
         * Muestra la lista de todos los ambientes comunales
         */
            $em = $this->getDoctrine()->getManager();
            $anexos = $em->getRepository('MisionSucreRipesBundle:AnexoAldea')->lista();
                
		if(!$anexos){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Ningun Dato Académico Registrado'
            );
            return $this->redirect($this->generateUrl('aldea_lista'));
            }
            
		return $this->render(
			'MisionSucreRipesBundle:Anexo:lista.html.twig',
                array('anexos' => $anexos)
		);	
	}
  public function resumenAction(Request $request)
	{  /*
         * Resumen Estadistico de los Ambientes Comunales
         */     
                $em = $this->getDoctrine()->getManager();
                
                        $cantidad = $em->createQuery(
                        "SELECT m.municipio, COUNT ( DISTINCT a.id) as total
                            FROM MisionSucreRipesBundle:AnexoAldea a JOIN a.sector s JOIN s.parroquia prq JOIN prq.municipio m
                            GROUP BY m.municipio
                        ")
                        ->getResult();
                        
                    return $this->render(
			'MisionSucreRipesBundle:Anexo:resumen.html.twig',
                        array(
                            'cantidad'=>$cantidad,
                        ));
                
        }        
        
        
/*
**FUNCIONES EXTRAS
 *  */
        
    protected function Parroquias($id) {
        
    $choices = array();
    
    $em = $this->getDoctrine()->getManager();
    $parroquias = $em->getRepository('MisionSucreRipesBundle:Parroquia')->findByMunicipio($id);
        
        foreach ($parroquias as $p) {
        $choices[$p->getId()] =$p->getParroquia();
    }        
    return $choices;
    }
    
    public function choicesanexoAction($aldea) {
        
    $em = $this->getDoctrine()->getManager();
    $anexos = $em->getRepository('MisionSucreRipesBundle:AnexoAldea')->findByAldea($aldea);

    return $this->render('MisionSucreRipesBundle:Anexo:anexos.html.twig',array('anexos'=>$anexos));
    } 
}