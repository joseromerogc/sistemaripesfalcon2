<?php

namespace MisionSucre\RipesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MisionSucre\RipesBundle\Entity\Malla;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\HttpFoundation\Response;


class MallaController extends Controller
{
	public function vincularUCAction(Request $request,$idpp) //pp->PeriodoPnf
	{       
                $em = $this->getDoctrine()->getManager();
                
                $periodospnf = $em->getRepository('MisionSucreRipesBundle:PeriodoPnf')->find($idpp);
                
                if(!$periodospnf){
                    
                    $request->getSession()->getFlashBag()->add(
                    'notice',
                    'Este Periodo/Tramo no ha sido Registrado'
                    );    
                    return $this->redirect($this->generateUrl('pnf_lista'));
                }
                
                $pnf = $periodospnf->getPnf();
                
                $unidadescurriculares = $em->getRepository('MisionSucreRipesBundle:Malla')->findByPeriodopnf($idpp);
                
                if($pnf->getModalidad()=="TRIMESTRAL"){
                    $p="Periodo";
                }
                else{
                    $p="Tramo";
                }
                
		return $this->render(
			'MisionSucreRipesBundle:Malla:registrarucs.html.twig',
                        array('pp'=>$periodospnf,'pnf'=>$pnf,'periodo'=>$p,'ucs'=>$unidadescurriculares)
		);	
	}
public function registrarVinculacionUCAction(Request $request,$json)
	{       
                $em = $this->getDoctrine()->getManager(); 
                
                $datos=json_decode($json,true);
                
                $error= "";
                
                foreach($datos[0] as $i=>$c) {
                    
               $uc = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:UnidadCurricular')
                ->findOneByCodigo($c); 
               
               if($uc){
                
                $mallauc = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Malla')
                ->existeUnidadCurricular($datos[3],$uc->getId());
                
                                   if($mallauc){
                                       $error.= "$c Unidad Curricular ya Vinculada / ";
                                   }
                else {
                        try{
                        $m = new Malla();
                        $m->setUc($uc);
                        $ppnf=$this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:PeriodoPnf')
                ->find($datos[3]);
                        $m->setPeriodoPnf($ppnf);
                        $m->setEstaller($datos[2][$i]);
                        $m->setEselectiva($datos[1][$i]);
                        
                        $em->persist($m);
                        }
                        catch (\Doctrine\ORM\EntityNotFoundException $ex) {
                            $error.="Problemas a Registrar a $c  / ";
                        }
                }
                }
                else{
                    $error.="$c No Registrada";
                }
                    
                }
                $em->flush();
               
                if($error){
                return new Response("Se han Encontrado los siguientes Errores:<br/>:".$error);
                }
                else
                {
                    return new Response("good"); //si se ha registrado
                }
                }    
    public function tablaVinculacionAction($param)
	{       
                //$params='{"query":{"codigos":"ADM-POL-00,ADM-MAT-001,ADM-CON-001","ppnf":"1"}}';
                $parametros=json_decode($param,true);
                
                $query=$parametros['query'];
                
                $codigos= preg_split("/[\s,]+/",$query['codigos']);
             
                $em = $this->getDoctrine()->getManager();
                
                $noregistrados = array();
                $nopnf = array();
                $vinculados=  array();
                
                //las cedulas que no estan registradas
                foreach ($codigos as $c) {
                    
                    $uc = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:UnidadCurricular')
                ->findOneByCodigo($c); 
                    
                    $idppnf = $query['ppnf']; //Periodo Pnf
                    
                    $ppnf = $this->getDoctrine() //periodopnf
                ->getRepository('MisionSucreRipesBundle:PeriodoPnf')
                ->find($idppnf);
                    
                    $pnf = $ppnf->getPnf();
                    
                    if($uc){
                        
                        if($uc->getPnf()->getId()!=$pnf->getId())
                        {
                            $nopnf[]=array ('cod'=>$c,'pnf'=>$pnf->getNombre());
                        }
                        else{
                            
                            $ucmalla = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Malla')
                ->existeUnidadCurricular($ppnf,$pnf->getId());
                            
                                   if($ucmalla)
                                   {
                                      $vinculados[] = array ('cod'=>$c);
                                   }
                        }
                                 
                    }
                    else{
                         $noregistrados[]=$c;
                    }
                }
                
                $result = $this->getDoctrine() 
                ->getRepository('MisionSucreRipesBundle:Malla')
                ->UnidadesCurricularesporCodigo($codigos);
                
		return $this->render(
			'MisionSucreRipesBundle:Malla:tablavinculacion.html.twig',
                        array('filas'=>$result,'noregistrados'=>$noregistrados,'nopnf'=>$nopnf,'vinculados'=>$vinculados,
                            'idppnf'=>$idppnf
                       
                        )
		);	
	}            
        
        public function deleteAction(Request $request,$iducmalla)
	{
            $em = $this->getDoctrine()->getManager();
                
                $mallauc = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Malla')
                ->find($iducmalla);
                
                if($mallauc){
                $pnf=$mallauc->getPeriodopnf()->getPnf()->getId();
                
                    $em->remove($mallauc);
                
                            $em->remove($mallauc);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Unidad Curricular Desvinculada con Éxito'
                            );

                    return $this->redirect($this->generateUrl('pnf_show',array('id'=>$pnf)));
                }    
		
        }        
}
