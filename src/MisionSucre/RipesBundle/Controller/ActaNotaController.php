<?php

namespace MisionSucre\RipesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;
use MisionSucre\RipesBundle\Entity\ActaNota;
use MisionSucre\RipesBundle\Entity\ImpresionActaNota;
use MisionSucre\RipesBundle\Form\Type\ActaNotaType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class ActaNotaController extends Controller
{
    
    public function newAction(Request $request,$idpamb,$idm)
	{       
        
        $em = $this->getDoctrine()->getManager();
        
        $periodoacademicoambiente = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:PeriodoAcademicoAmbiente')
                ->find($idpamb);
                
                if(!$periodoacademicoambiente){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Periodo Académico-Ambiente con ID: '.$idpamb.' no registrado'
                );
                return $this->redirect($this->generateUrl('ambiente'));
                }
        $malla_pnf = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Malla')
                ->find($idm);
                
                if(!$malla_pnf){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Malla-Pnf con ID: '.$malla_pnf.' no registrado'
                );
                return $this->redirect($this->generateUrl('periodo_academico_ambiente_show'),  array('idpamb'=>$idpamb));
                }
        
        if($this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:ActaNota')
                ->existeUnidadCurricular($idpamb,$idm))
        {
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Unidad Curricular ya Vinculada a este Periodo Académico-Ambiente'
                );
                return $this->redirect($this->generateUrl('periodo_academico_ambiente_show'),  array('idpamb'=>$idppamb));
                }
        
                $ambiente = $periodoacademicoambiente->getAmbiente();
                $aldea = $ambiente->getAldea();
                $periodoacademico = $periodoacademicoambiente->getPeriodoAcademico();
                $periodopnf = $periodoacademicoambiente->getPeriodoPnf();
               
                /*VALIDAR */
                $validar = $this->get('servicios.validar');
                
                $error = $validar->ValidarAmbiente($ambiente,$aldea->getId(),$request);
                if($error)
                    return $this->redirect($this->generateUrl($error['url'],array($error['param']=>$error['valueparam'])));
                
                
                $actanota = new ActaNota();
                
        $form = $this->createForm(new ActaNotaType(),$actanota)->
               
        add('save', 'submit',array('label' => 'Vincular Acta Nota'));
            
                $form->handleRequest($request);
                
		if ($form->isValid()) {
                            $iddoc = $form->get('iddoc')->getData();
                            
                            if($iddoc){
                                $docente =  $this->getDoctrine()
                                ->getRepository('MisionSucreRipesBundle:Docente')
                                ->find($iddoc);
                                $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Acta de Nota Registrada con Éxito'
                            );  
                            $actanota->setPeriodoacademicoambiente($periodoacademicoambiente);    
                            $actanota->setMalla($malla_pnf);    
                            $actanota->setDocente($docente);
                            $em->persist($actanota);
                            $em->flush();
                            }
                            else{
                               $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Error en la Cédula'
                            );  
                            }
                                        
                    return $this->redirect($this->generateUrl('periodo_academico_ambiente_show',array('idpamb'=>$idpamb)));
		}
		
        return $this->render('MisionSucreRipesBundle:ActaNota:new.html.twig', array(
		'form' => $form->createView(),'mensaje_heading'=>"Vincular Acta de Nota",
                    'sub_heading'=>"Vincular Docente",'periodoacademico'=>$periodoacademico,'periodoambiente'=>$periodoacademicoambiente,
                            'periodopnf'=>$periodopnf,'ambiente'=>$ambiente,'malla'=>$malla_pnf
		));
                            
        }
        
    public function updateAction(Request $request,$idan)
	{       
        
        $em = $this->getDoctrine()->getManager();
        
        $actanota = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:ActaNota')
                ->find($idan);
                
                if(!$actanota){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            "Acta de Nota con ID $idan no Existe"
                );
                return $this->redirect($this->generateUrl('ambiente'));
                }
                
                if(strtoupper($actanota->getValidad())=='SI'){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            "Acta de Nota Bloqueada"
                );
                return $this->redirect($this->generateUrl('acta_nota_show'),  array('idan',$actanota->getId()));
                } 
                
                $periodoacademicoambiente = $actanota->getPeriodoacademicoambiente();
                $malla_pnf = $actanota->getMalla();
                $idpamb=$periodoacademicoambiente->getId();
                $ambiente = $periodoacademicoambiente->getAmbiente();
                $aldea = $ambiente->getAldea();
                $periodoacademico = $periodoacademicoambiente->getPeriodoAcademico();
                $periodopnf = $periodoacademicoambiente->getPeriodoPnf();
               
                /*VALIDAR */
                $validar = $this->get('servicios.validar');
                
                $error = $validar->ValidarAmbiente($ambiente,$aldea->getId(),$request);
                if($error)
                    return $this->redirect($this->generateUrl($error['url'],array($error['param']=>$error['valueparam'])));
                
        $form = $this->createForm(new ActaNotaType(),$actanota)->
               
        add('save', 'submit',array('label' => 'Cambiar Docente'));
            
                $form->handleRequest($request);
                
		if ($form->isValid()) {
                            
                            $em = $this->getDoctrine()->getManager();    
                            $iddoc = $form->get('iddoc')->getData();
                            
                            if($iddoc){
                                $docente =  $this->getDoctrine()
                                ->getRepository('MisionSucreRipesBundle:Docente')
                                ->find($iddoc);
                                $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Docente Cambiado con Éxito'
                            );  
                            $actanota->setDocente($docente);
                            $em->persist($actanota);
                            $em->flush();
                            }
                            else{
                               $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Error en la Cédula'
                            );  
                            }
                                        
                    return $this->redirect($this->generateUrl('periodo_academico_ambiente_show',array('idpamb'=>$idpamb)));
		}
		
        return $this->render('MisionSucreRipesBundle:ActaNota:new.html.twig', array(
		'form' => $form->createView(),'mensaje_heading'=>"Vincular Acta de Nota",
                    'sub_heading'=>"Cambiar Docente",'periodoacademico'=>$periodoacademico,'periodoambiente'=>$periodoacademicoambiente,
                            'periodopnf'=>$periodopnf,'ambiente'=>$ambiente,'malla'=>$malla_pnf
		));
                            
                
                            
        }
    public function deleteAction(Request $request,$idan){       
        
        $em = $this->getDoctrine()->getManager();
        
        $actanota = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:ActaNota')
                ->find($idan);
                
                if(!$actanota){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            "Acta de Nota con ID $idan no Existe"
                );
                return $this->redirect($this->generateUrl('ambiente'));
                }
                
                $periodoacademicoambiente = $actanota->getPeriodoacademicoambiente();
                $idpamb=$periodoacademicoambiente->getId();
                $ambiente = $periodoacademicoambiente->getAmbiente();
                $aldea = $ambiente->getAldea();
                $periodoacademico = $periodoacademicoambiente->getPeriodoAcademico();
                $periodopnf = $periodoacademicoambiente->getPeriodoPnf();
               
                /*VALIDAR */
                $validar = $this->get('servicios.validar');
                
                $error = $validar->ValidarAmbiente($ambiente,$aldea->getId(),$request);
                if($error)
                    return $this->redirect($this->generateUrl($error['url'],array($error['param']=>$error['valueparam'])));
        
                            $em->remove($actanota);
                            $em->flush();
                               $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Acta Eliminada con Éxito'
                            );  
                    return $this->redirect($this->generateUrl('periodo_academico_ambiente_show',array('idpamb'=>$idpamb)));
        }
    
    public function dataDocenteAction($cedula,$aldea,Request $request)
	{
        
        $em = $this->getDoctrine()->getManager();
        
        $per = $em->getRepository('MisionSucreRipesBundle:Persona')->findOneByCedPer($cedula);
        
        $apellidos="";
        $nombres="";
        $perfil="";
        $idusr=0;
        $iddoc=0;
        
        $error=0;
        
        if($per){
        
        $validar = $this->get('servicios.validar');
        
        $idusr= $per->getUser()->getId();
        
        $errordocente = $validar->ValidarDocenteAldea($idusr,$aldea,$request);
        
        if($errordocente){
            $error=$errordocente['msg'];
        }
                else{
        $nombres=$per->getPriNom() ." ". $per->getSegNom();
        $apellidos=$per->getPriApe() ." ". $per->getSegApe();
        
        $docente = $em->getRepository('MisionSucreRipesBundle:Docente')->findDocenteByUserAndId($aldea,$idusr);
        
        $iddoc=$docente->getId();
        
                }
        }
        
        else{
            $error="Cédula no encontrada";
        }
        
        return new JsonResponse(array('nombres'=>$nombres,
                                'idusr'=>$idusr,
                                'iddoc'=>$iddoc,
                                'error' => $error,'apellidos'=>$apellidos
                                 
                ));
        }    
public function showAction(Request $request,$idan)
	{       
        
        $em = $this->getDoctrine()->getManager();
        
        $actanota = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:ActaNota')
                ->find($idan);
                
                if(!$actanota){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            "Acta de Nota con ID $idan no Existe"
                );
                return $this->redirect($this->generateUrl('ambiente'));
                }
                
                $periodoacademicoambiente = $actanota->getPeriodoacademicoambiente();
                $idpamb=$periodoacademicoambiente->getId();
                $ambiente = $periodoacademicoambiente->getAmbiente();
                $aldea = $ambiente->getAldea();
                $periodoacademico = $periodoacademicoambiente->getPeriodoAcademico();
                $periodopnf = $periodoacademicoambiente->getPeriodoPnf();
                $malla_pnf = $actanota->getMalla();
                $triunfadores= $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:PeriodoTriunfador')
                ->TriunfadoresVinculados($idpamb);
                $notas= $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Nota')
                ->TriunfadoresVinculadosConNotas($actanota->getId());
                $docente = $actanota->getDocente();
                $docente= $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Persona')
                ->findOneByUser($docente->getUser()->getId());
                
                /*VALIDAR */
                $validar = $this->get('servicios.validar');
                
                $error = $validar->ValidarAmbiente($ambiente,$aldea->getId(),$request);
                if($error)
                    return $this->redirect($this->generateUrl($error['url'],array($error['param']=>$error['valueparam'])));
		
        return $this->render('MisionSucreRipesBundle:ActaNota:show.html.twig', array(
		'periodoacademico'=>$periodoacademico,'periodoambiente'=>$periodoacademicoambiente,
                            'periodopnf'=>$periodopnf,'ambiente'=>$ambiente,'malla'=>$malla_pnf,
                'triunfadores'=> $triunfadores,'docente'=> $docente,'actanota'=>$actanota,'notas'=>$notas
		));                    
        }
public function validarAction(Request $request,$idan)
	{       
        
        $em = $this->getDoctrine()->getManager();
        
        $actanota = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:ActaNota')
                ->find($idan);
                
                if(!$actanota){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            "Acta de Nota con ID $idan no Existe"
                );
                return $this->redirect($this->generateUrl('ambiente'));
                }
                
                $periodoacademicoambiente = $actanota->getPeriodoacademicoambiente();
                $malla_pnf = $actanota->getMalla();
                $idpamb=$periodoacademicoambiente->getId();
                $ambiente = $periodoacademicoambiente->getAmbiente();
                $aldea = $ambiente->getAldea();
                $periodoacademico = $periodoacademicoambiente->getPeriodoAcademico();
                $periodopnf = $periodoacademicoambiente->getPeriodoPnf();
               
                /*VALIDAR */
                $validar = $this->get('servicios.validar');
                
                $error = $validar->ValidarAmbiente($ambiente,$aldea->getId(),$request);
                if($error)
                    return $this->redirect($this->generateUrl($error['url'],array($error['param']=>$error['valueparam'])));
                
                //REGISTRAR EN DB
                $actanota->setValidada("SI");
                $em->persist($actanota);
                /*
                 * DATOS DE IMPRESIÓN
                 */
                
                $impresion= $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:ImpresionActaNota')
                ->findOneByActanota($idan);
                
                if($impresion){
                $fecha = new \DateTime(date("Y-m-d"));
                $codigo = hash('md5',$fecha->format(date("Y-m-d H:i:s")));
                $impresion->setCodigo($codigo);
                $impresion->setFechaModificacion($fecha);
                $impresion->setActanota($actanota);
                
                }
                    else{
                
                $impresion = new ImpresionActaNota();
                $fecha = new \DateTime(date("Y-m-d"));
                $codigo = hash('md5',$fecha->format(date("Y-m-d H:i:s")));
                $impresion->setCodigo($codigo);
                $impresion->setFechaCreacion($fecha);
                $impresion->setFechaModificacion($fecha);
                $impresion->setActanota($actanota);
                    }
                $em->persist($impresion);
                $em->flush();
            $request->getSession()->getFlashBag()->add(
            'notice',
            "Acta de Nota Validada con Éxito"
                );    
            return $this->redirect($this->generateUrl('vistaprevia_acta_nota',array('idan'=>$idan)));    
                            
        }        
    public function desbloquearAction(Request $request,$idan)
	{       
        
        $em = $this->getDoctrine()->getManager();
        
        $actanota = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:ActaNota')
                ->find($idan);
                
                if(!$actanota){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            "Acta de Nota con ID $idan no Existe"
                );
                return $this->redirect($this->generateUrl('ambiente'));
                }
                
            $actanota->setValidada("");
                    
                $em->persist($actanota);
                $em->flush();
            $request->getSession()->getFlashBag()->add(
            'notice',
            "Acta de Nota Desbloqueada con Éxito"
                );    
            return $this->redirect($this->generateUrl('acta_nota_show',array('idan'=>$idan)));        
                
        }
}
