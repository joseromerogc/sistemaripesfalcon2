<?php

namespace MisionSucre\RipesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MisionSucre\RipesBundle\Entity\Operario;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;

class OperarioController extends Controller
{
    public function indexAction()
    {
        return $this->render('MisionSucreRipesBundle:Usuario:index.html.twig');
    }
	
	public function newAction(Request $request,$idaldea,$idusr)
	{       
		$operario = new Operario();
                
                $em = $this->getDoctrine()->getManager();
                
                $reg_operario = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Operario')
                ->find($idusr);
                
                if($reg_operario){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            "Operario con ID: $idusr Registrado"
                );
                return $this->redirect($this->generateUrl('usuario_show',array('id'=>$idusr)));
                }
            
                $useroperario = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:User')
                ->find($idusr);
                
                if(!$useroperario){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            "Usuario con ID:$idusr NO Registrado"
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
                
                if($caldea->getTurno()=='a'){
                    
                    $turnos=array('n'=>'Nocturno','f'=>'Fines de Semana');
                }
                elseif($caldea->getTurno()=='n'){
                    $turnos=array('n'=>'Nocturno');
                }
                else{
                    $turnos=array('f'=>'Fines de Semana');
                }
                
                
		$form = $this->createFormBuilder($operario)->
        add('turno', 'choice', array(
        'choices' => $turnos,
            'placeholder'=>"Seleccione una",'label' => 'Turno'))->
        add('cargo', 'text', array(
        'label' => 'Cargo o Función'))->
        add('estudia', 'choice', array(
        'choices' => array("Si"=>'Si',"No"=>'No'),
            'placeholder'=>"Seleccione una",'label' => 'Estudia?'))->
                        add('save', 'submit',array('label' => 'Registrar Operario de Aldea'))->getForm();
            
                $form->handleRequest($request);
                
                
		if ($form->isValid()) {
                            
                                
                            $operario->setAldea($aldea);    
                            $operario->setUser($useroperario);    
                            $em->persist($operario);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Operario Creado con Éxito'
                            );            
                    return $this->redirect($this->generateUrl('aldea_show',array('id'=>$idaldea)));
		}
		
                $per =  $em->getRepository('MisionSucreRipesBundle:Persona')->findOneByUser($idusr);
                
                $per =  $em->getRepository('MisionSucreRipesBundle:Persona')->findOneByUser($idusr);
		return $this->render('MisionSucreRipesBundle:Operario:new.html.twig', array(
		'form' => $form->createView(),'mensaje_heading'=>'Nuevo Operario',
                    'sub_heading'=>'Aldea Universitaria <br>'.$aldea->getNombre(),'usr'=>$useroperario,
                    'per' => $per
		));
	}
        
        public function deleteAction(Request $request,$idop)
	{       
	$em = $this->getDoctrine()->getManager();
                
                $operario = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Operario')
                ->find($idop);
                
                if(!$operario){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            "Operario con ID: $idop NO Registrado"
                );
                return $this->redirect($this->generateUrl('usuario_show',array('id'=>$idop)));
                }
                
                $idaldea = $operario->getAldea()->getId();
                
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
        
                            $em->remove($operario);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Operario Borrado con  con Éxito'
                            );            
                return $this->redirect($this->generateUrl('aldea_show',array('id'=>$idaldea)));    
        
        }
        
    
    public function asignaroperarioAction(Request $request,$idaldea)
	{       
                $em = $this->getDoctrine()->getManager();
                
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
                
                  
		return $this->render(
			'MisionSucreRipesBundle:Operario:buscaroperario.html.twig',
                        array('aldea'=>$aldea)
		);	
	}
        
        public function busquedaoperarioAction($param)
	{       
            
                $parametros=json_decode($param,true);
                
                $query=$parametros['query'];
                
                $em = $this->getDoctrine()->getManager();
                
                $result = $em->createQuery(
                        "SELECT DISTINCT u.tip_usr, p.priNom, p.segNom, p.priApe, p.segApe, p.cedPer, p.sexPer,u.id,
                            u.username
                            FROM MisionSucreRipesBundle:Persona p JOIN p.user u
                        WHERE p.cedPer LIKE :ced AND p.priApe LIKE :ape AND u.tip_usr = 9 AND NOT EXISTS
                   ( SELECT o FROM MisionSucreRipesBundle:Operario o WHERE u.id=o.user)" 
                        )->setParameters(array('ced'=>"%{$query['cedula']}%",'ape'=>"%{$query['apellido']}%"))->getResult();
                
		return $this->render(
			'MisionSucreRipesBundle:Operario:listaasignar.html.twig',
                        array('filas'=>$result,'idaldea'=>$query['idaldea'])
		);	
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
                
                $operario = $em->getRepository('MisionSucreRipesBundle:Operario')->findOneByUser($id);
                
                if($operario){
                $ideje = $operario->getAldea()->getParroquia()->getEje()->getId();
                $aldea = $operario->getAldea();
                
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
                }
                }
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
                
                if($operario){ 
                if($operario->getTurno()=='n')
                
                {
                    $turno = "Nocturno";
                }
                else{
                    $turno = "Fines de Semana";
                }
                }
                else{
                    $turno =null;
                }
                    
                return $this->render(
			'MisionSucreRipesBundle:Operario:show.html.twig',
			array('usuario' => $usuario,'persona' => $per,'sociales'=> $sociales,'enfermedades'=>$enfermedades,
                        'discapacidad'=>$discapacidad,'arte'=>$arte,'deporte'=>$deporte,'trabajo'=>$trabajo,
                            'comunitaria'=>$comunitaria,'politica'=>$politica,'academico'=>$academico,'operario'=>$operario,
                            'turno'=>$turno,'ubicacionvivienda'=>$ubicacionvivienda
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
                
                $operario = $em->getRepository('MisionSucreRipesBundle:Operario')->findOneByUser($id);
                
                if($operario){
                $ideje = $operario->getAldea()->getParroquia()->getEje()->getId();
                $aldea = $operario->getAldea();
                
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
                
                if($operario->getTurno()=='n')
                {
                    $turno = "Nocturno";
                }
                else{
                    $turno = "Fines de Semana";
                }
                
                return $this->render(
			'MisionSucreRipesBundle:Operario:show.html.twig',
			array('usuario' => $usuario,'persona' => $per,'sociales'=> $sociales,'enfermedades'=>$enfermedades,
                        'discapacidad'=>$discapacidad,'arte'=>$arte,'deporte'=>$deporte,'trabajo'=>$trabajo,
                            'comunitaria'=>$comunitaria,'politica'=>$politica,'academico'=>$academico,'operario'=>$operario,
                            'turno'=>$turno,'ubicacionvivienda'=>$ubicacionvivienda
                        )
		);
        }        
        
}

public function listaAction(Request $request)
	{   
    
            $operarios = $this->OperariosAldeas();
                
		if(!$operarios){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Ningun Operario Vinculado'
            );
            return $this->redirect($this->generateUrl('Operario'));
            }
            
		return $this->render(
			'MisionSucreRipesBundle:Operario:lista.html.twig',
                array('operarios' => $operarios)
		);	
    }
    
    public function OperariosAldeas()
    {   
        $em = $this->getDoctrine()->getManager();
        
        $operarios= array();
        
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
            $operarios = $em->getRepository('MisionSucreRipesBundle:Operario')->findAllByAldea($coordinador->getAldea()->getId());
            break;
        case 8:    
        
        $usreje = $em->getRepository('MisionSucreRipesBundle:CoordinadorEje')->findOneByUser($user->getId());
        $operarios = $em->getRepository('MisionSucreRipesBundle:Operario')->findAllOrderedByEje($usreje->getEje()->getId());
        break;
        case 1:
        $operarios = $em->getRepository('MisionSucreRipesBundle:Operario')->findAllOrderedByUser();
        break;
        }
        return $operarios;
    }
    
public function buscarAction(Request $request)
	{       
		return $this->render(
			'MisionSucreRipesBundle:Operario:buscar.html.twig',
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
    
    public function busquedaAction($param)
	{       
            
                $parametros=json_decode($param,true);
                
                $query=$parametros['query'];
                
                $em = $this->getDoctrine()->getManager();
                
                if($query['aldea']){
                    $queryaldea="op.aldea=:aldea";
                }
                else
                {
                    $queryaldea="op.aldea != :aldea";
                }
                    
                if($query['municipio']=="")
                    $query['municipio']="%%";
                 if($query['parroquia']=="")
                    $query['parroquia']="%%";
                 
                $result = $em->createQuery(
                        "SELECT  IDENTITY(p.user) AS u, p.priNom,p.segNom, p.priApe, p.segApe, p.cedPer, p.sexPer
                    ,p.celPer,p.telPer, op AS o FROM
                    MisionSucreRipesBundle:Operario op JOIN op.aldea a JOIN a.parroquia prq JOIN prq.municipio m, MisionSucreRipesBundle:Persona p
                    WHERE op.user = p.user AND $queryaldea AND m.id LIKE :mun AND prq.id LIKE :prq
                        AND op.turno LIKE :turno AND op.cargo LIKE :cargo 
                    AND p.cedPer LIKE :ced AND p.priNom LIKE :nom AND p.priApe LIKE :ape
                    
                    " )->setParameters(array('ced'=>"%{$query['cedula']}%",'ape'=>"%{$query['apellido']}%",'nom'=>"%{$query['nombre']}%",
                            'aldea'=>"{$query['aldea']}",'mun'=>"{$query['municipio']}", 'prq'=>"{$query['parroquia']}",
                                    'turno'=>"%{$query['turno']}%",'cargo'=>"%{$query['cargo']}%"
                        ))->getResult();
                
		return $this->render(
			'MisionSucreRipesBundle:Operario:listabusqueda.html.twig',
                        array('operarios'=>$result)
		);	
	}
        
    public function resumenAction(Request $request)
	{       
                $em = $this->getDoctrine()->getManager();
                
                $usr = $this->getUser();
                
                switch($usr->getTipUsr()){
                    
                    case 1:
                        $cantidadporaldeas = $em->createQuery(
                        "SELECT a.nombre, COUNT (DISTINCT ope.user) as cantidad FROM MisionSucreRipesBundle:Operario ope JOIN ope.aldea a
                            GROUP BY a.id
                        ")
                        ->getResult();
                 
                        $cantidadporeje = $em->createQuery(
                        "SELECT e.nombre, COUNT (DISTINCT ope.user) as cantidad FROM MisionSucreRipesBundle:Operario ope JOIN ope.aldea a
                            JOIN a.parroquia prq JOIN prq.eje e
                            GROUP BY e.id
                        ")
                        ->getResult();
                        
                        
                        $cantidadpormunicipio = $em->createQuery(
                        "SELECT m.municipio, COUNT (DISTINCT ope.user) as cantidad FROM MisionSucreRipesBundle:Operario ope JOIN ope.aldea a
                            JOIN a.parroquia prq JOIN prq.municipio m
                            GROUP BY m.id
                        ")
                        ->getResult();
                        
                    return $this->render(
			'MisionSucreRipesBundle:Operario:resumeneje.html.twig',
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
                        "SELECT a.nombre, COUNT (DISTINCT ope) as cantidad FROM MisionSucreRipesBundle:Operario ope JOIN ope.aldea a
                            JOIN a.parroquia prq
                            WHERE prq.eje =:eje
                            GROUP BY a.id
                        ")
                        ->setParameters(array('eje'=>$eje))
                                ->getResult();
                        
                        $cantidadpormunicipio = $em->createQuery(
                        "SELECT m.municipio, COUNT (DISTINCT ope) as cantidad FROM MisionSucreRipesBundle:Operario ope JOIN ope.aldea a
                            JOIN a.parroquia prq JOIN prq.municipio m
                            WHERE prq.eje =:eje
                            GROUP BY m.id
                        ")
                        ->setParameters(array('eje'=>$eje))
                        ->getResult();
                        
                    return $this->render(
			'MisionSucreRipesBundle:Operario:resumenmunicipio.html.twig',
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
    
}