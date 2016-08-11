<?php

namespace MisionSucre\RipesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MisionSucre\RipesBundle\Entity\Triunfador;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;

class EgresadoController extends Controller
{
    public function indexAction()
    {
        return $this->render('MisionSucreRipesBundle:Pnf:index.html.twig');
    }

    public function listaAction(Request $request)
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
            $egresados = $em->getRepository('MisionSucreRipesBundle:Triunfador')->findAllOrderedByEgresadoAndAldea($coordinador->getAldea()->getId());
            break;
        case 8:    
        
        $usreje = $em->getRepository('MisionSucreRipesBundle:CoordinadorEje')->findOneByUser($user->getId());
        $egresados = $em->getRepository('MisionSucreRipesBundle:Triunfador')->findAllOrderedByEgresadoAndEje($usreje->getEje()->getId());
        break;
        case 1:
        $egresados = $em->getRepository('MisionSucreRipesBundle:Triunfador')->findAllOrderedByEgresado();
        break;
        }
               
		if(!$egresados){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Ningun Egresado Registrado'
            );
            return $this->redirect($this->generateUrl('triunfador'));
            }
            
		return $this->render(
			'MisionSucreRipesBundle:Egresado:lista.html.twig',
                array('egresados' => $egresados)
		);	
}
    
public function resumenAction(Request $request)
	{       
                $em = $this->getDoctrine()->getManager();
                
                $usr = $this->getUser();
                
                switch($usr->getTipUsr()){
                    
                    case 1:
                        $cantidadporaldeas = $em->createQuery(
                        "SELECT a.nombre, COUNT (t) as cantidadtriunfadores FROM MisionSucreRipesBundle:Triunfador t JOIN t.ambiente am JOIN am.aldea a
                            WHERE t.condicion =:egresado
                           
                            GROUP BY a.id
                        "
                        )->setParameters(array('egresado'=>'Egresado')
                        )->getResult();
                        
                        $cantidadporeje = $em->createQuery(
                        "SELECT e.nombre, COUNT (t) as cantidadtriunfadores FROM MisionSucreRipesBundle:Triunfador t JOIN t.ambiente am JOIN am.aldea a
                            JOIN a.parroquia prq JOIN prq.eje e 
                            WHERE t.condicion =:egresado
                            GROUP BY e.id
                        "
                        )->setParameters(array('egresado'=>'Egresado')
                        )->getResult();
                        
                        
                        $cantidadpormunicipio = $em->createQuery(
                        "SELECT m.municipio, COUNT (t) as cantidadtriunfadores FROM MisionSucreRipesBundle:Triunfador t JOIN t.ambiente am JOIN am.aldea a
                            JOIN a.parroquia prq JOIN prq.municipio m
                            WHERE t.condicion =:egresado
                            
                            GROUP BY m.id
                        "
                        )->setParameters(array('egresado'=>'Egresado')
                        )->getResult();
                        
                    return $this->render(
			'MisionSucreRipesBundle:Egresado:resumeneje.html.twig',
                        array(
                            'cxeje'=>$cantidadporeje,
                            'cxaldeas'=>$cantidadporaldeas,
                            'cxmunicipios'=>$cantidadpormunicipio
                        ));
                    break;
                
                    case 8:
                        
                        $usreje = $em->getRepository('MisionSucreRipesBundle:CoordinadorEje')->findOneByUser($usr->getId());
                        $eje = $usreje->getEje()->getId();
                        
                        $cantidadporaldeas = $em->createQuery(
                        "SELECT a.nombre, COUNT (t) as cantidadtriunfadores FROM MisionSucreRipesBundle:Triunfador t JOIN t.ambiente am JOIN am.aldea a
                            JOIN a.parroquia prq
                            WHERE prq.eje =:eje
                            AND t.condicion =:egresado
                            GROUP BY a.id
                        "
                        )->setParameters(array('eje'=>$eje,'egresado'=>'Egresado'))
                                ->getResult();
                        
                        $cantidadpormunicipio = $em->createQuery(
                        "SELECT m.municipio, COUNT (t) as cantidadtriunfadores FROM MisionSucreRipesBundle:Triunfador t JOIN t.ambiente am JOIN am.aldea a
                            JOIN a.parroquia prq JOIN prq.municipio m
                            WHERE prq.eje =:eje
                            AND t.condicion =:egresado
                            GROUP BY m.id
                        "
                        )->setParameters(array('eje'=>$eje,'egresado'=>'Egresado'))
                        ->getResult();
                        
                    return $this->render(
			'MisionSucreRipesBundle:Egresado:resumenmunicipio.html.twig',
                        array(
                            'cxaldeas'=>$cantidadporaldeas,
                            'cxmunicipios'=>$cantidadpormunicipio
                        ));
                    break;
                    
                    case 5:
                        
                        $coord = $em->getRepository('MisionSucreRipesBundle:CoordinadorAldea')->findOneByUser($usr->getId());
                        $aldea = $coord->getAldea()->getId();
                        
                        $cantidadporpnf = $em->createQuery(
                        "SELECT pnf.nombre, COUNT (t) as cantidadtriunfadores FROM MisionSucreRipesBundle:Triunfador t JOIN t.ambiente am JOIN am.aldea a,
                            MisionSucreRipesBundle:Pnf pnf
                            WHERE am.aldea =:aldea AND pnf.id = am.pnf
                            AND t.condicion =:egresado
                            GROUP BY pnf.id
                        "
                        )->setParameters(array('aldea'=>$aldea,'egresado'=>'Egresado'))
                                ->getResult();
                        
                    return $this->render(
			'MisionSucreRipesBundle:Egresado:resumenaldea.html.twig',
                        array(
                            'cxpnf'=>$cantidadporpnf,
                        ));
                    break;
                
                }
        }    
        
}
