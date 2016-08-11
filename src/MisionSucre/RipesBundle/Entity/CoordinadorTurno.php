<?php

namespace MisionSucre\RipesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="coordinador_turno")
 * @ORM\Entity()
 * @ORM\Entity(repositoryClass="MisionSucre\RipesBundle\Entity\CoordinadorTurnoRepository")
 */
class CoordinadorTurno
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
    private $turno;
    
    /**
     * @ORM\ManyToOne(targetEntity="CoordinadorAldea", inversedBy="turnos")
     * @ORM\JoinColumn(name="coordinador_id", referencedColumnName="id")
     */
    protected $coordinador;
    
    
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
     * Set turno
     *
     * @param string $turno
     * @return CoordinadorTurno
     */
    public function setTurno($turno)
    {
        $this->turno = $turno;

        return $this;
    }

    /**
     * Get turno
     *
     * @return string 
     */
    public function getTurno()
    {
        return $this->turno;
    }

    /**
     * Set coordinador
     *
     * @param \MisionSucre\RipesBundle\Entity\CoordinadorAldea $coordinador
     * @return CoordinadorTurno
     */
    public function setCoordinador(\MisionSucre\RipesBundle\Entity\CoordinadorAldea $coordinador = null)
    {
        $this->coordinador = $coordinador;

        return $this;
    }

    /**
     * Get coordinador
     *
     * @return \MisionSucre\RipesBundle\Entity\CoordinadorAldea 
     */
    public function getCoordinador()
    {
        return $this->coordinador;
    }
}
