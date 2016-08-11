<?php

namespace MisionSucre\RipesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MisionSucre\RipesBundle\Entity\Estructura;
use MisionSucre\RipesBundle\Form\Type\EstructuraType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;

class EstructuraController extends Controller
{
    
    public function newAction(Request $request,$idaldea)
	{       
        
        $em = $this->getDoctrine()->getManager();
        
        $aldea =  $em->getRepository('MisionSucreRipesBundle:Aldea')->find($idaldea);
        
        if(!$aldea){
            
            $request->getSession()->getFlashBag()->add(
                            'notice',
                            "Aldea con ID $id No esta Registrada"
                            );            
                    return $this->redirect($this->generateUrl('aldea_new'));
        }
        
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
        
        
        $str= new Estructura();
        
        
        $form = $this->createForm(new EstructuraType(),$str)->
                add('nombre', 'text',array('label' => 'Nombre de la Estructura'))
        ->add('save', 'submit',array('label' => 'Registrar Estructura'));
            
                $form->handleRequest($request);
                
		if ($form->isValid()) {
                            
                            $em = $this->getDoctrine()->getManager();    
                            
                            $str->setAldea($aldea);
                            
                            $em->persist($str);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Estructura Creada con Éxito'
                            );            
                    return $this->redirect($this->generateUrl('aldea_show',array('id'=>$aldea->getId())));
		}
		
        
        return $this->render('MisionSucreRipesBundle:Estructura:new.html.twig', array(
		'form' => $form->createView(),'mensaje_heading'=>"Registro </br>Estructura",
                    'sub_heading'=>"Aldea {$aldea->getNombre()}"
		));
        
        }
    
        public function updateAction(Request $request,$id)
	{       
        
        $em = $this->getDoctrine()->getManager();
        
        $str = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Estructura')
                ->find($id);
                
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
        
        $form = $this->createForm(new EstructuraType(),$str)->
                add('nombre', 'text',array('label' => 'Nombre de la Estructura'))
        ->add('save', 'submit',array('label' => 'Registrar Estructura'));
            
                $form->handleRequest($request);
                
		if ($form->isValid()) {
                            
                            $em = $this->getDoctrine()->getManager();    
                            
                            $str->setAldea($aldea);
                            
                            $em->persist($str);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Estructura Actaulizada con Éxito'
                            );            
                    return $this->redirect($this->generateUrl('aldea_show',array('id'=>$aldea->getId())));
		}
		
        
        return $this->render('MisionSucreRipesBundle:Estructura:new.html.twig', array(
		'form' => $form->createView(),'mensaje_heading'=>"Registro </br>Estructura",
                    'sub_heading'=>"Aldea {$aldea->getNombre()}"
		));
        
        }
        
    public function showAction(Request $request,$id)
	{       
                $em = $this->getDoctrine()->getManager();
                
                $estructura = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Estructura')
                ->find($id);
                
                if(!$estructura){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Estructura con ID: '.$idamb.' no registrado'
                );
                return $this->redirect($this->generateUrl('aldea'));
                }
                
                $idaldea = $estructura->getAldea()->getId();
                
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
                
                $miembros = $em
                ->getRepository('MisionSucreRipesBundle:MiembroEstructura')
                ->findAllByEstructura($id);
                
                $actividades=$em
                ->getRepository('MisionSucreRipesBundle:EstructuraActividad')
                ->findAllByEstructura($id);;
                
		return $this->render(
			'MisionSucreRipesBundle:Estructura:show.html.twig',
			array('aldea'=>$aldea,'estructura'=>$estructura,'miembros'=>$miembros,
                           'actividades'=>$actividades, //'periodosacademicos' =>$periodosacademicos
                        ));
	}
 
     public function deleteAction(Request $request,$id)
	{       
        
        $em = $this->getDoctrine()->getManager();
        
        $str = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Estructura')
                ->find($id);
                
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
                            $em->remove($str);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Estructura Borrada con  con Éxito'
                            );            
                    return $this->redirect($this->generateUrl('aldea_show',array('id'=>$idaldea)));
	
        }   
 }
