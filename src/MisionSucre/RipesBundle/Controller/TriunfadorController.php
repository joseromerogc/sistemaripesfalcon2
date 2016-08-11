<?php

namespace MisionSucre\RipesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MisionSucre\RipesBundle\Entity\Triunfador;
use MisionSucre\RipesBundle\Entity\Persona;
use MisionSucre\RipesBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\HttpFoundation\Response;

class TriunfadorController extends Controller
{
    public function indexAction()
    {
        
        
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_COORD')) {
                
                if($this->get('security.authorization_checker')->isGranted('ROLE_TRF')){
                $id = $this->getUser()->getId();
                return $this->redirect($this->generateUrl('triunfador_info_academica'));
                }
                else
                    return $this->redirect($this->generateUrl('aldea'));
                }
                else{
                    return $this->render('MisionSucreRipesBundle:Triunfador:index.html.twig');
                }
    }
	
	public function newAction(Request $request,$idamb,$idtrf)
	{       
		$triunfador = new Triunfador();
                
                $em = $this->getDoctrine()->getManager();
                
                $ambiente = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Ambiente')
                ->find($idamb);
                
                 $infoambiente = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:PeriodoAcademicoAmbiente')
                ->findAllByAmbiente($idamb);
                
                $usertriunfador = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:User')
                ->find($idtrf);
                
                $usr = $this->getUser();
                
                $idaldea = $ambiente->getAldea()->getId();
                
                
                $aldea = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Aldea')
                ->find($idaldea);
                
                if(!$ambiente){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Ambiente con ID: '.$idambiente.' no registrado'
                );
                return $this->redirect($this->generateUrl('usuario_show',array('id'=>$usr->getId())));
                }
                
                if(!$usertriunfador){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Usuario con ID: '.$idtrf.' no registrado'
                );
                return $this->redirect($this->generateUrl('usuario_show',array('id'=>$usr->getId())));
                }
                
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
                
                if($usertriunfador->getTipUsr()!=6){

                $request->getSession()->getFlashBag()->add(
                'notice',
                'Usuario No es de Tipo Triunfador'
                );
                return $this->redirect($this->generateUrl('usuario_show',array('id'=>$usr->getId())));
                }
                
		$form = $this->createFormBuilder($triunfador)->
        add('condicion', 'choice', array(
        'choices' => array('Nuevo'=>'Nuevo','Activo'=>'Activo','Egresado'=>'Egresado','Retirado'=>'Retirado','Culminado'=>'Culminado' ),
            'placeholder'=>"Seleccione una",'label' => 'Condición del Triunfador'
            ))
            ->add('becamision', 'choice', array(
        'choices' => array('Si'=>'Si','No'=>'No'),'label' => 'Becado de la Misión','placeholder'=>'Seleccione'
            ))                     
            ->add('sistema', 'choice', array(
        'choices' => array('Si'=>'Si','No'=>'No'),'label' => 'Vinculado al Sistema','placeholder'=>'Seleccione'
            ))                     
            ->add('periodoingreso', 'text', array(
        'label' => 'Periodo de Ingreso al ambiente'
            ))                     
             ->add('save', 'submit',array('label' => 'Vincular Triunfador'))->getForm();
            
                $form->handleRequest($request);
                
                
		if ($form->isValid()) {
                            
                            $em = $this->getDoctrine()->getManager();    
                            
                            $triunfador->setUser($usertriunfador);    
                            $triunfador->setAmbiente($ambiente);    
                            $em->persist($triunfador);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Triunfador Creado con Éxito'
                            );            
                    return $this->redirect($this->generateUrl('ambiente_show',array('idamb'=>$idamb)));
                    
		}
		
                $per =  $em->getRepository('MisionSucreRipesBundle:Persona')->findOneByUser($usertriunfador->getId());
                
		return $this->render('MisionSucreRipesBundle:Triunfador:new.html.twig', array(
		'form' => $form->createView(),'mensaje_heading'=>'Nuevo Triunfador',
                    'sub_heading'=>'Aldea Universitaria <br>'.$aldea->getNombre(),
                    'ambiente'=>$infoambiente, 'usr'=>$usertriunfador,'per'=>$per
		));
	}
        
	public function updateAction(Request $request,$idtrf)
	{       
		
               
                $em = $this->getDoctrine()->getManager();
                $usr = $this->getUser();
               $triunfador = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Triunfador')
                ->find($idtrf);
                
                if(!$triunfador){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Triunfador con ID: '.$idtrf.' no registrado'
                );
                return $this->redirect($this->generateUrl('usuario_lista'));
                }
            
            $ambiente = $triunfador->getAmbiente();   
            
             $infoambiente = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:PeriodoAcademicoAmbiente')
                ->findAllByAmbiente($ambiente->getId());
                
             $aldea= $triunfador->getAmbiente()->getAldea();
             $idaldea =$aldea->getId();
                
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
                
                $usertriunfador = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:User')
                ->find($triunfador->getUser()->getId());
                
		$form = $this->createFormBuilder($triunfador)->
        add('condicion', 'choice', array(
        'choices' => array('Nuevo'=>'Nuevo','Activo'=>'Activo','Egresado'=>'Egresado','Retirado'=>'Retirado','Culminado'=>'Culminado' ),
            'placeholder'=>"Seleccione una",'label' => 'Condición del Triunfador'
            ))
            ->add('becamision', 'choice', array(
        'choices' => array('Si'=>'Si','No'=>'No'),'label' => 'Becado de la Misión','placeholder'=>'Seleccione'
            ))  ->add('sistema', 'choice', array(
        'choices' => array('Si'=>'Si','No'=>'No'),'label' => 'Vinculado al Sistema','placeholder'=>'Seleccione'
            ))          
             ->add('save', 'submit',array('label' => 'Actualizar Triunfador'))->getForm();
            
                $form->handleRequest($request);
                
                
		if ($form->isValid()) {
                            
                            $em = $this->getDoctrine()->getManager();    
                            
                            $triunfador->setUser($usertriunfador);    
                            $triunfador->setAmbiente($ambiente);    
                            $em->persist($triunfador);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Triunfador Actualizado con Éxito'
                            );            
                    return $this->redirect($this->generateUrl('ambiente_show',array('idamb'=>$ambiente->getId())));
                    
		}
		
                $per =  $em->getRepository('MisionSucreRipesBundle:Persona')->findOneByUser($usertriunfador->getId());
                
		return $this->render('MisionSucreRipesBundle:Triunfador:new.html.twig', array(
		'form' => $form->createView(),'mensaje_heading'=>'Actualización de Triunfador',
                    'sub_heading'=>'Aldea Universitaria <br>'.$aldea->getNombre(),
                    'ambiente'=>$infoambiente, 'usr'=>$usertriunfador,'per'=>$per
		));
	}
        
        public function deleteAction(Request $request,$idtrf)
	{
            $em = $this->getDoctrine()->getManager();
            
            $triunfador = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Triunfador')
                ->find($idtrf);
            $usertriunfador = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:User')
                ->find($triunfador->getUser()->getId());
            
            $ambiente = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Ambiente')
                ->find($triunfador->getAmbiente()->getId());
            
            if(!$triunfador){

                $request->getSession()->getFlashBag()->add(
                'notice',
                "Triunfador con ID $idtrf no Registrado"
                );
                return $this->redirect($this->generateUrl('ambiente_show',array('id'=>$ambiente->getId())));
                }
            
                $usr = $this->getUser();
                
                $idaldea = $ambiente->getAldea()->getId();
                
                $aldea = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Aldea')
                ->find($idaldea);
                
                if(!$ambiente){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Ambiente con ID: '.$idambiente.' no registrado'
                );
                return $this->redirect($this->generateUrl('usuario_show',array('id'=>$usr->getId())));
                }
                
                if(!$usertriunfador){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Usuario con ID: '.$idtrf.' no registrado'
                );
                return $this->redirect($this->generateUrl('usuario_show',array('id'=>$usr->getId())));
                }
                
                if($usertriunfador->getTipUsr()!=6){

                $request->getSession()->getFlashBag()->add(
                'notice',
                'Usuario No es de Tipo Triunfador'
                );
                return $this->redirect($this->generateUrl('usuario_show',array('id'=>$usr->getId())));
                }
                
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
                
                if(!$triunfador){

                $request->getSession()->getFlashBag()->add(
                'notice',
                "Triunfador con ID $idtrf no Registrado"
                );
                return $this->redirect($this->generateUrl('ambiente_show',array('idamb'=>$ambiente->getId())));
                }
                
                $vocero = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Vocero')
                ->findOneByUser($triunfador->getUser()->getId());
                
                if($vocero){
                    $em->remove($vocero);
                }
                            $em->remove($triunfador);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Triunfador Borrado con Éxito'
                            );

                    return $this->redirect($this->generateUrl('ambiente_show',array('idamb'=>$ambiente->getId())));
		}
        
        
    public function asignartriunfadorAction(Request $request,$idamb)
	{       
                $em = $this->getDoctrine()->getManager();
                
                $ambiente = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Ambiente')
                ->find($idamb);
                
                if(!$ambiente){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Ambiente con ID: '.$idaldea.' no registrado'
                );
                return $this->redirect($this->generateUrl('usuario_show',array('id'=>$usr->getId())));
                }
                
                 $infoambiente = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:PeriodoAcademicoAmbiente')
                ->findAllByAmbiente($idamb);
                 
                 if(!$infoambiente){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Ambiente no registrado en el Periodo Actual'
                );
                return $this->redirect($this->generateUrl('periodo_academico_new_ambiente',array('idamb'=>$ambiente->getId())));
                }
                
                $idaldea = $ambiente->getAldea()->getId();
                
                $aldea = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Aldea')
                ->find($idaldea);
                
                $usr = $this->getUser();
                
                if($usr->getTipUsr()==5){
                $caldea = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:CoordinadorAldea')
                ->findCoordinadorAldeaByUserAndId($idaldea,$usr->getId());
                
                if(!$caldea){

                $request->getSession()->getFlashBag()->add(
                'notice',
                'Usted no es Coordinador de Esta Aldea'
                );
                return $this->redirect($this->generateUrl('usuario_show',array('id'=>$usr->getId())));
                }
                }
                
                $triunfadores = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Triunfador')
                ->findAllOrderedByAmbiente($idamb);
                
		return $this->render(
			'MisionSucreRipesBundle:Triunfador:buscarasignar.html.twig',
                        array('aldea'=>$aldea,'ambiente'=>$infoambiente,'triunfadores'=>$triunfadores)
		);	
	}
                    //busqueda para vincular a ambiente
        public function busquedatriunfadorAction($param)
	{       
            
                $parametros=json_decode($param,true);
                
                $query=$parametros['query'];
                
                $cedulas= preg_split("/[\s,]+/",$query['cedulas']);
             
                $em = $this->getDoctrine()->getManager();
                
                $noregistrados = array();
                $noperfil = array(); //recoge los perfiles invalidos
                $vinculados=  array();
                
                //las cedulas que no estan registradas
                foreach ($cedulas as $c) {
                    
                    $per = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Persona')
                ->findOneByCedPer($c); 
                    
                    if($per){
                       switch($per->getUser()->getTipUsr()){
                             
                             case 5: 
                             case 8:
                                   $noperfil[]=array('ced'=>$c, 'msg'=>'Perfil Inválido') ;
                             break;
                             case 7:
                                    $noperfil[]=array('ced'=>$c, 'msg'=>'Es un Docente') ;
                             break;
                             case 9:
                                   $noperfil[]=array('ced'=>$c, 'msg'=>'Es un Operario') ;
                             break;        
                             case 6:
                                 $trf = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Triunfador')
                ->findOneByUser($per->getUser()->getId());
                                   if($trf)
                                   {
                                       $valorturno = $trf->getAmbiente()->getTurno();
                                       
                                       switch ($valorturno){
                                           
                                           case 'f': 
                                               $turno="Fines de Semana";
                                               break;
                                           case 'n':
                                               $turno="Nocturno";
                                               break;
                                       }
                                               
                                      $vinculados[] = array ('ced'=>$c,'pnf'=>$trf->getAmbiente()->getPnf()->getNombre()."/".$turno
                                          ,'aldea'=>$trf->getAmbiente()->getAldea()->getNombre());
                                   }
                             break;        
                    }
                    }
                    else{
                         $noregistrados[]=$c;
                    }
                }
                
                $result = $em->createQuery(
                        "SELECT DISTINCT u.tip_usr, p.priNom, p.segNom, p.priApe, p.segApe, p.cedPer, p.sexPer,u.id,
                            u.username
                            FROM MisionSucreRipesBundle:Persona p JOIN p.user u
                        WHERE p.cedPer IN (:cedulas) AND u.tip_usr = 6 AND NOT EXISTS
                   ( SELECT t FROM MisionSucreRipesBundle:Triunfador t WHERE u.id=t.user) ORDER BY p.cedPer" 
                        )->setParameters(array('cedulas'=>$cedulas))->getResult();
                
		return $this->render(
			'MisionSucreRipesBundle:Triunfador:listaasignar.html.twig',
                        array('filas'=>$result,'noregistrados'=>$noregistrados,'noperfil'=>$noperfil,'vinculados'=>$vinculados
                        ,'ambiente'=>$query['ambiente']
                        )
		);	
	}
        public function busquedatriunfadorvincularAction($param)
	{       
            
                $parametros=json_decode($param,true);
                
                $query=$parametros['query'];
                
                $em = $this->getDoctrine()->getManager();
                
                $result = $em->createQuery(
                        "SELECT DISTINCT u.tip_usr, p.priNom, p.segNom, p.priApe, p.segApe, p.cedPer, p.sexPer,u.id,
                            u.username
                            FROM MisionSucreRipesBundle:Persona p JOIN p.user u
                        WHERE p.cedPer LIKE :ced AND p.priApe LIKE :ape AND u.tip_usr = 6 AND NOT EXISTS
                   ( SELECT t FROM MisionSucreRipesBundle:Triunfador t WHERE u.id=t.user)" 
                        )->setParameters(array('ced'=>"%{$query['cedula']}%",'ape'=>"%{$query['apellido']}%"))->getResult();
                
		return $this->render(
			'MisionSucreRipesBundle:Triunfador:listavincular.html.twig',
                        array('filas'=>$result,'query'=>$query)
		);	
	}
        
        public function buscarAction(Request $request)
	{       
                $em = $this->getDoctrine()->getManager();
                
                if($this->getUser()->getTipUsr()==5){
                    $id = $this->getUser()->getId();
                $coordinador = $em->getRepository('MisionSucreRipesBundle:CoordinadorAldea')->findOneByUser($id);
                        if(!$coordinador){
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Coordinador No Vinculado a una Aldea'
                            );            
                            return $this->redirect($this->generateUrl('aldea_coordinador_info'));
                        }
                $aldea = $coordinador->getAldea()->getId();
                }
                else
                    $aldea =null;
                
		return $this->render(
			'MisionSucreRipesBundle:Triunfador:buscar.html.twig',
                        array('aldea'=>$aldea,'municipios'=>$this->MunicipiosEje())
		);	
	}
        
        public function showAction(Request $request,$id){
            
                $em = $this->getDoctrine()->getManager();
                
                $per =  $em->getRepository('MisionSucreRipesBundle:Persona')->findOneByUser($id);
                
                //SI NO SE HA REGISTRADO SUS DATOS PERSONALES SE REDIRECCIONA
                if(!$per){
                    
                    $request->getSession()->getFlashBag()->add(
                    'notice',
                    'Datos Personales no registrados. Por Favor Completar los Datos'
                    );    
                    return $this->redirect($this->generateUrl('persona_new',array('id'=>$id)));
                }
            
                $usr = $this->getUser();
                
                $triunfador = $em->getRepository('MisionSucreRipesBundle:Triunfador')->findOneByUser($id);
                
                
                if($triunfador){
                
                $ideje = $triunfador->getAmbiente()->getAldea()->getParroquia()->getEje()->getId();
                $aldea = $triunfador->getAmbiente()->getAldea();
                
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
                        
                        if(!$coordaldea->getAldea()->getId()==$aldea->getId())
                        {
                        $request->getSession()->getFlashBag()->add(
                        'notice',
                        'Acceso Denegado a esta Aldea'
                        );
                        return $this->redirect($this->generateUrl('usuario_lista'));     
                        }
                        break;
                }}
            
                $usuario =  $em->getRepository('MisionSucreRipesBundle:Role')->findOneByRole($id);    
                $user =  $em->getRepository('MisionSucreRipesBundle:User')->findOneById($id); 
                $ubicacionvivienda = $em->getRepository('MisionSucreRipesBundle:UbicacionVivienda')->findOneByUser($id); 
                $sociales =  $em->getRepository('MisionSucreRipesBundle:Social')->findOneByUser($id); 
                $enfermedades =  $user->getEnfermedades()->getValues();
                $discapacidad =  $em->getRepository('MisionSucreRipesBundle:Discapacidad')->findOneByUser($id);       
                $academico =  $em->getRepository('MisionSucreRipesBundle:Academico')->findOneByUser($id);
                $arte =  $em->getRepository('MisionSucreRipesBundle:Arte')->findOneByUser($id);       
                $deporte =  $em->getRepository('MisionSucreRipesBundle:Deporte')->findOneByUser($id);       
                $trabajo =  $em->getRepository('MisionSucreRipesBundle:Trabajo')->findOneByUser($id);       
                $comunitaria =  $em->getRepository('MisionSucreRipesBundle:ParticipacionComunitaria')->findOneByUser($id);       
                $politica =  $em->getRepository('MisionSucreRipesBundle:ParticipacionPolitica')->findOneByUser($id);    
                
                return $this->render(
			'MisionSucreRipesBundle:Triunfador:show.html.twig',
			array('usuario' => $usuario,'persona' => $per,'sociales'=> $sociales,'enfermedades'=>$enfermedades,
                        'discapacidad'=>$discapacidad,'arte'=>$arte,'deporte'=>$deporte,'trabajo'=>$trabajo,
                            'comunitaria'=>$comunitaria,'politica'=>$politica,'academico'=>$academico,'triunfador'=>$triunfador,
                            'ubicacionvivienda'=>$ubicacionvivienda
                        )
		);
        }        
        
        public function infoAction(Request $request){
            
                $usr = $this->getUser();
                
                $id = $usr->getId();
                        
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
                
                $triunfador = $em->getRepository('MisionSucreRipesBundle:Triunfador')->findOneByUser($id);
                
                
                if($triunfador){
                
                $ideje = $triunfador->getAmbiente()->getAldea()->getParroquia()->getEje()->getId();
                $aldea = $triunfador->getAmbiente()->getAldea();
                
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
                        
                        if(!$coordeje->getEje()->id()==$ideje)
                        {
                        $request->getSession()->getFlashBag()->add(
                        'notice',
                        'Acceso Denegado a este Eje'
                        );
                        return $this->redirect($this->generateUrl('usuario_lista'));     
                        }
                        
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
                        
                        if(!$coordaldea->getAldea()->getId()==$aldea->getId())
                        {
                        $request->getSession()->getFlashBag()->add(
                        'notice',
                        'Acceso Denegado a esta Aldea'
                        );
                        return $this->redirect($this->generateUrl('usuario_lista'));     
                        }
                        break;
                }}
            
                $usuario =  $em->getRepository('MisionSucreRipesBundle:Role')->findOneByRole($id);    
                $user =  $em->getRepository('MisionSucreRipesBundle:User')->findOneById($id);    
                $sociales =  $em->getRepository('MisionSucreRipesBundle:Social')->findOneByUser($id); 
                $ubicacionvivienda = $em->getRepository('MisionSucreRipesBundle:UbicacionVivienda')->findOneByUser($id); 
                $enfermedades =  $user->getEnfermedades()->getValues();
                $discapacidad =  $em->getRepository('MisionSucreRipesBundle:Discapacidad')->findOneByUser($id);       
                $academico =  $em->getRepository('MisionSucreRipesBundle:Academico')->findOneByUser($id);
                $arte =  $em->getRepository('MisionSucreRipesBundle:Arte')->findOneByUser($id);       
                $deporte =  $em->getRepository('MisionSucreRipesBundle:Deporte')->findOneByUser($id);       
                $trabajo =  $em->getRepository('MisionSucreRipesBundle:Trabajo')->findOneByUser($id);       
                $comunitaria =  $em->getRepository('MisionSucreRipesBundle:ParticipacionComunitaria')->findOneByUser($id);       
                $politica =  $em->getRepository('MisionSucreRipesBundle:ParticipacionPolitica')->findOneByUser($id);    
                
                return $this->render(
			'MisionSucreRipesBundle:Triunfador:show.html.twig',
			array('usuario' => $usuario,'persona' => $per,'sociales'=> $sociales,'enfermedades'=>$enfermedades,
                        'discapacidad'=>$discapacidad,'arte'=>$arte,'deporte'=>$deporte,'trabajo'=>$trabajo,
                            'comunitaria'=>$comunitaria,'politica'=>$politica,'academico'=>$academico,'triunfador'=>$triunfador, 
                            'ubicacionvivienda' => $ubicacionvivienda
                        )
		);
        }        
        
        /*
         * INFORMACIÓN ACADÉMICA MISIÓN SUCRE
         */
        public function infoacademicaAction(Request $request){
            
                $usr = $this->getUser();
                
                $id = $usr->getId();
                        
                $em = $this->getDoctrine()->getManager();
                
                
                $usr = $this->getUser();
                
                $triunfador = $em->getRepository('MisionSucreRipesBundle:Triunfador')->findOneByUser($id);
                
                if($triunfador){
                
                $ideje = $triunfador->getAmbiente()->getAldea()->getParroquia()->getEje()->getId();
                $aldea = $triunfador->getAmbiente()->getAldea();
                
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
                        
                        if(!$coordeje->getEje()->id()==$ideje)
                        {
                        $request->getSession()->getFlashBag()->add(
                        'notice',
                        'Acceso Denegado a este Eje'
                        );
                        return $this->redirect($this->generateUrl('usuario_lista'));     
                        }
                        
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
                        
                        if(!$coordaldea->getAldea()->getId()==$aldea->getId())
                        {
                        $request->getSession()->getFlashBag()->add(
                        'notice',
                        'Acceso Denegado a esta Aldea'
                        );
                        return $this->redirect($this->generateUrl('usuario_lista'));     
                        }
                        break;
                }}
            
                
                return $this->render(
			'MisionSucreRipesBundle:Triunfador:showacademica.html.twig',
			array('triunfador'=>$triunfador
                        )
		);
        }        
        
        /*
         * ASIGNAR AMBIENTE A UN TRIUNFADOR
         */
        
        public function asignarambienteAction(Request $request,$idtrf)
	{       
                $em = $this->getDoctrine()->getManager();
                
                $usertriunfador = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:User')
                ->find($idtrf);
                
                 if(!$usertriunfador){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Usuario con ID: '.$idtrf.' no registrado'
                );
                return $this->redirect($this->generateUrl('usuario_show',array('id'=>$usr->getId())));
                }
                
                $datospersonales = $em->getRepository('MisionSucreRipesBundle:Persona')->findOneByUser($idtrf);
                
                 if(!$datospersonales){
                    
                    $request->getSession()->getFlashBag()->add(
                    'notice',
                    'Datos Personales no registrados. Por Favor Completar los Datos'
                    );    
                    return $this->redirect($this->generateUrl('persona_new',array('id'=>$idtrf)));
                }
                
                 if($usertriunfador->getTipUsr()!=6){

                $request->getSession()->getFlashBag()->add(
                'notice',
                'Usuario No es de Tipo Triunfador'
                );
                return $this->redirect($this->generateUrl('usuario_show',array('id'=>$usr->getId())));
                }
                
                $usr = $this->getUser();
              
                $aldea =null;
                   
                 if($this->getUser()->getTipUsr()==5){
                $coordinador = $em->getRepository('MisionSucreRipesBundle:CoordinadorAldea')->findOneByUser($usr->getId());
                        if(!$coordinador){
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Coordinador No Vinculado a una Aldea'
                            );            
                            return $this->redirect($this->generateUrl('aldea_coordinador_info'));
                        }
                $aldea = $coordinador->getAldea()->getId();
                }
    
		return $this->render(
			'MisionSucreRipesBundle:Triunfador:buscarasignarambiente.html.twig',
                        array('datos'=>$datospersonales,'aldea'=>$aldea,'municipios'=>$this->MunicipiosEje())
		);
                
        }  
        
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
    
    public function busquedaambienteAction(Request $request)
	{       
                 if($request->request->get('param'))
                {   $param=$request->request->get('param');
                    $parametros=json_decode($param,true);}
                
                $query=$parametros['query'];
                
                $em = $this->getDoctrine()->getManager();
                $user = $this->getUser();
                
                if($this->getUser()->getTipUsr()==8){
                
                $usreje = $em->getRepository('MisionSucreRipesBundle:CoordinadorEje')->findOneByUser($user->getId());
                
                if(!$usreje){
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Coordinador de Eje no Vinculado'
                            );            
                            return $this->redirect($this->generateUrl('eje_info'));
                        }
                
               $eje = $usreje->getEje()->getId();
               
                }
                else
                    $eje = "%%";
                
                if($query['pnf']=="")
                    $query['pnf']="%%";
                if($query['periodoacademico']=="")
                    $query['periodoacademico']="%%";
                 if($query['municipio']=="")
                    $query['municipio']="%%";
                 if($query['parroquia']=="")
                    $query['parroquia']="%%";
                
                if($this->getUser()->getTipUsr()==5){
                    $id = $this->getUser()->getId();
                $caldea = $em->getRepository('MisionSucreRipesBundle:CoordinadorAldea')->findOneByUser($id);
                
                $turnoscoordinador=$em->getRepository('MisionSucreRipesBundle:CoordinadorTurno')->findAllByCoordinador($caldea->getId());
                            
                $aldea = $caldea->getAldea()->getId();
                $cond = "5=5";
                $idcoordinador=$caldea->getId();
                }
                else{
                    $aldea = $query['aldea'];
                    $idcoordinador = "%%";
                    $municipio = $query['municipio'];
                    $parroquia = $query['parroquia'];
                    
                   $cond= "prq.id LIKE '$parroquia' AND m.id LIKE '$municipio' AND e.id LIKE '$eje' ";
                  
                }
                
                if($query['nombre']!='')
                    $condnombre = 'am.nombre LIKE :nombre';
                else
                    $condnombre = '(am.nombre IS NULL OR am.nombre LIKE :nombre)';
                  
                $ambientes = $em->createQuery(
                        "SELECT am, pa
                            FROM MisionSucreRipesBundle:PeriodoAcademicoAmbiente pa JOIN pa.ambiente am JOIN am.aldea a JOIN a.parroquia prq JOIN prq.eje e,
                            MisionSucreRipesBundle:Pnf pnf, MisionSucreRipesBundle:PeriodoAcademico p, MisionSucreRipesBundle:Municipio m,
                            MisionSucreRipesBundle:CoordinadorTurno t JOIN t.coordinador c
                            WHERE $condnombre 
                               AND a.id LIKE :aldea AND a.parroquia=prq.id AND $cond AND
                               am.turno LIKE :turno AND pnf.id LIKE :pnf AND pnf.modalidad LIKE :modalidad
                            AND pa.trayecto LIKE :trayecto AND pa.periodo LIKE :periodo AND p.id LIKE :periodoacademico
                            AND am.condicion LIKE :condicion AND am.pnf = pnf.id  AND pa.periodoacademico = p.id AND m.id = prq.municipio
                            AND c.id LIKE :idcoordinador AND c.aldea = a.id AND t.turno=am.turno
                            
                        " 
                        )->setParameters(array('aldea'=>"$aldea",'nombre'=>"%{$query['nombre']}%"
                        ,'turno'=>"%{$query['turno']}%",'pnf'=>"{$query['pnf']}",'modalidad'=>"%{$query['modalidad']}%",
                            'trayecto'=>"%{$query['trayecto']}%",'periodo'=>"%{$query['periodo']}%"
                                ,'condicion'=>"%{$query['condicion']}%",'periodoacademico'=>"{$query['periodoacademico']}",'idcoordinador'=>$idcoordinador
                        ))
                        ->getResult();
                
                
		return $this->render(
			'MisionSucreRipesBundle:Triunfador:busquedaasignarambiente.html.twig',
                        array('ambientes'=>$ambientes,'idtrf'=>$query['idtrf'])
		);	
	}
    
    public function vinculartriunfadorAction(Request $request)
	{       
                $em = $this->getDoctrine()->getManager();
                
		return $this->render(
			'MisionSucreRipesBundle:Triunfador:buscarvincular.html.twig'
		);	
	}
        
        //vincular triunfadores seleccionados
    public function vinculartriunfadoresAction(Request $request,$json)
	{       
                $em = $this->getDoctrine()->getManager();
                
                
                $datos=json_decode($json,true);
                
                $error= "";
                
                foreach($datos[0] as $i=>$c) {
                    
               $per = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Persona')
                ->findOneByCedPer($c); 
                
               if($per){
                $trf = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Triunfador')
                ->findOneByUser($per->getUser()->getId());
                                   if($trf){
                                       $error.= "$c Triunfador ya Vinculado / ";
                                   }
                else {
                        try{
                        $t = new Triunfador();
                        $t->setUser($per->getUser());
                        $amb=$this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Ambiente')
                ->find($datos[3]);
                        $t->setAmbiente($amb);
                        $t->setCondicion("Activo");
                        $t->setBecamision($datos[2][$i]);
                        $t->setSistema($datos[1][$i]);
                        
                        $em->persist($t);
                        }
                        catch (\Doctrine\ORM\EntityNotFoundException $ex) {
                            $error.="Problemas a Registrar a $c  / ";
                        }
                }
                }
                else{
                    $error.="$c No Registrada";
                }
                    
                }
                $em->flush();
               
                if($error){
                return new Response("Se han Encontrado los siguientes Errores:<br/>:".$error);
                }
                else
                {
                    return new Response("good"); //si se ha registrado
                }
                }
                
                //lista de triunfadores
    public function listaAction(Request $request)
	{   
    
            $triunfadores = $this->TriunfadoresAldeas();
                
		if(!$triunfadores){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Ningun Triunfador Registrado'
            );
            return $this->redirect($this->generateUrl('Triunfador'));
            }
            
		return $this->render(
			'MisionSucreRipesBundle:Triunfador:lista.html.twig',
                array('triunfadores' => $triunfadores)
		);	
}
    public function TriunfadoresAldeas()
    {   
        $em = $this->getDoctrine()->getManager();
        
        $triunfadores= array();
        
        $user = $this->getUser();
        
        switch($user->getTipUsr()){
        
        case 5:
            $coordinador = $em->getRepository('MisionSucreRipesBundle:CoordinadorAldea')->findOneByUser($user->getId());
                        if(!$coordinador){
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Coordinador No Vinculado a una Aldea'
                            );            
                            return $this->redirect($this->generateUrl('aldea_coordinador_info'));
                        }
            $triunfadores = $em->getRepository('MisionSucreRipesBundle:CoordinadorTurno')->TriunfadoresCoordinador($coordinador->getAldea()->getId());
            break;
        case 8:    
        
        $usreje = $em->getRepository('MisionSucreRipesBundle:CoordinadorEje')->findOneByUser($user->getId());
        $triunfadores = $em->getRepository('MisionSucreRipesBundle:Triunfador')->findAllOrderedByEje($usreje->getEje()->getId());
        break;
        case 1:
        $triunfadores = $em->getRepository('MisionSucreRipesBundle:Triunfador')->findAllOrderedByUser();
        break;
        }
        return $triunfadores;
    }

public function busquedaAction(Request $request)
	{       
            
              //$param='{"query":{"aldea":"1","municipio":"%%","parroquia":"%%","nombre":"","turno":"","modalidad":"","pnf":"","trayecto":"","periodo":"","periodoacademico":"","condicion":"","sistema":"","becado":"","condicion_triunfador":"","nombrepersona":"","cedula":"","apellido":"","sexo":"","edad":""}}';
              
             // $parametros=json_decode($param,true);
                
                if($request->request->get('param'))
                {   $param=$request->request->get('param');
                    $parametros=json_decode($param,true);}
                
                $query=$parametros['query'];
                
                $em = $this->getDoctrine()->getManager();
                
                if($query['pnf']=="")
                    $query['pnf']="%%"; 
                if($query['aldea']=="")
                    $query['aldea']="%%"; 
                if($query['periodoacademico']=="")
                    $query['periodoacademico']="%%";
                 if($query['municipio']=="")
                    $query['municipio']="%%";
                 if($query['parroquia']=="")
                    $query['parroquia']="%%";
                
                if($this->getUser()->getTipUsr()==8){
                $user = $this->getUser();
                $usreje = $em->getRepository('MisionSucreRipesBundle:CoordinadorEje')->findOneByUser($user->getId());
                
                if(!$usreje){
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Coordinador de Eje no Vinculado'
                            );            
                            return $this->redirect($this->generateUrl('eje_info'));
                        }
                
                $eje = $usreje->getEje()->getId();
                }
                else
                    $eje = "%%";
                
                if($this->getUser()->getTipUsr()==5){
                    $id = $this->getUser()->getId();
                $caldea = $em->getRepository('MisionSucreRipesBundle:CoordinadorAldea')->findOneByUser($id);
                
                $turnoscoordinador=$em->getRepository('MisionSucreRipesBundle:CoordinadorTurno')->findAllByCoordinador($caldea->getId());
                            
                $aldea = $caldea->getAldea()->getId();
                $cond = "5=5"; //para validar query
                $idcoordinador=$caldea->getId();
                }
                else{
                    $aldea = $query['aldea'];
                    $idcoordinador = "%%";
                    $municipio = $query['municipio'];
                    $parroquia = $query['parroquia'];
                    
                   $cond= "prq.id LIKE '$parroquia' AND m.id LIKE '$municipio' AND e.id LIKE '$eje' ";  
                }
                
                if($query['nombre']!='')
                    $condnombre = 'am.nombre LIKE :nombre';
                else
                    $condnombre = '(am.nombre IS NULL OR am.nombre LIKE :nombre)';
                
                
                    
                $triunfadores = $em->createQuery(
                        "SELECT usr.id,usr.username,p.priNom,p.segNom, p.priApe, p.segApe, p.cedPer, p.sexPer
                    ,p.celPer,p.telPer, trf, pa.trayecto, pa.periodo
                            FROM MisionSucreRipesBundle:Triunfador trf, MisionSucreRipesBundle:Persona p JOIN p.user usr,
                            MisionSucreRipesBundle:PeriodoAcademicoAmbiente pa JOIN pa.ambiente am JOIN am.aldea a JOIN a.parroquia prq JOIN prq.eje e,
                            MisionSucreRipesBundle:Pnf pnf, MisionSucreRipesBundle:Municipio m,
                            MisionSucreRipesBundle:CoordinadorTurno t JOIN t.coordinador c
                            WHERE $condnombre
                               AND a.id LIKE :aldea AND prq.id = a.parroquia AND m.id = prq.municipio AND $cond AND
                               am.turno LIKE :turno AND pnf.id LIKE :pnf AND pnf.modalidad LIKE :modalidad
                            AND pa.trayecto LIKE :trayecto AND pa.periodo LIKE :periodo AND IDENTITY(pa.periodoacademico) LIKE :periodoacademico
                            AND am.condicion LIKE :condicion AND e.id LIKE :eje AND am.pnf = pnf.id  AND m.id = prq.municipio
                            AND trf.user = p.user AND usr.id = p.user AND trf.user = usr.id AND am.id=trf.ambiente AND
                            trf.sistema LIKE :sistema AND trf.becamision LIKE :becado AND trf.condicion LIKE :condicion_triunfador AND
                            p.priNom LIKE :nombrepersona AND p.priApe LIKE :apellido AND p.cedPer LIKE :cedula AND p.sexPer LIKE :sexo AND p.edadPer LIKE :edad 
                            AND c.id LIKE :idcoordinador AND c.aldea = a.id AND t.turno=am.turno
                        " 
                        )->setParameters(array('aldea'=>"$aldea",'nombre'=>"%{$query['nombre']}%"
                        ,'turno'=>"%{$query['turno']}%",'pnf'=>"{$query['pnf']}",'modalidad'=>"%{$query['modalidad']}%",
                            'trayecto'=>"%{$query['trayecto']}%",'periodo'=>"%{$query['periodo']}%"
                                ,'condicion'=>"%{$query['condicion']}%",'periodoacademico'=>"{$query['periodoacademico']}",'eje'=>"%{$eje}%",
                                        'nombrepersona'=>"%{$query['nombrepersona']}%",'apellido'=>"%{$query['apellido']}%",'cedula'=>"%{$query['cedula']}%"
                                            ,'sexo'=>"%{$query['sexo']}%",'edad'=>"%{$query['edad']}%",'idcoordinador'=>$idcoordinador,
                                       'sistema'=>"%{$query['sistema']}%",'becado'=>"%{$query['becado']}%",'condicion_triunfador'=>"%{$query['condicion_triunfador']}%"
                        ))
                        ->getResult();
                
                
		return $this->render(
			'MisionSucreRipesBundle:Triunfador:busqueda.html.twig',
                array('triunfadores' => $triunfadores)
		);	
	}
   
    public function resumenAction(Request $request)
	{       
                $em = $this->getDoctrine()->getManager();
                
                $usr = $this->getUser();
                
                switch($usr->getTipUsr()){
                    
                    case 1:
                        $cantidadporaldeas = $em->createQuery(
                        "SELECT a.nombre, COUNT (t) as cantidadtriunfadores ,SUM( CASE WHEN t.sistema ='No' THEN 1 ELSE 0 END) AS cantidadnovinculados FROM MisionSucreRipesBundle:Triunfador t JOIN t.ambiente am JOIN am.aldea a
                            WHERE am.condicion !=:egresado
                            AND am.condicion !=:culminado
                            GROUP BY a.id
                        "
                        )->setParameters(array('egresado'=>'Egresado','culminado'=>'Culminado')
                        )->getResult();
                        
                        $cantidadporeje = $em->createQuery(
                        "SELECT e.nombre, COUNT (t) as cantidadtriunfadores ,SUM( CASE WHEN t.sistema ='No' THEN 1 ELSE 0 END) AS cantidadnovinculados FROM MisionSucreRipesBundle:Triunfador t JOIN t.ambiente am JOIN am.aldea a
                            JOIN a.parroquia prq JOIN prq.eje e 
                            WHERE am.condicion !=:egresado
                            AND am.condicion !=:culminado
                            GROUP BY e.id
                        "
                        )->setParameters(array('egresado'=>'Egresado','culminado'=>'Culminado')
                        )->getResult();
                        
                        
                        $cantidadpormunicipio = $em->createQuery(
                        "SELECT m.municipio, COUNT (t) as cantidadtriunfadores ,SUM( CASE WHEN t.sistema ='No' THEN 1 ELSE 0 END) AS cantidadnovinculados FROM MisionSucreRipesBundle:Triunfador t JOIN t.ambiente am JOIN am.aldea a
                            JOIN a.parroquia prq JOIN prq.municipio m
                            WHERE am.condicion !=:egresado
                            AND am.condicion !=:culminado
                            GROUP BY m.id
                        "
                        )->setParameters(array('egresado'=>'Egresado','culminado'=>'Culminado')
                        )->getResult();
                        
                    return $this->render(
			'MisionSucreRipesBundle:Triunfador:resumeneje.html.twig',
                        array(
                            'cxeje'=>$cantidadporeje,
                            'cxaldeas'=>$cantidadporaldeas,
                            'cxmunicipios'=>$cantidadpormunicipio
                        ));
                    break;
                
                    case 8:
                        
                        $usreje = $em->getRepository('MisionSucreRipesBundle:CoordinadorEje')->findOneByUser($usr->getId());
                        $eje = $usreje->getEje()->getId();
                        
                        $cantidadporaldeas = $em->createQuery(
                        "SELECT a.nombre, COUNT (t) as cantidadtriunfadores ,SUM( CASE WHEN t.sistema ='No' THEN 1 ELSE 0 END) AS cantidadnovinculados FROM MisionSucreRipesBundle:Triunfador t JOIN t.ambiente am JOIN am.aldea a
                            JOIN a.parroquia prq
                            WHERE prq.eje =:eje
                            AND am.condicion !=:egresado
                            AND am.condicion !=:culminado
                            GROUP BY a.id
                        "
                        )->setParameters(array('eje'=>$eje,'egresado'=>'Egresado','culminado'=>'Culminado'))
                                ->getResult();
                        
                        $cantidadpormunicipio = $em->createQuery(
                        "SELECT m.municipio, COUNT (t) as cantidadtriunfadores ,SUM( CASE WHEN t.sistema ='No' THEN 1 ELSE 0 END) AS cantidadnovinculados FROM MisionSucreRipesBundle:Triunfador t JOIN t.ambiente am JOIN am.aldea a
                            JOIN a.parroquia prq JOIN prq.municipio m
                            WHERE prq.eje =:eje
                            AND am.condicion !=:egresado
                            AND am.condicion !=:culminado
                            GROUP BY m.id
                        "
                        )->setParameters(array('eje'=>$eje,'egresado'=>'Egresado','culminado'=>'Culminado'))
                        ->getResult();
                        
                    return $this->render(
			'MisionSucreRipesBundle:Triunfador:resumenmunicipio.html.twig',
                        array(
                            'cxaldeas'=>$cantidadporaldeas,
                            'cxmunicipios'=>$cantidadpormunicipio
                        ));
                    break;
                    
                    case 5:
                        
                        $coord = $em->getRepository('MisionSucreRipesBundle:CoordinadorAldea')->findOneByUser($usr->getId());
                        $aldea = $coord->getAldea()->getId();
                        
                        $cantidadporpnf = $em->createQuery(
                        "SELECT pnf.nombre, COUNT (t) as cantidadtriunfadores ,SUM( CASE WHEN t.sistema ='No' THEN 1 ELSE 0 END) AS cantidadnovinculados FROM MisionSucreRipesBundle:Triunfador t JOIN t.ambiente am JOIN am.aldea a,
                            MisionSucreRipesBundle:Pnf pnf
                            WHERE am.aldea =:aldea AND pnf.id = am.pnf
                            AND am.condicion !=:egresado
                            AND am.condicion !=:culminado
                            GROUP BY pnf.id
                        "
                        )->setParameters(array('aldea'=>$aldea,'egresado'=>'Egresado','culminado'=>'Culminado'))
                                ->getResult();
                        
                    return $this->render(
			'MisionSucreRipesBundle:Triunfador:resumenaldea.html.twig',
                        array(
                            'cxpnf'=>$cantidadporpnf,
                        ));
                    break;
                
                }
        }
        
         public function cargarAction(Request $request)
	{       
                $em = $this->getDoctrine()->getManager();
                
                $file=array('file'=>"");
                
                $form = $this->createFormBuilder($file)
                        ->add('submitFile', 'file', array('label' => 'Cargar Formato'))
                        ->getForm()
                        ;
                $form->handleRequest($request);
                
                $tipodeusuario=array('Triunfadores',6);
                
                
		return $this->render(
			'MisionSucreRipesBundle:Usuario:cargar.html.twig',
                        array('form' => $form->createView(),'tipousuario'=>$tipodeusuario)
		);	
	}
       
}
