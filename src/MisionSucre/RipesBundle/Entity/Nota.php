<?php

namespace MisionSucre\RipesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Nota
 *
 * @ORM\Table(name="nota")
 * @ORM\Entity(repositoryClass="MisionSucre\RipesBundle\Entity\NotaRepository")
 */
class Nota
{   
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="ActaNota", inversedBy="notas")
     * @ORM\JoinColumn(name="actanota_id", referencedColumnName="id")
     */
    protected $actanota;
    
    /**
     * @ORM\ManyToOne(targetEntity="PeriodoTriunfador", inversedBy="notas")
     * @ORM\JoinColumn(name="triunfador_id", referencedColumnName="id")
     */
    protected $triunfador;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="valor", type="integer",length=3)
     */
    private $valor;
    
    /**
     * @var string
     *
     * @ORM\Column(name="asistencia", type="string", length=3, nullable=true)
     */
    private $asistencia;
    

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
     * Set valor
     *
     * @param integer $valor
     * @return Nota
     */
    public function setValor($valor)
    {
        $this->valor = $valor;

        return $this;
    }

    /**
     * Get valor
     *
     * @return integer 
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set asistencia
     *
     * @param string $asistencia
     * @return Nota
     */
    public function setAsistencia($asistencia)
    {
        $this->asistencia = $asistencia;

        return $this;
    }

    /**
     * Get asistencia
     *
     * @return string 
     */
    public function getAsistencia()
    {
        return $this->asistencia;
    }

    /**
     * Set actanota
     *
     * @param \MisionSucre\RipesBundle\Entity\ActaNota $actanota
     * @return Nota
     */
    public function setActanota(\MisionSucre\RipesBundle\Entity\ActaNota $actanota = null)
    {
        $this->actanota = $actanota;

        return $this;
    }

    /**
     * Get actanota
     *
     * @return \MisionSucre\RipesBundle\Entity\ActaNota 
     */
    public function getActanota()
    {
        return $this->actanota;
    }

    /**
     * Set triunfador
     *
     * @param \MisionSucre\RipesBundle\Entity\PeriodoTriunfador $triunfador
     * @return Nota
     */
    public function setTriunfador(\MisionSucre\RipesBundle\Entity\PeriodoTriunfador $triunfador = null)
    {
        $this->triunfador = $triunfador;

        return $this;
    }

    /**
     * Get triunfador
     *
     * @return \MisionSucre\RipesBundle\Entity\PeriodoTriunfador 
     */
    public function getTriunfador()
    {
        return $this->triunfador;
    }
}
