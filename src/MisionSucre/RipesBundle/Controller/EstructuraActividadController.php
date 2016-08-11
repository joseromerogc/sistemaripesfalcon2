<?php

namespace MisionSucre\RipesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MisionSucre\RipesBundle\Entity\EstructuraActividad;
use MisionSucre\RipesBundle\Form\Type\EstructuraActividadType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;

class EstructuraActividadController extends Controller
{
    
    public function newAction(Request $request,$idstr)
	{       
        
        $em = $this->getDoctrine()->getManager();
        
        $str = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Estructura')
                ->find($idstr);
                
                if(!$str){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Estructura con ID: '.$idstr.' no registrado'
                );
                return $this->redirect($this->generateUrl('aldea'));
                }
        
        $idaldea = $str->getAldea()->getId();
                
        $aldea = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Aldea')
                ->find($idaldea);
                
        $usr = $this->getUser();
                
                
                switch($usr->getTipUsr()){
                
                    case 5:
                        $caldea = $this->getDoctrine()
                            ->getRepository('MisionSucreRipesBundle:CoordinadorAldea')
                            ->findOneByUser($usr->getId());

                            if( $caldea->getAldea()->getId() != $idaldea) {   
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Accesso Denegado a esta Aldea'
                            );
                            return $this->redirect($this->generateUrl('usuario_show',array('id'=>$usr->getId())));
                            }
                        break;
                    case 8:
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
                        break;
                }
        
        $actividad= new EstructuraActividad();
        
        
        $form = $this->createForm(new EstructuraActividadType(),$actividad)->
        add('idresponsable', 'choice', array(
        'choices' => $this->ChoicesMiembros($str->getId()),'mapped' =>false,
            'placeholder'=>"Seleccione una",'label' => 'Responsable'
            ))
        ->add('save', 'submit',array('label' => 'Registrar Actividad'));
            
                $form->handleRequest($request);
                
		if ($form->isValid()) {
                            
                            $em = $this->getDoctrine()->getManager();    
                            
                            $idresponsable = $form->get('idresponsable')->getData();
                            
                            $user =  $this->getDoctrine()
                                ->getRepository('MisionSucreRipesBundle:User')
                                ->find($idresponsable);
                            
                            if($user){
                                $actividad->setResponsable($user);
                            $actividad->setEstructura($str);
                            
                            $em->persist($actividad);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Actividad Creada con Éxito'
                            );            
                    return $this->redirect($this->generateUrl('estructura_show',array('id'=>$str->getId())));
                            }
                            else{
                                $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Error en Cédula'
                            );            
                            }
                            
		}
		
        
        return $this->render('MisionSucreRipesBundle:EstructuraActividad:new.html.twig', array(
		'form' => $form->createView(),'mensaje_heading'=>"Registro Actividad",'str'=>$str,
                    'sub_heading'=>"Estructura {$str->getNombre()} </br>Aldea {$aldea->getNombre()}"
		));
        
        }
    
        public function updateAction(Request $request,$id)
        {
             $em = $this->getDoctrine()->getManager();
        
             $actividad=$this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:EstructuraActividad')
                ->find($id);
             
        $str = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Estructura')
                ->find($actividad->getEstructura()->getId());
                
                if(!$str){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Estructura con ID: '.$id.' no registrado'
                );
                return $this->redirect($this->generateUrl('aldea'));
                }
        
        $idaldea = $str->getAldea()->getId();
                
        $aldea = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Aldea')
                ->find($idaldea);
                
        $usr = $this->getUser();
                
                
                switch($usr->getTipUsr()){
                
                    case 5:
                        $caldea = $this->getDoctrine()
                            ->getRepository('MisionSucreRipesBundle:CoordinadorAldea')
                            ->findOneByUser($usr->getId());

                            if( $caldea->getAldea()->getId() != $idaldea) {   
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Accesso Denegado a esta Aldea'
                            );
                            return $this->redirect($this->generateUrl('usuario_show',array('id'=>$usr->getId())));
                            }
                        break;
                    case 8:
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
                        break;
                }
                $idresp= $actividad->getResponsable()->getId();
                
                $p = $em->getRepository('MisionSucreRipesBundle:Persona')->findOneByUser($idresp);
       $nombre=$p->getPriNom()." ".$p->getPriApe()." (".$p->getCedPer().")";
                
        $form = $this->createForm(new EstructuraActividadType(),$actividad)->
        add('idresponsable', 'choice', array(
        'choices' => $this->ChoicesMiembros($str->getId()),'mapped' =>false,
            'placeholder'=>"$nombre",'label' => 'Responsable','empty_data'  => "$idresp", 'required'=>false
            ))
        ->add('save', 'submit',array('label' => 'Registrar Actividad'));
            
                $form->handleRequest($request);
                
		if ($form->isValid()) {
                            
                            $em = $this->getDoctrine()->getManager();    
                            
                            $idresponsable = $form->get('idresponsable')->getData();
                            
                            $user =  $this->getDoctrine()
                                ->getRepository('MisionSucreRipesBundle:User')
                                ->find($idresponsable);
                            
                            if($user){
                                $actividad->setResponsable($user);
                            $actividad->setEstructura($str);
                            
                            $em->persist($actividad);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Actividad Creada con Éxito'
                            );            
                    return $this->redirect($this->generateUrl('estructura_show',array('id'=>$str->getId())));
                            }
                            else{
                                $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Error en Cédula'
                            );            
                            }
                            
		}
		
        
        return $this->render('MisionSucreRipesBundle:EstructuraActividad:new.html.twig', array(
		'form' => $form->createView(),'mensaje_heading'=>"Registro Actividad",'str'=>$str,
                    'sub_heading'=>"Estructura {$str->getNombre()} </br>Aldea {$aldea->getNombre()}"
		));
        
        }
        
        public function deleteAction(Request $request,$id)
	{ 
            $em = $this->getDoctrine()->getManager();
        
         $actividad= $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:EstructuraActividad')
                ->find($id);
         if(!$actividad){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Actividad con ID: '.$id.' no registrado'
                );
                return $this->redirect($this->generateUrl('aldea'));
                }
         
        $str= $actividad->getEstructura();
                
        
        $idaldea = $str->getAldea()->getId();
                
        $aldea = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Aldea')
                ->find($idaldea);
                
        $usr = $this->getUser();
                
                
                switch($usr->getTipUsr()){
                
                    case 5:
                        $caldea = $this->getDoctrine()
                            ->getRepository('MisionSucreRipesBundle:CoordinadorAldea')
                            ->findOneByUser($usr->getId());

                            if( $caldea->getAldea()->getId() != $idaldea) {   
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Accesso Denegado a esta Aldea'
                            );
                            return $this->redirect($this->generateUrl('usuario_show',array('id'=>$usr->getId())));
                            }
                        break;
                    case 8:
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
                        break;
                }
                
                
                  $em->remove($actividad);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Actividad Borrada con  con Éxito'
                            );            
                    return $this->redirect($this->generateUrl('estructura_show',array('id'=>$str->getId())));
        
        }
    protected function ChoicesMiembros($idstr) {
        
    $choices = array();
    
    $em = $this->getDoctrine()->getManager();
    $miembros = $em->getRepository('MisionSucreRipesBundle:MiembroEstructura')->findByEstructura($idstr);
    
    foreach ($miembros as $i=>$m) {
        
        $p = $em->getRepository('MisionSucreRipesBundle:Persona')->findOneByUser($m->getuser()->getId());
        $choices[$p->getUser()->getId()] =$p->getPriNom()." ".$p->getPriApe()." (".$p->getCedPer().")";
    }
    return $choices;
    }  
}
