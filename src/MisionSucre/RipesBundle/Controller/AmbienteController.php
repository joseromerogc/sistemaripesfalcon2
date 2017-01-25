<?php

namespace MisionSucre\RipesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MisionSucre\RipesBundle\Entity\Ambiente;
use MisionSucre\RipesBundle\Form\Type\AmbienteType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;

class AmbienteController extends Controller
{
    public function indexAction()
    {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_COORD')) {
                
                if($this->get('security.authorization_checker')->isGranted('ROLE_TRF')){
                return $this->redirect($this->generateUrl('ambiente_info'));
                }
                else
                    return $this->redirect($this->generateUrl('ambiente_lista'));
                }
                else{
                    return $this->render('MisionSucreRipesBundle:Ambiente:index.html.twig');
                }
    }
	
	public function newAction(Request $request,$idaldea)
	{       
            /* Registra un Nuevo Ambiente
             * 
             */
		$ambiente = new Ambiente();
                
                $em = $this->getDoctrine()->getManager();
                
                $aldea = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Aldea')
                ->find($idaldea);
                
                $usr = $this->getUser();
                
                if(!$aldea){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Aldea con ID: '.$idaldea.' no registrada'
                );
                return $this->redirect($this->generateUrl('usuario_show',array('id'=>$usr->getId())));
                }
                
                /*VALIDAR */
                $validar = $this->get('servicios.validar');
                
                $error = $validar->ValidarAldea($aldea->getId(), $request);
                if($error)
                    return $this->redirect($this->generateUrl($error['url'],array($error['param']=>$error['valueparam'])));
                /*FIN VALIDAR*/
                
                $data = $this->get('servicios.ambiente');
                
                $turnos = $data->TurnosAldea($aldea->getId());
                
                $anexos = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:AnexoAldea')
                ->findByAldeaForm($idaldea);
                
		$form = $this->createForm(new AmbienteType(),$ambiente)->
        add('turno', 'choice', array(
        'choices' =>$turnos ,
            'placeholder'=>"Seleccione una",'label' => 'Turno'))
                        ->add('anexo', 'entity', array(
    'class' => 'MisionSucreRipesBundle:AnexoAldea',
    'choices' => $anexos, 'placeholder'=>"Seleccione una",'label' => 'Anexo','property' => 'nombre'))
                        ->add('save', 'submit',array('label' => 'Registrar Ambiente de Aldea'))
                        ;
            
                $form->handleRequest($request);
                
                
		if ($form->isValid()) {
                            
                            $em = $this->getDoctrine()->getManager();    
                            
                            
                            $idpnf = $form->get('idpnf')->getData();
                            $ingreso = $form->get('tingreso')->getData();
                            $egreso = $form->get('tegreso')->getData();
                            
                            $pnf = $em->getRepository('MisionSucreRipesBundle:Pnf')->find($idpnf);
                                
                            $ambiente->setAldea($aldea);    
                            $ambiente->setPnf($pnf);
                            $ambiente->setIngreso($ingreso);
                            $ambiente->setEgreso($egreso);
                            
                            $em->persist($ambiente);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Ambiente Creado con Éxito'
                            );            
                    return $this->redirect($this->generateUrl('ambiente_show',array('idamb'=>$ambiente->getId())));
		}
		
		return $this->render('MisionSucreRipesBundle:Ambiente:new.html.twig', array(
		'form' => $form->createView(),'mensaje_heading'=>'Nuevo Ambiente',
                    'sub_heading'=>'Aldea Universitaria <br>'.$aldea->getNombre()
		));
	}
        
        public function listanewAction(Request $request)
	{
            /*
             * Muestra una lista de aldea para asignar ambiente ROLE>EJE
             */
		$em = $this->getDoctrine()->getManager();
                
                $usr = $this->getUser();
                
                $id = $usr->getId();
                
                $aldeas = $this->AldeasEje();
                
		if(!$aldeas){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Ninguna Aldea Registrada'
            );
            return $this->redirect($this->generateUrl('aldea_new'));
            }
            
            return $this->render(
			'MisionSucreRipesBundle:Ambiente:listaaldeas.html.twig',
                array('aldeas' => $aldeas)
		);	

	}
        
    public function updateAction(Request $request,$idamb)
	{       
        /*
         * Actualizar Ambiente
         */
                $em = $this->getDoctrine()->getManager();
                
                $ambiente = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Ambiente')
                ->find($idamb);
                
                $idaldea = $ambiente->getAldea()->getId();
                
                $aldea = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Aldea')
                ->find($idaldea);
                
                if(!$ambiente){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Ambiente con ID: '.$idaldea.' no registrado'
                );
                return $this->redirect($this->generateUrl('aldea_show',array('id'=>$usr->getId())));
                }
                
                /*VALIDAR */
                $validar = $this->get('servicios.validar');
                
                $error = $validar->ValidarAmbiente($ambiente,$idaldea, $request);
                if($error)
                    return $this->redirect($this->generateUrl($error['url'],array($error['param']=>$error['valueparam'])));
                /*FIN VALIDAR*/
                                
                $anexos = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:AnexoAldea')
                ->findByAldeaForm($idaldea);
                
                $data = $this->get('servicios.ambiente');
                
                $turnos = $data->TurnosAldea($aldea->getId());
                
		$form = $this->createForm(new AmbienteType(),$ambiente)->
        add('turno', 'choice', array(
        'choices' => $turnos,
            'placeholder'=>"Seleccione una",'label' => 'Turno'))->add('anexo', 'entity', array(
    'class' => 'MisionSucreRipesBundle:AnexoAldea',
    'choices' => $anexos, 'placeholder'=>"Seleccione una",'label' => 'Anexo','property' => 'nombre'))
                        ->add('save', 'submit',array('label' => 'Actualizar Ambiente de Aldea'));
            
                $form->handleRequest($request);
                
		if ($form->isValid()) {
                            
                            $em = $this->getDoctrine()->getManager();    
                            $ingreso = $form->get('tingreso')->getData();
                            $egreso = $form->get('tegreso')->getData();
                            
                            $idpnf = $form->get('idpnf')->getData();
                            
                            $pnf = $em->getRepository('MisionSucreRipesBundle:Pnf')->find($idpnf);
                                
                            $ambiente->setAldea($aldea);    
                            $ambiente->setPnf($pnf);  
                            $ambiente->setIngreso($ingreso);
                            $ambiente->setEgreso($egreso);
                            $em->persist($ambiente);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Ambiente Actualizado con Éxito'
                            );            
                    return $this->redirect($this->generateUrl('aldea_show',array('id'=>$idaldea)));
		}
		
		return $this->render('MisionSucreRipesBundle:Ambiente:new.html.twig', array(
		'form' => $form->createView(),'mensaje_heading'=>'Actualizar Ambiente',
                    'sub_heading'=>'Aldea Universitaria <br>'.$aldea->getNombre(), 'idpnf'=>$ambiente->getPnf()->getId(),'pnf'=>$ambiente->getPnf()->getNombre(),
                    'modalidad  '=>$ambiente->getPnf()->getModalidad()
		));
	}
        
        public function showAction(Request $request,$idamb)
	{       
            /*Mostrar Información de Ambiente
             * 
             */
                $em = $this->getDoctrine()->getManager();
                
                $ambiente = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Ambiente')
                ->find($idamb);
                
                if(!$ambiente){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Ambiente con ID: '.$idamb.' no registrado'
                );
                return $this->redirect($this->generateUrl('ambiente'));
                }
                
                $idaldea = $ambiente->getAldea()->getId();
                
                /*VALIDAR */
                $validar = $this->get('servicios.validar');
                
                $error = $validar->ValidarAmbiente($ambiente,$idaldea, $request);
                if($error)
                    return $this->redirect($this->generateUrl($error['url'],array($error['param']=>$error['valueparam'])));
                /*FIN VALIDAR*/
                
                $aldea = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Aldea')
                ->find($idaldea);
               
                $periodosacademicos = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:PeriodoAcademicoAmbiente')
                ->findByAmbiente($idamb);
                
                $triunfadores = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Triunfador')
                ->findAllOrderedByAmbiente($idamb);
                
                $vocero = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Vocero')
                ->findAllOrderedByAmbiente($idamb);
                
                
                $modalidad=$ambiente->getPnf()->getModalidad();
                
                if($modalidad=="TI"){
                    $finalizados=$this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:TIFinalizado')
                ->findAllOrderedByAmbiente($idamb);
                }
                else{
                     $finalizados=NULL;
                }
		return $this->render(
			'MisionSucreRipesBundle:Ambiente:show.html.twig',
			array('aldea'=>$aldea,'ambiente'=>$ambiente,'triunfadores'=>$triunfadores,
                            'vocero'=>$vocero, 'periodosacademicos' =>$periodosacademicos,'modalidad'=>$modalidad,"finalizados"=>$finalizados
                        ));
	}
        
        public function infoAction(Request $request)
	{       
            /*
             * Muestra la Información de ambiente de un triunfador
             */
                $usr = $this->getUser();
                
                $triunfador = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Triunfador')
                ->findOneByUser($usr->getId());
                
                /*VALIDAR */
                $validar = $this->get('servicios.validar');
                
                $error = $validar->ValidarTriunfador($usr->getId(),$request);
                if($error)
                    return $this->redirect($this->generateUrl($error['url'],array($error['param']=>$error['valueparam'])));
                /*FIN VALIDAR*/
                
                $em = $this->getDoctrine()->getManager();
                
                $ambiente = $triunfador->getAmbiente();
                
                $idamb = $ambiente->getId();
                
                $idaldea = $ambiente->getAldea()->getId();
                
                $aldea = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Aldea')
                ->find($idaldea);
                
                if(!$ambiente){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Ambiente con ID: '.$idaldea.' no registrado'
                );
                return $this->redirect($this->generateUrl('aldea_show',array('id'=>$usr->getId())));
                }
                
                if(!$aldea){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Aldea con ID: '.$idaldea.' no registrada'
                );
                return $this->redirect($this->generateUrl('aldea_show',array('id'=>$usr->getId())));
                }
            
                $triunfadores = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Triunfador')
                ->findAllOrderedByAmbiente($idamb);
                
                $vocero = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Vocero')
                ->findAllOrderedByAmbiente($idamb);
                
                $periodosacademicos = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:PeriodoAcademicoAmbiente')
                ->findByAmbiente($idamb);
                
		return $this->render(
			'MisionSucreRipesBundle:Ambiente:show.html.twig',
			array('aldea'=>$aldea,'ambiente'=>$ambiente,'triunfadores'=>$triunfadores,
                            'vocero'=>$vocero,'periodosacademicos' =>$periodosacademicos
                        ));
	}
    
    public function listaAction(Request $request)
	{   
        /*Muestra los ambientes
         * 
         */
         $data = $this->get('servicios.ambiente');
                
         $ambientes = $data->Lista();
                
		if(!$ambientes){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Ningun Ambiente Registrado'
            );
            return $this->redirect($this->generateUrl('ambiente'));
            }
            
		return $this->render(
			'MisionSucreRipesBundle:Ambiente:lista.html.twig',
                array('ambientes' => $ambientes)
		);	
	}
        
        public function buscarAction(Request $request)
	{       
            $em = $this->getDoctrine()->getManager();
            
                if($this->getUser()->getTipUsr()==5){
                    $id = $this->getUser()->getId();
                $caldea = $em->getRepository('MisionSucreRipesBundle:CoordinadorAldea')->findOneByUser($id);
                
                $turnoscoordinador=$em->getRepository('MisionSucreRipesBundle:CoordinadorTurno')->findAllByCoordinador($caldea->getId());
                            
                $aldea = $caldea->getAldea()->getId();
                }
                else{
                    $aldea =null;
                    $turnoscoordinador=null;
                }
		return $this->render(
			'MisionSucreRipesBundle:Ambiente:buscar.html.twig',
                        array('aldea'=>$aldea,'municipios'=>$this->MunicipiosEje(),'turnoscoordinador'=>$turnoscoordinador)
		);	
	}

public function busquedaAction(Request $request)
	{       
//                $param='{"query":{"aldea":"1","municipio":"%%","parroquia":"%%","nombre":"","turno":"","modalidad":"","pnf":"","trayecto":"","periodo":"","periodoacademico":"","condicion":""}}';
//                
//                $parametros=json_decode($param,true);
                
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
//                $ambientes = $em->createQuery(
//                        "SELECT am, pa
//                            FROM MisionSucreRipesBundle:PeriodoAcademicoAmbiente pa JOIN pa.ambiente am JOIN am.aldea a JOIN a.parroquia prq JOIN prq.eje e,
//                            MisionSucreRipesBundle:Pnf pnf, MisionSucreRipesBundle:PeriodoAcademico p, MisionSucreRipesBundle:Municipio m
//                            WHERE a.id LIKE :aldea
//                            
//                        " 
//                        )->setParameters(array('aldea'=>"$aldea"))
//                        ->getResult();
                
                
		return $this->render(
			'MisionSucreRipesBundle:Ambiente:busqueda.html.twig',
                        array('ambientes'=>$ambientes)
		);	
	}
    
    public function resumenAction(Request $request)
	{       
        /*Resumen Estadistico de los ambientes
         * 
         */
                $em = $this->getDoctrine()->getManager();
                
                $usr = $this->getUser();
                
                switch($usr->getTipUsr()){
                    
                    case 1:
                        $cantidadporaldeas = $em->createQuery(
                        "SELECT a.nombre, COUNT (am) as cantidadambiente FROM MisionSucreRipesBundle:Ambiente am JOIN am.aldea a
                            WHERE am.condicion !=:egresado
                            AND am.condicion !=:culminado
                            AND (am.condicion ='Nuevo' OR am.condicion ='Activo')
                            GROUP BY a.id
                        ")
                        ->setParameters(array('egresado'=>'Egresado','culminado'=>'Culminado'))
                        ->getResult();
                 
                        $cantidadporeje = $em->createQuery(
                        "SELECT e.nombre, COUNT (am) as cantidadambiente FROM MisionSucreRipesBundle:Ambiente am JOIN am.aldea a
                            JOIN a.parroquia prq JOIN prq.eje e
                            WHERE am.condicion !=:egresado
                            AND am.condicion !=:culminado
                            AND (am.condicion ='Nuevo' OR am.condicion ='Activo')
                            GROUP BY e.id
                        ")
                        ->setParameters(array('egresado'=>'Egresado','culminado'=>'Culminado')
                        )->getResult();
                        
                        
                        $cantidadpormunicipio = $em->createQuery(
                        "SELECT m.municipio, COUNT (am) as cantidadambiente FROM MisionSucreRipesBundle:Ambiente am JOIN am.aldea a
                            JOIN a.parroquia prq JOIN prq.municipio m
                            WHERE am.condicion !=:egresado
                            AND am.condicion !=:culminado
                            AND (am.condicion ='Nuevo' OR am.condicion ='Activo')
                            GROUP BY m.id
                        ")
                        ->setParameters(array('egresado'=>'Egresado','culminado'=>'Culminado')
                        )->getResult();
                        
                    return $this->render(
			'MisionSucreRipesBundle:Ambiente:resumeneje.html.twig',
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
                        "SELECT a.nombre, COUNT (am) as cantidadambiente FROM MisionSucreRipesBundle:Ambiente am JOIN am.aldea a
                            JOIN a.parroquia prq
                            WHERE prq.eje =:eje
                            AND am.condicion !=:egresado
                            AND am.condicion !=:culminado
                            AND (am.condicion ='Nuevo' OR am.condicion ='Activo')
                            GROUP BY a.id
                        ")
                        ->setParameters(array('egresado'=>'Egresado','culminado'=>'Culminado','eje'=>$eje))
                                ->getResult();
                        
                        $cantidadpormunicipio = $em->createQuery(
                        "SELECT m.municipio, COUNT (am) as cantidadambiente FROM MisionSucreRipesBundle:Ambiente am JOIN am.aldea a
                            JOIN a.parroquia prq JOIN prq.municipio m
                            WHERE prq.eje =:eje AND
                            am.condicion !=:egresado
                            AND am.condicion !=:culminado
                            AND (am.condicion ='Nuevo' OR am.condicion ='Activo')
                            GROUP BY m.id
                        ")
                        ->setParameters(array('egresado'=>'Egresado','culminado'=>'Culminado','eje'=>$eje))
                        ->getResult();
                        
                    return $this->render(
			'MisionSucreRipesBundle:Ambiente:resumenmunicipio.html.twig',
                        array(
                            'cxaldeas'=>$cantidadporaldeas,
                            'cxmunicipios'=>$cantidadpormunicipio
                        ));
                    break;
                    
                    case 5:
                        
                        $coord = $em->getRepository('MisionSucreRipesBundle:CoordinadorAldea')->findOneByUser($usr->getId());
                        $aldea = $coord->getAldea()->getId();
                        
                        $cantidadporpnf = $em->createQuery(
                        "SELECT pnf.nombre, COUNT (am) as cantidadambiente FROM MisionSucreRipesBundle:Ambiente am,
                            MisionSucreRipesBundle:Pnf pnf, MisionSucreRipesBundle:CoordinadorTurno t JOIN t.coordinador c
                            WHERE am.aldea =:aldea AND pnf.id = am.pnf  
                            AND am.condicion !=:culminado
                            AND am.condicion !=:egresado
                            AND (am.condicion ='Nuevo' OR am.condicion ='Activo')
                            AND c.id = :idcoordinador AND c.aldea = am.aldea AND t.turno=am.turno
                            GROUP BY pnf.id
                        ")
                        ->setParameters(array('egresado'=>'Egresado','culminado'=>'Culminado','aldea'=>$aldea,'idcoordinador'=>$coord->getId()))
                                ->getResult();
                        
                    return $this->render(
			'MisionSucreRipesBundle:Ambiente:resumenaldea.html.twig',
                        array(
                            'cxpnf'=>$cantidadporpnf,
                        ));
                    break;
                
                }
        }        
    public function modalidadAction($modalidad) {
        
    $em = $this->getDoctrine()->getManager();
    $pnfs = $em->getRepository('MisionSucreRipesBundle:Pnf')->findByModalidad($modalidad);

    return $this->render('MisionSucreRipesBundle:Ambiente:pnfs.html.twig',array('pnfs'=>$pnfs));
    } 
    
    /*
     * FUNCIONES ESPECIALES
     */
    protected function AldeasEje()
    {   
        $em = $this->getDoctrine()->getManager();
        
        if($this->getUser()->getTipUsr()==8){
        $user = $this->getUser();
        $usreje = $em->getRepository('MisionSucreRipesBundle:CoordinadorEje')->findOneByUser($user->getId());
        $aldeas = $em->getRepository('MisionSucreRipesBundle:Aldea')->findAllOrderedByParroquia($usreje->getEje()->getId());
        }
        else{
        $aldeas = $em->getRepository('MisionSucreRipesBundle:Aldea')->findAll();
        }
        
        return $aldeas;
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
    
    $em = $this->getDoctrine()->getManager();
    
    
    return $choices;
    } 
    
    public function choicesTurno($idaldea,$idcoordinador="") {
       
    $em = $this->getDoctrine()->getManager();
        
    $choices = array();
    
    if($idcoordinador){
    $turnoscoordinador=$em->getRepository('MisionSucreRipesBundle:CoordinadorTurno')->findAllByCoordinador($idcoordinador);
    }
    else{
        $turnoscoordinador=$em->getRepository('MisionSucreRipesBundle:CoordinadorTurno')->findAllByAldea($idaldea);
    }
    
    foreach ($turnoscoordinador as $t) {
        $choices[$t['turno']] =$t['turno'];
        }
     
    return $choices;
    
        }
}
