<?php

namespace MisionSucre\RipesBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * OperarioRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class OperarioRepository extends EntityRepository
{
    public function findAllOrderedByAldea($idaldea){
        
        return $this->getEntityManager()
            ->createQuery(
                'SELECT DISTINCT op.id,u.username,p.priNom,p.segNom, p.priApe, p.segApe, p.cedPer, p.sexPer, u.id as idusr,
                    p.celPer,p.telPer,op AS o
                    FROM MisionSucreRipesBundle:Persona p, MisionSucreRipesBundle:User u,
                    MisionSucreRipesBundle:Operario op JOIN op.aldea a 
                    WHERE p.user=u.id AND op.user=u.id AND op.aldea =:idaldea
                    '
            )
            ->setParameter('idaldea', $idaldea)      
            ->getResult();
   
    }
    public function findAllOrderedByAldeaTurno($idcoordinador){
        
        return $this->getEntityManager()
            ->createQuery(
                'SELECT DISTINCT op.id,u.username,p.priNom,p.segNom, p.priApe, p.segApe, p.cedPer, p.sexPer, u.id as idusr,
                    p.celPer,p.telPer,op AS o
                    FROM MisionSucreRipesBundle:Persona p, MisionSucreRipesBundle:User u,
                    MisionSucreRipesBundle:Operario op JOIN op.aldea a,
                    MisionSucreRipesBundle:CoordinadorTurno t JOIN t.coordinador c
                    WHERE p.user=u.id AND op.user=u.id AND op.aldea =c.aldea AND c.id = :idcoordinador AND op.turno=t.turno 
                    '
            )
            ->setParameter('idcoordinador', $idcoordinador)      
            ->getResult();
   
    }
    
    
    public function findAllOrderedByUser(){
        
        return $this->getEntityManager()
            ->createQuery(
                'SELECT DISTINCT op.id,u.username,p.priNom,p.segNom, p.priApe, p.segApe, p.cedPer, p.sexPer, u.id as idusr,
                    p.celPer,p.telPer, op AS o
                    FROM MisionSucreRipesBundle:Persona p, MisionSucreRipesBundle:User u,
                    MisionSucreRipesBundle:Operario op JOIN op.aldea a 
                    WHERE p.user=u.id AND op.user=u.id 
                    '
            )
              
            ->getResult();
   
    }
    public function findAllOrderedByEje($eje){
        
        return $this->getEntityManager()
            ->createQuery(
                'SELECT DISTINCT op.id,u.username,p.priNom,p.segNom, p.priApe, p.segApe, p.cedPer, p.sexPer, u.id as idusr,
                    p.celPer,p.telPer,op AS o
                    FROM MisionSucreRipesBundle:Persona p, MisionSucreRipesBundle:User u,
                    MisionSucreRipesBundle:Operario op JOIN op.aldea a JOIN a.parroquia prq
                    WHERE p.user=u.id AND op.user=u.id AND prq.eje = :eje
                    '
            )
            ->setParameter('eje', $eje)      
            ->getResult();
    }
}