<?php
namespace MisionSucre\RipesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;

class ConsejoComunalController extends Controller
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
                        "SELECT e.nombre, COUNT (DISTINCT c.id ) as cantidad
                            FROM MisionSucreRipesBundle:ParticipacionComunitaria pac JOIN pac.cc c JOIN c.parroquia prq JOIN prq.eje e
                            GROUP BY e.id
                        "
                        
                        )->getResult();
                        
                        $cantidadporprq = $em->createQuery(
                        "
                            SELECT m.municipio,prq.parroquia, COUNT (DISTINCT c.id) as cantidad
                            FROM MisionSucreRipesBundle:ParticipacionComunitaria pac JOIN pac.cc c JOIN c.parroquia prq JOIN prq.municipio m
                            GROUP BY prq.id
                            ORDER BY m.municipio, prq.parroquia
                        "
                        )->getResult();
                        
                        $cantidadpormunicipio = $em->createQuery(
                        "
                            SELECT m.municipio, COUNT (DISTINCT c.id) as cantidad
                            FROM MisionSucreRipesBundle:ParticipacionComunitaria pac JOIN pac.cc c JOIN c.parroquia prq JOIN prq.municipio m
                            GROUP BY m.id
                           
                        "
                        
                        )->getResult();
                        
                    return $this->render(
			'MisionSucreRipesBundle:ConsejoComunal:resumeneje.html.twig',
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
                            SELECT m.municipio,prq.parroquia, COUNT (DISTINCT c.id) as cantidad
                            FROM MisionSucreRipesBundle:ParticipacionComunitaria pac JOIN pac.cc c JOIN c.parroquia prq JOIN prq.municipio m
                            WHERE prq.eje=:eje
                            GROUP BY prq.id
                            ORDER BY m.municipio, prq.parroquia
                        "
                        )->setParameters(array('eje'=>$eje)
                        )->getResult();
                        
                        $cantidadpormunicipio = $em->createQuery(
                        "
                            SELECT m.municipio, COUNT (DISTINCT c.id) as cantidad
                            FROM MisionSucreRipesBundle:ParticipacionComunitaria pac JOIN pac.cc c JOIN c.parroquia prq JOIN prq.municipio m
                            WHERE prq.eje=:eje
                            GROUP BY m.id
                           
                        "
                        )->setParameters(array('eje'=>$eje)
                        )->getResult();
                        
                    return $this->render(
			'MisionSucreRipesBundle:ConsejoComunal:resumenmunicipio.html.twig',
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
                              FROM MisionSucreRipesBundle:ParticipacionComunitaria pac JOIN pac.cc c JOIN c.parroquia prq JOIN prq.municipio m,
                              MisionSucreRipesBundle:User u
                              WHERE u.id = pac.user
                            GROUP BY prq.id
                            ORDER BY m.municipio, prq.parroquia

                        ")->setParameters(array('aldea'=>$aldea)
                        )->getResult();
                        
                    return $this->render(
			'MisionSucreRipesBundle:ConsejoComunal:resumenaldea.html.twig',
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
        $consejoscomunales=null;
        
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
            $consejoscomunales = $em->getRepository('MisionSucreRipesBundle:ConsejoComunal')->findAllOrderedByAldea($coordinador->getAldea()->getId());
            break;
        case 8:    
        
        $usreje = $em->getRepository('MisionSucreRipesBundle:CoordinadorEje')->findOneByUser($user->getId());
        $eje = $usreje->getEje()->getId();   
        $consejoscomunales= $em->getRepository('MisionSucreRipesBundle:ConsejoComunal')->findAllOrderedByEje($eje);
        break;
        case 1:
        $consejoscomunales= $em->getRepository('MisionSucreRipesBundle:ConsejoComunal')->findAllOrderedByParroquia();
        break;
        }
           if(!$consejoscomunales){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Ningun Registrado'
            );
            return $this->redirect($this->generateUrl('usuario'));
            }
            
	return $this->render(
			'MisionSucreRipesBundle:ConsejoComunal:lista.html.twig',
                array('consejoscomunales'=>$consejoscomunales )
		);		
            
		
}                
}