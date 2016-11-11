<?php

namespace MisionSucre\RipesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MisionSucre\RipesBundle\Form\Type\PersonaType;
use MisionSucre\RipesBundle\Entity\Persona;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\HttpFoundation\JsonResponse;

class PersonaController extends Controller
{
    public function indexAction()
    {
        return $this->render('MisionSucreRipesBundle:Usuario:index.html.twig');
    }
	
	public function newAction(Request $request,$id)
	{       
                $em = $this->getDoctrine()->getManager();
                
                $persona= $em->getRepository('MisionSucreRipesBundle:Persona')->findByUser($id);
                $usr = $em->getRepository('MisionSucreRipesBundle:User')->find($id);
                
                if($persona)
                {   
                    $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Persona ya Registrada'
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
                $prs= new Persona();
                        
		$form = $this->createForm(new PersonaType(), $prs)
               ->add('save', 'submit',array('label' => 'Registrar Persona'));
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
                            $prs->setUser($usr);
                            
                            $em->persist($prs);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Persona Registrada con Éxito'
                            );            
                    return $this->redirect($this->generateUrl('usuario_show',array('id'=>$id)));
                            
		}
		
               
		return $this->render('MisionSucreRipesBundle:Persona:new.html.twig', array(
		'form' => $form->createView(),'mensaje_heading'=>'Registro de Datos',
                            'sub_heading'=>'Nuevos Datos','prq'=>$prq,'sector' => $sec
		));
	}
    
        public function updateAction(Request $request,$id)
	{
            $em = $this->getDoctrine()->getManager();
                
            $persona= $em->getRepository('MisionSucreRipesBundle:Persona')->findByUser($id);
            
            $persona = $persona[0];
            
                if(!$persona)
                {   
                    $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Persona no Registrada'
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
                
                        
		$form = $this->createForm(new PersonaType(), $persona)
            ->add('save', 'submit',array('label' => 'Actualizar Persona'));
                
                
                $form->handleRequest($request);
                
		if ($form->isValid()) {
                            
                            $usr = $em->getRepository('MisionSucreRipesBundle:User')->find($id);
                            
                            $persona->setUser($usr);
                            
                            $em->persist($persona);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Persona Actualizada con Éxito'
                            );            
                    return $this->redirect($this->generateUrl('usuario_show',array('id'=>$id)));
                            
		}
		
               
		return $this->render('MisionSucreRipesBundle:Persona:new.html.twig', array(
		'form' => $form->createView(),'mensaje_heading'=>'Actualización de Datos',
                    'sub_heading'=>'Datos Personales'
		));
	}
    
    public function datacneAction(Request $request,$ced)
	{
        
        $em = $this->getDoctrine()->getManager();
        
        $per = $em->getRepository('MisionSucreRipesBundle:Persona')->findOneByCedPer($ced);
        
        $datacne= $em->getRepository('MisionSucreRipesBundle:DataCne')->find($ced);
        
        $existe=0;
        
        if($per){
            $existe=1;
        }
        
        $prinombre="";
        $segnombre="";
        $priapellido="";
        $segapellido="";
        $sexo="";
        $dia = "";
        $mes = "";
        $anyo ="";
        
        if($datacne){
        $nombres_apellidos = $datacne->getNombreApellido();
        
        $nombres_apellidos_array= preg_split("/[\s,]+/",$nombres_apellidos);
        
        switch (count($nombres_apellidos_array)){
            
            case 1:
                $priapellido=$nombres_apellidos_array[0];
                break;
            case 2:
                $prinombre=$nombres_apellidos_array[1];
                $priapellido=$nombres_apellidos_array[0];
                break;
            case 3:
                $prinombre=$nombres_apellidos_array[1];
                $segnombre=$nombres_apellidos_array[2];
                $priapellido=$nombres_apellidos_array[0];
                break;
            case 4:
            case 5:
            case 6:
                $prinombre=$nombres_apellidos_array[2];
                $segnombre=$nombres_apellidos_array[3];
                $priapellido=$nombres_apellidos_array[0];
                $segapellido=$nombres_apellidos_array[1];
                break;
            
        }
        $fn = $datacne->getFechanacimiento();
        $sexo = strtolower($datacne->getSexo());
        $fn = preg_split("[/]",$fn);
        
        $dia = intval($fn[0]);
        $mes = intval($fn[1]);
        $anyo =$fn[2];
        }
        
        return new JsonResponse(array('prinombre'=>$prinombre, 'segnombre'=>$segnombre,
                                'priapellido' => $priapellido, 'segapellido'=>$segapellido, 'existe' => $existe,
                                 'dia'=>$dia, 'mes' => $mes, 'anyo'=> $anyo, 'sexo' => $sexo
                ));
        }
    
}
