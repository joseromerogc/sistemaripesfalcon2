<?php

namespace MisionSucre\RipesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MisionSucre\RipesBundle\Entity\CoordinadorAldea;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;

class CoordinadorController extends Controller
{
    public function indexAction()
    {
        return $this->render('MisionSucreRipesBundle:Usuario:index.html.twig');
    }
	
	public function newAction(Request $request,$id)
	{       
            /*
             * Registrar Nuevo Coordinador de Aldea Dado su idusr
             */
             
		$caldea = new CoordinadorAldea();
                
                $em = $this->getDoctrine()->getManager();
                
                $user = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:User')
                ->find($id);
                /*VALIDAR */
                $validar = $this->get('servicios.validar');
                
                $error = $validar->ValidarUsuario($user,5,$id,$request);
                if($error)
                    return $this->redirect($this->generateUrl($error['url'],array($error['param']=>$error['valueparam'])));
                /*FIN VALIDAR*/
                
                 $auxcaldea = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:CoordinadorAldea')
                ->findByUser($id);            
                
                if($auxcaldea){

                $request->getSession()->getFlashBag()->add(
                'notice',
                'Coordinador(a) ya registrado(a)'
                );
                return $this->redirect($this->generateUrl('usuario_show',array('id'=>$id)));
                }      
                
                $per =  $em->getRepository('MisionSucreRipesBundle:Persona')->findOneByUser($id);
                    
                if(!$per){
                    
                    $request->getSession()->getFlashBag()->add(
                    'notice',
                    'Datos Personales no registrados. Por Favor Completar los Datos'
                    );    
                    return $this->redirect($this->generateUrl('persona_new',array('id'=>$id)));
                }
                        
		$form = $this->createFormBuilder($caldea)->
        add('municipio', 'choice', array(
        'choices' => $this->MunicipiosEje(),'mapped' =>false,
            'placeholder'=>"Seleccione una",'label' => 'Municipio de la Aldea'
            ))            
        ->add('idaldea', 'hidden',array('mapped' => false))                                           
        ->add('save', 'submit',array('label' => 'Registrar Coordinador de Aldea'))->getForm();
            
                $form->handleRequest($request);
                
                
		if ($form->isValid()) {
                            
                            $em = $this->getDoctrine()->getManager();    
                            
                            $idaldea = $form->get('idaldea')->getData();
                                
                            $aldea = $em->getRepository('MisionSucreRipesBundle:Aldea')->find($idaldea);
                            
                            $caldea->setUser($user);    
                            $caldea->setAldea($aldea);    
                            $em->persist($caldea);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Coordinador de Aldea Creado con Éxito'
                            );            
                    return $this->redirect($this->generateUrl('usuario_show',array('id'=>$id)));
		}
		
		return $this->render('MisionSucreRipesBundle:Coordinador:new.html.twig', array(
		'form' => $form->createView(),'mensaje_heading'=>'Nuevo Coordinador',
                    'sub_heading'=>'Aldea Universitaria','usr'=>$user,'per' => $per
		));
	}
        
        public function newidAction(Request $request,$idc,$idaldea)
	{       
	/*
         * Registrar Usuario dado la aldea
         */
		$caldea = new CoordinadorAldea();
                
                $em = $this->getDoctrine()->getManager();
                
                $aldea =  $em->getRepository('MisionSucreRipesBundle:Aldea')->find($idaldea);
                
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
                
                 $auxcaldea = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:CoordinadorAldea')
                ->findByUser($idc);            
                
                $usuario = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:User')
                ->find($idc);
                
                /*VALIDAR */
                $validar = $this->get('servicios.validar');
                
                $error = $validar->ValidarUsuario($usuario,5,$idc,$request);
                if($error)
                    return $this->redirect($this->generateUrl($error['url'],array($error['param']=>$error['valueparam'])));
                /*FIN VALIDAR*/
            
                if($auxcaldea){

                $request->getSession()->getFlashBag()->add(
                'notice',
                'Coordinador(a) ya registrado(a)'
                );
                return $this->redirect($this->generateUrl('usuario_show',array('id'=>$id)));
                }      
                
                
                $per =  $em->getRepository('MisionSucreRipesBundle:Persona')->findOneByUser($idc);
                    
                if(!$per){
                    
                    $request->getSession()->getFlashBag()->add(
                    'notice',
                    'Datos Personales no registrados. Por Favor Completar los Datos'
                    );    
                    return $this->redirect($this->generateUrl('persona_new',array('id'=>$idc)));
                }
                        
		$form = $this->createFormBuilder($caldea)                           
        ->add('save', 'submit',array('label' => 'Registrar Coordinador de Aldea'))->getForm();
            
                $form->handleRequest($request);
                
                
		if ($form->isValid()) {
                            
                            $em = $this->getDoctrine()->getManager();    
                            $caldea->setUser($usuario);    
                            $caldea->setAldea($aldea);    
                            $em->persist($caldea);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Coordinador de Aldea Creado con Éxito'
                            );            
                    return $this->redirect($this->generateUrl('usuario_show',array('id'=>$idc)));
		}
		
		return $this->render('MisionSucreRipesBundle:Coordinador:newid.html.twig', array(
		'form' => $form->createView(),'mensaje_heading'=>'Nuevo Coordinador',
                    'sub_heading'=>'Aldea Universitaria','usr'=>$usuario,'per' => $per,'aldea'=>$aldea
		));
	}
        
        public function updateAction(Request $request,$id)
	{
            /*
             * Actualizar Coordinador de Aldea
             */
             
            $em = $this->getDoctrine()->getManager();
            
            $caldea = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:CoordinadorAldea')
                ->find($id);            
                
                if(!$caldea){

                $request->getSession()->getFlashBag()->add(
                'notice',
                'Coordinador no Registrado(a)'
                );
                return $this->redirect($this->generateUrl('usuario_show',array('id'=>$id)));
                }      
                
                $usr = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:User')
                ->find($caldea->getUser()->getId());
                
                /*VALIDAR */
                $validar = $this->get('servicios.validar');
                
                $error = $validar->ValidarAldea($caldea->getAldea()->getId(),$request);
                if($error)
                    return $this->redirect($this->generateUrl($error['url'],array($error['param']=>$error['valueparam'])));
                /*FIN VALIDAR*/
                
                $per =  $em->getRepository('MisionSucreRipesBundle:Persona')->findOneByUser($caldea->getUser()->getId());
                    
                if(!$per){
                    
                    $request->getSession()->getFlashBag()->add(
                    'notice',
                    'Datos Personales no registrados. Por Favor Completar los Datos'
                    );    
                    return $this->redirect($this->generateUrl('persona_new',array('id'=>$id)));
                }
                
                        
		$form = $this->createFormBuilder($caldea)->
        add('municipio', 'choice', array(
        'choices' => $this->MunicipiosEje(),'mapped' =>false,
            'placeholder'=>"Seleccione una",'label' => 'Municipio de la Aldea'
            ))            
        ->add('idaldea', 'hidden',array('mapped' => false))                           
        ->add('save', 'submit',array('label' => 'Registrar Coordinador de Aldea'))->getForm();
            
                $form->handleRequest($request);
                
                
		if ($form->isValid()) {
                            
                            $em = $this->getDoctrine()->getManager();    
                            
                            $idaldea = $form->get('idaldea')->getData();
                                
                            $aldea = $em->getRepository('MisionSucreRipesBundle:Aldea')->find($idaldea);
                               
                            $caldea->setAldea($aldea);    
                            $em->persist($caldea);
                            $em->flush();
                            
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Coordinador de Aldea Actualizado con Éxito'
                            );            
                    return $this->redirect($this->generateUrl('usuario_show',array('id'=>$caldea->getUser()->getId())));
		}
		
		return $this->render('MisionSucreRipesBundle:Coordinador:new.html.twig', array(
		'form' => $form->createView(),'mensaje_heading'=>'Actualizar Coordinador',
                    'sub_heading'=>'Aldea Universitaria','usr'=>$usr,'per' => $per
		));
	}
        
        public function deleteAction(Request $request,$id)
	{
            $em = $this->getDoctrine()->getManager();
            
            $caldea = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:CoordinadorAldea')
                ->find($id);            
            
            if(!$caldea){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Coordinador con ID: '.$id.' no registrado'
            );
            
            return $this->redirect('asignar_coordinador');
            }
            
            /*VALIDAR */
                $validar = $this->get('servicios.validar');
                
                $error = $validar->ValidarAldea($caldea->getAldea()->getId(),$request);
                if($error)
                    return $this->redirect($this->generateUrl($error['url'],array($error['param']=>$error['valueparam'])));
                /*FIN VALIDAR*/
            
                                                        
                            $em->remove($caldea);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Coordinador de Aldea Borrado con Éxito'
                            );

                    return $this->redirect($this->generateUrl('usuario_show',array('id'=>$caldea->getUser()->getId())));
		}
                
        public function listaAction(Request $request)
	{       
            /*
             * Muestra el listado de Coordinadores de aldea ROLE>EJE
             */
                $em = $this->getDoctrine()->getManager();
                
		$caldeas =  $em->getRepository('MisionSucreRipesBundle:CoordinadorAldea')->findAllOrderedById();
                
                if($this->getUser()->getTipUsr()==8){
                $user = $this->getUser();
                $usreje = $em->getRepository('MisionSucreRipesBundle:CoordinadorEje')->findOneByUser($user->getId());
                $caldeas =  $em->getRepository('MisionSucreRipesBundle:CoordinadorAldea')->findAllOrderedByIdEje($usreje->getEje()->getId());
                }
                
		if(!$caldeas){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Ningún Coordinador Registrado'
            );
                return $this->redirect($this->generateUrl('asignar_coordinador'));
            }
			
		return $this->render(
			'MisionSucreRipesBundle:Coordinador:lista.html.twig',
                array('coordinadores' => $caldeas)
		);	
	}
                
        public function asignaraldeaAction(Request $request,$idaldea)
	{       
            $em = $this->getDoctrine()->getManager();
                if($idaldea){
                        $aldea = $em->getRepository('MisionSucreRipesBundle:Aldea')->find($idaldea);
                        $idaldea = $aldea->getID();
                 
                if(!$aldea){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Aldea No Registrada'
            );
                return $this->redirect($this->generateUrl('aldea_new'));
            }        
                    }
                else{
                    $aldea = null;        
                    $idaldea = 0;
                }
                    
		return $this->render(
			'MisionSucreRipesBundle:Coordinador:buscarasignar.html.twig',array(
                        'aldea' => $aldea,'idaldea' => $idaldea)
		);	
	}
        
        public function busquedaasignaraldeaAction($param)
	{       
                $parametros=json_decode($param,true);
                
                $query=$parametros['query'];
                
                $em = $this->getDoctrine()->getManager();
                
                /*
                 * Muestra la información de los coordinadores no vinculados
                 */
                $result = $em->createQuery(
                        "SELECT DISTINCT u.tip_usr, p.priNom, p.segNom, p.priApe, p.segApe, p.cedPer, p.sexPer,u.id,
                            u.username
                            FROM MisionSucreRipesBundle:Persona p JOIN p.user u
                        WHERE p.cedPer LIKE :ced AND p.priApe LIKE :ape AND u.tip_usr = 5 AND NOT EXISTS
                   ( SELECT a FROM MisionSucreRipesBundle:CoordinadorAldea a WHERE u.id=a.user)" 
                        )->setParameters(array('ced'=>"%{$query['cedula']}%",'ape'=>"%{$query['apellido']}%"))->getResult();
                
		return $this->render(
			'MisionSucreRipesBundle:Coordinador:listaasignar.html.twig',
                        array('filas' => $result,'idaldea'=>$query['idaldea'])
		);	
	}
            
public function choicesaldeaAction($prq) {
        
    $em = $this->getDoctrine()->getManager();
    $aldeas = $em->getRepository('MisionSucreRipesBundle:Aldea')->findByParroquia($prq);

    return $this->render('MisionSucreRipesBundle:Aldea:aldeas.html.twig',array('aldeas'=>$aldeas));
    } 

    public function showAction(Request $request,$id){
     /*
      * Muestra la informacion de coordinador de aldea
      */
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
                
                $coordinador = $em->getRepository('MisionSucreRipesBundle:CoordinadorAldea')->findOneByUser($id);
                
                if($coordinador){
                $ideje = $coordinador->getAldea()->getParroquia()->getEje()->getId();
                
                switch($usr->getTipUsr()){
                    case 8:
                        $coordeje = $this->getDoctrine()
                        ->getRepository('MisionSucreRipesBundle:CoordinadorEje')
                        ->findOneByUser($usr->getId());
                        
                        if(!$coordeje){

                        $request->getSession()->getFlashBag()->add(
                        'notice',
                        'Coordinador de Eje No registrado(a)'
                        );
                        return $this->redirect($this->generateUrl('eje_new',array('id'=>$usr->getId())));
                        } 
                        
                        if(!$coordeje->getEje()->getId()==$ideje)
                        {
                        $request->getSession()->getFlashBag()->add(
                        'notice',
                        'Acceso Denegado a este Eje'
                        );
                        return $this->redirect($this->generateUrl('usuario_lista'));     
                        }
                        
                        break;
                }
                }
                $usuario =  $em->getRepository('MisionSucreRipesBundle:Role')->findOneByRole($id);    
                $sociales =  $em->getRepository('MisionSucreRipesBundle:Social')->findOneByUser($id);
                $ubicacionvivienda = $em->getRepository('MisionSucreRipesBundle:UbicacionVivienda')->findOneByUser($id); 
                $enfermedades =  $usr->getEnfermedades()->getValues();
                $discapacidad =  $em->getRepository('MisionSucreRipesBundle:Discapacidad')->findOneByUser($id);       
                $academico =  $em->getRepository('MisionSucreRipesBundle:Academico')->findOneByUser($id);
                $arte =  $em->getRepository('MisionSucreRipesBundle:Arte')->findOneByUser($id);       
                $deporte =  $em->getRepository('MisionSucreRipesBundle:Deporte')->findOneByUser($id);       
                $trabajo =  $em->getRepository('MisionSucreRipesBundle:Trabajo')->findOneByUser($id);       
                $comunitaria =  $em->getRepository('MisionSucreRipesBundle:ParticipacionComunitaria')->findOneByUser($id);       
                $politica =  $em->getRepository('MisionSucreRipesBundle:ParticipacionPolitica')->findOneByUser($id); 
                $bloqueo =  $em->getRepository('MisionSucreRipesBundle:Bloqueado')->findOneByCedulas($per->getCedPer());
                
                if($coordinador)
                    $turnos = $em->getRepository('MisionSucreRipesBundle:CoordinadorTurno')->findAllByCoordinador($coordinador->getId()); 
                else
                    $turnos=null;
                
                return $this->render(
			'MisionSucreRipesBundle:Coordinador:show.html.twig',
			array('usuario' => $usuario,'persona' => $per,'sociales'=> $sociales,'enfermedades'=>$enfermedades,
                        'discapacidad'=>$discapacidad,'arte'=>$arte,'deporte'=>$deporte,'trabajo'=>$trabajo,
                            'comunitaria'=>$comunitaria,'politica'=>$politica,'academico'=>$academico,'coordinador'=>$coordinador,
                            'ubicacionvivienda'=>$ubicacionvivienda,'turnos'=>$turnos,'bloqueo'=>$bloqueo
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
            
                $coordinador = $em->getRepository('MisionSucreRipesBundle:CoordinadorAldea')->findOneByUser($id);
                
                if($coordinador){
                $ideje = $coordinador->getAldea()->getParroquia()->getEje()->getId();
                
                switch($usr->getTipUsr()){
                    case 8:
                        $coordeje = $this->getDoctrine()
                        ->getRepository('MisionSucreRipesBundle:CoordinadorEje')
                        ->findOneByUser($usr->getId());
                        
                        if(!$coordeje){

                        $request->getSession()->getFlashBag()->add(
                        'notice',
                        'Coordinador de Eje No registrado(a)'
                        );
                        return $this->redirect($this->generateUrl('eje_new',array('id'=>$usr->getId())));
                        } 
                        
                        if(!$coordeje->getEje()->getId()==$ideje)
                        {
                        $request->getSession()->getFlashBag()->add(
                        'notice',
                        'Acceso Denegado a este Eje'
                        );
                        return $this->redirect($this->generateUrl('usuario_lista'));     
                        }
                        
                        break;
                }
                }
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
                $turnos = $em->getRepository('MisionSucreRipesBundle:CoordinadorTurno')->findAllByCoordinador($coordinador->getId()); 
                
                
                return $this->render(
			'MisionSucreRipesBundle:Coordinador:show.html.twig',
			array('usuario' => $usuario,'persona' => $per,'sociales'=> $sociales,'enfermedades'=>$enfermedades,
                        'discapacidad'=>$discapacidad,'arte'=>$arte,'deporte'=>$deporte,'trabajo'=>$trabajo,
                            'comunitaria'=>$comunitaria,'politica'=>$politica,'academico'=>$academico,'coordinador'=>$coordinador,
                            'turnos'=>$turnos,'ubicacionvivienda'=>$ubicacionvivienda
                        )
		);
        }
/*
 * FUNCIONES EXTRAS
 */
 
protected function MunicipiosEje() {
        
    $choices = array();
    
    $user = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    
    if($this->getUser()->getTipUsr()==8){
        
        $usreje = $em->getRepository('MisionSucreRipesBundle:CoordinadorEje')->findOneByUser($user->getId());
        
        $municipioseje = $em->getRepository('MisionSucreRipesBundle:Eje')->findAllOrderedByEje($usreje->getEje()->getId());
        
        foreach ($municipioseje as $m) {
        $choices[$m['id']] =$m['municipio'];
        }
        
        }
    else{
        
        $municipioseje = $this->getDoctrine()
            ->getRepository('MisionSucreRipesBundle:Municipio')
            ->findAll();
        foreach ($municipioseje as $m) {
        $choices[$m->getId()] =$m->getMunicipio();
        }
    }
    
    return $choices;
                
 }

protected function ChoicesCoordinadoresAldea() {
        
    $choices = array();
    
    $em = $this->getDoctrine()->getManager();
    $ualdea = $em->getRepository('MisionSucreRipesBundle:CoordinadorAldea')->findAllOrderedByUser();
    foreach ($ualdea as $u) {
        $choices[$u['id']] =$u['username'];
    }
    return $choices;
    }        
    
}
