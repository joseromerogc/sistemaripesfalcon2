<?php

namespace MisionSucre\RipesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MisionSucre\RipesBundle\Entity\PeriodoAcademico;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;

class PeriodoAcademicoController extends Controller
{
    public function indexAction()
    {
        return $this->render('MisionSucreRipesBundle:PeriodoAcademico:index.html.twig');
    }
	
	public function newAction(Request $request)
	{       
                
                $em = $this->getDoctrine()->getManager();
                  
                $peracad = new PeriodoAcademico();
                
		$form = $this->createFormBuilder($peracad)->
                add('nombre', 'text',array('label' => 'Nombre de Periodo Académico'))        
                ->add('modalidad', 'choice',array('choices'=>array('TRIMESTRAL'=>'TRIMESTRAL','SEMESTRAL'=>'SEMESTRAL','TI'=>'T.I.'),'label' => 'Modalidad'))        
                ->add('fechainicio', 'date',array('label' => 'Fecha de Inicio'))        
                ->add('fechafin', 'date',array('label' => 'Fecha de Culminación'))  
                ->add('actual', 'choice',array('choices'=>array('SI'=>'SI','NO'=>'NO'),'label' => 'Periodo Académico Actual','empty_data'=>'NO'))   
             ->add('save', 'submit',array('label' => 'Registrar Periodo Académico'))->getForm();
            
                $form->handleRequest($request);
                
                
		if ($form->isValid()) {
                            
                    $actual= $form->get('actual')->getData();
                    $modalidad= $form->get('modalidad')->getData();
                    
                    if($actual=="SI"){
                            $update = $em->createQuery(
                        "UPDATE MisionSucreRipesBundle:PeriodoAcademico pa
                            SET pa.actual=:actual
                            WHERE pa.modalidad LIKE :modalidad
                            
                        ")
                        ->setParameters(array('actual'=>'NO','modalidad'=>$modalidad))
                            ->getResult();
                        $peracad->setActual('SI');
                    }
                            
                            $em->persist($peracad);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Periodo Académico Creado con Éxito'
                            );  
                         return $this->redirect($this->generateUrl('periodo_academico_lista'));
		}
             
		return $this->render('MisionSucreRipesBundle:PeriodoAcademico:new.html.twig', array(
		'form' => $form->createView(),'mensaje_heading'=>'Nuevo Periodo Académico'
		));
	}
        
	public function updateAction(Request $request,$id)
	{       
                
                $em = $this->getDoctrine()->getManager();
                
                $peracad = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:PeriodoAcademico')
                ->find($id);
                
                if(!$peracad){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Periodo Académico con ID: '.$idaldea.' no registrado'
                );
                return $this->redirect($this->generateUrl('periodo_academico_lista'));
                }
                
		$form = $this->createFormBuilder($peracad)->
                add('nombre', 'text',array('label' => 'Nombre de Periodo Académico'))        
                ->add('modalidad', 'choice',array('choices'=>array('TRIMESTRAL'=>'TRIMESTRAL','SEMESTRAL'=>'SEMESTRAL','TI'=>'T.I.'),'label' => 'Modalidad'))        
                ->add('fechainicio', 'date',array('label' => 'Fecha de Inicio'))        
                ->add('fechafin', 'date',array('label' => 'Fecha de Culminación'))  
                ->add('actual', 'choice',array('choices'=>array('SI'=>'SI','NO'=>'NO'),'label' => 'Periodo Académico Actual','empty_data'=>'NO'))   
             ->add('save', 'submit',array('label' => 'Registrar Periodo Académico'))->getForm();
            
                $form->handleRequest($request);
                
                
		if ($form->isValid()) {
                     
                    $actual= $form->get('actual')->getData();
                    $modalidad= $form->get('modalidad')->getData();
                    
                    if($actual=="SI"){
                            $update = $em->createQuery(
                        "UPDATE MisionSucreRipesBundle:PeriodoAcademico pa
                            SET pa.actual=:actual
                            WHERE pa.modalidad LIKE :modalidad
                            
                        ")
                        ->setParameters(array('actual'=>'NO','modalidad'=>$modalidad))
                            ->getResult();
                        $peracad->setActual('SI');
                    }
                            $em->persist($peracad);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Periodo Académico Actualizado con Éxito'.$actual
                            );  
                         return $this->redirect($this->generateUrl('periodo_academico_lista'));
		}
             
		return $this->render('MisionSucreRipesBundle:PeriodoAcademico:new.html.twig', array(
		'form' => $form->createView(),'mensaje_heading'=>'Actualizar Periodo Académico'
		));
	}
        
        public function listaAction(Request $request)
	{   
            $em = $this->getDoctrine()->getManager();
            
            $periodos_academicos = $this->getDoctrine()->getRepository('MisionSucreRipesBundle:PeriodoAcademico')->findAll();
                
		if(!$periodos_academicos){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Ningun Periodo Académico Registrado '
            );
            return $this->redirect($this->generateUrl('periodo_academico'));
            }
            
		return $this->render(
			'MisionSucreRipesBundle:PeriodoAcademico:lista.html.twig',
                array('periodos_academicos' => $periodos_academicos)
		);	
	}
        
        public function showAction(Request $request,$id)
	{       
                $em = $this->getDoctrine()->getManager();
                
                $periodo_academico = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:PeriodoAcademico')
                ->find($id);
                
                
                
                if(!$periodo_academico){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Periodo Académico con ID: '.$idaldea.' no registrado'
                );
                return $this->redirect($this->generateUrl('periodo_academico_lista'));
                }
                
                $cantidadambiente= $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:PeriodoAcademico')
                ->findAllPeriodoAcademico($id);
                
		return $this->render(
			'MisionSucreRipesBundle:PeriodoAcademico:show.html.twig',
			array('periodoacademico'=>$periodo_academico,'cantidadambiente' =>$cantidadambiente
                        ));
	}
    
    public function modalidadAction($modalidad) {
        
    $em = $this->getDoctrine()->getManager();
    $periodos = $em->getRepository('MisionSucreRipesBundle:PeriodoAcademico')->findByModalidad($modalidad);

    return $this->render('MisionSucreRipesBundle:PeriodoAcademico:periodosacademicosmodalidades.html.twig',array('periodos'=>$periodos));
    } 
        

        
}
