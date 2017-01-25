<?php

namespace MisionSucre\RipesBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ConsejoComunalRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ConsejoComunalRepository extends EntityRepository
{
    
    public function findAllOrderedByParroquia()
    {
          return $this->getEntityManager()
            ->createQuery(
                'SELECT m.municipio , prq.parroquia , c.nombre AS consejocomunal, e.nombre as eje, 
                             SUM ( CASE WHEN (
                             EXISTS 
                            ( SELECT ca FROM MisionSucreRipesBundle:CoordinadorAldea ca WHERE u.id=ca.user) OR
                             EXISTS 
                            ( SELECT d FROM MisionSucreRipesBundle:Docente d WHERE u.id=d.user ) OR
                            EXISTS
                            ( SELECT t FROM MisionSucreRipesBundle:Triunfador t WHERE u.id=t.user ) OR
                             EXISTS
                            ( SELECT op FROM MisionSucreRipesBundle:Operario op WHERE u.id=op.user)
                            )
                             THEN 1 ELSE 0 END )
                             AS cantidad
                              FROM MisionSucreRipesBundle:ParticipacionComunitaria pac JOIN pac.cc c JOIN c.parroquia prq JOIN prq.municipio m,
                              MisionSucreRipesBundle:User u, MisionSucreRipesBundle:Eje e
                              WHERE u.id = pac.user AND e.id=prq.eje
                            GROUP BY c.id
                            ORDER BY m.municipio, prq.parroquia
                    '
            ) 
            ->getResult();
    }
    public function findAllOrderedByEje($eje)
    {
          return $this->getEntityManager()
            ->createQuery(
                'SELECT m.municipio , prq.parroquia , c.nombre AS consejocomunal, e.nombre as eje, 
                             SUM ( CASE WHEN (
                             EXISTS 
                            ( SELECT cs FROM MisionSucreRipesBundle:CoordinadorAldea ca WHERE u.id=ca.user) OR
                             EXISTS 
                            ( SELECT d FROM MisionSucreRipesBundle:Docente d WHERE u.id=d.user ) OR
                            EXISTS
                            ( SELECT t FROM MisionSucreRipesBundle:Triunfador t WHERE u.id=t.user ) OR
                             EXISTS
                            ( SELECT op FROM MisionSucreRipesBundle:Operario op WHERE u.id=op.user)
                            )
                             THEN 1 ELSE 0 END )
                             AS cantidad
                              FROM MisionSucreRipesBundle:ParticipacionComunitaria pac JOIN pac.cc c JOIN c.parroquia prq JOIN prq.municipio m,
                              MisionSucreRipesBundle:User u, MisionSucreRipesBundle:Eje e
                              WHERE u.id = pac.user AND prq.eje=:eje AND e.id=prq.eje
                            GROUP BY c.id
                            ORDER BY m.municipio, prq.parroquia
                    '
            ) ->setParameters(array('eje'=>$eje))
            ->getResult();
    }
    
    public function findAllOrderedByAldea($aldea)
    {
          return $this->getEntityManager()
            ->createQuery(
                'SELECT m.municipio , prq.parroquia , c.nombre AS consejocomunal, e.nombre as eje, 
                             SUM ( CASE WHEN (
                             EXISTS 
                            ( SELECT cs FROM MisionSucreRipesBundle:CoordinadorAldea ca WHERE u.id=ca.user AND ca.aldea=:aldea) OR
                             EXISTS 
                            ( SELECT d FROM MisionSucreRipesBundle:Docente d WHERE u.id=d.user AND d.aldea=:aldea) OR
                            EXISTS
                            ( SELECT t FROM MisionSucreRipesBundle:Triunfador t JOIN t.ambiente a WHERE u.id=t.user AND a.aldea=:aldea) OR
                             EXISTS
                            ( SELECT op FROM MisionSucreRipesBundle:Operario op WHERE u.id=op.user AND op.aldea=:aldea)
                            )
                             THEN 1 ELSE 0 END )
                             AS cantidad
                              FROM MisionSucreRipesBundle:ParticipacionComunitaria pac JOIN pac.cc c JOIN c.parroquia prq JOIN prq.municipio m,
                              MisionSucreRipesBundle:User u, MisionSucreRipesBundle:Eje e
                              WHERE u.id = pac.user AND e.id=prq.eje
                            GROUP BY c.id
                            ORDER BY m.municipio, prq.parroquia
                    '
            ) ->setParameters(array('aldea'=>$aldea))
            ->getResult();
    }
    public function buscarPorNombreParroquia($nombre,$prq){
        
        return $this->getEntityManager()
            ->createQuery(
                'SELECT DISTINCT cc
                    FROM MisionSucreRipesBundle:ConsejoComunal cc
                    WHERE LOWER(cc.nombre)=LOWER(:nombre) AND cc.parroquia=:prq
                    '
            )
            ->setParameters(array('nombre'=>$nombre,'prq'=>$prq))     
            ->getResult();
    }
    
    public function sinParticipacion(){
        
        return $this->getEntityManager()
            ->createQuery(
                'SELECT DISTINCT m.municipio , prq.parroquia , cc.nombre AS consejocomunal, e.nombre as eje
                    FROM MisionSucreRipesBundle:ConsejoComunal cc JOIN cc.parroquia prq JOIN prq.municipio m,
                    MisionSucreRipesBundle:Eje e
                    WHERE  NOT EXISTS ( SELECT pac FROM MisionSucreRipesBundle:ParticipacionComunitaria pac WHERE pac.cc=cc.id)
                    AND e.id=prq.eje
                    '
            )
            ->getResult();
    }
}
