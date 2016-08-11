<?php

namespace MisionSucre\RipesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MisionSucre\RipesBundle\Entity\PeriodoAcademicoAmbiente;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;

class PeriodoAcademicoAmbienteController extends Controller
{
    public function indexAction()
    {
        return $this->render('MisionSucreRipesBundle:PeriodoAcademicoAmbiente:index.html.twig');
    }
    
    public function newAction(Request $request,$idamb)
	{       
		
                $periodoacademicoambiente = new PeriodoAcademicoAmbiente();
                
                $em = $this->getDoctrine()->getManager();
                
                $ambiente = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Ambiente')
                ->find($idamb);
                
                $usr = $this->getUser();
                
                $idaldea = $ambiente->getAldea()->getId();
                
                
                $aldea = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Aldea')
                ->find($idaldea);
                
                if(!$ambiente){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Ambiente con ID: '.$idambiente.' no registrado'
                );
                return $this->redirect($this->generateUrl('usuario_show',array('id'=>$usr->getId())));
                }
                
                switch($usr->getTipUsr()){
                
                    case 5:
                        $caldea = $this->getDoctrine()
                            ->getRepository('MisionSucreRipesBundle:CoordinadorAldea')
                            ->findOneByUser($usr->getId());

                            if( $caldea->getAldea()->getId() != $idaldea) {   
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Accesso Denegado a esta Aldea'
                            );
                            return $this->redirect($this->generateUrl('usuario_show',array('id'=>$usr->getId())));
                            }
                        break;
                    case 8:
                        $ceje = $this->getDoctrine()
                            ->getRepository('MisionSucreRipesBundle:CoordinadorEje')
                            ->findOneByUser($usr->getId());

                            if( $ceje->getEje()->getId() != $aldea->getParroquia()->getEje()->getId()) {   
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Accesso Denegado a este Eje'
                            );
                            return $this->redirect($this->generateUrl('usuario_show',array('id'=>$usr->getId())));
                            }
                        break;
                }
                
		$form = $this->createFormBuilder($periodoacademicoambiente)->
        add('_periodoacademico', 'choice', array(
        'choices' => $this->PeriodosAcademicos($ambiente),
            'placeholder'=>"Seleccione una",'label' => 'Periodo Académicos',
            'mapped'=>false
            ))
             ->add('trayecto', 'choice', array(
        'choices' => $this->Trayectos(),
            'placeholder'=>"Seleccione una",'label' => 'Trayecto'
            ))           
             ->add('periodo', 'choice', array(
        'choices' => $this->Periodos($ambiente),
            'placeholder'=>"Seleccione una",'label' => 'Periodo'
            ))           
             ->add('save', 'submit',array('label' => 'Registrar Periodo'))->getForm();
            
                $form->handleRequest($request);
                
                
		if ($form->isValid()) {
                            
                            $em = $this->getDoctrine()->getManager();    
                            
                            $idperiodo = $form->get('_periodoacademico')->getData();
                            
                            $periodoacademico = $em->getRepository('MisionSucreRipesBundle:PeriodoAcademico')->find($idperiodo);
                            
                            $periodoacademicoambiente->setAmbiente($ambiente);    
                            $periodoacademicoambiente->setPeriodoAcademico($periodoacademico); 
                            $em->persist($periodoacademicoambiente);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Periodo Académico Registrado con Éxito'
                            );            
                    return $this->redirect($this->generateUrl('ambiente_show',array('idamb'=>$idamb,'ambiente'=>$ambiente)));
                    
		}
		
		return $this->render('MisionSucreRipesBundle:PeriodoAcademicoAmbiente:new.html.twig', array(
		'form' => $form->createView(),'mensaje_heading'=>'Registro de Periodo Académico Ambiente',
                    'sub_heading'=>'Aldea Universitaria <br>'.$aldea->getNombre(),
                    'ambiente'=>$ambiente
		));
	}
        
    public function updateAction(Request $request,$idper)
	{       
                $em = $this->getDoctrine()->getManager();
        
                $periodoacademicoambiente = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:PeriodoAcademicoAmbiente')
                ->find($idper);
                
                if(!$periodoacademicoambiente){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Periodo Acadèmico de Ambiente con ID: '.$idambiente.' no registrado'
                );
                return $this->redirect($this->generateUrl('lista_ambiente'));
                }
                
                $ambiente = $periodoacademicoambiente->getAmbiente();
                
                $idamb = $ambiente->getId();
                
                
                $usr = $this->getUser();
                
                $idaldea = $ambiente->getAldea()->getId();
                
                
                $aldea = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Aldea')
                ->find($idaldea);
                
                if(!$ambiente){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Ambiente con ID: '.$idambiente.' no registrado'
                );
                return $this->redirect($this->generateUrl('usuario_show',array('id'=>$usr->getId())));
                }
                
                
                
                switch($usr->getTipUsr()){
                
                    case 5:
                        $caldea = $this->getDoctrine()
                            ->getRepository('MisionSucreRipesBundle:CoordinadorAldea')
                            ->findOneByUser($usr->getId());

                            if( $caldea->getAldea()->getId() != $idaldea) {   
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Accesso Denegado a esta Aldea'
                            );
                            return $this->redirect($this->generateUrl('usuario_show',array('id'=>$usr->getId())));
                            }
                        break;
                    case 8:
                        $ceje = $this->getDoctrine()
                            ->getRepository('MisionSucreRipesBundle:CoordinadorEje')
                            ->findOneByUser($usr->getId());

                            if( $ceje->getEje()->getId() != $aldea->getParroquia()->getEje()->getId()) {   
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Accesso Denegado a este Eje'
                            );
                            return $this->redirect($this->generateUrl('usuario_show',array('id'=>$usr->getId())));
                            }
                        break;
                }
                
                $nombreperiodo = $periodoacademicoambiente->getPeriodoAcademico()->getNombre();
                
		$form = $this->createFormBuilder($periodoacademicoambiente)->
        add('_periodoacademico', 'choice', array(
        'choices' => $this->PeriodosAcademicos($ambiente),
            'placeholder'=>"$nombreperiodo",'label' => 'Periodo Académicos',
            'mapped'=>false,'required' => false
            ))
             ->add('trayecto', 'choice', array(
        'choices' => $this->Trayectos(),
            'placeholder'=>"Seleccione una",'label' => 'Trayecto'
            ))           
             ->add('periodo', 'choice', array(
        'choices' => $this->Periodos($ambiente),
            'placeholder'=>"Seleccione una",'label' => 'Periodo'
            ))           
             ->add('save', 'submit',array('label' => 'Actualizar Periodo'))->getForm();
            
                $form->handleRequest($request);
                
                
		if ($form->isValid()) {
                            
                            $em = $this->getDoctrine()->getManager();    
                            
                            $idperiodo = $form->get('_periodoacademico')->getData();
                            if($idperiodo){
                            $periodoacademico = $em->getRepository('MisionSucreRipesBundle:PeriodoAcademico')->find($idperiodo);
                            $periodoacademicoambiente->setPeriodoAcademico($periodoacademico); 
                             }
                            $periodoacademicoambiente->setAmbiente($ambiente);    
                            
                            $em->persist($periodoacademicoambiente);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Periodo Académico Actualizado con Éxito'
                            );            
                    return $this->redirect($this->generateUrl('ambiente_show',array('idamb'=>$idamb,'ambiente'=>$ambiente)));
                    
		}
		
		return $this->render('MisionSucreRipesBundle:PeriodoAcademicoAmbiente:new.html.twig', array(
		'form' => $form->createView(),'mensaje_heading'=>'Actualización de Periodo Académico Ambiente',
                    'sub_heading'=>'Aldea Universitaria <br>'.$aldea->getNombre(),
                    'ambiente'=>$ambiente
		));
	}


        protected function PeriodosAcademicos($amb) {
        
        $modalidad = $amb->getPnf()->getModalidad();
        $choices = array();
    
         $periodosacademicos = $this->getDoctrine()
            ->getRepository('MisionSucreRipesBundle:PeriodoAcademico')
            ->findByModalidad($modalidad);
    
         foreach ($periodosacademicos as $p) {
        $choices[$p->getId()] =$p->getNombre();
        }
        return $choices;
        }
        
        protected function trayectos() {
            
            $choices=  array();
            
            $trayectos= array();
            for($i=1;$i<6;$i++)
                $choices["$i"] ="$i";
            return $choices;
        }
        
        protected function periodos($amb) {
            
            $choices= array();
            
            if($amb->getPnf()->getModalidad()=="SEMESTRAL"){
            for($i=1;$i<3;$i++)
                $choices["$i"] ="$i";
            }
            else{
                for($i=1;$i<4;$i++)
                    $choices["$i"] ="$i";
            }
            
            return $choices;
        }
        
}
