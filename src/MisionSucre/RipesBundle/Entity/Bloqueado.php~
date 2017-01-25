<?php

namespace MisionSucre\RipesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="bloqueados")
 * @ORM\Entity()
 * @ORM\Entity(repositoryClass="MisionSucre\RipesBundle\Entity\BloqueadoRepository")
 */
class Bloqueado
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=30)
     */
    private $motivo;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="cedula", type="bigint", nullable=false)
     */
    private $cedulas;
    
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set motivo
     *
     * @param string $motivo
     * @return Bloqueado
     */
    public function setMotivo($motivo)
    {
        $this->motivo = $motivo;

        return $this;
    }

    /**
     * Get motivo
     *
     * @return string 
     */
    public function getMotivo()
    {
        return $this->motivo;
    }

    /**
     * Set cedulas
     *
     * @param integer $cedulas
     * @return Bloqueado
     */
    public function setCedulas($cedulas)
    {
        $this->cedulas = $cedulas;

        return $this;
    }

    /**
     * Get cedulas
     *
     * @return integer 
     */
    public function getCedulas()
    {
        return $this->cedulas;
    }
}
