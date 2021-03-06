<?php

namespace MisionSucre\RipesBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * EstructuraRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EstructuraRepository extends EntityRepository
{
    public function findAllByEje($parroquia)
    {
          return $this->getEntityManager()
            ->createQuery(
                "SELECT str FROM MisionSucreRipesBundle:Estructura str JOIN str.aldea a
                    WHERE a.parroquia=:parroquia
                    
                "
            )
            ->setParameters(array('parroquia'=>$parroquia))->getResult();
    }
}
