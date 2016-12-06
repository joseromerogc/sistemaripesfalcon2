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
            if($ambiente->getPnf()->getModalidad()=="TRMESTRAL"){
            $p="Periodo";
            }
            else{
                $p="Tramo";
            }
		$form = $this->createFormBuilder($periodoacademicoambiente)->
        add('_periodoacademico', 'choice', array(
        'choices' => $this->PeriodosAcademicos($ambiente),
            'placeholder'=>"Seleccione una",'label' => 'Periodo Académicos',
            'mapped'=>false
            ))
             ->add('_periodopnf', 'choice', array(
        'choices' => $this->PeriodoPnf($ambiente),
            'placeholder'=>"Seleccione una",'label' => "Trayecto/$p", 
                 'mapped'=>false
            ))                     
             ->add('save', 'submit',array('label' => 'Registrar Periodo Académico'))->getForm();
            
                $form->handleRequest($request);
                
		if ($form->isValid()) {
                            
                            $em = $this->getDoctrine()->getManager();    
                            
                            $idperiodo = $form->get('_periodoacademico')->getData();
                            $idppnf = $form->get('_periodopnf')->getData();
                            
                            $periodoacademico = $em->getRepository('MisionSucreRipesBundle:PeriodoAcademico')->find($idperiodo);
                            $periodoPnf= $em->getRepository('MisionSucreRipesBundle:PeriodoPnf')->find($idppnf);
                            
                            
                            $periodoacademicoambiente->setAmbiente($ambiente);    
                            $periodoacademicoambiente->setPeriodoAcademico($periodoacademico); 
                            $periodoacademicoambiente->setPeriodoPnf($periodoPnf); 
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
                
                if($ambiente->getPnf()->getModalidad()=="TRMESTRAL"){
            $p="Periodo";
            }
            else{
                $p="Tramo";
            }
                
		$form = $this->createFormBuilder($periodoacademicoambiente)->
        add('_periodoacademico', 'choice', array(
        'choices' => $this->PeriodosAcademicos($ambiente),
            'placeholder'=>$nombreperiodo,'label' => 'Periodo Académicos',
            'empty_data'=>NULL,
            'required'=>false,
            'mapped'=>false
            ))
             ->add('_periodopnf', 'choice', array(
        'choices' => $this->PeriodoPnf($ambiente),
            'placeholder'=>"Seleccione una",'label' => "Trayecto/$p", 
                 'mapped'=>false
            ))                     
             ->add('save', 'submit',array('label' => 'Actualizar Periodo Académico'))->getForm();
            
                $form->handleRequest($request);
                
                
		if ($form->isValid()) {
                            
                            $em = $this->getDoctrine()->getManager();    
                            
                            $idperiodo = $form->get('_periodoacademico')->getData();
                            $idppnf = $form->get('_periodopnf')->getData();
                            
                            if($idperiodo){
                                $periodoacademico = $em->getRepository('MisionSucreRipesBundle:PeriodoAcademico')->find($idperiodo);
                                $periodoacademicoambiente->setPeriodoAcademico($periodoacademico); 
                            }
                            
                            $periodoPnf= $em->getRepository('MisionSucreRipesBundle:PeriodoPnf')->find($idppnf);
                            
                            
                            $periodoacademicoambiente->setAmbiente($ambiente);    
                            
                            $periodoacademicoambiente->setPeriodoPnf($periodoPnf); 
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
            ->getRepository('MisionSucreRipesBundle:PeriodoAcademicoAmbiente')
            ->PeriodosAcademicosDisponibles($modalidad,$amb->getId());
    
         foreach ($periodosacademicos as $p) {
        $choices[$p->getId()] =$p->getNombre();
        }
        return $choices;
        }
        
        
protected function PeriodoPnf($amb) {
            
            $choices= array();
            
            $pnf = $amb->getPnf()->getId();
            
            $trayectos = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:PeriodoAcademicoAmbiente')
                ->TrayectosPnfDisponibles($amb,$pnf);
            
            if($amb->getPnf()->getModalidad()=="TRIMESTRAL"){
            $p="Periodo";
            }
            else{
                $p="Tramo";
            }
            
            foreach ($trayectos as $t) {
                
            $periodos = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:PeriodoAcademicoAmbiente')
                ->PeriodosPnfDisponibles($amb,$pnf,$t["trayecto"]);  
            $array_periodos=array();
                    foreach ($periodos as $pe)
                    $array_periodos[$pe['ppnfid']]="{$t["trayecto"]}/ $p ".$pe['periodo'];
            $choices["Trayecto {$t["trayecto"]}"]=$array_periodos;
            }
             
            
            return $choices;
        }
public function showAction(Request $request,$idpamb)
	{       
                $em = $this->getDoctrine()->getManager();
                
                $periodoacademicoambiente = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:PeriodoAcademicoAmbiente')
                ->find($idpamb);
                
                $ambiente = $periodoacademicoambiente->getAmbiente();
                
                $idamb =$ambiente->getId();
                
                if(!$periodoacademicoambiente){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Periodo Académico-Ambiente con ID: '.$idpamb.' no registrado'
                );
                return $this->redirect($this->generateUrl('ambiente'));
                }
                
                $idaldea = $ambiente->getAldea()->getId();
                
                $aldea = $ambiente->getAldea();
               
                $periodoacademico = $periodoacademicoambiente->getPeriodoAcademico();
                $periodopnf = $periodoacademicoambiente->getPeriodoPnf();
                
                /* @var $validar \MisionSucre\RipesBundle\Ambiente */
                $validar = $this->get('servicios.validar');
                
                $error = $validar->ValidarAmbiente($ambiente,$aldea->getId(),$request);
                if($error)
                    return $this->redirect($this->generateUrl($error['url'],array($error['param']=>$error['valueparam'])));
                
                $ucs['vinculadas'] =$this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:ActaNota')
                ->UCVinculadasPeriodoAmbiente($idpamb);
                $ucs['novinculadas'] =$this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:ActaNota')
                ->UCnoVinculadasPeriodoAmbiente($idpamb,$periodopnf->getId());
                
		return $this->render(
			'MisionSucreRipesBundle:PeriodoAcademicoAmbiente:show.html.twig',
			array('ambiente'=>$ambiente,'periodoacademico'=>$periodoacademico,'periodoambiente'=>$periodoacademicoambiente,
                            'periodopnf'=>$periodopnf,'ucs'=>$ucs
                        ));
	}
        
}
