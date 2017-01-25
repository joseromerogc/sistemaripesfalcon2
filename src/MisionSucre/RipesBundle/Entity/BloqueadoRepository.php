<?php

namespace MisionSucre\RipesBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * BloqueadoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BloqueadoRepository extends EntityRepository
{
    public function findAllDatos()
    {
          return $this->getEntityManager()
            ->createQuery(
                'SELECT b.id,u.username, b.motivo, u.id AS idusr,p.priNom,p.segNom, p.priApe, p.segApe,p.cedPer AS cedula, r.name AS perfil FROM MisionSucreRipesBundle:Role r, 
                    MisionSucreRipesBundle:Persona p JOIN p.user u, MisionSucreRipesBundle:Bloqueado b WHERE r.id=u.tip_usr 
                    AND b.cedulas = p.cedPer
                    '
            )
            ->getResult();
    }
}
