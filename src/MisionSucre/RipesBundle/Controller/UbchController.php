<?php
namespace MisionSucre\RipesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;

class UbchController extends Controller
{
    public function indexAction()
    {
        return $this->render('MisionSucreRipesBundle:Sociales:index.html.twig');
    }	
          
 public function resumenAction(Request $request)
	{       
                $em = $this->getDoctrine()->getManager();
                
                $usr = $this->getUser();
                
                switch($usr->getTipUsr()){
                    
                    case 1:
                        $cantidadporeje = $em->createQuery(
                        "SELECT e.nombre, COUNT (DISTINCT u.id ) as cantidad
                            FROM MisionSucreRipesBundle:ParticipacionPolitica pp JOIN pp.ubch u JOIN u.parroquia prq JOIN prq.eje e
                            GROUP BY e.id
                        "
                        
                        )->getResult();
                        
                        $cantidadporprq = $em->createQuery(
                        "
                            SELECT m.municipio,prq.parroquia, COUNT (DISTINCT u.id ) as cantidad
                            FROM MisionSucreRipesBundle:ParticipacionPolitica pp JOIN pp.ubch u JOIN u.parroquia prq JOIN prq.municipio m
                            GROUP BY prq.id
                            ORDER BY m.municipio, prq.parroquia
                        "
                        )->getResult();
                        
                        $cantidadpormunicipio = $em->createQuery(
                        "
                            SELECT m.municipio, COUNT (DISTINCT u.id ) as cantidad
                            FROM MisionSucreRipesBundle:ParticipacionPolitica pp JOIN pp.ubch u JOIN u.parroquia prq JOIN prq.municipio m
                            GROUP BY m.id
                           
                        "
                        
                        )->getResult();
                        
                    return $this->render(
			'MisionSucreRipesBundle:Ubch:resumeneje.html.twig',
                        array(
                            'cxeje'=>$cantidadporeje,
                            'cxparroquia'=>$cantidadporprq,
                            'cxmunicipios'=>$cantidadpormunicipio
                        ));
                    break;
                
                    case 8:
                        
                        $usreje = $em->getRepository('MisionSucreRipesBundle:CoordinadorEje')->findOneByUser($usr->getId());
                        $eje = $usreje->getEje()->getId();
                        
                        $cantidadporprq = $em->createQuery(
                        "
                            SELECT m.municipio,prq.parroquia, COUNT (DISTINCT u.id ) as cantidad
                            FROM MisionSucreRipesBundle:ParticipacionPolitica pp JOIN pp.ubch u JOIN u.parroquia prq JOIN prq.municipio m
                            WHERE prq.eje=:eje
                            GROUP BY prq.id
                            ORDER BY m.municipio, prq.parroquia
                        "
                        )->setParameters(array('eje'=>$eje)
                        )->getResult();
                        
                        $cantidadpormunicipio = $em->createQuery(
                        "
                            SELECT m.municipio, COUNT (DISTINCT u.id ) as cantidad
                            FROM MisionSucreRipesBundle:ParticipacionPolitica pp JOIN pp.ubch u JOIN u.parroquia prq JOIN prq.municipio m
                            WHERE prq.eje=:eje
                            GROUP BY m.id
                           
                        "
                        )->setParameters(array('eje'=>$eje)
                        )->getResult();
                        
                    return $this->render(
			'MisionSucreRipesBundle:Ubch:resumenmunicipio.html.twig',
                        array(
                            'cxparroquia'=>$cantidadporprq,
                            'cxmunicipios'=>$cantidadpormunicipio
                        ));
                    break;
                
                    case 5:
                        
                        $coord = $em->getRepository('MisionSucreRipesBundle:CoordinadorAldea')->findOneByUser($usr->getId());
                        $aldea = $coord->getAldea()->getId();
                        
                       $cantidadporprq = $em->createQuery(
                        "SELECT m.municipio,prq.parroquia,
                              SUM ( CASE WHEN (
                             EXISTS 
                            ( SELECT ca FROM MisionSucreRipesBundle:CoordinadorAldea ca WHERE u.id=ca.user AND IDENTITY(ca.aldea)=:aldea ) OR
                             EXISTS 
                            ( SELECT d FROM MisionSucreRipesBundle:Docente d WHERE u.id=d.user AND IDENTITY(d.aldea)=:aldea ) OR
                            EXISTS
                            ( SELECT t FROM MisionSucreRipesBundle:Triunfador t JOIN t.ambiente a WHERE u.id=t.user AND IDENTITY(a.aldea)=:aldea ) OR
                             EXISTS
                            ( SELECT op FROM MisionSucreRipesBundle:Operario op WHERE u.id=op.user AND IDENTITY(op.aldea)=:aldea )
                            )
                             THEN 1 ELSE 0 END )
                             AS cantidad
                              FROM MisionSucreRipesBundle:ParticipacionPolitica pp JOIN pp.ubch ub JOIN ub.parroquia prq JOIN prq.municipio m,
                              MisionSucreRipesBundle:User u
                              WHERE u.id = pp.user
                            GROUP BY prq.id
                            ORDER BY m.municipio, prq.parroquia

                        ")->setParameters(array('aldea'=>$aldea)
                        )->getResult();
                        
                    return $this->render(
			'MisionSucreRipesBundle:Ubch:resumenaldea.html.twig',
                        array(
                            'cxparroquia'=>$cantidadporprq,
                        ));
                    break;
                
                }
        }    
 
  public function listaAction(Request $request)
	{   
    
            $em = $this->getDoctrine()->getManager();
            
        $user = $this->getUser();
        $centroselectorales=null;
        
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
            $centroselectorales = $em->getRepository('MisionSucreRipesBundle:Ubch')->findAllOrderedByAldea($coordinador->getAldea()->getId());
            break;
        case 8:    
        
        $usreje = $em->getRepository('MisionSucreRipesBundle:CoordinadorEje')->findOneByUser($user->getId());
        $eje = $usreje->getEje()->getId();   
        $centroselectorales= $em->getRepository('MisionSucreRipesBundle:Ubch')->findAllOrderedByEje($eje);
        break;
        case 1:
        $centroselectorales= $em->getRepository('MisionSucreRipesBundle:Ubch')->findAllOrderedByParroquia();
        break;
        }
           if(!$centroselectorales){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Ningun Registrado'
            );
            return $this->redirect($this->generateUrl('usuario'));
            }
            
	return $this->render(
			'MisionSucreRipesBundle:Ubch:lista.html.twig',
                array('consejoscomunales'=>$centroselectorales )
		);		
            
		
} 

public function showAction(Request $request,$id)
	{       
                $em = $this->getDoctrine()->getManager();
        
        
        
        $user = $this->getUser();
        $electores=null;
        
        if($user->getTipUsr()!=5){
                $ubch =  $em->getRepository('MisionSucreRipesBundle:Ubch')->find($id);
                
                if(!$ubch){
                    
                    $request->getSession()->getFlashBag()->add(
                    'notice',
                    "Centro Electoral con ID: $id no Registrado"
                    );    
                    return $this->redirect($this->generateUrl('politica_ubch_lista'));
                }
        }      
        
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
            $electores = $em->getRepository('MisionSucreRipesBundle:Ubch')->findAllOrderedByUbchAndAldea($coordinador->getAldea()->getId());
            return $this->render(
			'MisionSucreRipesBundle:Ubch:showaldea.html.twig',
                array('electores'=>$electores,'aldea'=>$coordinador->getAldea()->getNombre()) 
		);	
            break;
        case 8:    
        
        $usreje = $em->getRepository('MisionSucreRipesBundle:CoordinadorEje')->findOneByUser($user->getId());
        $eje = $usreje->getEje()->getId();   
        $centroselectorales= $em->getRepository('MisionSucreRipesBundle:Ubch')->findAllOrderedByEje($eje);
        break;
        case 1:
         
        $electores= $em->getRepository('MisionSucreRipesBundle:Ubch')->findAllOrderedByUbchAndParroquia($ubch->getId());
        $cantidadelectores = $em->createQuery(
                        " SELECT COUNT( DISTINCT pp.user ) as cantidad
                            FROM MisionSucreRipesBundle:ParticipacionPolitica pp JOIN pp.ubch u
                            WHERE u.id=:ubch
                            
                           
                        "
                        )->setParameters(array('ubch'=>$ubch->getId())
                        )->getSingleResult();
        break;
        }
           if(!$electores){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Ningun Registrado'
            );
            return $this->redirect($this->generateUrl('usuario'));
            }
            
	return $this->render(
			'MisionSucreRipesBundle:Ubch:show.html.twig',
                array('electores'=>$electores,'ubch'=>$ubch, 'cantidadelectores'=>$cantidadelectores['cantidad']
		));		
            
                
                
	}

}