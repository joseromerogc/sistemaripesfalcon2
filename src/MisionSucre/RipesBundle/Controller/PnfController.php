<?php

namespace MisionSucre\RipesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MisionSucre\RipesBundle\Entity\Pnf;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;

class PnfController extends Controller
{
    public function indexAction()
    {
        return $this->render('MisionSucreRipesBundle:Pnf:index.html.twig');
    }
	
	public function newAction(Request $request)
	{       
                $em = $this->getDoctrine()->getManager();
                
                $pnf = new Pnf();
                        
		$form = $this->createFormBuilder($pnf)->add('universidad', 'entity', array(
    'class' => 'MisionSucreRipesBundle:Universidad','query_builder' => function (EntityRepository $er) {
        return $er->createQueryBuilder('u')
            ->orderBy('u.nombre', 'ASC');
    },'placeholder'=>"Seleccione una",'label' => 'Universidad que Acredita'
    ))->add('nombre','text',array('label'=>'Nombre del P.N.F'))
            ->add('modalidad','choice',  array('choices'=>  array('TRIMESTRAL'=>'TRIMESTRAL','SEMESTRAL'=>'SEMESTRAL'),'label'=>'Modalidad de Estudio'))
                        ->add('save', 'submit',array('label' => 'Registrar P.N.F.'))->getForm();
                        
                $form->handleRequest($request);
		if ($form->isValid()) {
                            
                
                            
                            $em->persist($pnf);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Programa Nacional de Formación Registrado con Éxito'
                            );            
                    return $this->redirect($this->generateUrl('pnf_show',array('id'=>$id)));
                           
		}
		
               
		return $this->render('MisionSucreRipesBundle:Pnf:new.html.twig', array(
		'form' => $form->createView(),'mensaje_heading'=>'Programa Nacional de Formación',
                    'sub_heading'=>'Nuevos Datos'
		));
	}
        
        public function updateAction(Request $request,$id)
	{       
                 $em = $this->getDoctrine()->getManager();
                
                $pnf =  $em->getRepository('MisionSucreRipesBundle:Pnf')->find($id);
                        
		$form = $this->createFormBuilder($pnf)->add('universidad', 'entity', array(
    'class' => 'MisionSucreRipesBundle:Universidad','query_builder' => function (EntityRepository $er) {
        return $er->createQueryBuilder('u')
            ->orderBy('u.nombre', 'ASC');
    },'placeholder'=>"Seleccione una",'label' => 'Universidad que Acredita'
    ))->add('nombre','text',array('label'=>'Nombre del P.N.F'))
            ->add('modalidad','choice',  array('choices'=>  array('TRIMESTRAL'=>'TRIMESTRAL','SEMESTRAL'=>'SEMESTRAL'),'label'=>'Modalidad de Estudio'))
                        ->add('save', 'submit',array('label' => 'Registrar P.N.F.'))->getForm();
                        
                $form->handleRequest($request);
		if ($form->isValid()) {
                            
                
                            
                            $em->persist($pnf);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Programa Nacional de Formación Actualizado con Éxito'
                            );            
                    return $this->redirect($this->generateUrl('pnf_show',array('id'=>$id)));
                           
		}
		
               
		return $this->render('MisionSucreRipesBundle:Pnf:new.html.twig', array(
		'form' => $form->createView(),'mensaje_heading'=>'Programa Nacional de Formación',
                    'sub_heading'=>'Nuevos Datos'
		));
	}
    
        public function listaAction(Request $request)
	{       
                $em = $this->getDoctrine()->getManager();
                
                $usr = $this->getUser();
                $pnfs = $this->getDoctrine()
                        ->getRepository('MisionSucreRipesBundle:Pnf')
                        ->findAll();
                
		return $this->render(
			'MisionSucreRipesBundle:Pnf:lista.html.twig',
                array('pnfs' => $pnfs)
		);	
	}
        
        public function showAction(Request $request,$id){
                
                $em = $this->getDoctrine()->getManager();
                
                $pnf =  $em->getRepository('MisionSucreRipesBundle:Pnf')->find($id);
                
                $cantidadambientes = $em->createQuery(
                        "SELECT COUNT (am) as c FROM MisionSucreRipesBundle:Ambiente am WHERE am.pnf=:pnf
                        ")->setParameters(array('pnf'=>$pnf->getId())
                        )->getSingleResult();
                
                $cantidadtriunfadores = $em->createQuery(
                        "SELECT COUNT (t) as c FROM MisionSucreRipesBundle:Triunfador t JOIN t.ambiente am WHERE am.pnf=:pnf
                        ")->setParameters(array('pnf'=>$pnf->getId())
                        )->getSingleResult();
                
                $ucs = $em->getRepository('MisionSucreRipesBundle:UnidadCurricular')->findByPnf($id);
                
                if(!$pnf){
                    
                    $request->getSession()->getFlashBag()->add(
                    'notice',
                    'Pnf con ID: '.$id.' no registrado'
                    );    
                    return $this->redirect($this->generateUrl('pnf_lista'));
                }
                
                return $this->render(
			'MisionSucreRipesBundle:Pnf:show.html.twig',
                        array('pnf'=>$pnf,'cantidadambientes'=>$cantidadambientes,'cantidadtriunfadores'=>$cantidadtriunfadores,
                            'ucs' => $ucs)
		);
        }
        
    public function resumenAction(Request $request)
	{       
                $em = $this->getDoctrine()->getManager();
                
                $usr = $this->getUser();
                
                switch($usr->getTipUsr()){
                    
                    case 1:
                        $cantidadambientes = $em->createQuery(
                        "SELECT COUNT (am) as ca ,pnf.nombre FROM MisionSucreRipesBundle:Ambiente am JOIN am.pnf pnf
                            GROUP BY am.pnf
                        ")
                        ->getResult();
                        
                        $cantidadtriunfadores = $em->createQuery(
                        "SELECT COUNT (t) as ct,pnf.nombre FROM MisionSucreRipesBundle:Triunfador t JOIN t.ambiente am JOIN am.pnf pnf
                            GROUP BY am.pnf
                        ")
                        ->getResult();
                     
                    case 8:
                        $usreje = $em->getRepository('MisionSucreRipesBundle:CoordinadorEje')->findOneByUser($usr->getId());
                        $eje = $usreje->getEje()->getId();
                        
                        $cantidadambientes = $em->createQuery(
                        "SELECT COUNT (am) as ca ,pnf.nombre FROM MisionSucreRipesBundle:Ambiente am JOIN am.pnf pnf ,  MisionSucreRipesBundle:Aldea a
                            JOIN a.parroquia prq JOIN prq.municipio m
                            WHERE prq.eje =:eje AND a.id=am.aldea
                            GROUP BY am.pnf
                        ")->setParameters(array('eje'=>$eje))
                        ->getResult();
                        
                        $cantidadtriunfadores = $em->createQuery(
                        "SELECT COUNT (t) as ct,pnf.nombre FROM MisionSucreRipesBundle:Triunfador t JOIN t.ambiente am JOIN am.pnf pnf,  MisionSucreRipesBundle:Aldea a
                            JOIN a.parroquia prq JOIN prq.municipio m
                            WHERE prq.eje =:eje AND a.id=am.aldea
                            GROUP BY am.pnf
                        ")->setParameters(array('eje'=>$eje))
                        ->getResult();
                    break;
                    
                    case 5:
                        
                        $coord = $em->getRepository('MisionSucreRipesBundle:CoordinadorAldea')->findOneByUser($usr->getId());
                        $aldea = $coord->getAldea()->getId();
                        
                        $cantidadambientes = $em->createQuery(
                        "SELECT COUNT (am) as ca ,pnf.nombre FROM MisionSucreRipesBundle:Ambiente am JOIN am.pnf pnf ,  MisionSucreRipesBundle:Aldea a
                            WHERE a.id =:aldea AND a.id=am.aldea
                            GROUP BY am.pnf
                        ")->setParameters(array('aldea'=>$aldea))
                        ->getResult();
                        
                        $cantidadtriunfadores = $em->createQuery(
                        "SELECT COUNT (t) as ct,pnf.nombre FROM MisionSucreRipesBundle:Triunfador t JOIN t.ambiente am JOIN am.pnf pnf,  MisionSucreRipesBundle:Aldea a
                            WHERE a.id =:aldea AND a.id=am.aldea
                            GROUP BY am.pnf
                        ")->setParameters(array('aldea'=>$aldea))
                        ->getResult();
                    break;
                
                }
                
                return $this->render(
			'MisionSucreRipesBundle:Pnf:resumeneje.html.twig',
                        array(
                            'cantamb'=>$cantidadambientes,'canttrf'=>$cantidadtriunfadores
                        ));
        }        
}
