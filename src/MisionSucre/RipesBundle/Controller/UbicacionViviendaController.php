<?php

namespace MisionSucre\RipesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MisionSucre\RipesBundle\Form\Type\UbicacionViviendaType;
use MisionSucre\RipesBundle\Entity\UbicacionVivienda;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;

class UbicacionViviendaController extends Controller
{
    
	public function newAction(Request $request,$id)
	{       
                $em = $this->getDoctrine()->getManager();
                
                $ubvv= $em->getRepository('MisionSucreRipesBundle:UbicacionVivienda')->findByUser($id);
                $usr = $em->getRepository('MisionSucreRipesBundle:User')->find($id);
                
                if($ubvv)
                {   
                    $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Ubicación de Vivienda ya Registrada'
                            );            
                    return $this->redirect($this->generateUrl('usuario_show',array('id'=>$id)));
                    
                }
                
                if(!$usr)
                {   
                    $request->getSession()->getFlashBag()->add(
                            'notice',
                            "Usuario con ID:$id NO Registrado "
                            );            
                    return $this->redirect($this->generateUrl('usuario_show',array('id'=>$id)));
                    
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
                
		// create a task and give it some dummy data for this example
                $ubicacionvv= new UbicacionVivienda();
                        
		$form = $this->createForm(new UbicacionViviendaType(), $ubicacionvv)
                ->add('municipio', 'choice', array(
        'choices' => $this->Municipios() , 'label'=>'Municipios*',
            'placeholder'=>"Seleccione una",'mapped' => false
            ))->add('save', 'submit',array('label' => 'Registrar Ubicación Geográfica'));
                        ;
                
                
                $prq=null;
                $sec=null;
                
                if($request->getMethod() == 'POST'){
                          $idprq=$this->get('request')->request->get('persona_parroquia');
                          $idsect=$this->get('request')->request->get('persona_sector_');
                          if($idprq!="")
                            $prq= $em->getRepository('MisionSucreRipesBundle:Parroquia')->find($idprq);
                          if($idsect!="")
                            $sec= $em->getRepository('MisionSucreRipesBundle:Sector')->find($idsect);
                }
                        
                $form->handleRequest($request);
                   
		if ($form->isValid()) {
                            
                            $idsec = $form->get('sector')->getData();
                            
                            $sector = $em->getRepository('MisionSucreRipesBundle:Sector')->find($idsec);
                            
                            $ubicacionvv->setSector($sector);
                            $ubicacionvv->setUser($usr);
                            
                            $em->persist($ubicacionvv);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Ubicación Vivienda Registrada con Éxito'
                            );            
                    return $this->redirect($this->generateUrl('usuario_show',array('id'=>$id)));
                            
		}
		
		return $this->render('MisionSucreRipesBundle:UbicacionVivienda:new.html.twig', array(
		'form' => $form->createView(),'mensaje_heading'=>'Registro de Ubicación de Vivienda',
                            'sub_heading'=>'Nuevos Datos','prq'=>$prq,'sector' => $sec
		));
	}
    
        public function updateAction(Request $request,$id)
	{
            $em = $this->getDoctrine()->getManager();
                
            $ubicacionvv= $em->getRepository('MisionSucreRipesBundle:UbicacionVivienda')->findOneByUser($id);
                $usr = $em->getRepository('MisionSucreRipesBundle:User')->find($id);
                
                if(!$ubicacionvv)
                {   
                    $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Ubicación de Vivienda no Registrada'
                            );            
                    return $this->redirect($this->generateUrl('ubicacion_vivienda_new',array('id'=>$id)));
                    
                }
                
                if(!$usr)
                {   
                    $request->getSession()->getFlashBag()->add(
                            'notice',
                            "Usuario con ID:$id NO Registrado "
                            );            
                    return $this->redirect($this->generateUrl('usuario_show',array('id'=>$id)));
                    
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
                       
		
                $sect = $ubicacionvv->getSector();
                $mun = $em->getRepository('MisionSucreRipesBundle:Municipio')->find(($sect->getParroquia()->getMunicipio()->getId()));
                $prq=$em->getRepository('MisionSucreRipesBundle:Parroquia')->find(($sect->getParroquia()->getId()));
                $sec = $sec= $em->getRepository('MisionSucreRipesBundle:Sector')->find($sect->getId());
                
                $form = $this->createForm(new UbicacionViviendaType(), $ubicacionvv)
                ->add('municipio', 'choice', array(
        'choices' => $this->Municipios() , 'label'=>'Municipios*',
            'placeholder'=>$mun->getMunicipio(),'mapped' => false, 'required' =>false
            ))->add('save', 'submit',array('label' => 'Registrar Ubicación Geográfica'));
                        ;
                
                if($request->getMethod() == 'POST'){
                          $idprq=$this->get('request')->request->get('persona_parroquia');
                          $idsect=$this->get('request')->request->get('persona_sector_');
                          if($idprq!="")
                            $prq= $em->getRepository('MisionSucreRipesBundle:Parroquia')->find($idprq);
                          if($idsect!="")
                            $sec= $em->getRepository('MisionSucreRipesBundle:Sector')->find($idsect);
                }
                        
                $form->handleRequest($request);
                   
		if ($form->isValid()) {
                            
                            $idsec = $form->get('sector')->getData();
                            
                            $sector = $em->getRepository('MisionSucreRipesBundle:Sector')->find($idsec);
                            
                            $ubicacionvv->setSector($sector);
                            $ubicacionvv->setUser($usr);
                            
                            $em->persist($ubicacionvv);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Persona Actualizada con Éxito'
                            );            
                    return $this->redirect($this->generateUrl('usuario_show',array('id'=>$id)));
                            
		}
		
               
		return $this->render('MisionSucreRipesBundle:UbicacionVivienda:new.html.twig', array(
		'form' => $form->createView(),'mensaje_heading'=>'Actualización de Datos',
                    'sub_heading'=>'Datos Personales','prq'=>$prq,'sector' => $sec
		));
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
    
    public function cambiarparroquiaAction($id)
    {   
        $parroquias = $this->getDoctrine()
            ->getRepository('MisionSucreRipesBundle:Parroquia')
            ->findByMunicipio($id);
        
        return $this->render('MisionSucreRipesBundle:Persona:parroquia.html.twig',array('parroquias'=>$parroquias));
    }
    
    public function cambiarsectorAction($id)
    {   
        $sector = $this->getDoctrine()
            ->getRepository('MisionSucreRipesBundle:Sector')
            ->findByParroquia($id);
        return $this->render('MisionSucreRipesBundle:Persona:sector.html.twig',array('sector'=>$sector));
    }
    
}
