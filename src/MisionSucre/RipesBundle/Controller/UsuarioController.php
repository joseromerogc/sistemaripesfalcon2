<?php

namespace MisionSucre\RipesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MisionSucre\RipesBundle\Entity\User;
use MisionSucre\RipesBundle\Entity\Role;
use MisionSucre\RipesBundle\Entity\Persona;
use MisionSucre\RipesBundle\Entity\RegistroUsuario;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\HttpFoundation\Response;

class UsuarioController extends Controller
{
    public function indexAction()
    {   
        
                    if(!$this->get('security.authorization_checker')->isGranted('ROLE_COORD')) {
                //SI EL USUARIO NO ES COORDINADORES O SUPERIOR NO PUEDE ACCEDER A LAS OPCIONES DE USUARIO
                $id = $this->getUser()->getId();
                
                return $this->redirect($this->generateUrl('usuario_info'));
                }
                else{
                    return $this->render('MisionSucreRipesBundle:Usuario:index.html.twig');
                }
    }
	
	public function newAction(Request $request)
	{		
		$usr = new User();
                
		$form = $this->createFormBuilder($usr)->
                        add('username', 'email',array('label' => 'Correo Electrónico'))
                ->add('password', 'repeated', array(
           'first_name'  => 'password',
           'second_name' => 'confirm',
            'invalid_message' => 'Las Contraseñas no Coinciden.',
           'type'        => 'password',
            'required' => true,
            'first_options'  => array('label' => 'Contraseña'),
            'second_options' => array('label' => 'Repetir Contraseña'),
        ))->add('tip_usr', 'choice', array(
        'choices' => $this->TiposUsers(),
            'placeholder'=>"Seleccione una",'label' => 'Tipo de Usuario'
            ))
        ->add('save', 'submit',array('label' => 'Registrar Usuario'))->getForm();
            
                $form->handleRequest($request);
		if ($form->isValid()) {
                    
                            
                            $em = $this->getDoctrine()->getManager();    
                                
                            $role = $em->getRepository('MisionSucreRipesBundle:Role')->find($usr->getTipUsr());
                            
                            $encoder = $this->container->get('security.password_encoder');
                            $encoded = $encoder->encodePassword($usr,  $usr->getPassword());
							
                            $usr->setPassword($encoded);
                            
                            $role->addUser($usr);
                            $usr->addRole($role);
                            $em->persist($role);
                            $em->persist($usr);
                            
                            /*Datos de Registro
                             */
                            $registrado = new RegistroUsuario();
                            $registrado->setRegistrador($this->getUser());
                            $registrado->setRegistrado($usr);
                            $em->persist($registrado);
                            $em->flush();
                            
                            $id=$usr->getId();
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Usuario Creado con Éxito'
                            );            
                    return $this->redirect($this->generateUrl('usuario_show',array('id'=>$id)));
		}
		
		return $this->render('MisionSucreRipesBundle:Usuario:new.html.twig', array(
		'form' => $form->createView(),'mensaje_heading'=>'Nuevo Usuario',
                    'sub_heading'=>'Registro de Usuario'
		));
	}
        public function updateAction(Request $request,$id)
	{
            $em = $this->getDoctrine()->getManager();
            
            $usr = $this->getDoctrine()
            ->getRepository('MisionSucreRipesBundle:User')
            ->find($id);            
            
            if(!$this->get('security.authorization_checker')->isGranted('ROLE_COORD')) {
                
                $user = $this->getUser();
                
                if($user->getId()!=$id){
                    $request->getSession()->getFlashBag()->add(
                    'notice',
                    'Acceso Denegado'
                    );
            return $this->redirect($this->generateUrl('usuario_show',array('id'=>$id)));
            }}
            
            
            if(!$usr){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Usuario con ID: '.$id.' no registrado'
            );
            return $this->redirect($this->generateUrl('usuario_show',array('id'=>$id)));
            }
		$form = $this->createFormBuilder($usr)->
                        add('username', 'email',array('label' => 'Correo Electrónico'))
                ->add('password', 'repeated', array(
           'first_name'  => 'password',
           'second_name' => 'confirm',  
            'invalid_message' => 'Las Contraseñas no Coinciden.',
           'type'        => 'password',
            'required' => true,
            'first_options'  => array('label' => 'Contraseña'),
            'second_options' => array('label' => 'Repetir Contraseña'),
        ))->add('tip_usr', 'choice', array(
        'choices' => $this->TiposUsers(),'label' => 'Tipo de Usuario'
            ))            

        ->add('save', 'submit',array('label' => 'Actualizar Usuario'))->getForm();
            
                $form->handleRequest($request);
		if ($form->isValid()) {
                            
                            $em = $this->getDoctrine()->getManager();    
                                
                            $role = $em->getRepository('MisionSucreRipesBundle:Role')->find($usr->getTipUsr());
                            
                            $encoder = $this->container->get('security.password_encoder');
                            $encoded = $encoder->encodePassword($usr,  $usr->getPassword());
							
                            $usr->setPassword($encoded);
                            
                            $role->removeUser($usr);
                            $role->addUser($usr);
                            $usr->removeRole($role);
                            $usr->addRole($role);
                            $em->persist($role);
                            $em->persist($usr);
                            $em->flush();
                        $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Usuario Actualizado con Éxito'
                            );                  
                    return $this->redirect($this->generateUrl('usuario_show',array('id'=>$id)));
		}
		
		return $this->render('MisionSucreRipesBundle:Usuario:new.html.twig', array(
		'form' => $form->createView(),'usr'=> $usr,'mensaje_heading'=>'Actualización de Usuario',
                    'sub_heading'=>'Verificar datos'
		));
	} 
        public function deleteAction(Request $request,$id)
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
            return $this->redirect('../lista');
            }
		            
            $ceje = $ceje= $em->getRepository('MisionSucreRipesBundle:CoordinadorEje')->findOneByUser($id);
            
            if($ceje)
                {   
                    $request->getSession()->getFlashBag()->add(
                            'notice',
                            'No Puedes Borrar un Coordinador de Eje Activo. Primero Eliminelo en su Función'
                            );            
                    return $this->redirect($this->generateUrl('usuario_show',array('id'=>$id)));
                }
                
            $caldea = $em->getRepository('MisionSucreRipesBundle:CoordinadorAldea')->findOneByUser($id);
            
            if($caldea)
                {   
                    $request->getSession()->getFlashBag()->add(
                            'notice',
                            'No Puedes Borrar un Coordinador de Aldea Activo. Primero Eliminelo en su Función'
                            );            
                    return $this->redirect($this->generateUrl('usuario_show',array('id'=>$id)));
                }
            $triunfador = $em->getRepository('MisionSucreRipesBundle:Triunfador')->findOneByUser($id);
            
            if($triunfador)
                {   
                    $request->getSession()->getFlashBag()->add(
                            'notice',
                            'No Puedes Borrar un Triunfador Registrado. Primero Eliminelo en su Función'
                            );            
                    return $this->redirect($this->generateUrl('usuario_show',array('id'=>$id)));
                }    
                
            $docente = $em->getRepository('MisionSucreRipesBundle:Docente')->findOneByUser($id);
            
            if($docente)
                {   
                    $request->getSession()->getFlashBag()->add(
                            'notice',
                            'No Puedes Borrar un Docente Registrado. Primero Eliminelo en su Función'
                            );            
                    return $this->redirect($this->generateUrl('usuario_show',array('id'=>$id)));
                }    
            $operario = $em->getRepository('MisionSucreRipesBundle:Operario')->findOneByUser($id);
            
            if($operario)
                {   
                    $request->getSession()->getFlashBag()->add(
                            'notice',
                            'No Puedes Borrar un Personal Operativo Registrado. Primero Eliminelo en su Función'
                            );            
                    return $this->redirect($this->generateUrl('usuario_show',array('id'=>$id)));
                }        
                    
                            $role = $em->getRepository('MisionSucreRipesBundle:Role')->find($usr->getTipUsr());
                            $persona= $em->getRepository('MisionSucreRipesBundle:Persona')->findOneByUser($id);
                            $sociales =  $em->getRepository('MisionSucreRipesBundle:Social')->findByUser($id); 
                            $enfermedades =  $usr->getEnfermedades()->getValues();
                            
                            foreach ($enfermedades as $e) {
                                $e->removeUser($usr);
                                $usr->removeEnfermedade($e);
                            }
                            
                            $discapacidad =  $em->getRepository('MisionSucreRipesBundle:Discapacidad')->findOneByUser($id);       
                            $academico =  $em->getRepository('MisionSucreRipesBundle:Academico')->findOneByUser($id);
                            $arte =  $em->getRepository('MisionSucreRipesBundle:Arte')->findOneByUser($id);       
                            $deporte =  $em->getRepository('MisionSucreRipesBundle:Deporte')->findOneByUser($id);       
                            $trabajo =  $em->getRepository('MisionSucreRipesBundle:Trabajo')->findOneByUser($id);       
                            $comunitaria =  $em->getRepository('MisionSucreRipesBundle:ParticipacionComunitaria')->findOneByUser($id);       
                            $politica =  $em->getRepository('MisionSucreRipesBundle:ParticipacionPolitica')->findOneByUser($id);    
                            $usuarioregistrado =  $em->getRepository('MisionSucreRipesBundle:RegistroUsuario')->findOneByRegistrado($id);
                            
                            $role->removeUser($usr);
                            $usr->removeRole($role);
                            
                            if($persona)
                                $em->remove($persona);
                            if($sociales)
                                $em->remove($sociales);
                            if($discapacidad)
                                $em->remove($discapacidad);
                            if($academico)
                                $em->remove($academico);
                            if($arte)
                                $em->remove($arte);
                            if($deporte)
                                $em->remove($deporte);
                            if($trabajo)
                                $em->remove($trabajo);
                            if($comunitaria)
                                $em->remove($comunitaria);
                            if($politica)
                                $em->remove($politica);
                            if($usuarioregistrado)
                                $em->remove($usuarioregistrado);
                            
                            $em->remove($usr);
                            
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Usuario Borrado con Éxito'
                            );

                    return $this->redirect($this->generateUrl('usuario_lista'));
		}
                
	public function listaAction(Request $request)
	{       
                $em = $this->getDoctrine()->getManager();
                
                $usr = $this->getUser();
                
                switch($usr->getTipUsr()){
                    
                    case 1:
                    case 2:    
                        $usrs = $em->getRepository('MisionSucreRipesBundle:Role')->findAllOrderedByRoleAndName();
                        break;
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
                        $usrs = $em->getRepository('MisionSucreRipesBundle:CoordinadorEje')->findAllByEje($coordeje->getEje());
                        break;
                    case 5:
                        $coordaldea = $this->getDoctrine()
                        ->getRepository('MisionSucreRipesBundle:CoordinadorAldea')
                        ->findOneByUser($usr->getId());
                        
                        if(!$coordaldea){

                        $request->getSession()->getFlashBag()->add(
                        'notice',
                        'Coordinador de Aldea No registrado(a)'
                        );
                        return $this->redirect($this->generateUrl('eje_new',array('id'=>$usr->getId())));
                        } 
                        $usrs = $em->getRepository('MisionSucreRipesBundle:CoordinadorAldea')->findAllByAldea($coordaldea->getAldea());
                        break;
                }
            
            if(!$usrs){
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Ningún Usuario Registrado'
            );
                return $this->redirect($this->generateUrl('usuario_new'));
            }
                
		return $this->render(
			'MisionSucreRipesBundle:Usuario:lista.html.twig',
                array('usuarios' => $usrs)
		);	
	}
        
        public function listanovinculadoAction(Request $request)
	{       
                $em = $this->getDoctrine()->getManager();
                
		$cejes = $em->createQuery(
                        "SELECT DISTINCT u.username, u.id FROM MisionSucreRipesBundle:User u
                            WHERE u.tip_usr=8 AND NOT EXISTS 
                            ( SELECT c FROM MisionSucreRipesBundle:CoordinadorEje c WHERE u.id=c.user)
                        "
                        )->getResult();
		
                $caldeas = $em->createQuery(
                        "SELECT DISTINCT u.username, u.id FROM MisionSucreRipesBundle:User u
                            WHERE u.tip_usr=5 AND NOT EXISTS 
                            ( SELECT c FROM MisionSucreRipesBundle:CoordinadorAldea c WHERE u.id=c.user)
                        "
                        )->getResult();
                
                /* Si el tipo de usuario es coordinador debe verificar que el lo registro
                 */
                
                $usr = $this->getUser();
                $idusr= $usr->getId() ;
        if($usr->getTipUsr()==5){
                $triunfadores = $em->createQuery(
                        "SELECT DISTINCT u.username, u.id FROM MisionSucreRipesBundle:RegistroUsuario ru JOIN ru.registrado u
                            WHERE u.tip_usr=6 AND ru.registrador=:registrador AND NOT EXISTS 
                            ( SELECT t FROM MisionSucreRipesBundle:Triunfador t WHERE u.id=t.user)
                            
                        "
                        )->setParameters(array('registrador'=>$idusr))->getResult();
        }else{
                $triunfadores = $em->createQuery(
                        "SELECT DISTINCT u.username, u.id FROM MisionSucreRipesBundle:User u
                            WHERE u.tip_usr=6 AND NOT EXISTS 
                            ( SELECT t FROM MisionSucreRipesBundle:Triunfador t WHERE u.id=t.user)
                        "
                        )->getResult();
        }  
        
        if($usr->getTipUsr()==5){
                $docentes = $em->createQuery(
                        "SELECT DISTINCT u.username, u.id FROM MisionSucreRipesBundle:RegistroUsuario ru JOIN ru.registrado u
                            WHERE u.tip_usr=7 AND ru.registrador=:registrador AND NOT EXISTS 
                            ( SELECT d FROM MisionSucreRipesBundle:Docente d WHERE u.id=d.user)
                            
                        "
                        )->setParameters(array('registrador'=>$idusr))->getResult();
        }else{
                $docentes = $em->createQuery(
                        "SELECT DISTINCT u.username, u.id FROM MisionSucreRipesBundle:User u
                            WHERE u.tip_usr=7 AND NOT EXISTS 
                            ( SELECT d FROM MisionSucreRipesBundle:Docente d WHERE u.id=d.user)
                        "
                        )->getResult();
        } 
        
        if($usr->getTipUsr()==5){
                $operarios = $em->createQuery(
                        "SELECT DISTINCT u.username, u.id FROM MisionSucreRipesBundle:RegistroUsuario ru JOIN ru.registrado u
                            WHERE u.tip_usr=9 AND ru.registrador=:registrador AND NOT EXISTS 
                            ( SELECT o FROM MisionSucreRipesBundle:Operario o WHERE u.id=o.user)
                            
                        "
                        )->setParameters(array('registrador'=>$idusr))->getResult();
        }else{
                $operarios = $em->createQuery(
                        "SELECT DISTINCT u.username, u.id FROM MisionSucreRipesBundle:User u
                            WHERE u.tip_usr=9 AND NOT EXISTS 
                            ( SELECT o FROM MisionSucreRipesBundle:Operario o WHERE u.id=o.user)
                        "
                        )->getResult();
        } 
         
		return $this->render(
			'MisionSucreRipesBundle:Usuario:listanovinculados.html.twig',
                array('cejes' => $cejes,'caldeas' =>$caldeas,'triunfadores' =>$triunfadores
                        ,'docentes' =>$docentes,'operarios' =>$operarios
                        )
		);	
	}
        
        public function showAction(Request $request,$id)
	{       
                $em = $this->getDoctrine()->getManager();
                
                $usr =  $em->getRepository('MisionSucreRipesBundle:User')->find($id);
                
                if(!$usr){
                    
                    $request->getSession()->getFlashBag()->add(
                    'notice',
                    'Usuario con ID: '.$id.' no registrado'
                    );    
                    return $this->redirect($this->generateUrl('usuario_lista'));
                }
                else{
                    
                $novinculado = $this->getDoctrine()
                        ->getRepository('MisionSucreRipesBundle:Role')
                        ->NoVinculado($id);
                
                if(!$novinculado){
                    $usuario =  $em->getRepository('MisionSucreRipesBundle:Role')->findOneByRole($id);    
                $sociales =  $em->getRepository('MisionSucreRipesBundle:Social')->findOneByUser($id); 
                $enfermedades =  $usr->getEnfermedades()->getValues();
                $ubicacionvivienda = $em->getRepository('MisionSucreRipesBundle:UbicacionVivienda')->findOneByUser($id); 
                $discapacidad =  $em->getRepository('MisionSucreRipesBundle:Discapacidad')->findOneByUser($id);       
                $academico =  $em->getRepository('MisionSucreRipesBundle:Academico')->findOneByUser($id);
                $arte =  $em->getRepository('MisionSucreRipesBundle:Arte')->findOneByUser($id);       
                $deporte =  $em->getRepository('MisionSucreRipesBundle:Deporte')->findOneByUser($id);       
                $trabajo =  $em->getRepository('MisionSucreRipesBundle:Trabajo')->findOneByUser($id);       
                $comunitaria =  $em->getRepository('MisionSucreRipesBundle:ParticipacionComunitaria')->findOneByUser($id);       
                $politica =  $em->getRepository('MisionSucreRipesBundle:ParticipacionPolitica')->findOneByUser($id);    
                $per =  $em->getRepository('MisionSucreRipesBundle:Persona')->findOneByUser($id);
                $bloqueo = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Bloqueado')
                ->findOneByCedulas($per->getCedPer());
                
                return $this->render(
			'MisionSucreRipesBundle:Usuario:show.html.twig',
			array('usuario' => $usuario,'persona' => $per,'sociales'=> $sociales,'enfermedades'=>$enfermedades,
                        'discapacidad'=>$discapacidad,'arte'=>$arte,'deporte'=>$deporte,'trabajo'=>$trabajo,
                            'comunitaria'=>$comunitaria,'politica'=>$politica,'academico'=>$academico,
                            'ubicacionvivienda'=>$ubicacionvivienda,'bloqueo'=>$bloqueo
                        )
		);
                }    
                    
                switch($this->getUser()->getTipUsr()){
                    
                    case 6:
                    case 7:
                    case 9:
                        return $this->redirect($this->generateUrl('usuario_info'));
                }    
                    
                switch($usr->getTipUsr()){
                    
                    case 1:
                    case 2:
                        return $this->redirect($this->generateUrl('usuario_lista'));
                    case 6:
                        return $this->redirect($this->generateUrl('triunfador_show',array('id'=>$id)));
                    break;
                    
                    case 5:
                        return $this->redirect($this->generateUrl('aldea_coordinador_show',array('id'=>$id)));
                    break;
                    
                    case 8:
                        return $this->redirect($this->generateUrl('eje_show',array('id'=>$id)));
                    break;
                    
                    case 7:
                        return $this->redirect($this->generateUrl('docente_show',array('id'=>$id)));
                    break;
                    case 9:
                        return $this->redirect($this->generateUrl('operario_show',array('id'=>$id)));
                    break;
                    
                }  
            }	
	}
        
        public function infoAction(Request $request)
	{       
            
                $em = $this->getDoctrine()->getManager();
                
                $usr = $this->getUser();
                
                $id = $usr->getId();
                
                //DEPENDIENDO DEL TIPO DE USUARIO SE ACCEDE A SU RESPECTIVO CONTROLADOR
                $novinculado = $this->getDoctrine()
                        ->getRepository('MisionSucreRipesBundle:Role')
                        ->NoVinculado($usr->getId());
                
                if($novinculado){
                    $usuario =  $em->getRepository('MisionSucreRipesBundle:Role')->findOneByRole($id);    
                $sociales =  $em->getRepository('MisionSucreRipesBundle:Social')->findOneByUser($id); 
                $enfermedades =  $usr->getEnfermedades()->getValues();
                $ubicacionvivienda = $em->getRepository('MisionSucreRipesBundle:UbicacionVivienda')->findOneByUser($id); 
                $discapacidad =  $em->getRepository('MisionSucreRipesBundle:Discapacidad')->findOneByUser($id);       
                $academico =  $em->getRepository('MisionSucreRipesBundle:Academico')->findOneByUser($id);
                $arte =  $em->getRepository('MisionSucreRipesBundle:Arte')->findOneByUser($id);       
                $deporte =  $em->getRepository('MisionSucreRipesBundle:Deporte')->findOneByUser($id);       
                $trabajo =  $em->getRepository('MisionSucreRipesBundle:Trabajo')->findOneByUser($id);       
                $comunitaria =  $em->getRepository('MisionSucreRipesBundle:ParticipacionComunitaria')->findOneByUser($id);       
                $politica =  $em->getRepository('MisionSucreRipesBundle:ParticipacionPolitica')->findOneByUser($id);    
                $per =  $em->getRepository('MisionSucreRipesBundle:Persona')->findOneByUser($id);
                
                return $this->render(
			'MisionSucreRipesBundle:Operario:show.html.twig',
			array('usuario' => $usuario,'persona' => $per,'sociales'=> $sociales,'enfermedades'=>$enfermedades,
                        'discapacidad'=>$discapacidad,'arte'=>$arte,'deporte'=>$deporte,'trabajo'=>$trabajo,
                            'comunitaria'=>$comunitaria,'politica'=>$politica,'academico'=>$academico,
                            'ubicacionvivienda'=>$ubicacionvivienda,'aldea'=>$aldea
                        )
		);
                }
                
                switch($usr->getTipUsr()){
                    case 1:
                    case 2:
                        return $this->redirect($this->generateUrl('usuario'));
                    break;
                    
                    case 6:
                        return $this->redirect($this->generateUrl('usuario_triunfador_info'));
                    break;
                    
                    case 5:
                        return $this->redirect($this->generateUrl('usuario_aldea_coordinador_info'));
                    break;
                    
                    case 8:
                        return $this->redirect($this->generateUrl('usuario_eje_info'));
                    break;
                    
                    case 7:
                        return $this->redirect($this->generateUrl('usuario_docente_info'));
                    break;
                    case 9:
                        return $this->redirect($this->generateUrl('usuario_operario_info'));
                    break;
                    
                }  
            }	
        
    public function buscarAction(Request $request)
	{         
		return $this->render(
			'MisionSucreRipesBundle:Usuario:buscar.html.twig',
                        array()
		);	
	}
    public function busquedaAction($param)
	{       
            
                $parametros=json_decode($param,true);
                
                $query=$parametros['query'];
                
                
                $em = $this->getDoctrine()->getManager();
                
                $usr = $this->getUser();
                
                switch($usr->getTipUsr()){
                    
                    case 1:
                    case 2:
                        $usrs['vinculados'] = $em->getRepository('MisionSucreRipesBundle:Role')->findAllByRoleAndNameAndCedula($query['username'],$query['cedula']);
                        $usrs['novinculados'] = $em->getRepository('MisionSucreRipesBundle:Role')->NoVinculados($query['username'],$query['cedula']);
                        break;
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
                        $usrs['vinculados'] = $em->getRepository('MisionSucreRipesBundle:CoordinadorEje')->findAllByEjeAndUser($coordeje->getEje(),$query['username'],$query['cedula']);
                        $usrs['novinculados'] = array();
                        break;
                    case 5:
                        $coordaldea = $this->getDoctrine()
                        ->getRepository('MisionSucreRipesBundle:CoordinadorAldea')
                        ->findOneByUser($usr->getId());
                        
                        if(!$coordaldea){

                        $request->getSession()->getFlashBag()->add(
                        'notice',
                        'Coordinador de Aldea No registrado(a)'
                        );
                        return $this->redirect($this->generateUrl('eje_new',array('id'=>$usr->getId())));
                        } 
                        $usrs['vinculados'] = $em->getRepository('MisionSucreRipesBundle:CoordinadorAldea')->findAllByAldeaAndUser($coordaldea->getAldea(),$query['username'],$query['cedula']);
                        $usrs['novinculados'] = array();
                        break;
                }
                
		return $this->render(
			'MisionSucreRipesBundle:Usuario:busqueda.html.twig',
                        array('usuarios'=>$usrs)
		);	
	}    
    
    public function resumenAction(Request $request)
	{       
                $em = $this->getDoctrine()->getManager();
                
                $usr = $this->getUser();
                
                switch($usr->getTipUsr()){
                    
                    case 1:
                    case 2:
                        $cantidadejes = $em->createQuery(
                        "SELECT DISTINCT COUNT (u) cantidadcejes FROM MisionSucreRipesBundle:User u
                            WHERE u.tip_usr=8 AND EXISTS 
                            ( SELECT c FROM MisionSucreRipesBundle:CoordinadorEje c WHERE u.id=c.user)
                        "
                        )->getSingleResult();
                        
                        $cantidadcaldeas = $em->createQuery(
                        "SELECT DISTINCT COUNT (u) ccas FROM MisionSucreRipesBundle:User u
                            WHERE u.tip_usr=5 AND EXISTS 
                            ( SELECT c FROM MisionSucreRipesBundle:CoordinadorAldea c WHERE u.id=c.user)
                        "
                        )->getSingleResult();
                        
                        $cantidadtriunfadores = $em->createQuery(
                        "SELECT DISTINCT COUNT (u) cts FROM MisionSucreRipesBundle:User u
                            WHERE u.tip_usr=6 AND EXISTS 
                            ( SELECT t FROM MisionSucreRipesBundle:Triunfador t WHERE u.id=t.user)
                        "
                        )->getSingleResult();
                        
                        $cantidaddocentes = $em->createQuery(
                        "SELECT DISTINCT COUNT (u) cds FROM MisionSucreRipesBundle:User u
                            WHERE u.tip_usr=7 AND EXISTS 
                            ( SELECT d FROM MisionSucreRipesBundle:Docente d WHERE u.id=d.user)
                        "
                        )->getSingleResult();
                        
                        $cantidadoperarios = $em->createQuery(
                        "SELECT DISTINCT COUNT (u) cos FROM MisionSucreRipesBundle:User u
                            WHERE u.tip_usr=9 AND EXISTS 
                            ( SELECT o FROM MisionSucreRipesBundle:Operario o WHERE u.id=o.user)
                        "
                        )->getSingleResult();
                        
                        $cantidadnovinculados = $em->createQuery(
                        "SELECT DISTINCT COUNT (u) cnv FROM MisionSucreRipesBundle:User u 
                            WHERE u.tip_usr!=1 AND NOT EXISTS ( SELECT o FROM MisionSucreRipesBundle:Operario o WHERE u.id=o.user) AND NOT EXISTS
                            ( SELECT t FROM MisionSucreRipesBundle:Triunfador t WHERE u.id=t.user) AND NOT EXISTS
                            ( SELECT d FROM MisionSucreRipesBundle:Docente d WHERE u.id=d.user) AND NOT EXISTS
                            ( SELECT c FROM MisionSucreRipesBundle:CoordinadorAldea c WHERE u.id=c.user) AND NOT EXISTS
                            ( SELECT ce FROM MisionSucreRipesBundle:CoordinadorEje ce WHERE u.id=ce.user)
                        "
                        )->getSingleResult();
                        
                        break;
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
                        
                        $eje = $coordeje->getEje()->getId();
                        
                        $cantidadcaldeas = $em->createQuery(
                        "SELECT DISTINCT COUNT (u) ccas FROM MisionSucreRipesBundle:User u
                            WHERE u.tip_usr=5 AND EXISTS 
                            ( SELECT ca FROM MisionSucreRipesBundle:CoordinadorAldea ca JOIN ca.aldea a JOIN a.parroquia prq
                            WHERE u.id=ca.user AND prq.eje =:eje )
                        "
                        )
                        ->setParameter('eje',$eje)
                        ->getSingleResult();
                        
                        $cantidadtriunfadores = $em->createQuery(
                        "SELECT DISTINCT COUNT (u) cts FROM MisionSucreRipesBundle:User u
                            WHERE u.tip_usr=6 AND EXISTS 
                            ( SELECT t FROM MisionSucreRipesBundle:Triunfador t JOIN t.ambiente am JOIN 
                            am.aldea at JOIN at.parroquia prqt
                            WHERE u.id=t.user AND prqt.eje =:eje )
                        "
                        )->setParameter('eje',$eje)
                        ->getSingleResult();
                        
                        $cantidadejes = null;
                        
                        $cantidaddocentes = $em->createQuery(
                        "SELECT DISTINCT COUNT (u) cds FROM MisionSucreRipesBundle:User u
                            WHERE u.tip_usr=7 AND EXISTS 
                            ( SELECT d FROM MisionSucreRipesBundle:Docente d JOIN d.aldea ad JOIN ad.parroquia prqd
                            WHERE u.id=d.user AND prqd.eje =:eje)
                        "
                        )->setParameter('eje',$eje)
                        ->getSingleResult();
                        
                        $cantidadoperarios = $em->createQuery(
                        "SELECT DISTINCT COUNT (u) cos FROM MisionSucreRipesBundle:User u
                            WHERE u.tip_usr=9 AND EXISTS 
                            ( SELECT o FROM MisionSucreRipesBundle:Operario o JOIN o.aldea ao JOIN ao.parroquia prqo
                            WHERE u.id=o.user AND prqo.eje =:eje)
                        "
                        )->setParameter('eje',$eje)
                        ->getSingleResult();
                        
                        $cantidadnovinculados = $em->createQuery(
                        "SELECT DISTINCT COUNT (u) cnv FROM MisionSucreRipesBundle:User u 
                            WHERE u.tip_usr!=1 AND u.tip_usr!=8 AND NOT EXISTS ( SELECT o FROM MisionSucreRipesBundle:Operario o WHERE u.id=o.user) AND NOT EXISTS
                            ( SELECT t FROM MisionSucreRipesBundle:Triunfador t WHERE u.id=t.user) AND NOT EXISTS
                            ( SELECT d FROM MisionSucreRipesBundle:Docente d WHERE u.id=d.user) AND NOT EXISTS
                            ( SELECT c FROM MisionSucreRipesBundle:CoordinadorAldea c WHERE u.id=c.user)
                        "
                        )->getSingleResult();
                        break;
                    case 5:
                        $coordaldea = $this->getDoctrine()
                        ->getRepository('MisionSucreRipesBundle:CoordinadorAldea')
                        ->findOneByUser($usr->getId());
                        
                        if(!$coordaldea){

                        $request->getSession()->getFlashBag()->add(
                        'notice',
                        'Coordinador de Aldea No registrado(a)'
                        );
                        return $this->redirect($this->generateUrl('eje_new',array('id'=>$usr->getId())));
                        } 
                        
                        $aldea = $coordaldea->getAldea()->getId();
                        
                        $cantidadtriunfadores = $em->createQuery(
                        "SELECT DISTINCT COUNT (u) cts FROM MisionSucreRipesBundle:User u
                            WHERE u.tip_usr=6 AND EXISTS 
                            ( SELECT t FROM MisionSucreRipesBundle:Triunfador t JOIN t.ambiente am
                            WHERE u.id=t.user AND am.aldea =:aldea )
                        "
                        )->setParameter('aldea',$aldea)
                        ->getSingleResult();
                        
                        
                        $cantidadejes = null;
                        $cantidadcaldeas = null;
                        
                        $cantidaddocentes = $em->createQuery(
                        "SELECT DISTINCT COUNT (u) cds FROM MisionSucreRipesBundle:User u
                            WHERE u.tip_usr=7 AND EXISTS 
                            ( SELECT d FROM MisionSucreRipesBundle:Docente d
                            WHERE u.id=d.user AND d.aldea =:aldea )
                        "
                        )->setParameter('aldea',$aldea)
                        ->getSingleResult();
                        
                        $cantidadoperarios = $em->createQuery(
                        "SELECT DISTINCT COUNT (u) cos FROM MisionSucreRipesBundle:User u
                            WHERE u.tip_usr=9 AND EXISTS 
                            ( SELECT o FROM MisionSucreRipesBundle:Operario o
                            WHERE u.id=o.user AND o.aldea =:aldea)
                        "
                        )->setParameter('aldea',$aldea)
                        ->getSingleResult();
                        
                        $cantidadnovinculados = $em->createQuery(
                        "SELECT DISTINCT COUNT (u) cnv FROM MisionSucreRipesBundle:User u 
                            WHERE u.tip_usr!=1 AND u.tip_usr!=8 AND u.tip_usr!=5 AND NOT EXISTS ( SELECT o FROM MisionSucreRipesBundle:Operario o WHERE u.id=o.user) AND NOT EXISTS
                            ( SELECT t FROM MisionSucreRipesBundle:Triunfador t WHERE u.id=t.user) AND NOT EXISTS
                            ( SELECT d FROM MisionSucreRipesBundle:Docente d WHERE u.id=d.user) AND NOT EXISTS
                            ( SELECT c FROM MisionSucreRipesBundle:CoordinadorAldea c WHERE u.id=c.user)
                        "
                        )->getSingleResult();
                        
                        
                        break;
                }
        
		return $this->render(
			'MisionSucreRipesBundle:Usuario:resumen.html.twig',
                        array('ccejes' => $cantidadejes,'ccaldeas' =>$cantidadcaldeas,'cts'=>$cantidadtriunfadores,
                        'cds'=>$cantidaddocentes,'cos'=>$cantidadoperarios, 'cnv'=>$cantidadnovinculados,
                            'total'=>$cantidadcaldeas['ccas']+$cantidaddocentes['cds']+
                        $cantidadejes['cantidadcejes']+$cantidadtriunfadores['cts']+$cantidadoperarios['cos']
                        )
		);	
	}    
/*
 * FUNCIONES EXTRAS
 */
 
    protected function TiposUsers() {
    
    $em = $this->getDoctrine()->getManager();
        
    $choices = array();
    
    $usr = $this->getUser();
    
    switch($usr->getTipUsr()){
        
        case 1:
        $roles = $em->getRepository('MisionSucreRipesBundle:Role')->findAllOrderedByName("");
        break;    
        case 2:
            $coord = $em->getRepository('MisionSucreRipesBundle:Role')->find(5);
            $eje = $em->getRepository('MisionSucreRipesBundle:Role')->find(8);
            $docente = $em->getRepository('MisionSucreRipesBundle:Role')->find(7);
            $triunfador = $em->getRepository('MisionSucreRipesBundle:Role')->find(6);
            $operario = $em->getRepository('MisionSucreRipesBundle:Role')->find(9);
            $rolesobj = array($coord,$eje,$docente,$triunfador,$operario);
        break;
        case 8:
        $role = $em->getRepository('MisionSucreRipesBundle:Role')->find(5);
        $docente = $em->getRepository('MisionSucreRipesBundle:Role')->find(7);
        $triunfador = $em->getRepository('MisionSucreRipesBundle:Role')->find(6);
        $operario = $em->getRepository('MisionSucreRipesBundle:Role')->find(9);
        $rolesobj = array($role,$docente,$triunfador,$operario);
        break;
        case 5:
        $docente = $em->getRepository('MisionSucreRipesBundle:Role')->find(7);
        $triunfador = $em->getRepository('MisionSucreRipesBundle:Role')->find(6);
        $operario = $em->getRepository('MisionSucreRipesBundle:Role')->find(9);
        $rolesobj = array($docente,$triunfador,$operario);
        break;
        default:
            $role = $em->getRepository('MisionSucreRipesBundle:Role')->find($usr->getTipUsr());
        break;    
        
    }
    
    if(isset($roles)){
    foreach ($roles as $r) {
        $choices[$r['id']] =$r['name'];
    }
    return $choices;
    }
    if(isset($rolesobj)){
        foreach ($rolesobj as $role) {
        $choices[$role->getId()] =$role->getName();
    }
    return $choices;
    }
    else{
        $choices[$role->getId()] =$role->getName();
        return $choices;
    }
}

 public function passwordAction(Request $request)
	{
            $em = $this->getDoctrine()->getManager();
            
            $usr = $this->getUser();          
    
	    $form = $this->createFormBuilder($usr)
                ->add('password', 'repeated', array(
           'first_name'  => 'password',
           'second_name' => 'confirm',  
            'invalid_message' => 'Las Contraseñas no Coinciden.',
           'type'        => 'password',
            'required' => true,
            'first_options'  => array('label' => 'Contraseña'),
            'second_options' => array('label' => 'Repetir Contraseña'),
        ))          
        ->add('save', 'submit',array('label' => 'Actualizar Contraseña'))->getForm();
            
                $form->handleRequest($request);
		if ($form->isValid()) {
                            
                            $em = $this->getDoctrine()->getManager();    
                            
                            $encoder = $this->container->get('security.password_encoder');
                            $encoded = $encoder->encodePassword($usr,  $usr->getPassword());
							
                            $usr->setPassword($encoded);
                            $em->persist($usr);
                            $em->flush();
                        $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Contraseña Actualizada con Éxito'
                            );                  
                    return $this->redirect($this->generateUrl('usuario_show',array('id'=>$usr->getId())));
		}
		
		return $this->render('MisionSucreRipesBundle:Usuario:password.html.twig', array(
		'form' => $form->createView(),'usr'=> $usr,'mensaje_heading'=>'Actualización de Contraseña'
                    
		));
	} 
        public function nombreusuarioAction(Request $request)
	{   
            $em = $this->getDoctrine()->getManager();
    
            $usr = $this->getUser();
            
            $p = $em->getRepository('MisionSucreRipesBundle:Persona')->findOneByUser($usr->getId());
            $role = $em->getRepository('MisionSucreRipesBundle:Role')->find($usr->getTipUsr());
            if($p)
            {
                //return new Response($p['priNom'].'' .$p['segNom'].' '. $p['priApe'] .''.$p['segApe']);
                return new Response($role->getName()." <br>".$p->getPriNom().' '.$p->getPriApe());    
            }

            else{
               if($usr->getTipUsr()==1)
                   return new Response('Administrador');
               else
               return new Response('');
            }
        }

// public function cargarAction(Request $request)
//	{       
//                $em = $this->getDoctrine()->getManager();
//                
//                $file=array('file'=>"");
//                
//                $form = $this->createFormBuilder($file)
//                        ->add('submitFile', 'file', array('label' => 'Cargar Formato'))
//                        ->getForm()
//                        ;
//            
//                $form->handleRequest($request);
//                
//		return $this->render(
//			'MisionSucreRipesBundle:Triunfador:cargar.html.twig',
//                        array('form' => $form->createView())
//		);	
//	}
        
        public function registrarcargaAction(Request $request)
	{       
                $em = $this->getDoctrine()->getManager();
                $data = $request->request->get('usuarios');
                $tipousuario = $request->request->get('tipousuario');
                
               // $registros=array(array(1,1234563,'jose','chirinos','dsjfhkjs@gmail.com','42145','54124',23,'f','12/12/2013'),array(1,13454563,'jose','chirinos','dsjfhkdfdsjs@gmail.com','42145','54124',23,'f','12/12/2013'));
                
                if($data){
              $registros=json_decode($data,true);
               
               $batchSize = 20;
             
                foreach ($registros as $i =>$r) {
                    
                    $username= $r[4];
                    $pass= $r[1];
                    
                    $usuariotriunfador = new User();
                    $persona = new Persona();
                    $registrado = new RegistroUsuario();
                    $usuariotriunfador->setuserName($username);
                    $usuariotriunfador->SetTipUsr($tipousuario);
                    
                    $role = $em->getRepository('MisionSucreRipesBundle:Role')->find($tipousuario);        
                    $encoder = $this->container->get('security.password_encoder');
                    $encoded = $encoder->encodePassword($usuariotriunfador, $pass);
                    
                    $usuariotriunfador->setPassword($encoded);
                    
                    $role->addUser($usuariotriunfador);
                    $usuariotriunfador->addRole($role); 
                    
                    try {
                
                    $em->persist($role);
                    $em->persist($usuariotriunfador);
                    
                } catch (Exception $ex) {
                    return new Response("<h1><img src='http://icons.iconarchive.com/icons/designcontest/ecommerce-business/48/alert-icon.png'/>Error en la Carga de Usuarios!</h1>");	
                }
                    /*Datos de Registro
                             */
                            
                            $registrado->setRegistrador($this->getUser());
                            $registrado->setRegistrado($usuariotriunfador);
                            $em->merge($registrado);
                    
                    $persona->setUser($usuariotriunfador);
                    
                    $persona->setPriNom($r[2]);
                    $persona->setPriApe($r[3]);
                    $persona->setTelPer($r[5]);
                    $persona->setCelPer($r[6]);
                    $persona->setEdadPer($r[7]);
                    $persona->setSexPer($r[8]);
                    $persona->setCodCed(0);
                    $persona->setCedPer($r[1]);
                    
                    $fn = explode('/', htmlspecialchars($r[9]));
                    
                    $dia = intval($fn[0]);
                    $mes = intval($fn[1]);
                    $anyo =intval($fn[2]);
                    
                    $fechanacimiento=$anyo."-".$mes."-".$dia;
                    $persona->setFechPer(new \DateTime($fechanacimiento));
                    try{
                    $em->persist($persona);
                    } catch (Exception $ex) {
                    return new Response("<h1><img src='http://icons.iconarchive.com/icons/designcontest/ecommerce-business/48/alert-icon.png'/>Error en la Carga de Usuarios!</h1>");	
                    }
                    if (($i % $batchSize) == 0) {
                        $em->flush();
                        $em->clear();
                    }
                }
                try {
                
                $em->flush();
                $em->clear();
                } catch (Exception $ex) {
                    return new Response("<h1><img src='http://icons.iconarchive.com/icons/designcontest/ecommerce-business/48/alert-icon.png'/>Error en la Carga de Usuarios!</h1>");	
                }
                return new Response("<h1> <img src='http://icons.iconarchive.com/icons/custom-icon-design/pretty-office-8/48/Accept-icon.png' />Carga de Usuarios Éxitosa!</h1>");
                }
                else{
                    return new Response("<h1><img src='http://icons.iconarchive.com/icons/designcontest/ecommerce-business/48/alert-icon.png'/>Sin Data que cargar</h1>");
                }
                    
	}
        
        public function tablacargaAction(Request $request)
	{       
            $rechazadoscedulas=array();
            $rechazadoporrepeticion=array();
            $rechazadoscorreo=array();
            $rechazadosfaltadatos=array();
            $rezachodefechas=  array();
            $tabla= "";
            
         $dir=$_FILES['archivo']['tmp_name'];
            
           // move_uploaded_file($_FILES['archivo']['tmp_name'],$_SERVER['DOCUMENT_ROOT'].'documentos/'.$FILES['archivo']['name']);
            
//            $f = fopen("$dir", "r");
//            $line = fgetcsv($f);
//            $line = fgetcsv($f);
            
            
            //$dir=$_SERVER['DOCUMENT_ROOT'].'documentos/triunfadores.csv';
            $em = $this->getDoctrine()->getManager();
            
            
            $tabla = "<table class='table-style-two' id='tablacentrada' cellspacing='0'>\n\n";
            $f = fopen("$dir", "r");
            $tabla .= "<tr><th>#</th><th>Cédula</th><th>Nombre</th><th>Apellido</th><th>Correo Electrónico</th><th>Teléfono</th><th>Celular</th><th>Edad</th><th>Sexo</th><th>Fecha de Nacimiento</th></tr>\n\n";
            $line = fgetcsv($f);
            $i=1;
            
            $array_cedula = array();
            $array_username = array();
                    
            while (($line = fgetcsv($f)) !== false) {
                
            $tabla.= "<tr>";
            
            $cedula =htmlspecialchars($line[0]);
            $username =htmlspecialchars($line[3]);
            
            if(htmlspecialchars($line[1])=="" || htmlspecialchars($line[2])=="" || htmlspecialchars($line[6])=="" || htmlspecialchars($line[8])=="" || htmlspecialchars($line[3])==""){
                $rechazadosfaltadatos[]=$cedula;
                continue;
            }
            
            $usuario = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Persona')
                ->findByCedPer($cedula);
            
            $email = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:User')
                ->findByUsername($username);
            
            if($usuario){
                $rechazadoscedulas[]=$cedula;
                continue;
            }
            
            if(!in_array($cedula,$array_cedula))
            {
                array_push($array_cedula,$cedula);
            }else
            {
                $rechazadoporrepeticion[]=$cedula;
                 continue;
            }
            if(!in_array($username,$array_username))
            {
                array_push($array_username,$username);
            }else
            {
                $rechazadoporrepeticion[]=$username;
                 continue;
            }
            
            if (filter_var($cedula, FILTER_VALIDATE_INT, array("options" => array("min_range"=>100000, "max_range"=>100000000))) === false) {
                 $rechazadoscedulas[]=$cedula;
                 continue;
            }
            
            if($email){
                $rechazadoscorreo[]= $username;
                continue;
            }
            
            if (!filter_var($cedula, FILTER_VALIDATE_EMAIL) === false) {
                 $rechazadoscorreo[]=$username;
                 continue;
            }
            
            
            
            //Chequear Fecha
            if(validateDate($line[8], 'd/m/Y')){
            $test_arr  = explode('/', htmlspecialchars($line[8]));
         if (!checkdate($test_arr[1], $test_arr[0], $test_arr[2])) {
            $rezachodefechas[]=htmlspecialchars($line[8]);
            continue;
            }
            }
            else{
                $rezachodefechas[]=htmlspecialchars($line[8]);
                continue;
            }
            
            $tabla.="<td>$i</td>";
            foreach ($line as $cell) {
                $tabla.="<td>" . htmlspecialchars($cell) . "</td>";
            }
            $i++;
            $tabla.= "</tr>\n";
            
            if($i>50){
                return new Response("<h3> <img src='http://icons.iconarchive.com/icons/designcontest/ecommerce-business/48/alert-icon.png'/>ERROR: Número de Filas Mayor a 50¡¡¡¡¡¡</h3>");
            }
            
            }
            fclose($f);
            
            $tabla.= "\n</table>";
            
		return $this->render(
			'MisionSucreRipesBundle:Usuario:tablacarga.html.twig',
                        array('tabla'=>$tabla,'rechazadoscedulas'=>$rechazadoscedulas,
                            'rechazadoscorreo'=>$rechazadoscorreo,
                            'rechazadosfaltadatos'=>$rechazadosfaltadatos,
                            'rechazadosfecha' => $rezachodefechas,
                            'rechazadoporrepeticion'=>$rechazadoporrepeticion
                        )
		);	
	}

        
}

function validateDate($date, $format = 'Y-m-d H:i:s')
{
    $d = \DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}