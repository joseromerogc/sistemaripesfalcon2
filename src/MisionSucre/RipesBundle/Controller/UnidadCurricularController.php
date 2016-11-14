<?php

namespace MisionSucre\RipesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MisionSucre\RipesBundle\Entity\UnidadCurricular;
use MisionSucre\RipesBundle\Form\Type\UnidadCurricularType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\HttpFoundation\Response;


class UnidadCurricularController extends Controller
{
	
	public function newAction(Request $request,$idpnf)
	{       
		$uc = new UnidadCurricular();
                
                $em = $this->getDoctrine()->getManager();
                
                $pnf =  $em->getRepository('MisionSucreRipesBundle:Pnf')->find($idpnf);
                
                if(!$pnf){
                    
                    $request->getSession()->getFlashBag()->add(
                    'notice',
                    'Pnf con ID: '.$id.' no registrado'
                    );    
                    return $this->redirect($this->generateUrl('pnf_lista'));
                }
                
		$form = $this->createForm(new UnidadCurricularType(),$uc)
                        ->add('save', 'submit',array('label' => 'Registrar Unidad Curricular'))
                        ;
            
                $form->handleRequest($request);
                
		if ($form->isValid()) {
                               
                            $uc->setPnf($pnf);
                            $em->persist($uc);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Unidad Curricular Creada con Éxito'
                            );            
                    return $this->redirect($this->generateUrl('pnf_show',array('id'=>$idpnf)));
		}
		
		return $this->render('MisionSucreRipesBundle:UnidadCurricular:new.html.twig', array(
		'form' => $form->createView(),'mensaje_heading'=>'Nueva Unidad Curricular',
                    'sub_heading'=>"P.N.F. ".$pnf->getNombre()
		));
	}        
public function cargaAction(Request $request,$idpnf)
	{       
                $em = $this->getDoctrine()->getManager();
                
                $pnf =  $em->getRepository('MisionSucreRipesBundle:Pnf')->find($idpnf);
                
                if(!$pnf){
                    
                    $request->getSession()->getFlashBag()->add(
                    'notice',
                    'Pnf con ID: '.$id.' no registrado'
                    );    
                    return $this->redirect($this->generateUrl('pnf_lista'));
                }
                
                $file=array('file'=>"");
                
                $form = $this->createFormBuilder($file)
                        ->add('submitFile', 'file', array('label' => 'Cargar Formato'))
                        ->getForm();
            
                $form->handleRequest($request);
                
		return $this->render(
			'MisionSucreRipesBundle:UnidadCurricular:cargar.html.twig',
                        array('form' => $form->createView(),'pnf'=>$pnf)
		);	
	} 
        
                public function tablacargaAction(Request $request)
	{       
            $rechazadoscodigos=array();
            $rechazadosfaltadatos=array();
            $rechazadosporrepeticion=array();
            $tabla= "";
            
            $dir=$_FILES['archivo']['tmp_name'];
            
           // move_uploaded_file($_FILES['archivo']['tmp_name'],$_SERVER['DOCUMENT_ROOT'].'documentos/'.$FILES['archivo']['name']);
            
//            $f = fopen("$dir", "r");
//            $line = fgetcsv($f);
//            $line = fgetcsv($f);
            
            
            //$dir=$_SERVER['DOCUMENT_ROOT'].'documentos/unidadescurriculares2.csv';
            $em = $this->getDoctrine()->getManager();
            
            
            $tabla = "<table class='table-style-two' id='tablacentrada' cellspacing='0'>\n\n";
            $f = fopen("$dir", "r");
            $tabla .= "<tr><th>#</th><th>Unidad Curricular</th><th>Área</th><th>Código</th></tr>\n\n";
            $line = fgetcsv($f);
            $i=1;
            
            $array_codigo = array();
            
                    
            while (($line = fgetcsv($f)) !== false) {
                
            $tabla.= "<tr>";
            
            $codigo =htmlspecialchars($line[2]);
            $nombre =htmlspecialchars($line[0]);
            $area =htmlspecialchars($line[1]);
            
            if($codigo=="" || $nombre==""){
                $rechazadosfaltadatos[]=$codigo."/".$nombre;
                continue;
            }
                        
            $codigo_reg = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:UnidadCurricular')
                ->findByCodigo($codigo);
            
            if($codigo_reg){
                $rechazadoscodigos[]=$codigo."/".$nombre;
                continue;
            }
            
            if(!in_array($codigo,$array_codigo))
            {
                array_push($array_codigo,$codigo);
            }else
            {
                $rechazadosporrepeticion[]=$codigo;
                 continue;
            }
            
            $tabla.="<td>$i</td>";
            foreach ($line as $cell) {
                $tabla.="<td>" . htmlspecialchars($cell) . "</td>";
            }
            $i++;
            $tabla.= "</tr>\n";
            
            if($i>50){
                return new Response("<h3> <img src='http://icons.iconarchive.com/icons/designcontest/ecommerce-business/48/alert-icon.png'/>ERROR: Número de Filas Mayor a 50¡¡¡¡¡¡</h3>");
            }
            
            }
            fclose($f);
            
            $tabla.= "\n</table>";
            
		return $this->render(
			'MisionSucreRipesBundle:UnidadCurricular:tablacarga.html.twig',
                        array('tabla'=>$tabla,'rechazadoscodigos'=>$rechazadoscodigos,
                            'rechazadosporrepeticion'=>$rechazadosporrepeticion,
                            'rechazadosporfaltadatos'=>$rechazadosfaltadatos
                        )
		);	
	}
public function registrarcargaAction(Request $request)
	{       
                $em = $this->getDoctrine()->getManager();
                $data =1;// $request->request->get('ucs');
                $idpnf =2; $request->request->get('pnf');
                
               $registros=array(array(1,"Matematica",	"Matematica"	,"ADM-MAT-001"),
                   array(2,"Contabilidad","Contabilidad","ADM-CON-001"));
                
                if($data){
              //$registros=json_decode($data,true);
               
               $batchSize = 20;
             
                foreach ($registros as $i =>$r) {
                    
                    $codigo =htmlspecialchars($r[3]);
                    $nombre =htmlspecialchars($r[1]);
                    $area =htmlspecialchars($r[2]);
                    
                    $uc = new UnidadCurricular();
                    
                    $pnf = $em->getRepository('MisionSucreRipesBundle:Pnf')->find($idpnf);        
                    
                    $uc->setNombre($nombre);
                    $uc->setArea($area);
                    $uc->setCodigo($codigo);
                    $uc->setPnf($pnf);
                   
                    try {
                
                    $em->persist($uc);
                    
                } catch (Exception $ex) {
                    return new Response("<h1><img src='http://icons.iconarchive.com/icons/designcontest/ecommerce-business/48/alert-icon.png'/>Error en la Carga de Unidades Curriculares!</h1>");	
                }
                    if (($i % $batchSize) == 0) {
                        $em->flush();
                        $em->clear();
                    }
                }
                try {
                
                $em->flush();
                $em->clear();
                } catch (Exception $ex) {
                    return new Response("<h1><img src='http://icons.iconarchive.com/icons/designcontest/ecommerce-business/48/alert-icon.png'/>Error en la Carga de Unidades Curriculares!</h1>");	
                }
                return new Response("<h1> <img src='http://icons.iconarchive.com/icons/custom-icon-design/pretty-office-8/48/Accept-icon.png' />Carga de Unidades Curriculares Éxitosa!</h1>");
                }
                else{
                    return new Response("<h1><img src='http://icons.iconarchive.com/icons/designcontest/ecommerce-business/48/alert-icon.png'/>Sin Data que cargar</h1>");
                }   
	}        
}
