<?php

namespace MisionSucre\RipesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MisionSucre\RipesBundle\Entity\Atencion;
use MisionSucre\RipesBundle\Form\Type\AtencionType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\HttpFoundation\JsonResponse;

class AtencionController extends Controller
{
    public function indexAction()
    {
        return $this->render('MisionSucreRipesBundle:Usuario:index.html.twig');
    }

    public function newAction(Request $request)
	{       
        
        $em = $this->getDoctrine()->getManager();        
        
        $atencion= new Atencion();
        
        $fecha = new \DateTime(date("Y-m-d"));
        //$fecha->format('Y-m-d');
        
        $atencion->setFecha($fecha);
        
        $form = $this->createForm(new AtencionType(),$atencion)->
               
        add('save', 'submit',array('label' => 'Registrar Atención'));
            
                $form->handleRequest($request);
                
		if ($form->isValid()) {
                            
                            $em = $this->getDoctrine()->getManager();    
                            $idusr = $form->get('idusr')->getData();
                            
                            if($idusr){
                                $user =  $this->getDoctrine()
                                ->getRepository('MisionSucreRipesBundle:User')
                                ->find($idusr);
                                $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Atención Registrada con Éxito'
                            );  
                            $atencion->setUser($user);
                            $em->persist($atencion);
                            $em->flush();
                            return $this->redirect($this->generateUrl('atencion_show',array('id'=>$idusr)));
                            }
                            else{
                               $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Error en la Cédula'
                            );  
                            }
                                        
                    
		}
		
        
        return $this->render('MisionSucreRipesBundle:Atencion:new.html.twig', array(
		'form' => $form->createView(),'mensaje_heading'=>"Registrar Atención",
                    'sub_heading'=>"*********************************"
		));
        
        }
    public function updateAction(Request $request,$id)
	{       
        
        $em = $this->getDoctrine()->getManager();        
        
        $atencion= $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Atencion')
                ->find($id);
        
        if(!$atencion){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            "Ninguna Atención Registrada con ese ID:$id"
                );
                return $this->redirect($this->generateUrl('atencion_lista'));
                }
        $per= $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Persona')
                ->findOneByUser($atencion->getUser()->getId());
                
        $form = $this->createForm(new AtencionType(),$atencion)->
               
        add('save', 'submit',array('label' => 'Actualizar Atención'));
            
                $form->handleRequest($request);
                
		if ($form->isValid()) {
                            
                            $em = $this->getDoctrine()->getManager();    
                            $idusr = $form->get('idusr')->getData();
                            
                            if($idusr){
                                $user =  $this->getDoctrine()
                                ->getRepository('MisionSucreRipesBundle:User')
                                ->find($idusr);
                                $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Atención Actualizada con Éxito'
                            );  
                            $atencion->setUser($user);
                            $em->persist($atencion);
                            $em->flush();
                            }
                            else{
                               $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Error en la Cédula'
                            );  
                            }
                                        
                    return $this->redirect($this->generateUrl('atencion_show',array('id'=>$idusr)));
		}
		
        
        return $this->render('MisionSucreRipesBundle:Atencion:new.html.twig', array(
		'form' => $form->createView(),'mensaje_heading'=>"Actualizar Atención",
                    'sub_heading'=>"*********************************",'cedula'=>$per->getCedPer()
		));
        
        }  
        
     public function deleteAction(Request $request,$id)
	{       
        
        $em = $this->getDoctrine()->getManager();        
        
        $atencion= $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Atencion')
                ->find($id);
        
        if(!$atencion){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            "Ninguna Atención Registrada con ese ID:$id"
                );
                return $this->redirect($this->generateUrl('atencion_lista'));
                }
          $em->remove($atencion);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Atención Borrada con  con Éxito'
                            );            
                    return $this->redirect($this->generateUrl('atencion_lista'));
        
        }  
    public function datapersonaAction(Request $request,$ced)
	{
        
        $em = $this->getDoctrine()->getManager();
        
        $per = $em->getRepository('MisionSucreRipesBundle:Persona')->findOneByCedPer($ced);
        
        $apellidos="";
        $nombres="";
        $perfil="";
        $idusr=0;
        
        $error=0;
        
        if($per){
             
        $nombres=$per->getPriNom() ." ". $per->getSegNom();
        $apellidos=$per->getPriApe() ." ". $per->getSegApe();
        $idusr= $per->getUser()->getId();
        
       switch($per->getUser()->getTipUsr()){
           
            case 6:
                $perfil="Triunfador(a)";
                break;
            case 7:
                $perfil="Docente";
                break;
            case 9:
                $perfil="Operario";
                break;
            default:
                $error="Perfil no Aceptado";
                        
            
        }
        }
        else{
            $error="Cédula no encontrada";
        }
        
        return new JsonResponse(array('nombres'=>$nombres,
                                'idusr'=>$idusr,
                                'error' => $error,'apellidos'=>$apellidos, 'perfil'=>$perfil
                                 
                ));
        }
    public function showAction(Request $request,$id)
	{       
                $em = $this->getDoctrine()->getManager();
                
                $atenciones = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Atencion')
                ->findByUser($id);
                
                $datapersonal = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Atencion')
                ->DataPersonal($id);
                
                if(!$atenciones){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Ninguna Atención Registrada'
                );
                return $this->redirect($this->generateUrl('atencion_new'));
                }
                
                if(!$datapersonal){
                
                    $request->getSession()->getFlashBag()->add(
                    'notice',
                    'Datos Personales no registrados. Por Favor Completar los Datos'
                    );    
                    return $this->redirect($this->generateUrl('persona_new',array('id'=>$id)));
                }
                
		return $this->render(
			'MisionSucreRipesBundle:Atencion:show.html.twig',
			array('atenciones'=>$atenciones,'datapersonal'=>$datapersonal
                        ));
	}
public function listaAction(Request $request)
	{   
         $em = $this->getDoctrine()->getManager();        
            $atenciones = $atenciones = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Atencion')
                ->findAllOrderedByFecha();;
                
		if(!$atenciones){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Ninguna Atención Registrada'
            );
            return $this->redirect($this->generateUrl('atencion_new'));
            }
            
		return $this->render(
			'MisionSucreRipesBundle:Atencion:lista.html.twig',
                array('atenciones' => $atenciones)
		);	
	}
        
public function buscarAction(Request $request)
	{   
            
		return $this->render(
			'MisionSucreRipesBundle:Atencion:buscar.html.twig'
		);	
	}
  public function busquedaAction(Request $request)
	{
        
         if($request->request->get('im'))
                {   
                    $imes=intval($request->request->get('im'));  
                    $ianyo=intval($request->request->get('ia')); 
                    $fmes=intval($request->request->get('fm'));  
                    $fanyo=intval($request->request->get('fa')); 
                    $fanyo=intval($request->request->get('fa'));
                    $cedula=$request->request->get('ced');
                    $tipo=$request->request->get('tip');
                    $estatus=$request->request->get('est');
                }

//                    $imes=0;
//                    $ianyo=2016;
//                    $fmes=0;
//                    $fanyo=2017;   
//                    $cedula="5296835";
//                    $tipo="prueba";
//                    $estatus="Iniciado";
                
         $em = $this->getDoctrine()->getManager();
         $atenciones=$em
                ->getRepository('MisionSucreRipesBundle:Atencion')
                ->buscar($imes,$ianyo,$fmes,$fanyo,$cedula,$tipo,$estatus);
//         //$fechafinal="Sirve";
         return $this->render(
			'MisionSucreRipesBundle:Atencion:busqueda.html.twig',
			array('atenciones'=>$atenciones,'cedula'=>$tipo));
    }
    public function resumenAction(Request $request)
	{  /*
         * Resumen Estadistico de las atenciones
         */
        
        /*SUSTICIONES */
                $sustituciones = $this->get('servicios.sustituir');
                $meses = $sustituciones->Meses();
        /*FIN SUSTICIONES*/
         $em = $this->getDoctrine()->getManager();
                
                        $cantidad = $em->createQuery(
                        "SELECT MONTH(a.fecha) as mes,YEAR(a.fecha) as anyo, COUNT (a.id) as total FROM MisionSucreRipesBundle:Atencion a
                            GROUP BY mes, anyo
                            ORDER BY anyo,mes
                        ")
                        ->getResult();
                        
                    return $this->render(
			'MisionSucreRipesBundle:Atencion:resumen.html.twig',
                        array(
                            'cantidad'=>$cantidad,
                            'meses'=>$meses
                        ));
                
        }
}
