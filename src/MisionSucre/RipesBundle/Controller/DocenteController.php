<?php

namespace MisionSucre\RipesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MisionSucre\RipesBundle\Entity\Docente;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;

class DocenteController extends Controller
{
    public function indexAction()
    {
        return $this->render('MisionSucreRipesBundle:Usuario:index.html.twig');
    }
	
	public function newAction(Request $request,$idaldea,$idusr)
	{       
		$docente = new Docente();
                
                $em = $this->getDoctrine()->getManager();
                
                $userdocente = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:User')
                ->find($idusr);
                
                if(!$userdocente){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            "Usuario con ID:$idusr NO Registrado"
                );
                return $this->redirect($this->generateUrl('aldea_show',array('id'=>$idaldea)));
                }
                
                if($userdocente->getTipUsr()!=7){

                $request->getSession()->getFlashBag()->add(
                'notice',
                'Usuario No es de Tipo Docente de Aldea'
                );
                return $this->redirect($this->generateUrl('usuario_show',array('id'=>$usr->getId())));
                }
                
                $horastotales = $em->createQuery(
                        "SELECT SUM(d.horas) as horas                          
                            FROM MisionSucreRipesBundle:Docente d
                        WHERE d.user =:usr "
                        )->setParameter('usr',$idusr)->getSingleResult();
                
                $horastotales = $horastotales['horas'];
                
                if($horastotales>20){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            "Docente con ID: $idusr supera las 20 Horas"
                );
                return $this->redirect($this->generateUrl('usuario_show',array('id'=>$idusr)));
                }
            
                $aldea = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Aldea')
                ->find($idaldea);
                
                $usr = $this->getUser();
                
                $caldea = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:CoordinadorAldea')
                ->findCoordinadorAldeaByUserAndId($idaldea,$usr->getId());
                
                if(!$aldea){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Aldea con ID: '.$idaldea.' no registrada'
                );
                return $this->redirect($this->generateUrl('usuario_show',array('id'=>$usr->getId())));
                }
            
                if($usr->getTipUsr()!=5){

                $request->getSession()->getFlashBag()->add(
                'notice',
                'Usuario No es de Tipo Coordinador(a) de Aldea'
                );
                return $this->redirect($this->generateUrl('usuario_show',array('id'=>$usr->getId())));
                }
                
                if(!$caldea){

                $request->getSession()->getFlashBag()->add(
                'notice',
                'Usted no es Coordinador de Esta Aldea'
                );
                return $this->redirect($this->generateUrl('usuario_show',array('id'=>$usr->getId())));
                }
                
                $reg_docente = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Docente')
                ->findDocenteByUserAndId($idaldea,$idusr);
                
                if($reg_docente){

                $request->getSession()->getFlashBag()->add(
                'notice',
                "Docente ya registrado en esta aldea"
                );
                return $this->redirect($this->generateUrl('aldea_show',array('id'=>$idaldea)));
                }
                
                $cargahoraria = $em->getRepository('MisionSucreRipesBundle:Docente')->findByUser($idusr);
                
                $horas=array();
        
                foreach(range(1,20-$horastotales) as $n)
                    $horas[$n]=$n;
                
		$form = $this->createFormBuilder($docente)->
        add('unidadescurriculares', 'textarea', array(
        'label' => 'Unidades Curriculares'))->
        add('pnfs', 'textarea', array(
        'label' => 'PNFs que Trabaja'))->
                        add('componentedocente', 'choice', array(
        'choices' => array('Si'=>'Si','No'=>'No'),'label' => 'Componente Docente','placeholder'=>'Seleccione'
            ))-> 
        add('horas', 'choice', array('choices' => $horas,
            'placeholder'=>"Seleccione una",'label' => 'Horas Totales'))->
        add('exclusividad', 'choice', array(
        'choices' => array('Si'=>'Si','No'=>'No'),'label' => 'Trabajo Sólo en esta Aldea','placeholder'=>'Seleccione'
            ))->
        add('periodoingreso', 'text', array('label' => 'Periodo de Ingreso a la Misión Sucre'))
        
                        ->add('save', 'submit',array('label' => 'Registrar Docente'))->getForm();
            
                $form->handleRequest($request);
                
                
		if ($form->isValid()) {
                            
                                
                            $docente->setAldea($aldea);    
                            $docente->setUser($userdocente);    
                            $em->persist($docente);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Docente Creado con Éxito'
                            );            
                    return $this->redirect($this->generateUrl('aldea_show',array('id'=>$idaldea)));
		}
		
                $per =  $em->getRepository('MisionSucreRipesBundle:Persona')->findOneByUser($idusr);
               
		return $this->render('MisionSucreRipesBundle:Docente:new.html.twig', array(
		'form' => $form->createView(),'mensaje_heading'=>'Nuevo Docente',
                    'sub_heading'=>'Aldea Universitaria <br>'.$aldea->getNombre(),'usr'=>$userdocente,
                    'per' => $per,'cargahoraria' => $cargahoraria
		));
	}
        
        public function deleteAction(Request $request,$iddoc)
	{       
	$em = $this->getDoctrine()->getManager();
                
                $docente = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Docente')
                ->find($iddoc);
                
                if(!$docente){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            "Docente con ID:$idusr NO Registrado"
                );
                return $this->redirect($this->generateUrl('aldea_show',array('id'=>$idaldea)));
                }
                
                $idaldea = $docente->getAldea()->getId();
                
                $aldea = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Aldea')
                ->find($idaldea);
                
                $usr = $this->getUser();
                
                $caldea = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:CoordinadorAldea')
                ->findCoordinadorAldeaByUserAndId($idaldea,$usr->getId());
                
                if(!$aldea){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Aldea con ID: '.$idaldea.' no registrada'
                );
                return $this->redirect($this->generateUrl('usuario_show',array('id'=>$usr->getId())));
                }
            
                if($usr->getTipUsr()!=5){

                $request->getSession()->getFlashBag()->add(
                'notice',
                'Usuario No es de Tipo Coordinador(a) de Aldea'
                );
                return $this->redirect($this->generateUrl('usuario_show',array('id'=>$usr->getId())));
                }
                
                if(!$caldea){

                $request->getSession()->getFlashBag()->add(
                'notice',
                'Usted no es Coordinador de Esta Aldea'
                );
                return $this->redirect($this->generateUrl('usuario_show',array('id'=>$usr->getId())));
                }
        
                            $em->remove($docente);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Docente Borrado con con Éxito'
                            );            
                return $this->redirect($this->generateUrl('aldea_show',array('id'=>$idaldea)));    
        
        }
        
        public function updateAction(Request $request,$iddoc)
	{       
	$em = $this->getDoctrine()->getManager();
                
                $docente = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Docente')
                ->find($iddoc);
                
                if(!$docente){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            "Docente con ID:$idusr NO Registrado"
                );
                return $this->redirect($this->generateUrl('aldea_show',array('id'=>$idaldea)));
                }
                
                $idaldea = $docente->getAldea()->getId();
                
                $aldea = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Aldea')
                ->find($idaldea);
                
                $usr = $this->getUser();
                
                $caldea = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:CoordinadorAldea')
                ->findCoordinadorAldeaByUserAndId($idaldea,$usr->getId());
                
                if(!$aldea){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Aldea con ID: '.$idaldea.' no registrada'
                );
                return $this->redirect($this->generateUrl('usuario_show',array('id'=>$usr->getId())));
                }
            
                if($usr->getTipUsr()!=5){

                $request->getSession()->getFlashBag()->add(
                'notice',
                'Usuario No es de Tipo Coordinador(a) de Aldea'
                );
                return $this->redirect($this->generateUrl('usuario_show',array('id'=>$usr->getId())));
                }
                
                if(!$caldea){

                $request->getSession()->getFlashBag()->add(
                'notice',
                'Usted no es Coordinador de Esta Aldea'
                );
                return $this->redirect($this->generateUrl('usuario_show',array('id'=>$usr->getId())));
                }
                
                $userdocente = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:User')
                ->find($docente->getUser()->getId());
                 
                $cargahoraria = $em->getRepository('MisionSucreRipesBundle:Docente')->findByUser($iddoc);
                 
                $horas=array();
        
                foreach(range(1,20) as $n)
                    $horas[$n]=$n;
                
		$form = $this->createFormBuilder($docente)->
        add('unidadescurriculares', 'textarea', array(
        'label' => 'Unidades Curriculares'))->
        add('pnfs', 'textarea', array(
        'label' => 'PNFs que Trabaja'))->
                        add('componentedocente', 'choice', array(
        'choices' => array('Si'=>'Si','No'=>'No'),'label' => 'Componente Docente','placeholder'=>'Seleccione'
            ))-> 
        add('horas', 'choice', array('choices' => $horas,
            'placeholder'=>"Seleccione una",'label' => 'Horas Totales'))->
        add('exclusividad', 'choice', array(
        'choices' => array('Si'=>'Si','No'=>'No'),'label' => 'Trabajo Sólo en esta Aldea','placeholder'=>'Seleccione'
            ))->
        add('periodoingreso', 'text', array('label' => 'Periodo de Ingreso a la Misión Sucre'))
        
                        ->add('save', 'submit',array('label' => 'Actualizar Docente'))->getForm();
                
                $form->handleRequest($request);
                
		if ($form->isValid()) {
                            
                                
                            $docente->setAldea($aldea);    
                            $docente->setUser($userdocente);    
                            $em->persist($docente);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Docente Actualizado con Éxito'
                            );            
                    return $this->redirect($this->generateUrl('aldea_show',array('id'=>$idaldea)));
		}
		
                $per =  $em->getRepository('MisionSucreRipesBundle:Persona')->findOneByUser($docente->getUser()->getId());
               
		return $this->render('MisionSucreRipesBundle:Docente:new.html.twig', array(
		'form' => $form->createView(),'mensaje_heading'=>'Actualizar Datos<br> Docente',
                    'sub_heading'=>'Aldea Universitaria <br>'.$aldea->getNombre(),'usr'=>$userdocente,
                    'per' => $per,'cargahoraria'=>$cargahoraria
		));    
        
        }
        
        public function listaAction(Request $request)
	{   
    
            $docentes = $this->DocentesAldeas();
                
		if(!$docentes){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Ningun Docente Vinculado'
            );
            return $this->redirect($this->generateUrl('Docente'));
            }
            
		return $this->render(
			'MisionSucreRipesBundle:Docente:lista.html.twig',
                array('docentes' => $docentes)
		);	
    }
    
    public function buscarAction(Request $request)
	{       
		return $this->render(
			'MisionSucreRipesBundle:Docente:buscar.html.twig',
                        array('municipios'=>$this->MunicipiosEje())
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
    
    public function DocentesAldeas()
    {   
        $em = $this->getDoctrine()->getManager();
        
        $docentes= array();
        
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
            $docentes = $em->getRepository('MisionSucreRipesBundle:Docente')->findByAldea($coordinador->getAldea()->getId());
            break;
        case 8:    
        
        $usreje = $em->getRepository('MisionSucreRipesBundle:CoordinadorEje')->findOneByUser($user->getId());
        $docentes = $em->getRepository('MisionSucreRipesBundle:Docente')->findAllOrderedByEje($usreje->getEje()->getId());
        break;
        case 1:
        $docentes = $em->getRepository('MisionSucreRipesBundle:Docente')->findAllOrderedByUser();
        break;
        }
        return $docentes;
    }
    
    public function asignardocenteAction(Request $request)
	{       
                $em = $this->getDoctrine()->getManager();
                
                $usr = $this->getUser();
                
                $caldea = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:CoordinadorAldea')
                ->findOneByUser($usr->getId());
                
                
                if($usr->getTipUsr()!=5){

                $request->getSession()->getFlashBag()->add(
                'notice',
                'Usuario No es de Tipo Coordinador(a) de Aldea'
                );
                return $this->redirect($this->generateUrl('usuario_show',array('id'=>$usr->getId())));
                }
                
                if(!$caldea){

                $request->getSession()->getFlashBag()->add(
                'notice',
                'Usted no es Coordinador de Esta Aldea'
                );
                return $this->redirect($this->generateUrl('usuario_show',array('id'=>$usr->getId())));
                }
                
                 $aldea = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Aldea')
                ->find($caldea->getAldea()->getId());
                 
		return $this->render(
			'MisionSucreRipesBundle:Docente:buscardocente.html.twig',
                        array('aldea'=>$aldea)
		);	
	}
        
        public function busquedadocenteAction($param)
	{       
            
                $parametros=json_decode($param,true);
                
                $query=$parametros['query'];
                
                $em = $this->getDoctrine()->getManager();
                
                $result = $em->createQuery(
                        "SELECT DISTINCT u.tip_usr, p.priNom, p.segNom, p.priApe, p.segApe, p.cedPer, p.sexPer,u.id,
                            u.username
                            FROM MisionSucreRipesBundle:Persona p JOIN p.user u
                        WHERE p.cedPer LIKE :ced AND p.priApe LIKE :ape AND u.tip_usr = 7 AND NOT EXISTS
                   ( SELECT d FROM MisionSucreRipesBundle:Docente d WHERE d.aldea=:aldea )" 
                        )->setParameters(array('ced'=>"%{$query['cedula']}%",'ape'=>"%{$query['apellido']}%",
                                'aldea'=>"%{$query['idaldea']}%"
                                ))->getResult();
                
		return $this->render(
			'MisionSucreRipesBundle:Docente:listaasignar.html.twig',
                        array('filas'=>$result,'idaldea'=>$query['idaldea'])
		);	
	}
        public function busquedaAction($param)
	{       
            
                $parametros=json_decode($param,true);
                
                $query=$parametros['query'];
                
                $em = $this->getDoctrine()->getManager();
                
                if($query['aldea']){
                    $queryaldea="doc.aldea=:aldea";
                }
                else
                {
                    $queryaldea="doc.aldea != :aldea";
                }
                    
                if($query['municipio']=="")
                    $query['municipio']="%%";
                 if($query['parroquia']=="")
                    $query['parroquia']="%%";
                 
                $result = $em->createQuery(
                        "SELECT  IDENTITY(p.user) AS u, p.priNom,p.segNom, p.priApe, p.segApe, p.cedPer, p.sexPer
                    ,p.celPer,p.telPer, doc AS d FROM
                    MisionSucreRipesBundle:Docente doc JOIN doc.aldea a JOIN a.parroquia prq JOIN prq.municipio m, MisionSucreRipesBundle:Persona p
                    WHERE doc.user = p.user AND $queryaldea AND m.id LIKE :mun AND prq.id LIKE :prq
                        AND doc.unidadescurriculares LIKE :ucs AND doc.pnfs LIKE :pnfs AND doc.exclusividad LIKE :exclusividad
                    AND p.cedPer LIKE :ced AND p.priNom LIKE :nom AND p.priApe LIKE :ape
                    
                    " )->setParameters(array('ced'=>"%{$query['cedula']}%",'ape'=>"%{$query['apellido']}%",'nom'=>"%{$query['nombre']}%",
                            'aldea'=>"{$query['aldea']}",'mun'=>"{$query['municipio']}", 'prq'=>"{$query['parroquia']}",
                                    'ucs'=>"%{$query['ucs']}%",'pnfs'=>"%{$query['pnfs']}%",'exclusividad'=>"%{$query['exclusividad']}%"
                        ))->getResult();
                
		return $this->render(
			'MisionSucreRipesBundle:Docente:listabusqueda.html.twig',
                        array('docentes'=>$result)
		);	
	}
    public function showAction(Request $request, $id){
            
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
                
                $docente = $em->getRepository('MisionSucreRipesBundle:Docente')->findOneByUser($id);
                
                if($docente)   {
                switch($usr->getTipUsr()){
                    
                    case 8:
                        $coordeje = $this->getDoctrine()
                        ->getRepository('MisionSucreRipesBundle:CoordinadorEje')
                        ->findOneByUser($usr->getId());
                        
                        $eje = $coordeje->getEje()->getId();
                        
                        if($eje!=$docente->getAldea()->getParroquia()->getEje()->getId()){
                        $request->getSession()->getFlashBag()->add(
                        'notice',
                        'Acceso Retringido a este Eje'
                        );
                        return $this->redirect($this->generateUrl('usuario'));    
                        } 
                        break;
                    case 5:
                        $coordaldea = $this->getDoctrine()
                        ->getRepository('MisionSucreRipesBundle:CoordinadorAldea')
                        ->findOneByUser($usr->getId());
                        
                        
                        $docente_aldea = $em->getRepository('MisionSucreRipesBundle:Docente')->findByAldeaAndUser($coordaldea->getAldea()->getId(),$docente->getUser()->getId());
                        
                        if(!$docente_aldea)
                        {
                          $request->getSession()->getFlashBag()->add(
                        'notice',
                        'Docente no pertenece a esta Aldea'
                        );
                        return $this->redirect($this->generateUrl('usuario'));  
                        }
                        break;
                       
                }
                }
                $usuario =  $em->getRepository('MisionSucreRipesBundle:Role')->findOneByRole($id);    
                $sociales =  $em->getRepository('MisionSucreRipesBundle:Social')->findOneByUser($id); 
                $enfermedades =  $usr->getEnfermedades()->getValues();
                $discapacidad =  $em->getRepository('MisionSucreRipesBundle:Discapacidad')->findOneByUser($id);       
                $academico =  $em->getRepository('MisionSucreRipesBundle:Academico')->findOneByUser($id);
                $arte =  $em->getRepository('MisionSucreRipesBundle:Arte')->findOneByUser($id);       
                $deporte =  $em->getRepository('MisionSucreRipesBundle:Deporte')->findOneByUser($id);       
                $trabajo =  $em->getRepository('MisionSucreRipesBundle:Trabajo')->findOneByUser($id);       
                $comunitaria =  $em->getRepository('MisionSucreRipesBundle:ParticipacionComunitaria')->findOneByUser($id);       
                $politica =  $em->getRepository('MisionSucreRipesBundle:ParticipacionPolitica')->findOneByUser($id);   
                $ubicacionvivienda = $em->getRepository('MisionSucreRipesBundle:UbicacionVivienda')->findOneByUser($id); 
                
                return $this->render(
			'MisionSucreRipesBundle:Docente:show.html.twig',
			array('usuario' => $usuario,'persona' => $per,'sociales'=> $sociales,'enfermedades'=>$enfermedades,
                        'discapacidad'=>$discapacidad,'arte'=>$arte,'deporte'=>$deporte,'trabajo'=>$trabajo,
                            'comunitaria'=>$comunitaria,'politica'=>$politica,'academico'=>$academico,'docente'=>$docente,
                            'ubicacionvivienda'=>$ubicacionvivienda
                        )
		);
        }
    
    public function resumenAction(Request $request)
	{       
                $em = $this->getDoctrine()->getManager();
                
                $usr = $this->getUser();
                
                switch($usr->getTipUsr()){
                    
                    case 1:
                        $cantidadporaldeas = $em->createQuery(
                        "SELECT a.nombre, COUNT (DISTINCT doc.user) as cantidad FROM MisionSucreRipesBundle:Docente doc JOIN doc.aldea a
                            GROUP BY a.id
                        ")
                        ->getResult();
                 
                        $cantidadporeje = $em->createQuery(
                        "SELECT e.nombre, COUNT (DISTINCT doc.user) as cantidad FROM MisionSucreRipesBundle:Docente doc JOIN doc.aldea a
                            JOIN a.parroquia prq JOIN prq.eje e
                            GROUP BY e.id
                        ")
                        ->getResult();
                        
                        
                        $cantidadpormunicipio = $em->createQuery(
                        "SELECT m.municipio, COUNT (DISTINCT doc.user) as cantidad FROM MisionSucreRipesBundle:Docente doc JOIN doc.aldea a
                            JOIN a.parroquia prq JOIN prq.municipio m
                            GROUP BY m.id
                        ")
                        ->getResult();
                        
                    return $this->render(
			'MisionSucreRipesBundle:Docente:resumeneje.html.twig',
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
                        "SELECT a.nombre, COUNT (DISTINCT doc) as cantidad FROM MisionSucreRipesBundle:Docente doc JOIN doc.aldea a
                            JOIN a.parroquia prq
                            WHERE prq.eje =:eje
                            GROUP BY a.id
                        ")
                        ->setParameters(array('eje'=>$eje))
                                ->getResult();
                        
                        $cantidadpormunicipio = $em->createQuery(
                        "SELECT m.municipio, COUNT (DISTINCT doc) as cantidad FROM MisionSucreRipesBundle:Docente doc JOIN doc.aldea a
                            JOIN a.parroquia prq JOIN prq.municipio m
                            WHERE prq.eje =:eje
                            GROUP BY m.id
                        ")
                        ->setParameters(array('eje'=>$eje))
                        ->getResult();
                        
                    return $this->render(
			'MisionSucreRipesBundle:Docente:resumenmunicipio.html.twig',
                        array(
                            'cxaldeas'=>$cantidadporaldeas,
                            'cxmunicipios'=>$cantidadpormunicipio
                        ));
                    break;
                    
                    case 5:
                         
                        return $this->redirect($this->generateUrl('aldea_info'));
                        
                    break;
                
                }
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
                
                $docente = $em->getRepository('MisionSucreRipesBundle:Docente')->findByUser($id);
                
                if($docente)    {
                $es_coordeje = 0;
                $es_coordaldea = 0;
                
                foreach ($docente as $doc) {
                
                $ideje = $doc->getAldea()->getParroquia()->getEje()->getId();
                $aldea = $doc->getAldea();
                
                switch($usr->getTipUsr()){
                    
                    case 1:
                        $es_coordeje = 1;
                        $es_coordaldea = 1;
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
                        
                        if($coordeje->getEje()->id()==$ideje)
                        {
                            
                        $es_coordeje=1;   
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
                        
                        if($coordaldea->getAldea()->getId()==$aldea->getId())
                        {
                        $es_coordaldea=1;   
                        }
                        break;
                }
                
                }
                }
                switch($usr->getTipUsr()){
                    
                    case 8:
                    if(!$es_coordeje>0){
                    $request->getSession()->getFlashBag()->add(
                        'notice',
                        'Acceso Denegado a este Eje'
                        );
                        return $this->redirect($this->generateUrl('usuario_lista'));}
                    break;
                    
                    case 5:
                    if(!$es_coordaldea>0){
                    $request->getSession()->getFlashBag()->add(
                        'notice',
                        'Acceso Denegado a esta Aldea'
                        );
                        return $this->redirect($this->generateUrl('usuario_lista'));     }
                    break;
                }
                
                $usuario =  $em->getRepository('MisionSucreRipesBundle:Role')->findOneByRole($id);    
                $sociales =  $em->getRepository('MisionSucreRipesBundle:Social')->findOneByUser($id); 
                $enfermedades =  $usr->getEnfermedades()->getValues();
                $discapacidad =  $em->getRepository('MisionSucreRipesBundle:Discapacidad')->findOneByUser($id);       
                $academico =  $em->getRepository('MisionSucreRipesBundle:Academico')->findOneByUser($id);
                $arte =  $em->getRepository('MisionSucreRipesBundle:Arte')->findOneByUser($id);       
                $deporte =  $em->getRepository('MisionSucreRipesBundle:Deporte')->findOneByUser($id);       
                $trabajo =  $em->getRepository('MisionSucreRipesBundle:Trabajo')->findOneByUser($id);       
                $comunitaria =  $em->getRepository('MisionSucreRipesBundle:ParticipacionComunitaria')->findOneByUser($id);       
                $politica =  $em->getRepository('MisionSucreRipesBundle:ParticipacionPolitica')->findOneByUser($id);    
                $ubicacionvivienda = $em->getRepository('MisionSucreRipesBundle:UbicacionVivienda')->findOneByUser($id); 
                
                return $this->render(
			'MisionSucreRipesBundle:Docente:show.html.twig',
			array('usuario' => $usuario,'persona' => $per,'sociales'=> $sociales,'enfermedades'=>$enfermedades,
                        'discapacidad'=>$discapacidad,'arte'=>$arte,'deporte'=>$deporte,'trabajo'=>$trabajo,
                            'comunitaria'=>$comunitaria,'politica'=>$politica,'academico'=>$academico,'docente'=>$docente,
                            'ubicacionvivienda'=>$ubicacionvivienda
                        )
		);
        }  
    
    public function infoacademicaAction(Request $request){
            
                $usr = $this->getUser();
                
                $id = $usr->getId();
                        
                $em = $this->getDoctrine()->getManager();
                
                $docente = $em->getRepository('MisionSucreRipesBundle:Docente')->findByUser($id);
                
                if(!$docente){
                    $request->getSession()->getFlashBag()->add(
                        'notice',
                        'Usuario no es Docente o No esta Registrado en una Aldea'
                        );
                        return $this->redirect($this->generateUrl('home'));
                }
                
                return $this->render(
			'MisionSucreRipesBundle:Docente:showacademica.html.twig',
			array('docente'=>$docente
                        )
		);
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
                
                $tipodeusuario=array('Docentes',7);
                
                
		return $this->render(
			'MisionSucreRipesBundle:Usuario:cargar.html.twig',
                        array('form' => $form->createView(),'tipousuario'=>$tipodeusuario)
		);	
	}    
}
