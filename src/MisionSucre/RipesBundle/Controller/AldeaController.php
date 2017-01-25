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
        
        /*
         * Registro de Aldea
         */
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
                            $sector = $em->getRepository('MisionSucreRipesBundle:Sector')->findOneByParroquia($idprq);
                            
                            $aldea->setParroquia($prq);
                            $em->persist($aldea);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Aldea Creada con Éxito'
                            );  
                            /*
                             * registrar anexo principal
                             */
                            $anexo= new AnexoAldea();
                            $anexo->setSector($sector);
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
        /*
         * Actualizar de Aldea
         */    
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
                    'sub_heading'=>$aldea->getNombre()
		));
        
        }
    
    public function showAction(Request $request,$id)
	{       
        /*
         * Mostrar informacion de aldea. Role > COORD. Si es Coordinador se redirecciona a info
         */
         
                $em = $this->getDoctrine()->getManager();
                
                $aldea =  $em->getRepository('MisionSucreRipesBundle:Aldea')->find($id);
                
                if(!$aldea){
                    
                    $request->getSession()->getFlashBag()->add(
                    'notice',
                    'Aldea con ID: '.$id.' no registrada'
                    );    
                    return $this->redirect($this->generateUrl('aldea_new'));
                }
                
                switch(($this->getUser()->getTipUsr())){
                
                case 8:
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
                break;
                
                case 7:
                    return $this->redirect($this->generateUrl('docente_aldea_show',  array('id'=>$id)));
                case 5:
                case 6:
                case 9:
                    return $this->redirect($this->generateUrl('aldea_info'));
                }
                
                $coordinadoresaldea = $em->getRepository('MisionSucreRipesBundle:CoordinadorAldea')->findAllOrderedByAldea($id);
                $anexos =  $em->getRepository('MisionSucreRipesBundle:AnexoAldea')->findByAldea($id);
                $ambientesnovinculados = $em->getRepository('MisionSucreRipesBundle:Ambiente')->cantidadAmbientesnovinculados($id);
                
//                SERVICIOS AMBIENTES
                $containerambientes = $this->get('servicios.ambiente');
                $ambientes = $containerambientes->AldeaModalidad($id);
//                FIN
                
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
			array('aldea'=>$aldea,'ambientescta'=>$ambientes['CTA'],
                            'ambientesubv'=>$ambientes['UBV'],'operarios'=>$operarios,'docentes'=>$docentes,
                            'ambientesti'=>$ambientes['TI'],
                            'coord' => $coordinadoresaldea, 'coordenadas' => $coordenadas,'anexos'=> $anexos,
                            'datosambientes'=>$datosambientes,'ambientesnovinculados' =>$ambientesnovinculados,
                            'cantidadtriunfadores'=>$cantidadtriunfadores, 'triunfadoresnovinculados'=>$triunfadoresnovinculados,
                            'cantidaddocentes'=>$cantidaddocentes,'estructuras'=>$estructuras,'turnos'=>$turnos
                        ));
	}
        
        public function showdocAction(Request $request,$id)
	{   
            /*
             * Mostrar la aldea al que esta vinculado dicho docente
             */
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
                
                //                SERVICIOS AMBIENTES
                $containerambientes = $this->get('servicios.ambiente');
                $ambientes = $containerambientes->AldeaModalidad($id);
//                FIN
                $ambientesnovinculados = $em->getRepository('MisionSucreRipesBundle:Ambiente')->cantidadAmbientesnovinculados($id);
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
                
                $estructuras = $em->getRepository('MisionSucreRipesBundle:Estructura')->findByAldea($id);
                
                $turnos=$em->getRepository('MisionSucreRipesBundle:CoordinadorTurno')->findAllByAldea($id);
                
                return $this->render(
			'MisionSucreRipesBundle:Aldea:show.html.twig',
			array('aldea'=>$aldea,'ambientescta'=>$ambientes['CTA'],
                            'ambientesubv'=>$ambientes['UBV'],'operarios'=>$operarios,'docentes'=>$docentes,
                            'ambientesti'=>$ambientes['TI'],
                            'coord' => $coordinadoresaldea, 'coordenadas' => $coordenadas,'anexos'=> $anexos,
                            'datosambientes'=>$datosambientes,'ambientesnovinculados' =>$ambientesnovinculados,
                            'cantidadtriunfadores'=>$cantidadtriunfadores, 'triunfadoresnovinculados'=>$triunfadoresnovinculados,
                            'cantidaddocentes'=>$cantidaddocentes,'estructuras'=>$estructuras,'turnos'=>$turnos
                        ));
	}
        
        public function infoAction(Request $request)
	{       /*
         * Muestra la información de aldea de un usuario especifico. No es visible para administradores
         */
            
                $em = $this->getDoctrine()->getManager();
                
                $usr = $this->getUser();
                
                $id = $usr->getId();
                    
                switch($usr->getTipUsr()){
                   
                    case 6:
                      
                      /*VALIDAR */
                $validar = $this->get('servicios.validar');
                $error = $validar->ValidarTriunfador($id, $request);
                if($error)
                    return $this->redirect($this->generateUrl($error['url'],array($error['param']=>$error['valueparam'])));
                /*FIN VALIDAR*/
                        
                      $triunfador = $em->getRepository('MisionSucreRipesBundle:Triunfador')->findOneByUser($id);
                        
                      $aldea = $triunfador->getAmbiente()->getAldea();
                      $idaldea = $aldea->getId();
                      //                SERVICIOS AMBIENTES
                $containerambientes = $this->get('servicios.ambiente');
                $ambientes = $containerambientes->AldeaModalidad($idaldea);
//                FIN  
                    break;
                    
                    case 5:
                        /*VALIDAR */
                $validar = $this->get('servicios.validar');
                $error = $validar->ValidarCoordinadorAldea($id, $request);
                if($error)
                    return $this->redirect($this->generateUrl($error['url'],array($error['param']=>$error['valueparam'])));
                /*FIN VALIDAR*/
                        $coordinador = $em->getRepository('MisionSucreRipesBundle:CoordinadorAldea')->findOneByUser($id);
                        if(!$coordinador){
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Coordinador No Vinculado a una Aldea'
                            );            
                            return $this->redirect($this->generateUrl('aldea_coordinador_info'));
                        }
                        
                $aldea = $coordinador->getAldea();
                $idaldea = $aldea->getId();
                $idcoord= $coordinador->getId();
                
                $coordinadoresaldea = $em->getRepository('MisionSucreRipesBundle:CoordinadorAldea')->findAllOrderedByAldea($idaldea);
                $anexos =  $em->getRepository('MisionSucreRipesBundle:AnexoAldea')->findByAldea($idaldea);
                $aldeascomunales =  $em->getRepository('MisionSucreRipesBundle:AldeaComunal')->findByAldea($idaldea);
                
                //                SERVICIOS AMBIENTES
                $containerambientes = $this->get('servicios.ambiente');
                $ambientes = $containerambientes->AldeaModalidad($idaldea);
//                FIN
                
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
			array('aldea'=>$aldea,'ambientescta'=>$ambientes['CTA'],
                            'ambientesubv'=>$ambientes['UBV'],'operarios'=>$operarios,'docentes'=>$docentes,
                            'ambientesti'=>$ambientes['TI'],
                            'coord' => $coordinadoresaldea, 'coordenadas' => $coordenadas,'anexos'=> $anexos,
                            'datosambientes'=>$datosambientes,'ambientesnovinculados' =>$ambientesnovinculados,
                            'cantidadtriunfadores'=>$cantidadtriunfadores, 'triunfadoresnovinculados'=>$triunfadoresnovinculados,
                            'cantidaddocentes'=>$cantidaddocentes,'estructuras'=>$estructuras,'turnos'=>$turnos
                        ));
                 break;   
                    case 7:
                        return $this->redirect($this->generateUrl('aldea_lista_docente'));
                    break;
                    case 9:
                    
                    /*VALIDAR */
                $validar = $this->get('servicios.validar');
                $error = $validar->ValidarOperario($id, $request);
                if($error)
                    return $this->redirect($this->generateUrl($error['url'],array($error['param']=>$error['valueparam'])));
                /*FIN VALIDAR*/
                $operario = $em->getRepository('MisionSucreRipesBundle:Operario')->findOneByUser($id);        
                $aldea = $operario->getAldea();
                $idaldea = $aldea->getId();
                
                $containerambientes = $this->get('servicios.ambiente');
                $ambientes = $containerambientes->AldeaModalidad($idaldea);
//                FIN
                    break;
                default :
                    return $this->redirect($this->generateUrl('aldea_lista'));
                }
                
                $coordinadoresaldea = $em->getRepository('MisionSucreRipesBundle:CoordinadorAldea')->findAllOrderedByAldea($idaldea);
                $anexos =  $em->getRepository('MisionSucreRipesBundle:AnexoAldea')->findByAldea($idaldea);
                $aldeascomunales =  $em->getRepository('MisionSucreRipesBundle:AldeaComunal')->findByAldea($idaldea);
                $cantidaddocentes = $em->getRepository('MisionSucreRipesBundle:Docente')->cantidadAldea($idaldea);
                $ambientesnovinculados = $em->getRepository('MisionSucreRipesBundle:Ambiente')->cantidadAmbientesnovinculados($idaldea);
                $estructuras = $em->getRepository('MisionSucreRipesBundle:Estructura')->findByAldea($idaldea);
                
                $turnos =$em->getRepository('MisionSucreRipesBundle:CoordinadorTurno')->findAllByAldea($idaldea);
                
                $operarios = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Operario')
                ->findAllOrderedByAldea($idaldea);
                
                $docentes = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Docente')
                ->findAllOrderedByAldea($idaldea);
                $coordenadas =$em->getRepository('MisionSucreRipesBundle:CoordenadasAldea')->findOneByAldea($idaldea);
                $cantidadtriunfadores = $em->getRepository('MisionSucreRipesBundle:Triunfador')->cantidadAldea($id);
                $triunfadoresnovinculados= $em->getRepository('MisionSucreRipesBundle:Triunfador')->cantidadAldeanovinculados($id);
                $datosambientes = $em->getRepository('MisionSucreRipesBundle:Ambiente')->cantidadAmbientes($id);
                
                return $this->render(
			'MisionSucreRipesBundle:Aldea:show.html.twig',
			array('aldea'=>$aldea,'ambientescta'=>$ambientes['CTA'],
                            'ambientesubv'=>$ambientes['UBV'],'operarios'=>$operarios,'docentes'=>$docentes,
                            'ambientesti'=>$ambientes['TI'],
                            'coord' => $coordinadoresaldea, 'coordenadas' => $coordenadas,'anexos'=> $anexos,
                            'datosambientes'=>$datosambientes,'ambientesnovinculados' =>$ambientesnovinculados,
                            'cantidadtriunfadores'=>$cantidadtriunfadores, 'triunfadoresnovinculados'=>$triunfadoresnovinculados,
                            'cantidaddocentes'=>$cantidaddocentes,'estructuras'=>$estructuras,'turnos'=>$turnos
                        ));
        
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
    /*
     * Muestra las Aldea . ROLE > EJE 
     */
    
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
        
public function listadocenteAction(Request $request)
	{   
    /*
     * lista de aldeas que esta vinculado un docente
     */
    $em = $this->getDoctrine()->getManager();
    $idusr=$this->getUser()->getId();
    
    /*VALIDAR */
                $validar = $this->get('servicios.validar');
                $error = $validar->ValidarDocente($idusr, $request);
                if($error)
                    return $this->redirect($this->generateUrl($error['url'],array($error['param']=>$error['valueparam'])));
                /*FIN VALIDAR*/
    
    
            $aldeas = $em->getRepository('MisionSucreRipesBundle:Docente')->Aldeas($idusr);
                
		if(!$aldeas){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Ninguna Aldea Vinculada'
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
                
                if($this->getUser()->getTipUsr()==8){
                $user = $this->getUser();
                $usreje = $em->getRepository('MisionSucreRipesBundle:CoordinadorEje')->findOneByUser($user->getId());
                $eje = $usreje->getEje()->getId();
                }
                else{
                    $eje = "";
                }
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
