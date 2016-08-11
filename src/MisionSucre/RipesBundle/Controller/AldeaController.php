<?php

namespace MisionSucre\RipesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MisionSucre\RipesBundle\Entity\Aldea;
use MisionSucre\RipesBundle\Entity\AnexoAldea;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;

class AldeaController extends Controller
{
    public function indexAction()
    {   
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_EJE')) {
                
                $id = $this->getUser()->getId();
                
                return $this->redirect($this->generateUrl('aldea_info'));
                }
                else{
                    return $this->render('MisionSucreRipesBundle:Aldea:index.html.twig');
                }
        
    }
    
    public function newAction(Request $request)
	{       
	$em = $this->getDoctrine()->getManager();
        
        $aldea= new Aldea();
                
        $form = $this->createFormBuilder($aldea)->
                add('nombre', 'text',array('label' => 'Nombre de la Aldea'))->
                add('codigo', 'text',array('label' => 'Código de la Aldea'))
                        ->add('municipio', 'choice', array(
        'choices' => $this->MunicipiosEje(),'mapped' =>false,
            'placeholder'=>"Seleccione una",'label' => 'Municipio de la Aldea'
            ))
            ->add('prq', 'hidden',array('mapped' => false))
            ->add('direccion', 'textarea',array('label' => 'Dirrección de la Aldea'))
        ->add('save', 'submit',array('label' => 'Registrar Aldea'))->getForm();
            
                $form->handleRequest($request);
                
		if ($form->isValid()) {
                            
                            $em = $this->getDoctrine()->getManager();    
                            
                            $idprq = $form->get('prq')->getData();
                                
                            $prq = $em->getRepository('MisionSucreRipesBundle:Parroquia')->find($idprq);
                            
                            $aldea->setParroquia($prq);
                            $em->persist($aldea);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Aldea Creada con Éxito'
                            );  
                            $anexo= new AnexoAldea();
                            //$anexo->setSector($sector);
                            $anexo->setAldea($aldea);
                            $anexo->setNombre("Principal");
                            $anexo->setDireccion($aldea->getDireccion());
                            $em->persist($anexo);
                            $em->flush();
                    return $this->redirect($this->generateUrl('aldea_show',array('id'=>$aldea->getId())));
		}
		
        
        return $this->render('MisionSucreRipesBundle:Aldea:new.html.twig', array(
		'form' => $form->createView(),'mensaje_heading'=>'Registro de Aldea',
                    'sub_heading'=>'Universitaria Misión Sucre'
		));
        
        }
        
        public function updateAction(Request $request,$id)
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
                    return $this->redirect($this->generateUrl('aldea_new'));
            }
        }

        $form = $this->createFormBuilder($aldea)->
                add('codigo', 'text',array('label' => 'Código de la Aldea'))->
                add('nombre', 'text',array('label' => 'Nombre de la Aldea'))
                        ->add('municipio', 'choice', array(
        'choices' => $this->MunicipiosEje(),'mapped' =>false,
            'placeholder'=>"Seleccione una",'label' => 'Municipio de la Aldea'
            ))
            ->add('prq', 'hidden',array('mapped' => false))
            ->add('direccion', 'textarea',array('label' => 'Dirrección de la Aldea'))
        ->add('save', 'submit',array('label' => 'Actualizar Aldea'))->getForm();
            
                $form->handleRequest($request);
                
		if ($form->isValid()) {
                            
                            $em = $this->getDoctrine()->getManager();    
                            
                            $idprq = $form->get('prq')->getData();
                                
                            $prq = $em->getRepository('MisionSucreRipesBundle:Parroquia')->find($idprq);
                            
                            $aldea->setParroquia($prq);
                            
                            $em->persist($aldea);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Aldea Actualiza con  con Éxito'
                            );            
                    return $this->redirect($this->generateUrl('aldea_show',array('id'=>$aldea->getId())));
		}
		
        
        return $this->render('MisionSucreRipesBundle:Aldea:new.html.twig', array(
		'form' => $form->createView(),'mensaje_heading'=>'Actualización de Aldea',
                    'sub_heading'=>'Datos de Aldea Universitaria'
		));
        
        }
    
    public function showAction(Request $request,$id)
	{       
                $em = $this->getDoctrine()->getManager();
                
                $aldea =  $em->getRepository('MisionSucreRipesBundle:Aldea')->find($id);
                
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
                
                $coordinadoresaldea = $em->getRepository('MisionSucreRipesBundle:CoordinadorAldea')->findAllOrderedByAldea($id);
                $aldeascomunales =  $em->getRepository('MisionSucreRipesBundle:AldeaComunal')->findByAldea($id);
                $anexos =  $em->getRepository('MisionSucreRipesBundle:AnexoAldea')->findByAldea($id);
                
                $ambientescta = array();
                $ambientescta['conperiodos'] =  $em->getRepository('MisionSucreRipesBundle:Ambiente')->findAmbienteByAldeaAndModalidad($id,'TRIMESTRAL');
                $ambientescta['sinperiodos'] =  $em->getRepository('MisionSucreRipesBundle:Ambiente')->findAllOrderedByPeriodoAcademicoAndAldea($id,'TRIMESTRAL');
                $ambientesnovinculados = $em->getRepository('MisionSucreRipesBundle:Ambiente')->cantidadAmbientesnovinculados($id);
                $ambientesubv = array();                               
                $ambientesubv['conperiodos'] =  $em->getRepository('MisionSucreRipesBundle:Ambiente')->findAmbienteByAldeaAndModalidad($id,'SEMESTRAL');               
                $ambientesubv['sinperiodos'] =  $em->getRepository('MisionSucreRipesBundle:Ambiente')->findAllOrderedByPeriodoAcademicoAndAldea($id,'SEMESTRAL');
                $ambientesti =  $em->getRepository('MisionSucreRipesBundle:Ambiente')->findAmbienteByAldeaAndModalidad($id,'TI');
                $datosambientes = $em->getRepository('MisionSucreRipesBundle:Ambiente')->cantidadAmbientes($id);
                $cantidadtriunfadores = $em->getRepository('MisionSucreRipesBundle:Triunfador')->cantidadAldea($id);
                $triunfadoresnovinculados= $em->getRepository('MisionSucreRipesBundle:Triunfador')->cantidadAldeanovinculados($id);
                $cantidaddocentes = $em->getRepository('MisionSucreRipesBundle:Docente')->cantidadAldea($id);
                $estructuras = $em->getRepository('MisionSucreRipesBundle:Estructura')->findByAldea($id);
                
                $turnos=$em->getRepository('MisionSucreRipesBundle:CoordinadorTurno')->findAllByAldea($id);
                
                $operarios = $em->getRepository('MisionSucreRipesBundle:Operario')
                ->findAllOrderedByAldea($id);
                
                $docentes = $em->getRepository('MisionSucreRipesBundle:Docente')
                ->findAllOrderedByAldea($id);
                
                $coordenadas =$em->getRepository('MisionSucreRipesBundle:CoordenadasAldea')->findOneByAldea($id);
                
		return $this->render(
			'MisionSucreRipesBundle:Aldea:show.html.twig',
			array('aldea'=>$aldea,'aldeascomunales'=>$aldeascomunales,'ambientescta'=>$ambientescta,
                            'ambientesubv'=>$ambientesubv,'operarios'=>$operarios,'docentes'=>$docentes,'ambientesti'=>$ambientesti,
                            'coord' => $coordinadoresaldea, 'coordenadas' => $coordenadas,'anexos'=> $anexos,
                            'datosambientes'=>$datosambientes,'ambientesnovinculados' =>$ambientesnovinculados,
                            'cantidadtriunfadores'=>$cantidadtriunfadores, 'triunfadoresnovinculados'=>$triunfadoresnovinculados,
                            'cantidaddocentes'=>$cantidaddocentes,'estructuras'=>$estructuras,'turnos'=>$turnos
                        ));
	}
        
        public function showdocAction(Request $request,$id)
	{       
                $em = $this->getDoctrine()->getManager();
                
                $aldea =  $em->getRepository('MisionSucreRipesBundle:Aldea')->find($id);
                
                if(!$aldea){
                    
                    $request->getSession()->getFlashBag()->add(
                    'notice',
                    'Aldea con ID: '.$id.' no registrada'
                    );    
                    return $this->redirect($this->generateUrl('aldea_new'));
                }
                
                $idusr = $this->getUser()->getId();
                
                $docente = $em->getRepository('MisionSucreRipesBundle:Docente')->findByUser($idusr);
                        
                        if(!$docente){
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Docente No Vinculado a una Aldea'
                            );            
                            return $this->redirect($this->generateUrl('docente_info'));
                        }
                
                $es_docente = $em->getRepository('MisionSucreRipesBundle:Docente')->findByAldeaAndUser($id,$idusr);
                
                if(!$es_docente){
                    
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Docente No Vinculado a esta Aldea'
                            );            
                            return $this->redirect($this->generateUrl('docente_info'));
                    
                }
                
                $coordinadoresaldea = $em->getRepository('MisionSucreRipesBundle:CoordinadorAldea')->findAllOrderedByAldea($id);
                $anexos =  $em->getRepository('MisionSucreRipesBundle:AnexoAldea')->findByAldea($id);
                $aldeascomunales =  $em->getRepository('MisionSucreRipesBundle:AldeaComunal')->findByAldea($id);
                
                $ambientescta = array();                
                $ambientescta['conperiodos'] =  $em->getRepository('MisionSucreRipesBundle:Ambiente')->findAmbienteByAldeaAndModalidad($idaldea,'TRIMESTRAL');             
                $ambientescta['sinperiodos'] =  $em->getRepository('MisionSucreRipesBundle:Ambiente')->findAllOrderedByPeriodoAcademicoAndAldea($idaldea,'TRIMESTRAL');
                $ambientesubv = array();                
                $ambientesubv['conperiodos'] =  $em->getRepository('MisionSucreRipesBundle:Ambiente')->findAmbienteByAldeaAndModalidad($idaldea,'SEMESTRAL');
                $ambientesubv['sinperiodos'] =  $em->getRepository('MisionSucreRipesBundle:Ambiente')->findAllOrderedByPeriodoAcademicoAndAldea($idaldea,'SEMESTRAL');
                $ambientesti =  $em->getRepository('MisionSucreRipesBundle:Ambiente')->findAmbienteByAldeaAndModalidad($id,'TI');
                $datosambientes = $em->getRepository('MisionSucreRipesBundle:Ambiente')->cantidadAmbientes($id);
                $cantidadtriunfadores = $em->getRepository('MisionSucreRipesBundle:Triunfador')->cantidadAldea($id);
                $triunfadoresnovinculados= $em->getRepository('MisionSucreRipesBundle:Triunfador')->cantidadAldeanovinculados($id);
                $cantidaddocentes = $em->getRepository('MisionSucreRipesBundle:Docente')->cantidadAldea($id);
                
                $operarios = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Operario')
                ->findAllOrderedByAldea($id);
                
                $docentes = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Docente')
                ->findAllOrderedByAldea($id);
                
                $coordenadas =$em->getRepository('MisionSucreRipesBundle:CoordenadasAldea')->findOneByAldea($id);
                
                
                
		return $this->render(
			'MisionSucreRipesBundle:Aldea:show.html.twig',
			array('aldea'=>$aldea,'aldeascomunales'=>$aldeascomunales,'ambientescta'=>$ambientescta,
                            'ambientesubv'=>$ambientesubv,'operarios'=>$operarios,'docentes'=>$docentes,'ambientesti'=>$ambientesti,
                            'coord' => $coordinadoresaldea,'anexos'=>$anexos,
                            'datosambientes'=>$datosambientes,'ambientesnovinculados' =>$ambientesnovinculados,
                            'cantidadtriunfadores'=>$cantidadtriunfadores, 'triunfadoresnovinculados'=>$triunfadoresnovinculados,
                            'cantidaddocentes'=>$cantidaddocentes,'coordenadas' => $coordenadas
                        ));
	}
        
        public function infoAction(Request $request)
	{       
            
                $em = $this->getDoctrine()->getManager();
                
                $usr = $this->getUser();
                
                $id = $usr->getId();
                    
                switch($usr->getTipUsr()){
                   
                    case 6:
                        $triunfador = $em->getRepository('MisionSucreRipesBundle:Triunfador')->findOneByUser($id);
                        
                        if(!$triunfador){
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Triunfador No Vinculado a una Aldea'
                            );            
                            return $this->redirect($this->generateUrl('triunfador_info'));
                        }
                        else{
                $aldea = $triunfador->getAmbiente()->getAldea();
                $idaldea = $aldea->getId();
                
                $coordinadoresaldea = $em->getRepository('MisionSucreRipesBundle:CoordinadorAldea')->findAllOrderedByAldea($idaldea);
                $anexos =  $em->getRepository('MisionSucreRipesBundle:AnexoAldea')->findByAldea($idaldea);
                $aldeascomunales =  $em->getRepository('MisionSucreRipesBundle:AldeaComunal')->findByAldea($idaldea);
                $ambientescta = array();                
                $ambientescta['conperiodos'] =  $em->getRepository('MisionSucreRipesBundle:Ambiente')->findAmbienteByAldeaAndModalidad($idaldea,'TRIMESTRAL');             
                $ambientescta['sinperiodos'] =  $em->getRepository('MisionSucreRipesBundle:Ambiente')->findAllOrderedByPeriodoAcademicoAndAldea($idaldea,'TRIMESTRAL');
                $ambientesubv = array();                
                $ambientesubv['conperiodos'] =  $em->getRepository('MisionSucreRipesBundle:Ambiente')->findAmbienteByAldeaAndModalidad($idaldea,'SEMESTRAL');
                $ambientesubv['sinperiodos'] =  $em->getRepository('MisionSucreRipesBundle:Ambiente')->findAllOrderedByPeriodoAcademicoAndAldea($idaldea,'SEMESTRAL');
                $ambientesti =  $em->getRepository('MisionSucreRipesBundle:Ambiente')->findAmbienteByAldeaAndModalidad($idaldea,'TI');
                $cantidaddocentes = $em->getRepository('MisionSucreRipesBundle:Docente')->cantidadAldea($idaldea);
                $ambientesnovinculados = $em->getRepository('MisionSucreRipesBundle:Ambiente')->cantidadAmbientesnovinculados($idaldea);
                $estructuras = $em->getRepository('MisionSucreRipesBundle:Estructura')->findByAldea($idaldea);
                
                $operarios = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Operario')
                ->findAllOrderedByAldea($idaldea);
                
                $docentes = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Docente')
                ->findAllOrderedByAldea($idaldea);
                
                $coordenadas =$em->getRepository('MisionSucreRipesBundle:CoordenadasAldea')->findOneByAldea($idaldea);
		return $this->render(
			'MisionSucreRipesBundle:Aldea:show.html.twig',
			array('aldea'=>$aldea,'aldeascomunales'=>$aldeascomunales,'ambientescta'=>$ambientescta,'ambientesti'=>$ambientesti,
                            'ambientesubv'=>$ambientesubv,'operarios'=>$operarios,'docentes'=>$docentes,
                            'coord' => $coordinadoresaldea,'anexos'=>$anexos,'coordenadas' => $coordenadas,
                            'estructuras'=>$estructuras
                        ));
                        }
                    break;
                    
                    case 5:
                        $coordinador = $em->getRepository('MisionSucreRipesBundle:CoordinadorAldea')->findOneByUser($id);
                        if(!$coordinador){
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Coordinador No Vinculado a una Aldea'
                            );            
                            return $this->redirect($this->generateUrl('aldea_coordinador_info'));
                        }
                        else{
                $aldea = $coordinador->getAldea();
                $idaldea = $aldea->getId();
                $idcoord= $coordinador->getId();
                
                $coordinadoresaldea = $em->getRepository('MisionSucreRipesBundle:CoordinadorAldea')->findAllOrderedByAldea($idaldea);
                $anexos =  $em->getRepository('MisionSucreRipesBundle:AnexoAldea')->findByAldea($idaldea);
                $aldeascomunales =  $em->getRepository('MisionSucreRipesBundle:AldeaComunal')->findByAldea($idaldea);
                
                $ambientescta = array();
                $ambientesubv = array();
                $ambientesti  = array();
                
                $turnoscoordinador=$em->getRepository('MisionSucreRipesBundle:CoordinadorTurno')->findAllByCoordinador($idcoord);
                
                $ambientescta['conperiodos']= array();
                $ambientescta['sinperiodos']= array();         
                $ambientesubv['conperiodos']= array();
                $ambientesubv['sinperiodos']= array();
                         
                foreach($turnoscoordinador as $t) {
                $ambientescta['conperiodos'] = $ambientescta['conperiodos'] + $em->getRepository('MisionSucreRipesBundle:Ambiente')->AmbientesAldeaTurnoModalidad($idaldea,$t['turno'],'TRIMESTRAL');
                $ambientescta['sinperiodos'] =$ambientescta['sinperiodos'] + $em->getRepository('MisionSucreRipesBundle:Ambiente')->AmbientesAldeaTurnoModalidadSinPeriodoActual($idaldea,$t['turno'],'TRIMESTRAL');
                $ambientesubv['conperiodos'] = $ambientesubv['conperiodos'] + $em->getRepository('MisionSucreRipesBundle:Ambiente')->AmbientesAldeaTurnoModalidad($idaldea,$t['turno'],'SEMESTRAL');               
                $ambientesubv['sinperiodos'] = $ambientesubv['sinperiodos']+ $em->getRepository('MisionSucreRipesBundle:Ambiente')->AmbientesAldeaTurnoModalidadSinPeriodoActual($idaldea,$t['turno'],'SEMESTRAL');
                $ambientesti = $ambientesti + $em->getRepository('MisionSucreRipesBundle:Ambiente')->AmbientesAldeaTurnoModalidad($idaldea,$t['turno'],'TI');
                }
                
                $datosambientes = $em->getRepository('MisionSucreRipesBundle:Ambiente')->cantidadAmbientesTurnos($idcoord);
                $cantidadtriunfadores = $em->getRepository('MisionSucreRipesBundle:Triunfador')->cantidadAldeaTurno($idcoord);
                $triunfadoresnovinculados= $em->getRepository('MisionSucreRipesBundle:Triunfador')->cantidadAldeanovinculadosTurnos($idaldea);
                $cantidaddocentes = $em->getRepository('MisionSucreRipesBundle:Docente')->cantidadAldea($idaldea);
                $ambientesnovinculados = $em->getRepository('MisionSucreRipesBundle:Ambiente')->cantidadAmbientesnovinculadosTurnos($idcoord);
                $estructuras = $em->getRepository('MisionSucreRipesBundle:Estructura')->findByAldea($idaldea);
                
                $turnos=$em->getRepository('MisionSucreRipesBundle:CoordinadorTurno')->findAllByAldea($idaldea);
                
                $operarios = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Operario')
                ->findAllOrderedByAldeaTurno($idcoord);
                
                $docentes = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Docente')
                ->findAllOrderedByAldea($idaldea);
                
                $coordenadas =$em->getRepository('MisionSucreRipesBundle:CoordenadasAldea')->findOneByAldea($idaldea);
                
		return $this->render(
			'MisionSucreRipesBundle:Aldea:show.html.twig',
			array('aldea'=>$aldea,'aldeascomunales'=>$aldeascomunales,'ambientescta'=>$ambientescta,
                            'ambientesubv'=>$ambientesubv,'operarios'=>$operarios,'docentes'=>$docentes,'ambientesti'=>$ambientesti,
                            'coord' => $coordinadoresaldea,'anexos'=>$anexos,
                            'datosambientes'=>$datosambientes,'ambientesnovinculados' =>$ambientesnovinculados,
                            'cantidadtriunfadores'=>$cantidadtriunfadores, 'triunfadoresnovinculados'=>$triunfadoresnovinculados
                        ,'cantidaddocentes'=>$cantidaddocentes,'coordenadas' => $coordenadas,'turnos'=>$turnos,
                            'estructuras'=>$estructuras
                        ));
                        }
                    break;
                    
                    case 7:
                        $docente = $em->getRepository('MisionSucreRipesBundle:Docente')->findByUser($id);
                        
                        if(!$docente){
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Docente No Vinculado a una Aldea'
                            );            
                            return $this->redirect($this->generateUrl('usuario_info'));
                        }
                        if(count($docente)<2){
                        
                        $docente = $docente[0];   
                        $aldea = $docente->getAldea();
                        $idaldea = $aldea->getId();

                        $coordinadoresaldea = $em->getRepository('MisionSucreRipesBundle:CoordinadorAldea')->findAllOrderedByAldea($idaldea);
                        $anexos =  $em->getRepository('MisionSucreRipesBundle:AnexoAldea')->findByAldea($idaldea);
                        $aldeascomunales =  $em->getRepository('MisionSucreRipesBundle:AldeaComunal')->findByAldea($idaldea);
                        $ambientescta = array();    
                        $ambientescta['conperiodos'] =  $em->getRepository('MisionSucreRipesBundle:Ambiente')->findAmbienteByAldeaAndModalidad($idaldea,'TRIMESTRAL');               
                        $ambientescta['sinperiodos'] =  $em->getRepository('MisionSucreRipesBundle:Ambiente')->findAllOrderedByPeriodoAcademicoAndAldea($idaldea,'TRIMESTRAL');
                        $ambientesubv = array();                               
                        $ambientesubv['conperiodos'] =  $em->getRepository('MisionSucreRipesBundle:Ambiente')->findAmbienteByAldeaAndModalidad($idaldea,'SEMESTRAL');               
                        $ambientesubv['sinperiodos'] =  $em->getRepository('MisionSucreRipesBundle:Ambiente')->findAllOrderedByPeriodoAcademicoAndAldea($idaldea,'SEMESTRAL');
                        $ambientesti =  $em->getRepository('MisionSucreRipesBundle:Ambiente')->findAmbienteByAldeaAndModalidad($idaldea,'TI');
                        $datosambientes = $em->getRepository('MisionSucreRipesBundle:Ambiente')->cantidadAmbientes($idaldea);
                        $cantidadtriunfadores = $em->getRepository('MisionSucreRipesBundle:Triunfador')->cantidadAldea($idaldea);
                        $triunfadoresnovinculados= $em->getRepository('MisionSucreRipesBundle:Triunfador')->cantidadAldeanovinculados($idaldea);
                        $cantidaddocentes = $em->getRepository('MisionSucreRipesBundle:Docente')->cantidadAldea($idaldead);
                        $ambientesnovinculados = $em->getRepository('MisionSucreRipesBundle:Ambiente')->cantidadAmbientesnovinculados($idaldea);
                        $estructuras = $em->getRepository('MisionSucreRipesBundle:Estructura')->findByAldea($idaldea);
                        
                        $operarios = $this->getDoctrine()
                        ->getRepository('MisionSucreRipesBundle:Operario')
                        ->findAllOrderedByAldea($idaldea);

                        $docentes = $this->getDoctrine()
                        ->getRepository('MisionSucreRipesBundle:Docente')
                        ->findAllOrderedByAldea($idaldea);
                        
                        $coordenadas =$em->getRepository('MisionSucreRipesBundle:CoordenadasAldea')->findOneByAldea($idaldea);

                        return $this->render(
                                'MisionSucreRipesBundle:Aldea:show.html.twig',
                                array('aldea'=>$aldea,'aldeascomunales'=>$aldeascomunales,'ambientescta'=>$ambientescta,'ambientesti'=>$ambientesti,
                                    'ambientesubv'=>$ambientesubv,'operarios'=>$operarios,'docentes'=>$docentes,'ambientesti'=>$ambientesti,
                                    'coord' => $coordinadoresaldea,'anexos'=>$anexos,
                            'datosambientes'=>$datosambientes,'ambientesnovinculados' =>$ambientesnovinculados,
                            'cantidadtriunfadores'=>$cantidadtriunfadores, 'triunfadoresnovinculados'=>$triunfadoresnovinculados
                                ,'cantidaddocentes'=>$cantidaddocentes,'coordenadas' => $coordenadas,
                            'estructuras'=>$estructuras
                                ));
                    } else
                    {
                    $aldeas = array();
                    foreach ($docente as $d){    
                            $aldeas[]=$d->getAldea();
                        }
                        
                        return $this->render(
			'MisionSucreRipesBundle:Aldea:aldeadocente.html.twig',
			array('aldeas'=>$aldeas
                        ));
                        }
                    break;
                    case 9:
                        $operario = $em->getRepository('MisionSucreRipesBundle:Operario')->findOneByUser($id);
                        
                        if(!$operario){
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Operario No Vinculado a una Aldea'
                            );            
                            return $this->redirect($this->generateUrl('triunfador_info'));
                        }
                        else{
                $aldea = $operario->getAldea();
                $idaldea = $aldea->getId();
                
                $coordinadoresaldea = $em->getRepository('MisionSucreRipesBundle:CoordinadorAldea')->findAllOrderedByAldea($idaldea);
                $anexos =  $em->getRepository('MisionSucreRipesBundle:AnexoAldea')->findByAldea($idaldea);
                $aldeascomunales =  $em->getRepository('MisionSucreRipesBundle:AldeaComunal')->findByAldea($idaldea);
                $ambientescta = array();                
                $ambientescta['conperiodos'] =  $em->getRepository('MisionSucreRipesBundle:Ambiente')->findAmbienteByAldeaAndModalidad($idaldea,'TRIMESTRAL');             
                $ambientescta['sinperiodos'] =  $em->getRepository('MisionSucreRipesBundle:Ambiente')->findAllOrderedByPeriodoAcademicoAndAldea($idaldea,'TRIMESTRAL');
                $ambientesubv = array();                
                $ambientesubv['conperiodos'] =  $em->getRepository('MisionSucreRipesBundle:Ambiente')->findAmbienteByAldeaAndModalidad($idaldea,'SEMESTRAL');
                $ambientesubv['sinperiodos'] =  $em->getRepository('MisionSucreRipesBundle:Ambiente')->findAllOrderedByPeriodoAcademicoAndAldea($idaldea,'SEMESTRAL');
                $ambientesti =  $em->getRepository('MisionSucreRipesBundle:Ambiente')->findAmbienteByAldeaAndModalidad($idaldea,'TI');
                $datosambientes = $em->getRepository('MisionSucreRipesBundle:Ambiente')->cantidadAmbientes($idaldea);
                $cantidadtriunfadores = $em->getRepository('MisionSucreRipesBundle:Triunfador')->cantidadAldea($idaldea);
                $triunfadoresnovinculados= $em->getRepository('MisionSucreRipesBundle:Triunfador')->cantidadAldeanovinculados($idaldea);
                $cantidaddocentes = $em->getRepository('MisionSucreRipesBundle:Docente')->cantidadAldea($idaldea);
                $ambientesnovinculados = $em->getRepository('MisionSucreRipesBundle:Ambiente')->cantidadAmbientesnovinculados($idaldea);
                $estructuras = $em->getRepository('MisionSucreRipesBundle:Estructura')->findByAldea($idaldea);
                
                $operarios = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Operario')
                ->findAllOrderedByAldea($idaldea);
                
                $docentes = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Docente')
                ->findAllOrderedByAldea($idaldea);
                
                $coordenadas =$em->getRepository('MisionSucreRipesBundle:CoordenadasAldea')->findOneByAldea($id);
                
		return $this->render(
			'MisionSucreRipesBundle:Aldea:show.html.twig',
			array('aldea'=>$aldea,'aldeascomunales'=>$aldeascomunales,'ambientescta'=>$ambientescta,'ambientesti'=>$ambientesti,
                            'ambientesubv'=>$ambientesubv,'operarios'=>$operarios,'docentes'=>$docentes,
                            'coord' => $coordinadoresaldea,'anexos'=>$anexos,
                            'datosambientes'=>$datosambientes,'ambientesnovinculados' =>$ambientesnovinculados,
                            'cantidadtriunfadores'=>$cantidadtriunfadores, 'triunfadoresnovinculados'=>$triunfadoresnovinculados
                         ,'cantidaddocentes'=>$cantidaddocentes,'coordenadas' => $coordenadas,
                            'estructuras'=>$estructuras
                        ));
                        }
                    break;
                    
                }  
            }
        
        
        public function deleteAction(Request $request,$id)
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
                            return $this->redirect($this->generateUrl('aldea_new'));
                    }
                }
                
                $aldeacomunales = $em->getRepository('MisionSucreRipesBundle:AldeaComunal')->findByAldea($id);
                
                if($aldeacomunales)
                {   
                    $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Antes de Eliminar la Aldea. Por favor Eliminar los Espacios Comunales Asociados a Ella'
                            );            
                    return $this->redirect($this->generateUrl('aldea_show',array('id'=>$id)));
                }  
                
                $ambientes = $em->getRepository('MisionSucreRipesBundle:Ambiente')->findByAldea($id);
                
                if($ambientes)
                {   
                    $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Antes de Eliminar la Aldea. Por favor Eliminar los Ambientes Asociados a Ella'
                            );            
                    return $this->redirect($this->generateUrl('aldea_show',array('id'=>$id)));
                }  
                
                $docentes = $em->getRepository('MisionSucreRipesBundle:Docente')->findByAldea($id);
                
                if($docentes)
                {   
                    $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Antes de Eliminar la Aldea. Por favor Eliminar los Docentes Asociados a Ella'
                            );            
                    return $this->redirect($this->generateUrl('aldea_show',array('id'=>$id)));
                }  
                
                $operarios = $em->getRepository('MisionSucreRipesBundle:Operario')->findByAldea($id);
                
                if($operarios)
                {   
                    $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Antes de Eliminar la Aldea. Por favor Eliminar el Personal Operativo Asociado a Ella'
                            );            
                    return $this->redirect($this->generateUrl('aldea_show',array('id'=>$id)));
                }  
        
                            $em->remove($aldea);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Aldea Borrada con  con Éxito'
                            );            
                    return $this->redirect($this->generateUrl('aldea_lista'));
        
        }
public function listaAction(Request $request)
	{   
    
            $aldeas = $this->AldeasEje();
                
		if(!$aldeas){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Ninguna Aldea Registrada'
            );
            return $this->redirect($this->generateUrl('aldea_new'));
            }
            
		return $this->render(
			'MisionSucreRipesBundle:Aldea:lista.html.twig',
                array('aldeas' => $aldeas)
		);	
	}

public function buscarAction(Request $request)
	{         
		return $this->render(
			'MisionSucreRipesBundle:Aldea:buscar.html.twig',
                        array('municipios'=>$this->MunicipiosEje())
		);	
	}
public function busquedaAction($param)
	{       
            
                $parametros=json_decode($param,true);
                
                $query=$parametros['query'];
                
                $em = $this->getDoctrine()->getManager();
                
                $aldeas=null;
                
                if($this->getUser()->getTipUsr()==8){
                $user = $this->getUser();
                $usreje = $em->getRepository('MisionSucreRipesBundle:CoordinadorEje')->findOneByUser($user->getId());
                $eje = $usreje->getEje()->getId();
                }
                else
                    $eje = "";
                
                $aldeas = $em->createQuery(
                        "SELECT DISTINCT a
                            FROM MisionSucreRipesBundle:Aldea a JOIN a.parroquia prq JOIN prq.municipio m,
                            MisionSucreRipesBundle:Eje e
                        WHERE m.id LIKE :municipio AND prq.id LIKE :parroquia AND a.nombre LIKE :nombre
                        AND a.direccion LIKE :direccion AND e.id = prq.eje AND e.id LIKE :eje 
                        ORDER BY a.nombre
                        " 
                        )->setParameters(array('municipio'=>"%{$query['municipio']}%",'parroquia'=>"%{$query['parroquia']}%"
                                ,'nombre'=>"%{$query['nombre']}%",'direccion'=>"%{$query['direccion']}%"
                                    ,'eje'=>"%{$eje}%"
                                ))->getResult();
                
		return $this->render(
			'MisionSucreRipesBundle:Aldea:busqueda.html.twig',
                        array('aldeas'=>$aldeas)
		);	
	}    
        
        public function resumenAction(Request $request)
	{       
                $em = $this->getDoctrine()->getManager();
                
                $usr = $this->getUser();
                
                switch($usr->getTipUsr()){
                    
                    case 1:
                        $cantidadaldeasejes = $em->createQuery(
                        "SELECT e.nombre, COUNT (a) as cantidadaldea FROM MisionSucreRipesBundle:Aldea a JOIN a.parroquia prq
                            JOIN prq.eje e
                            GROUP BY e.id
                        "
                        )->getResult();
                        
                        $cantidadaldeasmunicipios = $em->createQuery(
                        "SELECT m.municipio, COUNT (a) as cantidadaldea FROM MisionSucreRipesBundle:Aldea a JOIN a.parroquia prq
                            JOIN prq.municipio m
                            GROUP BY m.id
                        "
                        )->getResult();
                        
                    return $this->render(
			'MisionSucreRipesBundle:Aldea:resumeneje.html.twig',
                        array(
                            'caldeasejes'=>$cantidadaldeasejes,
                            'caldeasmunicipios'=>$cantidadaldeasmunicipios
                        ));
                    break;
                
                    case 8:
                        
                        $usreje = $em->getRepository('MisionSucreRipesBundle:CoordinadorEje')->findOneByUser($usr->getId());
                        $eje = $usreje->getEje()->getId();
                        
                        $cantidadaldeasmunicipios = $em->createQuery(
                        "SELECT m.municipio, COUNT (a) as cantidadaldea FROM MisionSucreRipesBundle:Aldea a JOIN a.parroquia prq
                            JOIN prq.municipio m WHERE prq.eje =:eje
                            GROUP BY m.id
                        "
                        )->setParameter('eje',$eje)
                        ->getResult();
                        
                    return $this->render(
			'MisionSucreRipesBundle:Aldea:resumenmunicipio.html.twig',
                        array(
                            'caldeasmunicipios'=>$cantidadaldeasmunicipios
                        ));
                    break;
                }
        }        
/*
**FUNCIONES EXTRAS
 *  */
        
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
    
    public function cambiarparroquiaAction($id)
    {   
        $em = $this->getDoctrine()->getManager();
        
        if($this->getUser()->getTipUsr()==8){
        
        $user = $this->getUser();
        $usreje = $em->getRepository('MisionSucreRipesBundle:CoordinadorEje')->findOneByUser($user->getId());
        
        $parroquias = $this->getDoctrine()
            ->getRepository('MisionSucreRipesBundle:Parroquia')
            ->findAllOrderedByEje($usreje->getEje()->getId(),$id);
        }
        else{
            $parroquias = $this->getDoctrine()
            ->getRepository('MisionSucreRipesBundle:Parroquia')
            ->findByMunicipio($id);
        }
        return $this->render('MisionSucreRipesBundle:Persona:parroquia.html.twig',array('parroquias'=>$parroquias));
    }
    
    public function AldeasEje()
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
}
