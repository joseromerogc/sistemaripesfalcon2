<?php

namespace MisionSucre\RipesBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * CoordinadorTurnoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CoordinadorTurnoRepository extends EntityRepository
{
    public function findAllByCoordinador($idc)
    {
          return $this->getEntityManager()
            ->createQuery(
                'SELECT t FROM MisionSucreRipesBundle:CoordinadorTurno t
                    WHERE t.coordinador =:idc
                '
            )
            ->setParameters(array('idc'=>$idc))->getArrayResult();
    }
    public function findAllByAldea($aldea)
    {
          return $this->getEntityManager()
            ->createQuery(
                'SELECT t.id, t.turno, p.priNom,p.segNom, p.priApe, p.segApe FROM MisionSucreRipesBundle:CoordinadorTurno t JOIN t.coordinador c JOIN c.user u,
                    MisionSucreRipesBundle:Persona p
                    WHERE c.aldea =:aldea AND p.user=u.id
                '
            )
            ->setParameters(array('aldea'=>$aldea))->getResult();
    }
    public function TurnosAldea($aldea)
    {
          return $this->getEntityManager()
            ->createQuery(
                'SELECT t.turno FROM MisionSucreRipesBundle:CoordinadorTurno t JOIN t.coordinador c 
                    WHERE c.aldea =:aldea
                '
            )
            ->setParameters(array('aldea'=>$aldea))->getArrayResult();
    }
    
    public function ExisteTurno($turno,$aldea)
    {
          return $this->getEntityManager()
            ->createQuery(
                'SELECT t.id FROM MisionSucreRipesBundle:CoordinadorTurno t JOIN t.coordinador c
                    WHERE c.aldea =:aldea AND t.turno=:turno
                '
            )
            ->setParameters(array('aldea'=>$aldea,'turno'=>$turno))->getResult();
    }
    public function CoordinadorTurno($turno,$coordinador)
    {
          return $this->getEntityManager()
            ->createQuery(
                'SELECT t.id FROM MisionSucreRipesBundle:CoordinadorTurno t JOIN t.coordinador c
                    WHERE c.id =:coordinador AND t.turno=:turno
                '
            )
            ->setParameters(array('coordinador'=>$coordinador,'turno'=>$turno))->getResult();
    }
     public function AmbientesTurnos($idcoordinador)
    {
          return $this->getEntityManager()
            ->createQuery(
                " SELECT pa as ambiente, 
                    (select count(tr.id)  FROM MisionSucreRipesBundle:Triunfador tr WHERE tr.ambiente=a.id) AS cantidadtriunfadores 
                    FROM MisionSucreRipesBundle:PeriodoAcademico p,  MisionSucreRipesBundle:PeriodoAcademicoAmbiente pa 
                    JOIN pa.ambiente a,
                    MisionSucreRipesBundle:CoordinadorTurno t JOIN t.coordinador c
                            WHERE c.id = :idcoordinador AND c.aldea = a.aldea AND t.turno=a.turno
                            AND p.actual='SI' AND pa.periodoacademico=p.id
                    "
            )
            ->setParameters(array('idcoordinador'=> $idcoordinador))
            ->getResult();
    }
    public function AmbientesTurnosSinPeriodoAcademico($idcoordinador)
    {
          return $this->getEntityManager()
            ->createQuery(
                "SELECT am as ambiente, (select count(tr.id)  FROM MisionSucreRipesBundle:Triunfador tr WHERE tr.ambiente=am.id) AS cantidadtriunfadores 
                    FROM MisionSucreRipesBundle:Ambiente am JOIN am.pnf pn,
                    MisionSucreRipesBundle:CoordinadorTurno t JOIN t.coordinador c
                            WHERE c.id = :idcoordinador AND c.aldea = am.aldea AND t.turno=am.turno
                            AND (am.condicion ='Nuevo' OR am.condicion ='Activo')
                            AND NOT EXISTS
                   ( SELECT pa FROM MisionSucreRipesBundle:PeriodoAcademicoAmbiente pa join pa.periodoacademico  p WHERE pa.ambiente=am.id AND p.actual=:actual)
                      "
            )
            ->setParameters(array('idcoordinador'=> $idcoordinador,'actual'=>'SI'))
            ->getResult();
    }
    
    public function TriunfadoresCoordinador($idcoordinador)
    {
          return $this->getEntityManager()
            ->createQuery(
                'SELECT  IDENTITY(p.user) AS id,p.priNom,p.segNom, p.priApe, p.segApe, p.cedPer, p.sexPer
                    ,p.celPer,p.telPer, trf AS t FROM MisionSucreRipesBundle:PeriodoAcademicoAmbiente pacd JOIN pacd.ambiente a JOIN a.pnf pnf,
                    MisionSucreRipesBundle:Triunfador trf, MisionSucreRipesBundle:Persona p,MisionSucreRipesBundle:CoordinadorTurno tu JOIN tu.coordinador c
                    WHERE trf.ambiente = a.id AND trf.user = p.user
                    AND c.id = :idcoordinador AND c.aldea = a.aldea AND tu.turno=a.turno
                '
            )
            ->setParameters(array('idcoordinador'=>$idcoordinador))->getResult();
    }
    public function TriunfadoresCoordinadorCedula($idcoordinador,$cedula)
    {
          return $this->getEntityManager()
            ->createQuery(
                'SELECT  IDENTITY(p.user) AS id,p.priNom,p.segNom, p.priApe, p.segApe, p.cedPer, p.sexPer
                    ,p.celPer,p.telPer, trf AS t FROM MisionSucreRipesBundle:PeriodoAcademicoAmbiente pacd JOIN pacd.ambiente a JOIN a.pnf pnf,
                    MisionSucreRipesBundle:Triunfador trf, MisionSucreRipesBundle:Persona p,MisionSucreRipesBundle:CoordinadorTurno tu JOIN tu.coordinador c
                    WHERE trf.ambiente = a.id AND trf.user = p.user AND p.cedPer LIKE :cedula
                    AND c.id = :idcoordinador AND c.aldea = a.aldea AND tu.turno=a.turno
                '
            )
            ->setParameters(array('idcoordinador'=>$idcoordinador,'cedula'=>"%$cedula%"))->getResult();
    }
    
    public function CoordinadorTurnoAmbiente($amb)
    {
          return $this->getEntityManager()
            ->createQuery(
                'SELECT t FROM MisionSucreRipesBundle:CoordinadorTurno t JOIN t.coordinador c,
                    MisionSucreRipesBundle:Ambiente a
                    WHERE a.id =:amb AND a.turno=t.turno AND a.aldea=c.aldea
                '
            )
            ->setParameters(array('amb'=>$amb))->getSingleResult();
    }
    
}
