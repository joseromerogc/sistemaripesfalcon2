<?php

namespace MisionSucre\RipesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MisionSucre\RipesBundle\Entity\MiembroEstructura;
use MisionSucre\RipesBundle\Form\Type\MiembroEstructuraType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\HttpFoundation\JsonResponse;

class EstructuraMiembroController extends Controller
{
    
    public function newAction(Request $request,$idstr)
	{       
        
        $em = $this->getDoctrine()->getManager();
        
        $estructura =  $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Estructura')
                ->find($idstr);
                
                if(!$estructura){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Estructura con ID: '.$idstr.' no registrado'
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
        
        
        $miembro= new MiembroEstructura();
        
        
        $form = $this->createForm(new MiembroEstructuraType(),$miembro)->
               
        add('save', 'submit',array('label' => 'Registrar Miembro'));
            
                $form->handleRequest($request);
                
		if ($form->isValid()) {
                            
                            $em = $this->getDoctrine()->getManager();    
                            $idusr = $form->get('idusr')->getData();
                            
                            if($idusr){
                                $user =  $this->getDoctrine()
                                ->getRepository('MisionSucreRipesBundle:User')
                                ->find($idusr);
                                $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Miembro Vinculado con Éxito'
                            );  
                            $miembro->setUser($user);
                            $miembro->setEstructura($estructura);
                            $em->persist($miembro);
                            $em->flush();
                            }
                            else{
                               $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Error en la Cédula'
                            );  
                            }
                                        
                    return $this->redirect($this->generateUrl('estructura_show',array('id'=>$idstr)));
		}
		
        
        return $this->render('MisionSucreRipesBundle:EstructuraMiembro:new.html.twig', array(
		'form' => $form->createView(),'mensaje_heading'=>"Registrar </br>Miembro para {$estructura->getNombre()}",
                    'sub_heading'=>"Aldea {$aldea->getNombre()}",'aldea'=>$aldea->getId(),'str'=>$estructura->getId()
		));
        
        }
    
        public function updateAction(Request $request,$idm)
        {
             $em = $this->getDoctrine()->getManager();
        
        $miembro = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:MiembroEstructura')
                ->find($idm);
         if(!$miembro){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Estructura con ID: '.$idm.' no registrado'
                );
                return $this->redirect($this->generateUrl('aldea'));
                }
        
        $str = $miembro->getEstructura();
           
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
        
        $form = $this->createFormBuilder($miembro)->
               
        add('save', 'submit',array('label' => 'Actualizar Miembro'))
                ->add('cargo', 'text',array('label' => 'Cargo'))
                ->getForm();
            
                $form->handleRequest($request);
                
		if ($form->isValid()) {
                            
                            $em = $this->getDoctrine()->getManager();    
                            $em->persist($miembro);
                            $em->flush();
                         $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Miembro Actualizado con Éxito'
                            );     
                                        
                    return $this->redirect($this->generateUrl('estructura_show',array('id'=>$str->getId())));
		}
	
                $nombrestr=$str->getNombre();
                $data= $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Persona')
                ->findOneByUser($miembro->getUser()->getId());
        
        return $this->render('MisionSucreRipesBundle:EstructuraMiembro:new.html.twig', array(
		'form' => $form->createView(),'mensaje_heading'=>"Actualizar </br>Miembro para $nombrestr",
                    'sub_heading'=>"Aldea {$aldea->getNombre()}",'aldea'=>$aldea->getId(),'data'=>$data, 'str'=>$str->getId()
		));
        }
        
         public function datapersonaAction(Request $request,$ced,$aldea,$str)
	{
        
        $em = $this->getDoctrine()->getManager();
        
        $per = $em->getRepository('MisionSucreRipesBundle:Persona')->findOneByCedPer($ced);
        
        $apellidos="";
        $nombres="";
        $perfil="";
        $idusr=0;
        
        $error=0;
        
        if($str){
            $estructura = $em->getRepository('MisionSucreRipesBundle:MiembroEstructura')->findOneByEstructura($str);
            if($estructura)
                {
                    $error="Ya esta Vinculado a esta Estructura";
                }
        }
        
        if($per){
            
        $nombres=$per->getPriNom() ." ". $per->getSegNom();
        $apellidos=$per->getPriApe() ." ". $per->getSegApe();
        $idusr= $per->getUser()->getId();
        
       switch($per->getUser()->getTipUsr()){
           
            case 6:
                $perfil="Triunfador(a)";
                $triunfador = $em->getRepository('MisionSucreRipesBundle:Triunfador')->findOneByUser($idusr);
                if($triunfador->getAmbiente()->getAldea()->getId()!=$aldea)
                {
                    $error="No esta Vinculado a la Aldea";
                }
                break;
            case 7:
                $perfil="Docente";
                $docente = $em->getRepository('MisionSucreRipesBundle:Docente')->findOneByUser($idusr);
                if($docente->getAldea()->getId()!=$aldea)
                {
                    $error="No esta Vinculado a la Aldea";
                }
                break;
            default:
                $error="Perfil no Aceptado";
            case 9:
                $perfil="Operario";
                $operario = $em->getRepository('MisionSucreRipesBundle:Operario')->findOneByUser($idusr);
                if($operario->getAldea()->getId()!=$aldea)
                {
                    $error="No esta Vinculado a la Aldea";
                }
                break;
            default:
                $error="Perfil no Aceptado";
                        
            
        }
        }
        else{
            $error="Cédula no encontrada";
        }
        
        return new JsonResponse(array('nombres'=>$nombres,
                                'idusr'=>$idusr,
                                'error' => $error,'apellidos'=>$apellidos, 'perfil'=>$perfil
                                 
                ));
        }
public function deleteAction(Request $request,$idm)
	{       
        
        $em = $this->getDoctrine()->getManager();
        
        $miembro = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:MiembroEstructura')
                ->find($idm);
         if(!$miembro){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Estructura con ID: '.$idm.' no registrado'
                );
                return $this->redirect($this->generateUrl('aldea'));
                }
        
        $str = $miembro->getEstructura();
                
               
        
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
        
       $em->remove($miembro);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Miembro Borrado con Éxito'
                            );            
                return $this->redirect($this->generateUrl('estructura_show',array('id'=>$str->getId())));   
        }
}
