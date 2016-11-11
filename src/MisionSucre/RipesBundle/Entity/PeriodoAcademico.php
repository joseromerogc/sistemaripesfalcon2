<?php

namespace MisionSucre\RipesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PeriodoAcademico
 *
 * @ORM\Table(name="periodo_academico")
 * @ORM\Entity(repositoryClass="MisionSucre\RipesBundle\Entity\PeriodoAcademicoRepository")
 */
class PeriodoAcademico
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
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=20)
     */
    
    private $nombre;
    
    /**
     * @var string
     *
     * @ORM\Column(name="modalidad", type="string", nullable=true)
     */
    private $modalidad;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_inicio", type="date", nullable=true)
     */
    private $fechainicio;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_fin", type="date", nullable=true)
     */
    private $fechafin;
    
    /**
     * @var string
     *
     * @ORM\Column(name="actual", type="string", length=2,nullable=true)
     */
    
    private $actual;
    
    /**
     * @ORM\OneToMany(targetEntity="PeriodoAcademicoAmbiente", mappedBy="periodoacademico")
     */
    protected $periodosacademicosambiente;
    /**
     * @ORM\OneToMany(targetEntity="TIFinalizado", mappedBy="periodoacademico")
     */
    protected $periodosacademicostifinalizados;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->periodosacademicosambiente = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set nombre
     *
     * @param string $nombre
     * @return PeriodoAcademico
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set fechainicio
     *
     * @param \DateTime $fechainicio
     * @return PeriodoAcademico
     */
    public function setFechainicio($fechainicio)
    {
        $this->fechainicio = $fechainicio;

        return $this;
    }

    /**
     * Get fechainicio
     *
     * @return \DateTime 
     */
    public function getFechainicio()
    {
        return $this->fechainicio;
    }

    /**
     * Set fechafin
     *
     * @param \DateTime $fechafin
     * @return PeriodoAcademico
     */
    public function setFechafin($fechafin)
    {
        $this->fechafin = $fechafin;

        return $this;
    }

    /**
     * Get fechafin
     *
     * @return \DateTime 
     */
    public function getFechafin()
    {
        return $this->fechafin;
    }

    /**
     * Add periodosacademicosambiente
     *
     * @param \MisionSucre\RipesBundle\Entity\PeriodoAcademicoAmbiente $periodosacademicosambiente
     * @return PeriodoAcademico
     */
    public function addPeriodosacademicosambiente(\MisionSucre\RipesBundle\Entity\PeriodoAcademicoAmbiente $periodosacademicosambiente)
    {
        $this->periodosacademicosambiente[] = $periodosacademicosambiente;

        return $this;
    }

    /**
     * Remove periodosacademicosambiente
     *
     * @param \MisionSucre\RipesBundle\Entity\PeriodoAcademicoAmbiente $periodosacademicosambiente
     */
    public function removePeriodosacademicosambiente(\MisionSucre\RipesBundle\Entity\PeriodoAcademicoAmbiente $periodosacademicosambiente)
    {
        $this->periodosacademicosambiente->removeElement($periodosacademicosambiente);
    }

    /**
     * Get periodosacademicosambiente
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPeriodosacademicosambiente()
    {
        return $this->periodosacademicosambiente;
    }

    /**
     * Set modalidad
     *
     * @param \DateTime $modalidad
     * @return PeriodoAcademico
     */
    public function setModalidad($modalidad)
    {
        $this->modalidad = $modalidad;

        return $this;
    }

    /**
     * Get modalidad
     *
     * @return \DateTime 
     */
    public function getModalidad()
    {
        return $this->modalidad;
    }

    /**
     * Set actual
     *
     * @param string $actual
     * @return PeriodoAcademico
     */
    public function setActual($actual)
    {
        $this->actual = $actual;

        return $this;
    }

    /**
     * Get actual
     *
     * @return string 
     */
    public function getActual()
    {
        return $this->actual;
    }
}
