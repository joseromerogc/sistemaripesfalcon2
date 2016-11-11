<?php
namespace MisionSucre\RipesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MisionSucre\RipesBundle\Form\Type\ParticipacionComunitariaType;
use MisionSucre\RipesBundle\Form\Type\ParticipacionPoliticaType;
use MisionSucre\RipesBundle\Entity\ParticipacionComunitaria;
use MisionSucre\RipesBundle\Entity\ParticipacionPolitica;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;

class ParticipacionController extends Controller
{
    public function indexAction()
    {
        return $this->render('MisionSucreRipesBundle:Sociales:index.html.twig');
    }	
        
/*
 * PARTICIPACION COMUNITARIA
 */  
           public function newcomunitariaAction(Request $request,$id)
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
                    return $this->redirect($this->generateUrl('usuario_new',array('id'=>$id)));
                    }
                
                $comunitaria = $em->getRepository('MisionSucreRipesBundle:ParticipacionComunitaria')->findByUser($id);
                
                if($comunitaria)
                {   
                    $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Datos Sociales ya Registrados '
                            );            
                    return $this->redirect("../../admin/usuario/show/$id");
                    
                }
                
                
		// create a task and give it some dummy data for this example
                $c= new ParticipacionComunitaria();
                
                $c->setVoceria('Ninguna');
                $c->setOrganizacionSocial('Ninguna');
                
                $viv =  $em->getRepository('MisionSucreRipesBundle:UbicacionVivienda')->findOneByUser($id);
                
                if(!$viv){
                    
                   $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Por Favor Registrar Vivienda'
                            );            
                    return $this->redirect($this->generateUrl('ubicacion_vivienda_new',array('id'=>$usr->getId()))); 
                }
                
                $sector=$viv->GetSector()->GetParroquia()->GetId();
                
                $ccs= $this->ConsejosComunales($sector);
                $misiones = $this->Misiones();
                ksort($ccs);
                ksort($misiones);
                
                $ningunamision =  $em->getRepository('MisionSucreRipesBundle:Mision')->find(1);
                $c->setMision($ningunamision); //Ninguna
		$form = $this->createForm(new ParticipacionComunitariaType(), $c)
                        ->add('cc', 'choice', array(
        'choices' => $ccs , 'label'=>'Consejo Comunal',
            'placeholder'=>"Seleccione una",'mapped' => false
            ))->add('mision', 'choice', array(
        'choices' => $misiones , 'label'=>'Otra Misión a la que pertenece',
            'placeholder'=>"Seleccione una",'mapped' => false,'required'=>false
            )); 
                
                $form->handleRequest($request);
                
		if ($form->isValid()) {
                            
                            $idcc = $form->get('cc')->getData();
                            $idms = $form->get('mision')->getData();
                            if($idms=="")
                                $idms=1;//valor que apunta a ninguno
                            $cc = $em->getRepository('MisionSucreRipesBundle:ConsejoComunal')->find($idcc);
                            $ms = $em->getRepository('MisionSucreRipesBundle:Mision')->find($idms);
                            
                            $c->setCc($cc);
                            $c->setMision($ms);
                            $c->setUser($usr);
                            
                            $em->persist($c);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Datos de Participación Comunitaria Registrado con Éxito'
                            );            
                    return $this->redirect($this->generateUrl('usuario_show',array('id'=>$id)));
                            
		}
		return $this->render('MisionSucreRipesBundle:Persona:newcomunitaria.html.twig', array(
		'form' => $form->createView(),'mensaje_heading'=>'Participación Comunitaria',
                    'sub_heading'=>'Registro de Datos'
		));
	}
        public function updatecomunitariaAction(Request $request,$id)
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
                    return $this->redirect($this->generateUrl('usuario_new',array('id'=>$id)));
                    }
            
            if(!$this->get('security.authorization_checker')->isGranted('ROLE_COORD')) {
                
                $user = $this->getUser();
                
                if($user->getId()!=$id){
                    $request->getSession()->getFlashBag()->add(
                    'notice',
                    'Acceso Denegado'
                    );
            return $this->redirect($this->generateUrl('usuario_show',array('id'=>$id)));
            }}
                    
            $comunitaria = $em->getRepository('MisionSucreRipesBundle:ParticipacionComunitaria')->findByUser($id);
            
                if(!$comunitaria)
                {   
                    $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Participación Comunitaria no Registrada'
                            );            
                    return $this->redirect($this->generateUrl('usuario_new',array('id'=>$id)));
                    
                }
                
                $comunitaria=$comunitaria[0];
                
		// create a task and give it some dummy data for this example
                
                $per =  $em->getRepository('MisionSucreRipesBundle:Persona')->findOneByUser($id);
                $ccs= $this->ConsejosComunales($per->GetSector()->GetParroquia()->GetId());
                $misiones = $this->Misiones();
                ksort($ccs);
                ksort($misiones);
                
		$form = $this->createForm(new ParticipacionComunitariaType(), $comunitaria)
                        ->add('cc', 'choice', array(
        'choices' => $ccs , 'label'=>'Consejo Comunal',
            'placeholder'=>"Seleccione una",'mapped' => false
            ))->add('mision', 'choice', array(
        'choices' => $misiones , 'label'=>'Otra Misión a la que pertenece',
            'placeholder'=>"Seleccione una",'mapped' => false,'required'=>false
            )); 
                
                $form->handleRequest($request);
		if ($form->isValid()) {
                            
                            $idcc = $form->get('cc')->getData();
                            $idms = $form->get('mision')->getData();
                            if($idms=="")
                                $idms=1;//valor que apunta a ninguno
                            $cc = $em->getRepository('MisionSucreRipesBundle:ConsejoComunal')->find($idcc);
                            $ms = $em->getRepository('MisionSucreRipesBundle:Mision')->find($idms);
                            
                            $comunitaria->setCc($cc);
                            $comunitaria->setMision($ms);
                            $comunitaria->setUser($usr);
                            
                            $em->persist($comunitaria);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Participación Comunitaria Actualizada con Éxito'
                            );            
                    return $this->redirect("../../admin/usuario/show/$id");
                            
		}
		
               
		return $this->render('MisionSucreRipesBundle:Persona:newcomunitaria.html.twig', array(
		'form' => $form->createView(),'mensaje_heading'=>'Actualización de Datos',
                    'sub_heading'=>'Datos de Participación Comunitaria'
		));
	}
        public function deletecomunitariaAction(Request $request,$id,$id_com)
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
            
            return $this->redirect($this->generateUrl('usuario_new',array('id'=>$id)));
            }
            
            $comunitaria= $em->getRepository('MisionSucreRipesBundle:ParticipacionComunitaria')->find($id_com);
            
            if(!$comunitaria)
                {   
                    $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Participación Comunitaria no Registrada'
                            );            
                    return $this->redirect($this->generateUrl('usuario_new',array('id'=>$id)));
                    
                }
                            
                            $em->remove($comunitaria);
                            
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Participación Comunitaria Borrada con Éxito'
                            );

                    return $this->redirect($this->generateUrl('usuario_show',array('id'=>$id)));
		}             
     
/*
 * PARTICIPACION POLITICA
 */  
           public function newpoliticaAction(Request $request,$id)
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
                    return $this->redirect($this->generateUrl('usuario_new',array('id'=>$id)));
                    }
                
                $politica = $em->getRepository('MisionSucreRipesBundle:ParticipacionPolitica')->findByUser($id);
                
                if($politica)
                {   
                    $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Datos ya Registrados '
                            );            
                    return $this->redirect("../../admin/usuario/show/$id");
                    
                }
                
		// create a task and give it some dummy data for this example
                $p= new ParticipacionPolitica();
                
		$form = $this->createForm(new ParticipacionPoliticaType(), $p)
                        ->add('municipio', 'choice', array(
        'choices' => $this->Municipios() , 'label'=>'Municipios de Centro Electoral',
            'placeholder'=>"Seleccione una",'mapped' => false
            ));
                
                $form->handleRequest($request);
                
		if ($form->isValid()) {
                            
                            $idubch = $form->get('ubch')->getData();
                            
                            $ubch = $em->getRepository('MisionSucreRipesBundle:Ubch')->find($idubch);
                               
                            $p->setUbch($ubch);
                            $p->setUser($usr);
                            $em->persist($p);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Datos de Participación Politica Registrado con Éxito'
                            );            
                    return $this->redirect($this->generateUrl('usuario_show',array('id'=>$id)));          
		}
		return $this->render('MisionSucreRipesBundle:Persona:newpolitica.html.twig', array(
		'form' => $form->createView(),'mensaje_heading'=>'Participación Politica',
                    'sub_heading'=>'Registro de Datos'
		));
	}
        public function updatepoliticaAction(Request $request,$id)
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
                    return $this->redirect($this->generateUrl('usuario_new',array('id'=>$id)));
                    }
                    
            if(!$this->get('security.authorization_checker')->isGranted('ROLE_COORD')) {
                
                $user = $this->getUser();
                
                if($user->getId()!=$id){
                    $request->getSession()->getFlashBag()->add(
                    'notice',
                    'Acceso Denegado'
                    );
            return $this->redirect($this->generateUrl('usuario_show',array('id'=>$id)));
            }}
            
            $politica = $em->getRepository('MisionSucreRipesBundle:ParticipacionPolitica')->findByUser($id);
            
                if(!$politica)
                {   
                    $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Participación Comunitaria no Registrada'
                            );            
                    return $this->redirect($this->generateUrl('usuario_new',array('id'=>$id)));
                    
                }
		// create a task and give it some dummy data for this example
                $p=$politica[0];
                
		$form = $this->createForm(new ParticipacionPoliticaType(), $p)
                        ->add('municipio', 'choice', array(
        'choices' => $this->Municipios() , 'label'=>'Municipios de UBCH',
            'placeholder'=>"Seleccione una",'mapped' => false
            ));
                
                $form->handleRequest($request);
		if ($form->isValid()) {
                            
                            $idubch = $form->get('ubch')->getData();
                            
                            $ubch = $em->getRepository('MisionSucreRipesBundle:Ubch')->find($idubch);
                               
                            $p->setUbch($ubch);
                            $p->setUser($usr);
                            $em->persist($p);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Participación Política Actualizada con Éxito'
                            );            
                    return $this->redirect("../../admin/usuario/show/$id");
                            
		}
		
               
		return $this->render('MisionSucreRipesBundle:Persona:newpolitica.html.twig', array(
		'form' => $form->createView(),'mensaje_heading'=>'Actualización de Datos',
                    'sub_heading'=>'Datos de Participación Política'
		));
	}
        
        public function deletepoliticaAction(Request $request,$id,$id_pol)
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
            
            return $this->redirect($this->generateUrl('usuario_new',array('id'=>$id)));
            }
            
            $politica= $em->getRepository('MisionSucreRipesBundle:ParticipacionPolitica')->find($id_pol);
            
            if(!$politica)
                {   
                    $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Participación Política no Registrada'
                            );            
                    return $this->redirect($this->generateUrl('usuario_new',array('id'=>$id)));
                    
                }
                            
                            $em->remove($politica);
                            
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Participación Política Borrada con Éxito'
                            );

                    return $this->redirect($this->generateUrl('usuario_show',array('id'=>$id)));
		}     
                
public function listaComunidadAction(Request $request)
	{   
    
            $em = $this->getDoctrine()->getManager();
            
            $user = $this->getUser();
            
            $comunitarioscoordinadores=null;
        
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
            $comunitariostriunfadores = $em->getRepository('MisionSucreRipesBundle:ParticipacionComunitaria')->findAllOrderedByTriunfadorAndAldea($coordinador->getAldea()->getId());
            $comunitariosdocentes = $em->getRepository('MisionSucreRipesBundle:ParticipacionComunitaria')->findAllOrderedByDocentesAndAldea($coordinador->getAldea()->getId());
            $comunitariosoperarios = $em->getRepository('MisionSucreRipesBundle:ParticipacionComunitaria')->findAllOrderedByOperariosAndAldea($coordinador->getAldea()->getId());
            break;
            
            if(!$comunitariostriunfadores && !$comunitariosdocentes && !$comunitariosoperarios){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Ningun Registrado'
            );
            return $this->redirect($this->generateUrl('usuario'));
            }
            
            return $this->render(
			'MisionSucreRipesBundle:Comunitaria:lista.html.twig',
                array('comunitariostriunfadores' => $comunitariostriunfadores,
                        'comunitariosdocentes' => $comunitariosdocentes,
                     'comunitariosoperarios' => $comunitariosoperarios
                        )
		);	
            
        case 8:    
        
        $usreje = $em->getRepository('MisionSucreRipesBundle:CoordinadorEje')->findOneByUser($user->getId());
        $comunitariostriunfadores = $em->getRepository('MisionSucreRipesBundle:ParticipacionComunitaria')->findAllOrderedByTriunfadorAndEje($usreje->getEje()->getId());
        $comunitariosdocentes = $em->getRepository('MisionSucreRipesBundle:ParticipacionComunitaria')->findAllOrderedByDocentesAndEje($usreje->getEje()->getId());
        $comunitariosoperarios = $em->getRepository('MisionSucreRipesBundle:ParticipacionComunitaria')->findAllOrderedByOperariosAndEje($usreje->getEje()->getId());
        $comunitarioscoordinadores = $em->getRepository('MisionSucreRipesBundle:ParticipacionComunitaria')->findAllOrderedByCoordinadoresAndEje($usreje->getEje()->getId());
        break;
        case 1:
        $comunitariostriunfadores = $em->getRepository('MisionSucreRipesBundle:ParticipacionComunitaria')->findAllOrderedByTriunfador();
        $comunitariosdocentes = $em->getRepository('MisionSucreRipesBundle:ParticipacionComunitaria')->findAllOrderedByDocentes();
        $comunitariosoperarios = $em->getRepository('MisionSucreRipesBundle:ParticipacionComunitaria')->findAllOrderedByOperarios();
        $comunitarioscoordinadores = $em->getRepository('MisionSucreRipesBundle:ParticipacionComunitaria')->findAllOrderedByCoordinadores();
        break;
        }
           if(!$comunitariostriunfadores && !$comunitariosdocentes && !$comunitariosoperarios && !$comunitarioscoordinadores){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Ningun Registrado'
            );
            return $this->redirect($this->generateUrl('usuario'));
            }
            
	return $this->render(
			'MisionSucreRipesBundle:Comunitaria:lista.html.twig',
                array('comunitariostriunfadores' => $comunitariostriunfadores,
                        'comunitariosdocentes' => $comunitariosdocentes,
                     'comunitariosoperarios' => $comunitariosoperarios,
                     'comunitarioscoordinadores' => $comunitarioscoordinadores
                        )
		);		
            
		
}                

public function listaPoliticaAction(Request $request)
	{   
    
            $em = $this->getDoctrine()->getManager();
            
            $user = $this->getUser();
        $politicascoordinadores=null;
        
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
            $politicastriunfadores = $em->getRepository('MisionSucreRipesBundle:ParticipacionPolitica')->findAllOrderedByTriunfadorAndAldea($coordinador->getAldea()->getId());
            $politicasdocentes = $em->getRepository('MisionSucreRipesBundle:ParticipacionPolitica')->findAllOrderedByDocentesAndAldea($coordinador->getAldea()->getId());
            $politicasoperarios = $em->getRepository('MisionSucreRipesBundle:ParticipacionPolitica')->findAllOrderedByOperariosAndAldea($coordinador->getAldea()->getId());
            break;
            
        case 8:    
        
        $usreje = $em->getRepository('MisionSucreRipesBundle:CoordinadorEje')->findOneByUser($user->getId());
        $politicastriunfadores = $em->getRepository('MisionSucreRipesBundle:ParticipacionPolitica')->findAllOrderedByTriunfadorAndEje($usreje->getEje()->getId());
        $politicasdocentes = $em->getRepository('MisionSucreRipesBundle:ParticipacionPolitica')->findAllOrderedByDocentesAndEje($usreje->getEje()->getId());
        $politicasoperarios = $em->getRepository('MisionSucreRipesBundle:ParticipacionPolitica')->findAllOrderedByOperariosAndEje($usreje->getEje()->getId());
        $politicascoordinadores = $em->getRepository('MisionSucreRipesBundle:ParticipacionPolitica')->findAllOrderedByCoordinadoresAndEje($usreje->getEje()->getId());
        break;
        case 1:
        $politicastriunfadores = $em->getRepository('MisionSucreRipesBundle:ParticipacionPolitica')->findAllOrderedByTriunfador();
        $politicasdocentes = $em->getRepository('MisionSucreRipesBundle:ParticipacionPolitica')->findAllOrderedByDocentes();
        $politicasoperarios = $em->getRepository('MisionSucreRipesBundle:ParticipacionPolitica')->findAllOrderedByOperarios();
        $politicascoordinadores = $em->getRepository('MisionSucreRipesBundle:ParticipacionPolitica')->findAllOrderedByCoordinadores();
        break;
        }
        
        if(!$politicastriunfadores && !$politicasdocentes && !$politicastriunfadores){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Ningun Registrado'
            );
            return $this->redirect($this->generateUrl('usuario'));
            }
            
	return $this->render(
			'MisionSucreRipesBundle:Politica:lista.html.twig',
                array('politicastriunfadores' => $politicastriunfadores,
                        'politicasdocentes' => $politicasdocentes,
                     'politicasoperarios' => $politicasoperarios,
                     'politicascoordinadores' => $politicascoordinadores
                        )
		);	
           	
            
		
}                
/*
 * RESUMENES
 */

 public function resumenComunitariosAction(Request $request)
	{       
                $em = $this->getDoctrine()->getManager();
                
                $usr = $this->getUser();
                
                switch($usr->getTipUsr()){
                    
                    case 1:
                        $cantidadporeje = $em->createQuery(
                        "SELECT e.nombre, COUNT (pac.voceria) as cantidadvoceria
                            FROM MisionSucreRipesBundle:ParticipacionComunitaria pac, MisionSucreRipesBundle:Sector s JOIN s.parroquia prq JOIN prq.eje e,
                             MisionSucreRipesBundle:UbicacionVivienda uv JOIN uv.user u 
                             WHERE pac.user=u.id AND s.id=uv.sector AND
                            lower(pac.voceria) NOT LIKE 'ninguno' AND lower(pac.voceria) NOT LIKE 'ninguna' AND lower(pac.voceria) NOT LIKE ''
                            AND lower(pac.voceria) NOT LIKE 'no'
                            GROUP BY e.id
                        "
                        
                        )->getResult();
                        
                        $cantidadporvoceria = $em->createQuery(
                        "SELECT pac.voceria, COUNT (pac.voceria) as cantidadvoceria,
                            SUM( CASE WHEN IDENTITY(prq.eje) ='1' THEN 1 ELSE 0 END) AS centrala,
                            SUM( CASE WHEN IDENTITY(prq.eje) ='2' THEN 1 ELSE 0 END) AS centralb,
                            SUM( CASE WHEN IDENTITY(prq.eje) ='3' THEN 1 ELSE 0 END) AS centralc,
                            SUM( CASE WHEN IDENTITY(prq.eje) ='5' THEN 1 ELSE 0 END) AS costa,
                            SUM( CASE WHEN IDENTITY(prq.eje) ='4' THEN 1 ELSE 0 END) AS paraguana,
                            SUM( CASE WHEN IDENTITY(prq.eje) ='7' THEN 1 ELSE 0 END) AS occidental,
                            SUM( CASE WHEN IDENTITY(prq.eje) ='6' THEN 1 ELSE 0 END) AS sierra
                            FROM MisionSucreRipesBundle:ParticipacionComunitaria pac, MisionSucreRipesBundle:Sector s JOIN s.parroquia prq,
                             MisionSucreRipesBundle:UbicacionVivienda uv JOIN uv.user u 
                             WHERE pac.user=u.id AND s.id=uv.sector AND
                            lower(pac.voceria) NOT LIKE 'ninguno' AND lower(pac.voceria) NOT LIKE 'ninguna' AND lower(pac.voceria) NOT LIKE ''
                            AND lower(pac.voceria) NOT LIKE 'no'
                            GROUP BY pac.voceria
                        "
                        )->getResult();
                        
                        $cantidadpormunicipio = $em->createQuery(
                        "SELECT m.municipio, COUNT (pac.voceria) as cantidadvoceria
                            FROM MisionSucreRipesBundle:ParticipacionComunitaria pac, MisionSucreRipesBundle:Sector s JOIN s.parroquia prq JOIN prq.municipio m,
                             MisionSucreRipesBundle:UbicacionVivienda uv JOIN uv.user u 
                             WHERE pac.user=u.id AND s.id=uv.sector AND
                            lower(pac.voceria) NOT LIKE 'ninguno' AND lower(pac.voceria) NOT LIKE 'ninguna' AND lower(pac.voceria) NOT LIKE ''
                            AND lower(pac.voceria) NOT LIKE 'no'
                            GROUP BY m.municipio
                        "
                        
                        )->getResult();
                        
                    return $this->render(
			'MisionSucreRipesBundle:Comunitaria:resumeneje.html.twig',
                        array(
                            'cxeje'=>$cantidadporeje,
                            'cxvoceria'=>$cantidadporvoceria,
                            'cxmunicipios'=>$cantidadpormunicipio
                        ));
                    break;
                
                    case 8:
                        
                        $usreje = $em->getRepository('MisionSucreRipesBundle:CoordinadorEje')->findOneByUser($usr->getId());
                        $eje = $usreje->getEje()->getId();
                        
                        $municipios= $em->createQuery(
                        "SELECT DISTINCT m.municipio,m.id
                            FROM MisionSucreRipesBundle:ParticipacionComunitaria pac, MisionSucreRipesBundle:Sector s JOIN s.parroquia prq JOIN prq.municipio m,
                             MisionSucreRipesBundle:UbicacionVivienda uv JOIN uv.user u 
                             WHERE pac.user=u.id AND s.id=uv.sector AND
                            lower(pac.voceria) NOT LIKE 'ninguno' AND lower(pac.voceria) NOT LIKE 'ninguna' AND lower(pac.voceria) NOT LIKE ''
                            AND lower(pac.voceria) NOT LIKE 'no' AND prq.eje=:eje
                            GROUP BY m.id
                            
                        ")->setParameters(array('eje'=>$eje)
                        )->getResult();
                        
                        $cond="";
                        foreach ($municipios as $m) {
                        $cond.= ",SUM( CASE WHEN m.id ='".$m['id']."' THEN 1 ELSE 0 END) AS ".$m['municipio'];
                        
                    }
                        
                        $cantidadporvoceria = $em->createQuery(
                        "SELECT pac.voceria,COUNT (pac.voceria) as cantidadvoceria
                            $cond
                            FROM MisionSucreRipesBundle:ParticipacionComunitaria pac, MisionSucreRipesBundle:Sector s JOIN s.parroquia prq JOIN prq.municipio m,
                             MisionSucreRipesBundle:UbicacionVivienda uv JOIN uv.user u 
                             WHERE pac.user=u.id AND s.id=uv.sector AND
                            lower(pac.voceria) NOT LIKE 'ninguno' AND lower(pac.voceria) NOT LIKE 'ninguna' AND lower(pac.voceria) NOT LIKE ''
                            AND lower(pac.voceria) NOT LIKE 'no' AND prq.eje=:eje
                            GROUP BY pac.voceria
                            
                        ")->setParameters(array('eje'=>$eje)
                        )->getResult();
                        
                        $cantidadpormunicipio = $em->createQuery(
                        "SELECT m.municipio,m.id,COUNT (pac.voceria) as cantidadvoceria
                            FROM MisionSucreRipesBundle:ParticipacionComunitaria pac, MisionSucreRipesBundle:Sector s JOIN s.parroquia prq JOIN prq.municipio m,
                             MisionSucreRipesBundle:UbicacionVivienda uv JOIN uv.user u 
                             WHERE pac.user=u.id AND s.id=uv.sector AND
                            lower(pac.voceria) NOT LIKE 'ninguno' AND lower(pac.voceria) NOT LIKE 'ninguna' AND lower(pac.voceria) NOT LIKE ''
                            AND lower(pac.voceria) NOT LIKE 'no' AND prq.eje=:eje
                            GROUP BY m.municipio
                        "
                        )->setParameters(array('eje'=>$eje)
                        )->getResult();
                        
                    return $this->render(
			'MisionSucreRipesBundle:Comunitaria:resumenmunicipio.html.twig',
                        array(
                            'cxvoceria'=>$cantidadporvoceria,
                            'cxmunicipios'=>$cantidadpormunicipio,
                            'municipios' =>$municipios,
                        ));
                    break;
                    
                    case 5:
                        
                        $coord = $em->getRepository('MisionSucreRipesBundle:CoordinadorAldea')->findOneByUser($usr->getId());
                        $aldea = $coord->getAldea()->getId();
                        
                       $cantidadporvoceria = $em->createQuery(
                        "SELECT pac.voceria,
                             SUM( CASE WHEN (
                             EXISTS 
                            ( SELECT c FROM MisionSucreRipesBundle:CoordinadorAldea c WHERE u.id=c.user AND IDENTITY(c.aldea)=:aldea ) OR
                             EXISTS 
                            ( SELECT d FROM MisionSucreRipesBundle:Docente d WHERE u.id=d.user AND IDENTITY(d.aldea)=:aldea ))
                             THEN 1 ELSE 0 END) AS cantidadvoceria
                            FROM MisionSucreRipesBundle:ParticipacionComunitaria pac JOIN pac.user u
                            WHERE
                            lower(pac.voceria) NOT LIKE 'ninguno' AND lower(pac.voceria) NOT LIKE 'ninguna' AND lower(pac.voceria) NOT LIKE ''
                            AND lower(pac.voceria) NOT LIKE 'no'
                            GROUP BY pac.voceria
                            
                        ")->setParameters(array('aldea'=>$aldea)
                        )->getResult();
                        
                    return $this->render(
			'MisionSucreRipesBundle:Comunitaria:resumenaldea.html.twig',
                        array(
                            'cxvoceria'=>$cantidadporvoceria,
                        ));
                    break;
                
                }
        }    
        
 public function resumenPoliticaAction(Request $request)
	{       
                $em = $this->getDoctrine()->getManager();
                
                $usr = $this->getUser();
                
                switch($usr->getTipUsr()){
                    
                    case 1:
                        $cantidadporeje = $em->createQuery(
                        "SELECT e.nombre, COUNT (p.cargo) as cantidadcargo
                            FROM MisionSucreRipesBundle:ParticipacionPolitica p, MisionSucreRipesBundle:Sector s JOIN s.parroquia prq JOIN prq.eje e,
                             MisionSucreRipesBundle:UbicacionVivienda uv JOIN uv.user u 
                             WHERE p.user=u.id AND s.id=uv.sector AND
                            lower(p.cargo) NOT LIKE 'ninguno' AND lower(p.cargo) NOT LIKE 'ninguna' AND lower(p.cargo) NOT LIKE ''
                            AND lower(p.cargo) NOT LIKE 'no'
                            GROUP BY e.id
                        "
                        
                        )->getResult();
                        
                        $cantidadporvoceria = $em->createQuery(
                        "SELECT p.cargo, COUNT (p.cargo) as cantidadcargo,
                            SUM( CASE WHEN IDENTITY(prq.eje) ='1' THEN 1 ELSE 0 END) AS centrala,
                            SUM( CASE WHEN IDENTITY(prq.eje) ='2' THEN 1 ELSE 0 END) AS centralb,
                            SUM( CASE WHEN IDENTITY(prq.eje) ='3' THEN 1 ELSE 0 END) AS centralc,
                            SUM( CASE WHEN IDENTITY(prq.eje) ='5' THEN 1 ELSE 0 END) AS costa,
                            SUM( CASE WHEN IDENTITY(prq.eje) ='4' THEN 1 ELSE 0 END) AS paraguana,
                            SUM( CASE WHEN IDENTITY(prq.eje) ='7' THEN 1 ELSE 0 END) AS occidental,
                            SUM( CASE WHEN IDENTITY(prq.eje) ='6' THEN 1 ELSE 0 END) AS sierra
                            FROM MisionSucreRipesBundle:ParticipacionPolitica p, MisionSucreRipesBundle:Sector s JOIN s.parroquia prq,
                             MisionSucreRipesBundle:UbicacionVivienda uv JOIN uv.user u 
                             WHERE p.user=u.id AND s.id=uv.sector AND
                            lower(p.cargo) NOT LIKE 'ninguno' AND lower(p.cargo) NOT LIKE 'ninguna' AND lower(p.cargo) NOT LIKE ''
                            AND lower(p.cargo) NOT LIKE 'no'
                            GROUP BY p.cargo
                        "
                        )->getResult();
                        
                        $cantidadpormunicipio = $em->createQuery(
                        "SELECT m.municipio, COUNT (p.cargo) as cantidadcargo
                            FROM MisionSucreRipesBundle:ParticipacionPolitica p, MisionSucreRipesBundle:Sector s JOIN s.parroquia prq JOIN prq.municipio m,
                             MisionSucreRipesBundle:UbicacionVivienda uv JOIN uv.user u 
                             WHERE p.user=u.id AND s.id=uv.sector AND
                            lower(p.cargo) NOT LIKE 'ninguno' AND lower(p.cargo) NOT LIKE 'ninguna' AND lower(p.cargo) NOT LIKE ''
                            AND lower(p.cargo) NOT LIKE 'no'
                            GROUP BY m.municipio
                        "
                        
                        )->getResult();
                        
                    return $this->render(
			'MisionSucreRipesBundle:Politica:resumeneje.html.twig',
                        array(
                            'cxeje'=>$cantidadporeje,
                            'cxvoceria'=>$cantidadporvoceria,
                            'cxmunicipios'=>$cantidadpormunicipio
                        ));
                    break;
                
                    case 8:
                        
                        $usreje = $em->getRepository('MisionSucreRipesBundle:CoordinadorEje')->findOneByUser($usr->getId());
                        $eje = $usreje->getEje()->getId();
                        
                        $municipios= $em->createQuery(
                        "SELECT DISTINCT m.municipio,m.id
                            FROM MisionSucreRipesBundle:ParticipacionPolitica p, MisionSucreRipesBundle:Sector s JOIN s.parroquia prq JOIN prq.municipio m,
                             MisionSucreRipesBundle:UbicacionVivienda uv JOIN uv.user u 
                             WHERE p.user=u.id AND s.id=uv.sector AND
                            lower(p.cargo) NOT LIKE 'ninguno' AND lower(p.cargo) NOT LIKE 'ninguna' AND lower(p.cargo) NOT LIKE ''
                            AND lower(p.cargo) NOT LIKE 'no' AND prq.eje=:eje
                            GROUP BY m.id
                            
                        ")->setParameters(array('eje'=>$eje)
                        )->getResult();
                        
                        $cond="";
                        foreach ($municipios as $m) {
                        $cond.= ",SUM( CASE WHEN m.id ='".$m['id']."' THEN 1 ELSE 0 END) AS ".$m['municipio'];
                        
                    }
                        
                        $cantidadporvoceria = $em->createQuery(
                        "SELECT p.cargo,COUNT (p.cargo) as cantidadcargo
                            $cond
                            FROM MisionSucreRipesBundle:ParticipacionPolitica p, MisionSucreRipesBundle:Sector s JOIN s.parroquia prq JOIN prq.municipio m,
                             MisionSucreRipesBundle:UbicacionVivienda uv JOIN uv.user u 
                             WHERE p.user=u.id AND s.id=uv.sector AND
                            lower(p.cargo) NOT LIKE 'ninguno' AND lower(p.cargo) NOT LIKE 'ninguna' AND lower(p.cargo) NOT LIKE ''
                            AND lower(p.cargo) NOT LIKE 'no' AND prq.eje=:eje
                            GROUP BY p.cargo
                            
                        ")->setParameters(array('eje'=>$eje)
                        )->getResult();
                        
                        $cantidadpormunicipio = $em->createQuery(
                        "SELECT m.municipio,m.id,COUNT (p.cargo) as cantidadcargo
                            FROM MisionSucreRipesBundle:ParticipacionPolitica p, MisionSucreRipesBundle:Sector s JOIN s.parroquia prq JOIN prq.municipio m,
                             MisionSucreRipesBundle:UbicacionVivienda uv JOIN uv.user u 
                             WHERE p.user=u.id AND s.id=uv.sector AND
                            lower(p.cargo) NOT LIKE 'ninguno' AND lower(p.cargo) NOT LIKE 'ninguna' AND lower(p.cargo) NOT LIKE ''
                            AND lower(p.cargo) NOT LIKE 'no' AND prq.eje=:eje
                            GROUP BY m.municipio
                        "
                        )->setParameters(array('eje'=>$eje)
                        )->getResult();
                        
                    return $this->render(
			'MisionSucreRipesBundle:Politica:resumenmunicipio.html.twig',
                        array(
                            'cxvoceria'=>$cantidadporvoceria,
                            'cxmunicipios'=>$cantidadpormunicipio,
                            'municipios' =>$municipios,
                        ));
                    break;
                    
                    case 5:
                        
                        $coord = $em->getRepository('MisionSucreRipesBundle:CoordinadorAldea')->findOneByUser($usr->getId());
                        $aldea = $coord->getAldea()->getId();
                        
                       $cantidadporcargos = $em->createQuery(
                        "SELECT p.cargo,
                             SUM( CASE WHEN (
                             EXISTS 
                            ( SELECT c FROM MisionSucreRipesBundle:CoordinadorAldea c WHERE u.id=c.user AND IDENTITY(c.aldea)=:aldea ) OR
                             EXISTS 
                            ( SELECT d FROM MisionSucreRipesBundle:Docente d WHERE u.id=d.user AND IDENTITY(d.aldea)=:aldea ) OR
                             EXISTS 
                            ( SELECT t FROM MisionSucreRipesBundle:Triunfador t JOIN t.ambiente a WHERE u.id=t.user AND IDENTITY(a.aldea)=:aldea ) OR
                             EXISTS 
                            ( SELECT op FROM MisionSucreRipesBundle:Operario op WHERE u.id=op.user AND IDENTITY(op.aldea)=:aldea )
                            )
                             THEN 1 ELSE 0 END) AS cantidadcargo
                            FROM MisionSucreRipesBundle:ParticipacionPolitica p JOIN p.user u
                            WHERE
                            lower(p.cargo) NOT LIKE 'ninguno' AND lower(p.cargo) NOT LIKE 'ninguna' AND lower(p.cargo) NOT LIKE ''
                            AND lower(p.cargo) NOT LIKE 'no'
                            GROUP BY p.cargo
                            
                        ")->setParameters(array('aldea'=>$aldea)
                        )->getResult();
                        
                    return $this->render(
			'MisionSucreRipesBundle:Politica:resumenaldea.html.twig',
                        array(
                            'cxcargos'=>$cantidadporcargos,
                        ));
                    break;
                
                }
        }    

/**
 * OTRAS FUNCIONES
 */
 protected function ConsejosComunales($prq) {
        
    $choices = array();
    
    $ccs = $this->getDoctrine()
            ->getRepository('MisionSucreRipesBundle:ConsejoComunal')
            ->findByParroquia($prq);
    
    foreach ($ccs as $c) {
        $choices[$c->getId()] =$c->getNombre();
    }
    return $choices;
    }
    
 protected function Misiones() {
        
    $choices = array();
    
    $ms = $this->getDoctrine()
            ->getRepository('MisionSucreRipesBundle:Mision')
            ->findAll();
    
    foreach ($ms as $m) {
        $choices[$m->getId()] =$m->getNombre();
    }
    return $choices;
    }

protected function Municipios() {
        
    $choices = array();
    
    $municipios = $this->getDoctrine()
            ->getRepository('MisionSucreRipesBundle:Municipio')
            ->findAll();
    
    foreach ($municipios as $m) {
        $choices[$m->getId()] =$m->getMunicipio();
    }
    return $choices;
    }

public function cambiarubchAction($id)
    {   
        $ubchs = $this->getDoctrine()
            ->getRepository('MisionSucreRipesBundle:Ubch')
            ->findByParroquia($id);
        
        return $this->render('MisionSucreRipesBundle:Persona:ubch.html.twig',array('ubchs'=>$ubchs));
    }
                
}