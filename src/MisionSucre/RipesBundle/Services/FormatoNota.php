<?php

namespace MisionSucre\RipesBundle\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\HttpFoundation\Request;

class FormatoNota {
 
    protected $em;
    protected $router;
    protected $user;
    protected $pnf;
    protected $municipio;
    protected $aldea;
    protected $fechainicio;
    protected $fechafin;
    protected $malla;
    protected $seccion;
    protected $docente;
    protected $triunfadores;
    protected $universidad;
    protected $unidadescurriculares;
 
    public function __construct(EntityManager $entityManager, Router $router, TokenStorage $tokenStorage) {
        $this->em = $entityManager;
        $this->router = $router;
        $this->user = $tokenStorage->getToken()->getUser();
    }
    
    public function Formato($idan){
        
        $actanota = $this->em
                ->getRepository('MisionSucreRipesBundle:ActaNota')
                ->find($idan);
                
                $periodoacademicoambiente = $actanota->getPeriodoacademicoambiente();
                $idpamb=$periodoacademicoambiente->getId();
                $ambiente = $periodoacademicoambiente->getAmbiente();
                $aldea = $ambiente->getAldea();
                $periodoacademico = $periodoacademicoambiente->getPeriodoAcademico();
                $periodopnf = $periodoacademicoambiente->getPeriodoPnf();
                $malla_pnf = $actanota->getMalla();
                
                $triunfadores= $this->em
                ->getRepository('MisionSucreRipesBundle:Nota')
                ->TriunfadoresVinculadosConNotas($actanota->getId());
                
                $docente = $actanota->getDocente();
                $docente= $this->em
                ->getRepository('MisionSucreRipesBundle:Persona')
                ->findOneByUser($docente->getUser()->getId());
                $vocero= $this->em
                ->getRepository('MisionSucreRipesBundle:Vocero')
                ->findOneByAmbiente($ambiente->getId());
                
                $datosvocero= $this->em
                ->getRepository('MisionSucreRipesBundle:Persona')
                ->findOneByUser($vocero->getUser()->getId());
                
                $caldea = $this->em
                ->getRepository('MisionSucreRipesBundle:CoordinadorTurno')
                ->CoordinadorTurnoAmbiente($ambiente->getId());
                
                $coordinador= $this->em
                ->getRepository('MisionSucreRipesBundle:Persona')
                ->findOneByUser($caldea->getCoordinador()->getUser()->getId());
                
                $this->unidadescurriculares= $periodoacademicoambiente->getActasNotas();
                
                $this->universidad = $ambiente->getPnf()->getUniversidad()->getId();
                $this->pnf=$ambiente->getPnf()->getId();
                $this->municipio=strtoupper($aldea->getParroquia()->getMunicipio()->getMunicipio());
                $this->aldea=strtoupper($aldea->getNombre());
                
                $datetime = $periodoacademico->getFechainicio();
                $this->fechainicio = array();
                $this->fechainicio['dia']=$datetime->format('d');
                $this->fechainicio['mes']=$datetime->format('m');
                $this->fechainicio['anyo']=$datetime->format('y');
                $datetime = $periodoacademico->getFechafin();
                $this->fechafin = array();
                $this->fechafin['dia']=$datetime->format('d');
                $this->fechafin['mes']=$datetime->format('m');
                $this->fechafin['anyo']=$datetime->format('y');
                
                $this->malla = array();
                $this->malla['uc']=$malla_pnf->getUc()->getNombre();
                $this->malla['periodo']=$periodopnf->getPeriodo();
                $this->malla['trayecto']=$periodopnf->getTrayecto();
                
                if($ambiente->getSeccion())
                    $this->seccion=strtoupper($ambiente->getSeccion());
                else
                    $this->seccion=strtoupper("Única");
                
                $this->docente = array();
                $this->docente['nombre']=strtoupper($docente->getPriNom())." ".strtoupper($docente->getPriApe());
                
                $this->triunfadores= $triunfadores;
                
                return $this->html();
        
    }
private function html(){
        $table = "";
        
        switch ($this->universidad) {
                    case 7:
                        $table = $this->uptag();
                        break;

                    default:
                        break;
                }
        
        $r= '<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
'.$this->css().'
</head>      
<body>
'.
$table
 .'
</body>     
</html>';
        return $r;
    }    
    
    
    private function uptag() {
        switch ($this->pnf) {
                    case 2:
                        return $this->Administracion1();
                        break;

                    default:
                        break;
                }
    }
    
    private function MisionSucre() {
        switch ($this->pnf) {
                    case 15:
                        return $this->TrayectoInicial1();
                        break;

                    default:
                        break;
                }
    }

    private function Administracion1() {
        
        $r='
<table style="border-collapse: collapse; padding: 0px;"
      cellpadding="0" cellspacing="0" id="Acta">
      <tbody>
        <tr>
          <td colspan="2" height="15"><br>
          </td>
          <td height="30" colspan="5" class="naranja titulo negrita" rowspan="1"><b>PROGRAMA NACIONAL DE FORMACION EN
                  ADMINISTRACIÓN</b></td>
          <td valign="top" colspan="3"><br>
          </td>          
        </tr>
        <tr>
          <td colspan="3" rowspan="1" height="92" valign="top"
            ><img alt="misionsucre" src="img/formatos/sWg9HG.png" id="logo_uni"><br>
          </td>
          <td colspan="3" rowspan="1" class="arial diezpt izquierda" ><b>ACTA DE EVALUACION
              DEFINITIVA</b>
            <br>
          </td>
          <td colspan="4" rowspan="1" align="center" valign="top"
            ><img alt="misionsucre" src="img/formatos/zgjemk.png"
              height="71" ><br>
          </td>
        </tr>
        <tr class="naranja centrado nuevept" >
          <td colspan="2" align="center" class="borde"><b>Estado<br>
              </b> </td>
          <td class="borde"><b>Municipio</b></td>
          <td class="borde" colspan="3"
            align="center"  ><b>Aldea</b></td>
          <td  colspan="4" 
            align="center" class="borde" ><b>Lapso</b></td>
        </tr>
        <tr class="centrado nuevept" height="20">
          <td colspan="2" rowspan="1"  class="borde"
            >FALCÓN
          </td>
          <td align="center" class="borde"
              valign="top" >'.$this->municipio.' 
          </td>
          <td colspan="3" rowspan="1" class="borde"
              align="center" valign="top" >'.$this->aldea.'
          </td>
          <td colspan="1" rowspan="1" class="borde paddinglaterales" ><b>Inicio</b><br>
          </td>
          <td colspan="1"  class="centrado paddinglaterales borde">
              '.$this->fechainicio['dia'].'
          </td>
          <td colspan="1"  class="centrado paddinglaterales borde">
              '.$this->fechainicio['mes'].'
          </td>
          <td colspan="1"  class="centrado paddinglaterales borde">
              '.$this->fechainicio['anyo'].'
          </td>
          
        </tr>
        <tr class="naranja centrado nuevept">
          <td class="centrado paddinglaterales borde" colspan="3"><b>Unidad Curricular \ Eje \ Taller</b></td>
          <td  class="centrado paddinglaterales borde" ><b>Trayecto</font></b> </td>
          <td class="centrado paddinglaterales borde" ><b>Trimestre</b> </td>
          <td class="centrado paddinglaterales borde"><b>Sección</b> </td>
          <td class="centrado paddinglaterales borde"
          </td>
          <td colspan="1"  class="centrado paddinglaterales borde sietept">
              día
          </td>
          <td colspan="1"  class="centrado paddinglaterales borde sietept">
              mes
          </td>
          <td colspan="1"  class="centrado paddinglaterales borde sietept">
              año
          </td>
          
        </tr>
        <tr class="centrado nuevept">
          <td class="centrado paddinglaterales borde"  colspan="3">'.strtoupper($this->malla['uc']).'</td>
          <td  class="centrado paddinglaterales borde" >'.$this->malla['trayecto'].' </td>
          <td class="centrado paddinglaterales borde" >'.$this->malla['periodo'].' </td>
          <td class="centrado paddinglaterales borde">'.$this->seccion.'</b> </td>
          <td colspan="1" rowspan="1" class="borde paddinglaterales" ><b>Fin</b><br>
          </td>
          <td colspan="1"  class="centrado paddinglaterales borde">
              '.$this->fechafin['dia'].'
          </td>
          <td colspan="1"  class="centrado paddinglaterales borde">
              '.$this->fechafin['mes'].'
          </td>
          <td colspan="1"  class="centrado paddinglaterales borde">
              '.$this->fechafin['anyo'].'
          </td>
        </tr>
        <tr class="naranja centrado nuevept">
          <td class="centrado paddinglaterales borde" colspan="4"><b>Nombre de la Profesora o Profesor Asesor</b></td>
          </td>
          <td class="centrado paddinglaterales borde" colspan="6"><b>Nombre de la Preparadora o Preparador</b></td>
          </td>
        </tr>
        <tr class="centrado nuevept">
          <td class="centrado paddinglaterales borde" colspan="4"><b>
              '.$this->docente['nombre'].'
              </b></td>
          </td>
          <td class="centrado paddinglaterales borde" colspan="6"><b></b></td>
          </td>
        </tr>
        <tr class="naranja centrado diezpt">
          <td class="centrado paddinglaterales borde" colspan="1" rowspan="2"><b>Nº</b></td>
          </td>
          <td class="centrado paddinglaterales borde" colspan="1"rowspan="2"><b>C.I.</b></td>
          </td>
          <td class="centrado paddinglaterales borde" colspan="1"rowspan="2"><b>APELLIDOS</b></td>
          <td class="centrado paddinglaterales borde" colspan="1"rowspan="2"><b>NOMBRES</b></td>
          </td>
          <td class="centrado paddinglaterales borde" colspan="6"rowspan="1"><b>CALIFICACION</b></td>
          </td>
        </tr>
        <tr class="naranja centrado diezpt">
        <td class="centrado paddinglaterales borde sietept" colspan="1"rowspan="1"><b>númerica o literal</b></td>
          </td>
        <td class="centrado paddinglaterales borde sietept" colspan="5"rowspan="1"><b>en letras</b></td>
          </td>
        </tr>';
        
        $i = 1;

        foreach( $this->triunfadores as $t){
        $r.='<tr class="centrado diezpt">
          <td class="centrado paddinglaterales borde" colspan="1"><b>'.$i.'</b></td>
          </td>
          <td class="centrado paddinglaterales borde" colspan="1"><b>'.strtoupper($t['cedPer']).'</b></td>
          </td>
          <td class="centrado paddinglaterales borde" colspan="1"><b>'.strtoupper($t['priApe']).'</b></td>
          <td class="centrado paddinglaterales borde" colspan="1"><b>'.strtoupper($t['segNom']).'</b></td>
          </td>';
        
           if (strtoupper($t['asistencia']) =="NO" ){
              $r.='<td class="centrado paddinglaterales borde " colspan="1"><b>PI</b></td>
              <td class="centrado paddinglaterales borde" colspan="5"><b>PI</b></td>';
           }
          else{
        $r.='<td class="centrado paddinglaterales borde " colspan="1"><b>'.$t['valor'].'</b></td>';
        $r.= '<td class="centrado paddinglaterales borde" colspan="5"><b>'.$this->Literal($t['valor']).'</b></td></tr>';
          }
         
        $i++;
        }
        foreach(range($i, 30) as $j)
        {
        $r.='<tr class="centrado diezpt">
          <td class="centrado paddinglaterales borde" colspan="1"><b>'.$j.'</b></td>
          </td>
          <td class="centrado paddinglaterales borde" colspan="1"><b></b></td>
          </td>
          <td class="centrado paddinglaterales borde" colspan="1"><b></b></td>
          <td class="centrado paddinglaterales borde" colspan="1"><b></b></td>
          </td>
        <td class="centrado paddinglaterales borde sietept" colspan="1"><b></b></td>
          </td>
          
        <td class="centrado paddinglaterales borde sietept" colspan="5"><b></b></td>
          </td>
        </tr>';
        }
        $r.='
        <tr class="diezpt">
          <td class="centrado padding" colspan="10"><b>VA SIN ENMIENDAS</b></td>
        </tr>
        <tr class="diezpt">
          <td class="centrado padding bordeinferior" colspan="3"></td>
          <td  colspan="1"></td>
          <td class="centrado padding bordeinferior" colspan="6"></td>
        </tr>
        <tr class="diezpt izquierda">
          <td colspan="3" class="izquierda">
	Nombre y Firma - Vocero (a) estudiantil</td>
          <td colspan="1">
	</td>
          <td colspan="6" class="izquierda">
	Firma - Coordinador (a) Academico</td>
        </tr>
        <tr class="diezpt">
          <td colspan="3" class="izquierda">
	C.I.: {{cedulavocero}}</td>
          <td colspan="1">
	</td>
          <td colspan="6" class="izquierda">
	C.I.:</td>
        </tr>
        <tr>
          <td class="centrado padding sietept" colspan="10">SELLO</td>
        </tr>
        <tr class="diezpt">
          <td class="centrado padding bordeinferior" colspan="3"></td>
          <td  colspan="1"></td>
          <td class="centrado padding bordeinferior" colspan="6"></td>
        </tr>
        <tr class="diezpt izquierda">
          <td colspan="3" class="izquierda">
Nombre y Firma - Coordinador (a) de Aldea</td>
          <td colspan="1">
	</td>
          <td colspan="6" class="izquierda">
Firma - Profesora o profesor Asesor</td>
        </tr>
        <tr class="diezpt">
          <td colspan="3" class="izquierda">
	C.I.: {{coordinador.cedPer}}</td>
          <td colspan="1">
	</td>
          <td colspan="6" class="izquierda">
	C.I.: {{cedulavocero}}</td>
        </tr>
      </tbody>
    </table>
';
        return $r;
        }
    
    private function TrayectoInicial1() {
        
        $r= '
<table style="border-collapse: collapse; padding: 0px; margin: 20px 30px 0px 20px"
      cellpadding="0" cellspacing="0" id="Acta">
      <tbody>
        <tr>
            <td  colspan="11">
                <img alt="cintillo" src="img/formatos/cintillo.png" id="cintillo"/>
            </td>
        </tr>
        <tr height="30">
                <td colspan="11"  class="centrado oncept"><b>NOTAS DE TRAYECTO INICIAL GENERAL</b> 
                </td>
        </tr>
        <tr height="30">
                <td  colspan="2" class="derecha diezpt"><b>Coordinador de Aldea:</b> 
                </td>
                <td colspan="2" class="derecha bordeinferior"> 
                </td>
                <td class="centrado diezpt"> 
                    <b>Periodo Académico</b>
                </td>
                <td class="derecha bordeinferior"> 
                </td>
                <td > 
                </td>
                <td class="centrado diezpt"> 
                    <b>Fecha de Inicio</b>
                </td>
                <td class="derecha bordeinferior" colspan="2"> 
                </td>
        </tr>
        <tr >
                <td colspan="2" class="derecha diezpt "><b>Aldea Universitaria:</b> 
                </td>
                <td colspan="2" class="derecha bordeinferior "> 
                </td>
                <td colspan="3" class=""> 
                </td>
                <td class="centrado diezpt"> 
                    <b>Fecha de Culminación</b>
                </td>
                <td colspan="2" class="derecha bordeinferior"> 
                </td>
        </tr>
      </tbody>
    </table>
    <table style="border-collapse: collapse; padding: 0px; margin: 20px 30px 0px 20px"
      cellpadding="0" cellspacing="0" id="cuerpo">
        <tr class="borde centrado ochopt">
                <td   width="10" height="30"><b>Nº</b> 
                </td>
                <td   width="80px"><b>Nombres</b> 
                </td>
                <td   width="80px"><b>Apellidos</b> 
                </td>
                <td   width="80px"><b>C.I.</b> 
                </td>
                <td   width="80px"><b>Matemática</b> 
                </td>
                <td  width="80px" ><b>Proyecto Nacional y </br> y Nueva Ciudadania</b> 
                </td>
                <td   width="80px"><b>Lenguaje y Comunicación</b> 
                </td>
                <td  width="80px" ><b>Taller de Orientación <br/> y Acreditación</b> 
                </td>
                <td   width="80px" ><b>Plan Nacional de Alfabetización </br>Tecnológica</b> 
                </td>
                <td   width="80px"><b>Protección Civil y </br> Administración de</br> 
                        Desastre</b>
                </td>
                <td   width="80px"><b>FIRMA DEL </br>TRIUNFADOR(A)</b> 
                </td>
        </tr>';
        
        $i = 1;

        foreach( $this->triunfadores as $t){
        $r .='
        <tr class="borde centrado ochopt">
                <td   width="10" height="12"><b>'.$i.'</b> 
                </td>
                </td>
          <td class="centrado paddinglaterales borde" colspan="1"><b>'.strtoupper($t['priNom']).'</b></td>
          <td class="centrado paddinglaterales borde" colspan="1"><b>'.strtoupper($t['priApe']).'</b></td>
          <td class="centrado paddinglaterales borde" colspan="1"><b>'.strtoupper($t['cedPer']).'</b></td>';
        }
            $r.=    '</td
        </tr>';
            
        foreach(range($i, 20) as $j)
        {
        $r .='
        <tr class="borde centrado ochopt">
                <td   width="10" height="12"><b>'.$j.'</b> 
                </td>
                </td>
          <td class="centrado paddinglaterales borde" colspan="1"><b></b></td>
          <td class="centrado paddinglaterales borde" colspan="1"><b></b></td>
          <td class="centrado paddinglaterales borde" colspan="1"><b></b></td>';
        
            $r.=    '</td
        </tr>';    
        }
        $r.='
      </tbody>
    </table>
';
        return $r;
        }
    
    private function css() {
       return "<style>
    .naranja{
        background-color:#ff6600;
    }
    .inline{
        display: inline;
    }
    .titulo{
        font-size: 8pt; font-family: arial;
        text-align: center;
        vertical-align: middle;
    }
    .negrita td{
        font-weight: bold;        
    }
    .izquierda{
        text-align: left;
    }
    .bordeinferior{
        border-bottom: 1px solid black;
    }
    .sietept{
        font-size: 7pt;
    }
    .ochopt{
        font-size: 8pt;
    }
    .nuevept,.nuevept td{
        font-size: 9pt;
    }
    .diezpt{
        font-size: 10pt;
    }
    .centrado,.centrado tr td{
        text-align: center;
        vertical-align: middle;
    }
    #logo_uni{
        width: 145px;
        height: 50px;   
    }
    img{
        vertical-align: middle;
        padding-top: 15px;
    }
    .arial{
        font-family: arial;
    }
    table{
        margin-left: 1.5em;
        font-family: arial;
        width: 1000px;
        font-family: arial;
    }
    .borde td,.borde{
    border: 1px solid black;
    }
    .paddinglaterales {
    padding: 0 10px 0 10px;
    }
    .padding{
    padding: 10px 10px 10px 10px;
    }
    #cintillo{
        width: 1020px;
        height: 80px;   
    }
    .derecha{
        text-align: right;
    }
</style>";
        }    

protected function Literal($valor){
 switch( $valor ){   
           
     case 1: return 'UNO';
         break;
     case 2: return 'DOS';
         break;
     case 3: return 'TRES';
         break;
     case 4: return 'CUATRO';
         break;
     case 5: return 'CINCO';
         break;
     case 6: return 'SEIS';
         break;
     case 7: return 'SIETE';
         break;
     case 8: return 'OCHO';
         break;
     case 9: return 'NUEVE';
         break;
     case 10: return 'DIEZ';
         break;
     case 11: return 'ONCE';
         break;
     case 12: return 'DOCE';
         break;
     case 13: return 'TRECE';
         break;
     case 14: return 'CATORCE';
         break;
     case 15: return 'QUINCE';
         break;
     case 16: return 'DIECISEIS';
         break;
     case 17: return 'DIECISIETE';
         break;
     case 18: return 'DIECIOCHO';
         break;
     case 18: return 'DIECIOCHO';
         break;
     case 19: return 'DIECINUEVE';
         break;
     case 20: return 'VEINTE';
         break;
    }
}
}
