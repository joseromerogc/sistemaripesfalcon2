<?php

namespace MisionSucre\RipesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;

class SistemaController extends Controller
{
    public function resumenAction(Request $request)
	{   
    
            $resumen = $this->ResumenEje();
                
		if(!$resumen){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Ninguna Aldea Registrada'
            );
            return $this->redirect($this->generateUrl('aldea_new'));
            }
            
		return $this->render(
			'MisionSucreRipesBundle:Sistema:resumen.html.twig',
                array('resumen' => $resumen)
		);	
	}
        public function ResumenEje()
    {   
        $em = $this->getDoctrine()->getManager();
        
        $resumen=array();
        
        if($this->getUser()->getTipUsr()==8){
        $user = $this->getUser();
        $usreje = $em->getRepository('MisionSucreRipesBundle:CoordinadorEje')->findOneByUser($user->getId());
        $coordinadores = $em->getRepository('MisionSucreRipesBundle:CoordinadorAldea')->findAllOrderedByEje($usreje->getEje()->getId());
        }
        else{
        $coordinadores = $em->getRepository('MisionSucreRipesBundle:CoordinadorAldea')->findAllOrderedByEje();
        }
        
        $i=0;
        foreach ($coordinadores as $c) {
        $idc=$c['cid'];
        $resumen[$i]['aldea']=$c['nombre'];
        $resumen[$i]['idusr']=$c['id'];
        $resumen[$i]['idaldea']=$c['idaldea'];
        $resumen[$i]['coordinador']=$c['coordinador'];
        $resumen[$i]['ambientes']=$em->getRepository('MisionSucreRipesBundle:Ambiente')->cantidadAmbientesCoordinador($idc);
        $resumen[$i]['triunfadores']=$em->getRepository('MisionSucreRipesBundle:Triunfador')->cantidadAldeaTurno($idc);
        $resumen[$i]['operarios']=$em->getRepository('MisionSucreRipesBundle:Operario')->cantidadCoordinador($idc);
        $resumen[$i]['docente']=$em->getRepository('MisionSucreRipesBundle:Docente')->cantidadAldea($c['idaldea']);
        $resumen[$i]['registrados']=$em->getRepository('MisionSucreRipesBundle:Registrousuario')->CantidadporCoordinador($c['id']);
        $i++;
        }
        
        return $resumen;
    }
}
