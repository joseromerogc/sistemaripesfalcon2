<?php

namespace MisionSucre\RipesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MisionSucre\RipesBundle\Entity\CoordinadorEje;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;

class EjeController extends Controller
{
    public function indexAction()
    {
        return $this->render('MisionSucreRipesBundle:Usuario:index.html.twig');
    }
	
	public function newAction(Request $request,$id)
	{       
		// create a task and give it some dummy data for this example
		$ceje = new CoordinadorEje();
                
                if($id)
                {
                 $em = $this->getDoctrine()->getManager();
                 $coordeje = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:CoordinadorEje')
                ->findByUser($id);            
                
                $usr = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:User')
                ->find($id);
                
            
                if($coordeje){

                $request->getSession()->getFlashBag()->add(
                'notice',
                'Coordinador(a) ya registrado(a)'
                );
                return $this->redirect($this->generateUrl('usuario_show',array('id'=>$id)));
                }      
                
                if($usr->getTipUsr()!=8){

                $request->getSession()->getFlashBag()->add(
                'notice',
                'Usuario No es de Tipo Coordinador(a) de Eje'
                );
                return $this->redirect($this->generateUrl('usuario_show',array('id'=>$id)));
                }
                $coordinadores[$usr->getId()]= $usr->getUsername();        
                }
                else{
                    $coordinadores= $this->ChoicesCoordinadoresEje();    
                }
                
		$form = $this->createFormBuilder($ceje)->
                        add('usr_coord', 'choice', array(
        'choices' => $coordinadores,'mapped' =>false,
            'placeholder'=>"Seleccione una",'label' => 'Usuario'
            ))
        ->add('eje', 'choice', array(
        'choices' => $this->Ejes(),'mapped' =>false,
            'placeholder'=>"Seleccione un",'label' => 'Eje'
            ))                
        ->add('save', 'submit',array('label' => 'Registrar Usuario'))->getForm();
            
                $form->handleRequest($request);
                
		if ($form->isValid()) {
                            
                            $em = $this->getDoctrine()->getManager();    
                            
                            $ideje = $form->get('eje')->getData();
                            $idusr = $form->get('usr_coord')->getData();
                                
                            $eje = $em->getRepository('MisionSucreRipesBundle:Eje')->find($ideje);
                            $user = $em->getRepository('MisionSucreRipesBundle:User')->find($idusr);
                            
                            $ceje->setEje($eje);
                            $ceje->setUser($user);
                            $em->persist($ceje);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Coordinador de Eje Creado con Éxito'
                            );            
                    return $this->redirect($this->generateUrl('usuario_show',array('id'=>$idusr)));
		}
		
		return $this->render('MisionSucreRipesBundle:Eje:new.html.twig', array(
		'form' => $form->createView(),'mensaje_heading'=>'Nuevo Coordinador',
                    'sub_heading'=>'EJES'
		));
	}
        
        public function updateAction(Request $request,$id)
	{
            $em = $this->getDoctrine()->getManager();
            
            $ceje = $this->getDoctrine()
            ->getRepository('MisionSucreRipesBundle:CoordinadorEje')
            ->findByUser($id);            
            $ceje = $ceje[0];
            
            if(!$ceje){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Coordinador con ID: '.$id.' no registrado'
            );
            return $this->redirect($this->generateUrl('usuario_show',array('id'=>$id)));
            }
                
		$form = $this->createFormBuilder($ceje)
        ->add('eje', 'choice', array(
        'choices' => $this->Ejes(),'mapped' =>false,
            'placeholder'=>"Seleccione un",'label' => 'Eje'
            ))                
        ->add('save', 'submit',array('label' => 'Registrar Usuario'))->getForm();
            
                $form->handleRequest($request);
                
		if ($form->isValid()) {
                            
                            $em = $this->getDoctrine()->getManager();    
                            
                            $ideje = $form->get('eje')->getData();
                                
                            $eje = $em->getRepository('MisionSucreRipesBundle:Eje')->find($ideje);
                            
                            $ceje->setEje($eje);

                            $em->persist($ceje);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Coordinador de Eje Actualizado con Éxito'
                            );            
                    return $this->redirect($this->generateUrl('usuario_show',array('id'=>$id)));
		}
		
		return $this->render('MisionSucreRipesBundle:Eje:update.html.twig', array(
		'form' => $form->createView(),'mensaje_heading'=>'Actualizar Coordinador',
                    'sub_heading'=>'EJES','nombre'=>$ceje->getUser()->getUsername()
		));
	}
        
        public function listaAction(Request $request)
	{       
            $coordinadoresEje = $this->CoordinadoresEje();
                
		if(!$coordinadoresEje){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Ningún Coordinador Registrado'
            );
            return $this->redirect($this->generateUrl('eje_new'));
            }
            
		return $this->render(
			'MisionSucreRipesBundle:Eje:lista.html.twig',
                array('coordinadoreseje' => $coordinadoresEje)
		);	
	}
        public function deleteAction(Request $request,$id,$id_coord)
	{
            $em = $this->getDoctrine()->getManager();
            
            $usr = $this->getDoctrine()
            ->getRepository('MisionSucreRipesBundle:User')
            ->find($id);            
            
            if(!$usr){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Usuario con ID: '.$id.' no registrado'
            );
            
            return $this->redirect($this->generateUrl('usuario_new'));
            }
            
            $ceje= $em->getRepository('MisionSucreRipesBundle:CoordinadorEje')->find($id_coord);
            
            if(!$ceje)
                {   
                    $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Coordinador(a) de Eje no Registrado(a)'
                            );            
                    return $this->redirect($this->generateUrl('usuario_new'));
                    
                }
                            
                            $em->remove($ceje);
                            
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',  
                            'Coordinador de Eje Borrado(a) con Éxito'
                            );

                    return $this->redirect($this->generateUrl('usuario_show',array('id'=>$id)));
		}
                
                public function showAction(Request $request,$id){
            
                $em = $this->getDoctrine()->getManager();
                
                $per =  $em->getRepository('MisionSucreRipesBundle:Persona')->findOneByUser($id);
                    
                if(!$per){
                    $request->getSession()->getFlashBag()->add(
                    'notice',
                    'Datos Personales no registrados. Por Favor Completar los Datos'
                    );    
                    return $this->redirect($this->generateUrl('persona_new',array('id'=>$id)));
                }
                
                $usr = $this->getUser();
              
                $coordinadoreje =  $em->getRepository('MisionSucreRipesBundle:CoordinadorEje')->findOneByUser($id);
                
                if(!$coordinadoreje){
                    $request->getSession()->getFlashBag()->add(
                    'notice',
                    'Eje No Asignado'
                    );    
                    return $this->redirect($this->generateUrl('eje_new',array('id'=>$id)));
                }
                
                $municipioseje = $em->getRepository('MisionSucreRipesBundle:Eje')->findAllOrderedByEje($coordinadoreje->getEje());
                $parroquiaseje = $em->getRepository('MisionSucreRipesBundle:Eje')->findAllOrderedByParroquia($coordinadoreje->getEje());
                
                $usuario =  $em->getRepository('MisionSucreRipesBundle:Role')->findOneByRole($id);    
                $sociales =  $em->getRepository('MisionSucreRipesBundle:Social')->findOneByUser($id); 
                $enfermedades =  $usr->getEnfermedades()->getValues();
                $discapacidad =  $em->getRepository('MisionSucreRipesBundle:Discapacidad')->findOneByUser($id);    
                $ubicacionvivienda = $em->getRepository('MisionSucreRipesBundle:UbicacionVivienda')->findOneByUser($id); 
                $academico =  $em->getRepository('MisionSucreRipesBundle:Academico')->findOneByUser($id);
                $arte =  $em->getRepository('MisionSucreRipesBundle:Arte')->findOneByUser($id);       
                $deporte =  $em->getRepository('MisionSucreRipesBundle:Deporte')->findOneByUser($id);       
                $trabajo =  $em->getRepository('MisionSucreRipesBundle:Trabajo')->findOneByUser($id);       
                $comunitaria =  $em->getRepository('MisionSucreRipesBundle:ParticipacionComunitaria')->findOneByUser($id);       
                $politica =  $em->getRepository('MisionSucreRipesBundle:ParticipacionPolitica')->findOneByUser($id);                    
                
                return $this->render(
			'MisionSucreRipesBundle:Eje:show.html.twig',
			array('usuario' => $usuario,'persona' => $per,'sociales'=> $sociales,'enfermedades'=>$enfermedades,
                        'discapacidad'=>$discapacidad,'arte'=>$arte,'deporte'=>$deporte,'trabajo'=>$trabajo,
                            'comunitaria'=>$comunitaria,'politica'=>$politica,'academico'=>$academico,
                            'coordinadoreje'=>$coordinadoreje,'municipioseje'=>$municipioseje,'parroquiaseje'=>$parroquiaseje,
                            'ubicacionvivienda'=>$ubicacionvivienda
                        )
		);
        }
        
                public function infoAction(Request $request){
            
                $em = $this->getDoctrine()->getManager();
                
                $usr = $this->getUser();
                
                $id = $usr->getId();
                
                $per =  $em->getRepository('MisionSucreRipesBundle:Persona')->findOneByUser($id);
                    
                if(!$per){
                    $request->getSession()->getFlashBag()->add(
                    'notice',
                    'Datos Personales no registrados. Por Favor Completar los Datos'
                    );    
                    return $this->redirect($this->generateUrl('persona_new',array('id'=>$id)));
                }
                
                $usr = $this->getUser();
                
                $coordinadoreje =  $em->getRepository('MisionSucreRipesBundle:CoordinadorEje')->findOneByUser($id);
                
                if($coordinadoreje){
                $municipioseje = $em->getRepository('MisionSucreRipesBundle:Eje')->findAllOrderedByEje($coordinadoreje->getEje());
                $parroquiaseje = $em->getRepository('MisionSucreRipesBundle:Eje')->findAllOrderedByParroquia($coordinadoreje->getEje());
                }
                else{
                $municipioseje = null;
                $parroquiaseje = null;
                }
                    
                $usuario =  $em->getRepository('MisionSucreRipesBundle:Role')->findOneByRole($id);    
                $sociales =  $em->getRepository('MisionSucreRipesBundle:Social')->findOneByUser($id); 
                $enfermedades =  $usr->getEnfermedades()->getValues();
                $discapacidad =  $em->getRepository('MisionSucreRipesBundle:Discapacidad')->findOneByUser($id);       
                $academico =  $em->getRepository('MisionSucreRipesBundle:Academico')->findOneByUser($id);
                $arte =  $em->getRepository('MisionSucreRipesBundle:Arte')->findOneByUser($id);   
                $ubicacionvivienda = $em->getRepository('MisionSucreRipesBundle:UbicacionVivienda')->findOneByUser($id); 
                $deporte =  $em->getRepository('MisionSucreRipesBundle:Deporte')->findOneByUser($id);       
                $trabajo =  $em->getRepository('MisionSucreRipesBundle:Trabajo')->findOneByUser($id);       
                $comunitaria =  $em->getRepository('MisionSucreRipesBundle:ParticipacionComunitaria')->findOneByUser($id);       
                $politica =  $em->getRepository('MisionSucreRipesBundle:ParticipacionPolitica')->findOneByUser($id);                    
                
                return $this->render(
			'MisionSucreRipesBundle:Eje:show.html.twig',
			array('usuario' => $usuario,'persona' => $per,'sociales'=> $sociales,'enfermedades'=>$enfermedades,
                        'discapacidad'=>$discapacidad,'arte'=>$arte,'deporte'=>$deporte,'trabajo'=>$trabajo,
                            'comunitaria'=>$comunitaria,'politica'=>$politica,'academico'=>$academico,
                            'coordinadoreje'=>$coordinadoreje,'municipioseje'=>$municipioseje,'parroquiaseje'=>$parroquiaseje,
                            'ubicacionvivienda' => $ubicacionvivienda
                        )
		);
        }
/*
 * FUNCIONES EXTRAS
 */
 
    protected function ChoicesCoordinadoresEje() {
        
    $choices = array();
    
    $em = $this->getDoctrine()->getManager();
    $usreje = $em->getRepository('MisionSucreRipesBundle:CoordinadorEje')->findAllOrderedByUser();

    foreach ($usreje as $u) {
        $choices[$u['id']] =$u['username'];
    }
    return $choices;
    }        
    
    protected function CoordinadoresEje() {
        
    $coordinadoresejes = array();
    
    $em = $this->getDoctrine()->getManager();
    $usreje = $em->getRepository('MisionSucreRipesBundle:CoordinadorEje')->findAll();

    foreach ($usreje as $u) {
        $coordinadoresejes[$u->getId()]['info'] =$u;
        $municipioseje = $em->getRepository('MisionSucreRipesBundle:Eje')->findAllOrderedByEje($u->getEje()->getId());
        $parroquiaseje = $em->getRepository('MisionSucreRipesBundle:Eje')->findAllOrderedByParroquia($u->getEje()->getId());
        $coordinadoresejes[$u->getId()]['municipios'] =$municipioseje;
        $coordinadoresejes[$u->getId()]['parroquias'] =$parroquiaseje;
    }
    return $coordinadoresejes;
    }        
    
    protected function Ejes() {
        
    $choices = array();
    
    $em = $this->getDoctrine()->getManager();
    $ejes = $em->getRepository('MisionSucreRipesBundle:CoordinadorEje')->findAllOrderedByEje();

    foreach ($ejes as $e) {
        $choices[$e['id']] =$e['nombre'];
    }
    return $choices;
    }        
}
