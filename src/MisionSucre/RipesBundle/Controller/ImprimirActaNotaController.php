<?php

namespace MisionSucre\RipesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;
use MisionSucre\RipesBundle\Entity\ImpresionActaNota;
use MisionSucre\RipesBundle\Form\Type\ImpresionActaNotaType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Dompdf\Dompdf;

class ImprimirActaNotaController extends Controller
{
    
    public function vistaPreviaAction(Request $request,$idan)
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
                ->getRepository('MisionSucreRipesBundle:Nota')
                ->TriunfadoresVinculadosConNotas($idan);
                
                if(!$triunfadores){
                    $request->getSession()->getFlashBag()->add(
            'notice',
            "Notas No Cargadas"
                );
                return $this->redirect($this->generateUrl('acta_nota_cargar',  array('idan'=>$idan)));
                }
                
                $nombresunidadescurriculares=  array();
                
                foreach ($triunfadores as $k => $t) {
                    
                    $notastriunfadores[$k]['nombres']=$t['priNom'];
                    $notastriunfadores[$k]['apellidos']=$t['priApe'];
                    $notastriunfadores[$k]['cedula']=$t['cedPer'];
                    $notastriunfadores[$k]['notas']= $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Nota')
                ->NotasTriunfador($t['cedPer'],$idpamb);
                    
                    if($k==0){
                        foreach ($notastriunfadores[$k]['notas'] as $n) {
                        $nombresunidadescurriculares[]=$n['nombre'];    
                        }
                        
                    }
                }
                
                /*VALIDAR */
                $validar = $this->get('servicios.validar');
                $error = $validar->ValidarAmbiente($ambiente,$aldea->getId(),$request);
                if($error)
                    return $this->redirect($this->generateUrl($error['url'],array($error['param']=>$error['valueparam'])));
                
                $docente = $actanota->getDocente();
                $docente= $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Persona')
                ->findOneByUser($docente->getUser()->getId());
                $vocero= $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Vocero')
                ->findOneByAmbiente($ambiente->getId());
                
                if(!$vocero){
                    $request->getSession()->getFlashBag()->add(
            'notice',
            "Debe de Seleccionar un Vocero del Ambiente"
                );
                return $this->redirect($this->generateUrl('asignar_vocero',  array('idamb'=>$ambiente->getId())));
                }
                
                $datosvocero= $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Persona')
                ->findOneByUser($vocero->getUser()->getId());
                
                $caldea = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:CoordinadorTurno')
                ->CoordinadorTurnoAmbiente($ambiente->getId());
                
                $coordinador= $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Persona')
                ->findOneByUser($caldea->getCoordinador()->getUser()->getId());
                
                $impresion= $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:ImpresionActaNota')
                ->findOneByActanota($idan);
                
                
        return $this->render('MisionSucreRipesBundle:FormatoNota:TrayectoInicial/Acta1.html.twig', array(
		'periodoacademico'=>$periodoacademico,'periodoambiente'=>$periodoacademicoambiente,
                            'periodopnf'=>$periodopnf,'ambiente'=>$ambiente,'malla'=>$malla_pnf,
                'triunfadores'=> $triunfadores,'docente'=> $docente,'actanota'=>$actanota,
            'cedulavocero'=>$datosvocero->getCedPer(),'coordinador'=>$coordinador,'impresion'=>$impresion,
                'notastriunfadores'=>$notastriunfadores,'nombresunidadescurriculares'=>$nombresunidadescurriculares
		));                    
        }
    public function imprimirAction(Request $request,$idan){
        
        
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
                
                /*VALIDAR */
                $formato = $this->get('servicios.formatonota')->Formato($idan);
          
                //return new Response($formato);	             
                //if($error)
                    
        $dompdf = new Dompdf();

$dompdf->loadHtml($formato);

// (Optional) Setup the paper size and orientation
// landscape
$dompdf->setPaper('A4', 'landscape');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream();
    }            
}
