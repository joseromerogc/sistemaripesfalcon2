<?php

namespace MisionSucre\RipesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;
use MisionSucre\RipesBundle\Entity\PeriodoTriunfador;
use Symfony\Component\HttpFoundation\Response;

class PeriodoTriunfadorController extends Controller
{
    
    public function newAction(Request $request)
	{       
        
        $em = $this->getDoctrine()->getManager();
                $idppamb = $request->request->get('pidppamb');
                $idusr = $request->request->get('pidusr');
//        $idppamb =1;
//        $idusr   =271;     
        
        $periodoacademicoambiente = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:PeriodoAcademicoAmbiente')
                ->find($idppamb);
                
                if(!$periodoacademicoambiente){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Periodo Académico-Ambiente con ID: '.$idpamb.' no registrado'
                );
                return $this->redirect($this->generateUrl('ambiente'));
                }
                
        if($this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:PeriodoTriunfador')
                ->existeTriunfador($idppamb,$idusr))
        {
            return new Response("<h1>Triunfador ya vinculado a este Periodo Académico-Ambiente</h1>");
        }
                
                
                
                $ambiente = $periodoacademicoambiente->getAmbiente();
                $idamb =$ambiente->getId();
                
                
                $idaldea = $ambiente->getAldea()->getId();
                
                $aldea = $ambiente->getAldea();
               
                /*VALIDAR */
                $validar = $this->get('servicios.validar');
                
                $error = $validar->ValidarAmbiente($ambiente,$aldea->getId(),$request);
                if($error)
                    return new Response("<h1>ERROR al Vincular Triunfador</h1>");
                
                $error = $validar->ValidarTriunfador($idusr,$request,$idamb);
                if($error)
                    return new Response("<h1>ERROR al Vincular Triunfador</h1>");
                
                $idpacad=$periodoacademicoambiente->getPeriodoAcademico()->getId();
                
                if($this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:PeriodoTriunfador')
                ->ExistePeriodoAcademico($idpacad,$idusr)){
                    return new Response("<h1>Triunfador ya vinculado a este Periodo Académico</h1>");
                }
                
                
                $usertriunfador = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:User')
                ->find($idusr);
                
                $periodotriunfador = new PeriodoTriunfador();
                
                            $periodotriunfador->setUser($usertriunfador); 
                            $periodotriunfador->setPeriodoAcademicoAmbiente($periodoacademicoambiente); 
                            $em->persist($periodotriunfador);
                            $em->flush();
                            
                            return new Response("<h1>Triunfador Vinculado con Éxito al Periodo Académico</h1>");
                            
        }
    
    public function tablaTriunfadoresvinculadosAction(Request $request){
        $em = $this->getDoctrine()->getManager();
                $idppamb = $request->request->get('idppamb');
                
                $periodoacademicoambiente = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:PeriodoAcademicoAmbiente')
                ->find($idppamb);
                
                $ambiente = $periodoacademicoambiente->getAmbiente();
                
                $idamb =$ambiente->getId();
                
                if(!$periodoacademicoambiente){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Periodo Académico-Ambiente con ID: '.$idpamb.' no registrado'
                );
                return $this->redirect($this->generateUrl('ambiente'));
                }
                
                $aldea = $ambiente->getAldea();
               
                /* @var $validar \MisionSucre\RipesBundle\Ambiente */
                $validar = $this->get('servicios.validar');
                
                $error = $validar->ValidarAmbiente($ambiente,$aldea->getId(),$request);
                if($error)
                    return new Response("<h1>ERROR al Mostrar Triunfadores</h1>");
                
                $triunfadores= $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:PeriodoTriunfador')
                ->TriunfadoresVinculados($idppamb);
//                $triunfadores['novinculados'] = $this->getDoctrine()
//                ->getRepository('MisionSucreRipesBundle:PeriodoTriunfador')
//                ->TriunfadoresNoVinculados($ambiente,$idppamb);
                
                return $this->render(
			'MisionSucreRipesBundle:PeriodoTriunfador:tablatriunfadores.html.twig',
                array('triunfadores'=>$triunfadores)
		);	
    }

    public function tablaTriunfadoresnovinculadosAction(Request $request){
        $em = $this->getDoctrine()->getManager();
                $idppamb = $request->request->get('idppamb');
                //$idppamb =1;    
                $periodoacademicoambiente = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:PeriodoAcademicoAmbiente')
                ->find($idppamb);
                
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
               
                /* @var $validar \MisionSucre\RipesBundle\Ambiente */
                $validar = $this->get('servicios.validar');
                
                $error = $validar->ValidarAmbiente($ambiente,$aldea->getId(),$request);
                if($error)
                    return new Response("<h1>ERROR al Mostrar Triunfadores</h1>");
                
                
                $triunfadores = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:PeriodoTriunfador')
                ->TriunfadoresNoVinculados($idppamb);
                
                return $this->render(
			'MisionSucreRipesBundle:PeriodoTriunfador:tablatriunfadoresnovinculados.html.twig',
                array('triunfadores'=>$triunfadores,'idppamb'=>$idppamb)
		);	
    }
    
public function deleteAction(Request $request)
	{       
        
        $em = $this->getDoctrine()->getManager();
        $ptrf = $request->request->get('pidpt');
        $em = $this->getDoctrine()->getManager();
                
                $periodotriunfador = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:PeriodoTriunfador')
                ->find($ptrf);
                
                if(!$periodotriunfador){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Periodo Académico-Triunfador con ID: '.$periodotrf.' no registrado'
                );
                return $this->redirect($this->generateUrl('ambiente'));
                }
                
                $ambiente = $periodotriunfador->getPeriodoAcademicoAmbiente()->getAmbiente();
                
                $idamb =$ambiente->getId();
                $idusr =$periodotriunfador->getUser()->getId();
                
                $aldea = $ambiente->getAldea();
               
                /*VALIDAR */
                $validar = $this->get('servicios.validar');
                
                $error = $validar->ValidarAmbiente($ambiente,$aldea->getId(),$request);
                if($error)
                    return new Response("<h1>ERROR al desVincular Triunfador</h1>");
                    
                
                $error = $validar->ValidarTriunfador($idusr,$request,$idamb);
                if($error)
                    return new Response("<h1>ERROR al desVincular Triunfador</h1>");
                
                            try{ 
                            $em->remove($periodotriunfador);
                            $em->flush();
                            } catch (Exception $ex) {
                    return new Response("<h1>Problemas al desVincular Triunfador</h1>");	
                }
                            return new Response("<h1>Triunfador desVinculado con Éxito al Periodo Académico</h1>");
        }
public function listacedulaAction(Request $request,$ced)
    {   
        $em = $this->getDoctrine()->getManager();
        
        $user = $this->getUser();
        
        switch($user->getTipUsr()){
        
        case 5:
            $coordinador = $em->getRepository('MisionSucreRipesBundle:CoordinadorAldea')->findOneByUser($user->getId());
                        if(!$coordinador){
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Coordinador No Vinculado a una Aldea'
                            );            
                            return $this->redirect($this->generateUrl('aldea_coordinador_info'));
                        }
            $triunfadores = $em->getRepository('MisionSucreRipesBundle:CoordinadorTurno')->TriunfadoresCoordinadorCedula($coordinador->getId(),$ced);
            break;
        case 8:    
        
        $usreje = $em->getRepository('MisionSucreRipesBundle:CoordinadorEje')->findOneByUser($user->getId());
        $triunfadores = $em->getRepository('MisionSucreRipesBundle:Triunfador')->TriunfadoresCedulaEje($usreje->getEje()->getId(),$cedula);
        break;
        case 1:
        $triunfadores = $em->getRepository('MisionSucreRipesBundle:Triunfador')->TriunfadoresCedula($ced);
        break;
        }
        
        return $this->render(
			'MisionSucreRipesBundle:PeriodoTriunfador:listaanexar.html.twig',
                array('triunfadores'=>$triunfadores)
		);	
    }
        
}
