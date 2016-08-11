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
        $em = $this->getDoctrine()->getManager();
	$anexo =  $em->getRepository('MisionSucreRipesBundle:AnexoAldea')->find($id);
        
        if(!$anexo){
            
            $request->getSession()->getFlashBag()->add(
                            'notice',
                            "Ambiente Comunal con ID $id No esta Registrada"
                            );            
                    return $this->redirect($this->generateUrl('aldea_new'));
        }
        
        if($this->getUser()->getTipUsr()==8){
        $user = $this->getUser();
        $usreje = $em->getRepository('MisionSucreRipesBundle:CoordinadorEje')->findOneByUser($user->getId());
        if($usreje->getEje()->getId()!=$anexo->getSector()->getParroquia()->getEje()->getId())
            {
            $request->getSession()->getFlashBag()->add(
                            'notice',
                            "Acceso Denegado al Eje"
                            );            
                    return $this->redirect($this->generateUrl('aldea_lista'));
            }
        }
                            $em->remove($anexo);
                            $idaldea = $anexo->getAldea()->getId();
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Anexo Borrado con Éxito'
                            );            
                    return $this->redirect($this->generateUrl('aldea_show',array('id'=>$idaldea)    ));
        
        }
    public function showAction(Request $request,$id)
	{       
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
                
                $aldea =  $em->getRepository('MisionSucreRipesBundle:Aldea')->find($anexo->getAldea()->getId());
                
                if(!$aldea){
                    
                    $request->getSession()->getFlashBag()->add(
                    'notice',
                    'Aldea con ID: '.$id.' no registrada'
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
                            return $this->redirect($this->generateUrl('aldea_new'));
                    }
                }
                
                $coordinadoresaldea = $em->getRepository('MisionSucreRipesBundle:CoordinadorAldea')->findAllOrderedByAldea($aldea->getId());
                $ambientescta =  $em->getRepository('MisionSucreRipesBundle:Ambiente')->findAmbienteByAnexoAldeaAndModalidad($id,'CTA');
                $ambientesubv =  $em->getRepository('MisionSucreRipesBundle:Ambiente')->findAmbienteByAnexoAldeaAndModalidad($id,'UBV');
                $ambientesti =  $em->getRepository('MisionSucreRipesBundle:Ambiente')->findAmbienteByAnexoAldeaAndModalidad($id,'TI');
                
		return $this->render(
			'MisionSucreRipesBundle:Anexo:show.html.twig',
			array('aldea'=>$aldea,'ambientescta'=>$ambientescta,'anexo'=>$anexo,
                            'ambientesubv'=>$ambientesubv,'ambientesti'=>$ambientesti 
                    
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