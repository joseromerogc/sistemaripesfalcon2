<?php

namespace MisionSucre\RipesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MisionSucre\RipesBundle\Form\Type\SocialType;
use MisionSucre\RipesBundle\Form\Type\DiscapacidadType;
use MisionSucre\RipesBundle\Form\Type\ArteType;
use MisionSucre\RipesBundle\Form\Type\DeporteType;
use MisionSucre\RipesBundle\Form\Type\TrabajoType;
use MisionSucre\RipesBundle\Entity\Social;
use MisionSucre\RipesBundle\Entity\Enfermedad;
use MisionSucre\RipesBundle\Entity\Discapacidad;
use MisionSucre\RipesBundle\Entity\Deporte;
use MisionSucre\RipesBundle\Entity\Trabajo;
use MisionSucre\RipesBundle\Entity\Arte;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;

class SocialesController extends Controller
{
    public function indexAction()
    {
        return $this->render('MisionSucreRipesBundle:Sociales:index.html.twig');
    }
	
	public function newAction(Request $request,$id)
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
                
                $sociales= $em->getRepository('MisionSucreRipesBundle:Social')->findByUser($id);
                
                if($sociales)
                {   
                    $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Datos Sociales ya Registrados '
                            );            
                    return $this->redirect($this->generateUrl('usuario_show',array('id'=>$id)));
                    
                }
		// create a task and give it some dummy data for this example
                $s= new Social();
                        
		$form = $this->createForm(new SocialType(), $s);
                
                $form->handleRequest($request);
                
		if ($form->isValid()) {
                    
                            $s->setUser($usr);
                            
                            $em->persist($s);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Persona Registrada con Éxito'
                            );            
                    return $this->redirect($this->generateUrl('usuario_show',array('id'=>$id)));
                            
		}
		
               
		return $this->render('MisionSucreRipesBundle:Persona:newsocial.html.twig', array(
		'form' => $form->createView(),'mensaje_heading'=>'Registro de Datos Sociales',
                    'sub_heading'=>'Nuevos Datos'
		));
	}
        public function updateAction(Request $request,$id)
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
                    
                    
            $sociales= $em->getRepository('MisionSucreRipesBundle:Social')->findByUser($id);
            
            $sociales = $sociales[0];
            
                if(!$sociales)
                {   
                    $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Persona no Registrada'
                            );            
                    return $this->redirect($this->generateUrl('usuario_new',array('id'=>$id)));
                    
                }
		// create a task and give it some dummy data for this example
                        
		$form = $this->createForm(new SocialType(), $sociales);
                
                
                $form->handleRequest($request);
		if ($form->isValid()) {
                            
                            $em->persist($sociales);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Datos Sociales Actualizados con Éxito'
                            );            
                    return $this->redirect($this->generateUrl('usuario_show',array('id'=>$id)));
                            
		}
		
               
		return $this->render('MisionSucreRipesBundle:Persona:newsocial.html.twig', array(
		'form' => $form->createView(),'mensaje_heading'=>'Actualización de Datos',
                    'sub_heading'=>'Datos Sociales'
		));
	}
        
/*
 * DISCAPACIDAD
 */        
        public function updatediscapacidadAction(Request $request,$id)
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
                    
            $discapacidad= $em->getRepository('MisionSucreRipesBundle:Discapacidad')->findByUser($id);
            
            $discapacidad = $discapacidad[0];
            
                if(!$discapacidad)
                {   
                    $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Discapacidad ya Registrada'
                            );            
                    return $this->redirect($this->generateUrl('usuario_new',array('id'=>$id)));
                    
                }
		// create a task and give it some dummy data for this example
                        
		$form = $this->createForm(new DiscapacidadType(), $discapacidad);
                
                
                $form->handleRequest($request);
		if ($form->isValid()) {
                            
                            $em->persist($discapacidad);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Discapacidad Actualizada con Éxito'
                            );            
                    return $this->redirect($this->generateUrl('usuario_show',array('id'=>$id)));
                            
		}
		
               
		return $this->render('MisionSucreRipesBundle:Persona:newdiscapacidad.html.twig', array(
		'form' => $form->createView(),'mensaje_heading'=>'Actualización de Datos',
                    'sub_heading'=>'Datos Discapacidad'
		));
	}
        public function deletediscapacidadAction(Request $request,$id,$id_dis)
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
            
            $discapacidad= $em->getRepository('MisionSucreRipesBundle:Discapacidad')->find($id_dis);
            	                
                            $em->remove($discapacidad);
                            
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Discapacidad Borrada con Éxito'
                            );

                    return $this->redirect($this->generateUrl('usuario_show',array('id'=>$id)));
		}             
        public function newdiscapacidadAction(Request $request,$id)
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
                
                $discapacidad= $em->getRepository('MisionSucreRipesBundle:Discapacidad')->findByUser($id);
                
                if($discapacidad)
                {   
                    $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Datos Sociales ya Registrados '
                            );            
                    return $this->redirect($this->generateUrl('usuario_show',array('id'=>$id)));
                    
                }
		// create a task and give it some dummy data for this example
                $d= new Discapacidad();
                        
		$form = $this->createForm(new DiscapacidadType(), $d);
                
                $form->handleRequest($request);
                
		if ($form->isValid()) {
                    
                            $d->setUser($usr);            
                            $em->persist($d);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Persona Registrada con Éxito'
                            );            
                    return $this->redirect($this->generateUrl('usuario_show',array('id'=>$id)));
                            
		}
		
               
		return $this->render('MisionSucreRipesBundle:Persona:newdiscapacidad.html.twig', array(
		'form' => $form->createView(),'mensaje_heading'=>'Registro de Discacidad',
                    'sub_heading'=>'Nuevos Datos'
		));
	}
/*
 * Artes
 */        
        public function updateartesAction(Request $request,$id)
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
                    
            $artes= $em->getRepository('MisionSucreRipesBundle:Arte')->findByUser($id);
            
            $artes = $artes[0];
            
                if(!$artes)
                {   
                    $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Habilidad Artistica ya Registrada'
                            );            
                    return $this->redirect($this->generateUrl('usuario_new',array('id'=>$id)));
                    
                }
		// create a task and give it some dummy data for this example
                        
		$form = $this->createForm(new ArteType(), $artes);
                
                
                $form->handleRequest($request);
		if ($form->isValid()) {
                            
                            $em->persist($artes);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Habilidad Artistica Actualizada con Éxito'
                            );            
                    return $this->redirect($this->generateUrl('usuario_show',array('id'=>$id)));
                            
		}
		
		return $this->render('MisionSucreRipesBundle:Persona:newarte.html.twig', array(
		'form' => $form->createView(),'mensaje_heading'=>'Actualización de Datos',
                    'sub_heading'=>'Habilidades Artisticas'
		));
	}   
        
        public function deletearteAction(Request $request,$id,$id_art)
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
            
            $arte= $em->getRepository('MisionSucreRipesBundle:Arte')->find($id_art);
            	                
                            $em->remove($arte);
                            
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Habilidad Artistica Borrada con Éxito'
                            );

                    return $this->redirect($this->generateUrl('usuario_show',array('id'=>$id)));
		}             
                
        public function newartesAction(Request $request,$id)
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
                
                $artes= $em->getRepository('MisionSucreRipesBundle:Arte')->findByUser($id);
                
                if($artes)
                {   
                    $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Habilidad Artistica ya Registrados '
                            );            
                    return $this->redirect($this->generateUrl('usuario_show',array('id'=>$id)));
                    
                }
		
                $a= new Arte();
                        
		$form = $this->createForm(new ArteType(), $a);
                
                $form->handleRequest($request);
                
		if ($form->isValid()) {
                    
                            $a->setUser($usr);            
                            $em->persist($a);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Habilidades Artisticas Registradas con Éxito'
                            );            
                    return $this->redirect($this->generateUrl('usuario_show',array('id'=>$id)));
		}
		return $this->render('MisionSucreRipesBundle:Persona:newarte.html.twig', array(
		'form' => $form->createView(),'mensaje_heading'=>'Registro',
                    'sub_heading'=>'Habilidades Artisticas'
		));
	}
/*
 * Deporte
 */        
        public function updatedeporteAction(Request $request,$id)
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
                    
            $deporte= $em->getRepository('MisionSucreRipesBundle:Deporte')->findByUser($id);
            
            $deporte = $deporte[0];
            
                if(!$deporte)
                {   
                    $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Deporte ya Registrado'
                            );            
                    return $this->redirect($this->generateUrl('usuario_new',array('id'=>$id)));
                    
                }
		// create a task and give it some dummy data for this example
                        
		$form = $this->createForm(new DeporteType(), $deporte);
                
                
                $form->handleRequest($request);
		if ($form->isValid()) {
                            
                            $em->persist($deporte);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Deporte Actualizado con Éxito'
                            );            
                    return $this->redirect($this->generateUrl('usuario_show',array('id'=>$id)));
                            
		}
		
               
		return $this->render('MisionSucreRipesBundle:Persona:newdeporte.html.twig', array(
		'form' => $form->createView(),'mensaje_heading'=>'Actualización de Datos',
                    'sub_heading'=>'Deporte'
		));
	}
        
        public function deletedeporteAction(Request $request,$id,$id_dep)
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
            
            $deporte= $em->getRepository('MisionSucreRipesBundle:Deporte')->find($id_dep);
            	                
                            $em->remove($deporte);
                            
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Deporte Borrado con Éxito'
                            );

                    return $this->redirect($this->generateUrl('usuario_show',array('id'=>$id)));
		}             
                
        public function newdeporteAction(Request $request,$id)
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
                
                $deporte= $em->getRepository('MisionSucreRipesBundle:Deporte')->findByUser($id);
                
                if($deporte)
                {   
                    $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Deporte ya Registrado '
                            );            
                    return $this->redirect($this->generateUrl('usuario_show',array('id'=>$id)));
                    
                }
		
                $d= new Deporte();
                        
		$form = $this->createForm(new DeporteType(), $d);
                
                $form->handleRequest($request);
                
		if ($form->isValid()) {
                    
                            $d->setUser($usr);            
                            $em->persist($d);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Deporte Registradas con Éxito'
                            );            
                    return $this->redirect($this->generateUrl('usuario_show',array('id'=>$id)));
		}
		return $this->render('MisionSucreRipesBundle:Persona:newdeporte.html.twig', array(
		'form' => $form->createView(),'mensaje_heading'=>'Registro',
                    'sub_heading'=>'Deportivas'
		));
	}
/*
 * TRABAJO
 */        
        public function updatetrabajoAction(Request $request,$id)
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
            
            $trabajo= $em->getRepository('MisionSucreRipesBundle:Trabajo')->findByUser($id);
            
            $trabajo = $trabajo[0];
            
                if(!$trabajo)
                {   
                    $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Trabajo ya Registrado'
                            );            
                    return $this->redirect($this->generateUrl('usuario_new',array('id'=>$id)));
                    
                }
		// create a task and give it some dummy data for this example
                        
		$form = $this->createForm(new TrabajoType(), $trabajo);
                
                
                $form->handleRequest($request);
		if ($form->isValid()) {
                            
                            $em->persist($trabajo);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Trabajo Actualizado con Éxito'
                            );            
                    return $this->redirect($this->generateUrl('usuario_show',array('id'=>$id)));
                            
		}
		
		return $this->render('MisionSucreRipesBundle:Persona:newtrabajo.html.twig', array(
		'form' => $form->createView(),'mensaje_heading'=>'Actualización de Datos',
                    'sub_heading'=>'Trabajo'
		));
	}
        
        public function deletetrabajoAction(Request $request,$id,$id_dep)
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
            
            $trabajo= $em->getRepository('MisionSucreRipesBundle:Trabajo')->find($id_dep);
            	                
                            $em->remove($trabajo);
                            
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Trabajo Borrado con Éxito'
                            );

                    return $this->redirect($this->generateUrl('usuario_show',array('id'=>$id)));
		}             
                
        public function newtrabajoAction(Request $request,$id)
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
                
                $trabajo= $em->getRepository('MisionSucreRipesBundle:Trabajo')->findByUser($id);
                
                if($trabajo)
                {   
                    $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Trabajo ya Registrado '
                            );            
                    return $this->redirect($this->generateUrl('usuario_show',array('id'=>$id)));
                    
                }
		
                $t= new Trabajo();
                        
		$form = $this->createForm(new TrabajoType(), $t);
                
                $form->handleRequest($request);
                
		if ($form->isValid()) {
                    
                            $t->setUser($usr);            
                            $em->persist($t);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Trabajo Registradas con Éxito'
                            );            
                    return $this->redirect($this->generateUrl('usuario_show',array('id'=>$id)));
		}
		return $this->render('MisionSucreRipesBundle:Persona:newtrabajo.html.twig', array(
		'form' => $form->createView(),'mensaje_heading'=>'Registro',
                    'sub_heading'=>'Deportivas'
		));
	}
        
                
/*
 * ENFERMEDAD
 */
	public function newenfermedadAction(Request $request,$id)
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
                
		// create a task and give it some dummy data for this example
                
                    $enfs=$this->Tipo_Enfermedades();
                    
		$form = $this->createFormBuilder($enfs)
        ->add('enfermedades', 'choice', array(
        'choices' => $enfs , 'label'=>'Tipo de Enfermedad',
            'placeholder'=>"Seleccione una",'mapped' => false, 'required'=>false,
            ))->add('enf', 'hidden',array('mapped' => false))
                        ->add('save', 'submit',array('label' => 'Registrar Enfermedad'))
                        ->getForm();
                
                $form->handleRequest($request);
                
		if ($form->isValid()) {
                            
                            $idenf = $form->get('enf')->getData();
                            
                            $enf= $em->getRepository('MisionSucreRipesBundle:Enfermedad')->find($idenf);
                            
                            $usr->addEnfermedade($enf);
                            $enf->addUser($usr);
                            $em->persist($enf);
                            $em->persist($usr);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Enfermedad Registrada con Éxito'
                            );            
                    return $this->redirect($this->generateUrl('usuario_show',array('id'=>$id)));
                            
		}
		
		return $this->render('MisionSucreRipesBundle:Persona:newenfermedad.html.twig', array(
		'form' => $form->createView(),'mensaje_heading'=>'Registro de Enfermedad',
                    'sub_heading'=>'Datos de Salud',
		));
	}
        public function deleteenfermedadAction(Request $request,$id,$id_enf)
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
		                
                            $enf= $em->getRepository('MisionSucreRipesBundle:Enfermedad')->find($id_enf);
                            
                            $enf->removeUser($usr);
                            $usr->removeEnfermedade($enf);
                            
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Enfermedad Borrada con Éxito'
                            );

                    return $this->redirect($this->generateUrl('usuario_show',array('id'=>$id)));
		}

/*
 * FUNCIONES EXTRAS
 */
    
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
    
    public function Tipo_Enfermedades()
    {   
        
    $choices = array();
    
    $enfs = $this->getDoctrine()
            ->getRepository('MisionSucreRipesBundle:Enfermedad')
            ->findByTipEnf();
   
    foreach ($enfs as $e) {
        $choices[$e['tipo_enf']] =$e['tipo_enf'];
    } 
    return $choices;
    }
    
    public function enfermedadesAction($tip)
    {   
        
        $e = $this->getDoctrine()
            ->getRepository('MisionSucreRipesBundle:Enfermedad')
            ->findOneByTipEnf($tip);
        return $this->render('MisionSucreRipesBundle:Persona:enfermedades.html.twig',array('enfermedades'=>$e));
    }
    
}
