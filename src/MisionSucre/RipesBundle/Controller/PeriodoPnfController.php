<?php

namespace MisionSucre\RipesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MisionSucre\RipesBundle\Entity\PeriodoPnf;
use Doctrine\ORM\EntityRepository;
use MisionSucre\RipesBundle\Form\Type\PeriodoPnfType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\HttpFoundation\Response;


class PeriodoPnfController extends Controller
{
	
	public function newAction(Request $request)
	{       
		$pp = new PeriodoPnf();
                
                $em = $this->getDoctrine()->getManager();
                
                $idpnf = $request->request->get('idpnf');
                $trayecto = $request->request->get('trayecto');
                $periodo = $request->request->get('periodo');
                
//                $idpnf = 1;
//                $trayecto = 1;
//                $periodo = 1;
                
                $pnf =  $em->getRepository('MisionSucreRipesBundle:Pnf')->find($idpnf);
                
                if(!$pnf){
                    
                    return new Response("<h4>PNF no Existe</h4>");	             
                }
                
                if($pnf->getModalidad()=="TRIMESTRAL"){
                    $p="Periodo";
                }
                else{
                    $p="Tramos";
                }
                
                if($em->getRepository('MisionSucreRipesBundle:PeriodoPnf')->existe($idpnf,$trayecto,$periodo)){
                    
                    return new Response("<h4>Ya existe este Trayecto");	             
                }
                                               
                            $pp->setPnf($pnf);
                            $pp->setTrayecto($trayecto);
                            $pp->setPeriodo($periodo);
                            $em->persist($pp);
                            $em->flush();
               return new Response("<h4>Trayecto/$p Registrado con Éxito</h4>");	             

}
public function listapnfAction(Request $request)
	{       
                $em = $this->getDoctrine()->getManager();
                
                $idpnf = $request->request->get('idpnf');
                
                //$idpnf=2;
                $pnf =  $em->getRepository('MisionSucreRipesBundle:Pnf')->find($idpnf);
                
                $periodospnf = $em->getRepository('MisionSucreRipesBundle:PeriodoPnf')->findByPnf($idpnf);
                
                
                if(!$pnf){
                    
                    $request->getSession()->getFlashBag()->add(
                    'notice',
                    'Pnf con ID: '.$id.' no registrado'
                    );    
                    return $this->redirect($this->generateUrl('pnf_lista'));
                }
                
                if($pnf->getModalidad()=="TRIMESTRAL"){
                    $p="Periodo";
                }
                else{
                    $p="Tramo";
                }
                
		return $this->render(
			'MisionSucreRipesBundle:PeriodosPnf:listapnf.html.twig',
                array('periodospnf' => $periodospnf,'periodo'=>$p)
		);	
	}	
        
        public function updateAction(Request $request,$idppnf)
	{       
                $em = $this->getDoctrine()->getManager();
                
		$pp = $em->getRepository('MisionSucreRipesBundle:PeriodoPnf')->find($idppnf);
                
                if(!$pp){
                    
                    $request->getSession()->getFlashBag()->add(
                    'notice',
                    "$p con ID: '.$id.' no registrado"
                    );    
                    return $this->redirect($this->generateUrl('pnf_show',array('id'=>$pnf->getId())));
                }
                $pnf = $pp->getPnf();
                $idpnf = $pp->getPnf()->getId();
                
                if($pnf->getModalidad()=="TRIMESTRAL"){
                    $p="Periodo";
                }
                else{
                    $p="Tramo";
                }
                
                $form = $this->createForm(new PeriodoPnfType(),$pp)
                        ->add('save', 'submit',array('label' => "Actualizar $p"))
                        ;
                
                
                $form->handleRequest($request);
                
                if ($form->isValid()) { 
                if($request->getMethod() == 'POST'){
                          $trayecto=$form->get('trayecto')->getData();
                          $periodo=$form->get('periodo')->getData();
                          
                          if($em->getRepository('MisionSucreRipesBundle:PeriodoPnf')->existe($idpnf,$trayecto,$periodo)){
                    $request->getSession()->getFlashBag()->add(
                            'notice',
                            "$p ya Registrado"
                            ); 
                          }else
                              {
                              
                            $em->persist($pp);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            "$p Actualizado  Éxito "
                            );            
                    return $this->redirect($this->generateUrl('pnf_show',array('id'=>$idpnf)));
		}
                          }
                }
                
		return $this->render('MisionSucreRipesBundle:PeriodosPnf:new.html.twig', array(
		'form' => $form->createView(),'mensaje_heading'=>"Actualizar $p",
                    'sub_heading'=>"P.N.F. ".$pnf->getNombre()
		));
        }
        
        public function deleteAction(Request $request,$idppnf)
	{       
                $em = $this->getDoctrine()->getManager();
                
		$pp = $em->getRepository('MisionSucreRipesBundle:PeriodoPnf')->find($idppnf);
                
                if(!$pp){
                    
                    $request->getSession()->getFlashBag()->add(
                    'notice',
                    "$p con ID: '.$id.' no registrado"
                    );    
                    return $this->redirect($this->generateUrl('pnf_show',array('id'=>$pnf->getId())));
                }
                $pnf = $pp->getPnf();
                $idpnf = $pp->getPnf()->getId();
                
                $ucmalla = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Malla')
                ->findByPeriodopnf($idppnf);
                
                if($pnf->getModalidad()=="TRIMESTRAL"){
                    $p="Periodo";
                }
                else{
                    $p="Tramo";
                }
                
                if($ucmalla){
                    $request->getSession()->getFlashBag()->add(
                    'notice',
                    "$p No se puede Eliminar. Tiene Unidades Curriculares Vinculadas"
                    );    
                    return $this->redirect($this->generateUrl('pnf_show',array('id'=>$idpnf)));
                }
                

                            $em->remove($pp);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            "$p Eliminado con $trayecto Éxito $periodo"
                            );            
                    return $this->redirect($this->generateUrl('pnf_show',array('id'=>$idpnf)));
		
        }
} 



