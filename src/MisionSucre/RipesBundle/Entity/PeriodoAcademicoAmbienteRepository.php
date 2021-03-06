<?php

namespace MisionSucre\RipesBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * PeriodoAcademicoAmbienteRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PeriodoAcademicoAmbienteRepository extends EntityRepository
{
    
    public function findAllByAmbiente($ambiente)
    {
          return $this->getEntityManager()
            ->createQuery(
                'SELECT pa FROM MisionSucreRipesBundle:PeriodoAcademico p, MisionSucreRipesBundle:PeriodoAcademicoAmbiente pa JOIN pa.ambiente a
                    WHERE p.actual=:actual AND a.id =:ambiente
                '
            )
            ->setParameters(array('actual'=>'SI','ambiente'=>$ambiente))->getOneOrNullResult();
    }
    public function Ambiente($ambiente)
    {
          return $this->getEntityManager()
            ->createQuery(
                'SELECT pa FROM MisionSucreRipesBundle:PeriodoAcademico p, MisionSucreRipesBundle:PeriodoAcademicoAmbiente pa JOIN pa.ambiente a
                    WHERE p.actual=:actual AND a.id =:ambiente AND
                    pa.periodoacademico=p.id
                '
            )
            ->setParameters(array('actual'=>'SI','ambiente'=>$ambiente))->getOneOrNullResult();
    }
    public function UltimoPeriodo($ambiente)
    {
          return $this->getEntityManager()
            ->createQuery(
                "SELECT pa FROM MisionSucreRipesBundle:PeriodoAcademico p, MisionSucreRipesBundle:PeriodoAcademicoAmbiente pa JOIN pa.ambiente a
                    WHERE a.id =:ambiente AND
                    pa.periodoacademico=p.id AND
                    p.nombre LIKE CONCAT('%',a.egreso)
                "
            )
            ->setParameters(array('ambiente'=>$ambiente))->getOneOrNullResult();
    }
    public function PeriodosAcademicosDisponibles($modalidad,$ambiente)
    {
          return $this->getEntityManager()
            ->createQuery(
                "SELECT p FROM MisionSucreRipesBundle:PeriodoAcademico p
                    WHERE p.modalidad =:modalidad AND NOT EXISTS 
                   ( SELECT pa FROM MisionSucreRipesBundle:PeriodoAcademicoAmbiente pa 
                   join pa.periodoacademico pac WHERE pa.ambiente=:ambiente AND p.id=pac.id)
                "
            )
            ->setParameters(array('ambiente'=>$ambiente,'modalidad'=>$modalidad))->getResult();
    }
    public function TrayectosPnfDisponibles($ambiente,$pnf)
    {
          return $this->getEntityManager()
            ->createQuery(
                "SELECT DISTINCT(ppnf.trayecto) as trayecto FROM MisionSucreRipesBundle:PeriodoPnf ppnf
                    WHERE ppnf.pnf =:pnf AND NOT EXISTS 
                   ( SELECT p FROM MisionSucreRipesBundle:PeriodoAcademicoAmbiente pa 
                   join pa.periodopnf p WHERE pa.ambiente=:ambiente AND ppnf.id=p.id)
                   ORDER BY ppnf.trayecto
                "
            )
            ->setParameters(array('ambiente'=>$ambiente,'pnf'=>$pnf))->getResult();
    }
    public function PeriodosPnfDisponibles($ambiente,$pnf,$trayecto)
    {
          return $this->getEntityManager()
            ->createQuery(
                "SELECT ppnf.periodo,ppnf.id ppnfid  FROM MisionSucreRipesBundle:PeriodoPnf ppnf
                    WHERE ppnf.pnf =:pnf AND ppnf.trayecto=:trayecto AND NOT EXISTS 
                   ( SELECT p FROM MisionSucreRipesBundle:PeriodoAcademicoAmbiente pa 
                   join pa.periodopnf p WHERE pa.ambiente=:ambiente AND p.trayecto=:trayecto AND ppnf.id=p.id)
                "
            )
            ->setParameters(array('ambiente'=>$ambiente,'pnf'=>$pnf,'trayecto'=>$trayecto))->getResult();
    }
}
