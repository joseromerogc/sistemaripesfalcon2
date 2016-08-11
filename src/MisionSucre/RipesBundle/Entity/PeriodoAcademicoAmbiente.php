<?php

namespace MisionSucre\RipesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PeriodoAcademicoAmbiente
 *
 * @ORM\Table(name="periodo_academico_ambiente")
 * @ORM\Entity(repositoryClass="MisionSucre\RipesBundle\Entity\PeriodoAcademicoAmbienteRepository")
 */
class PeriodoAcademicoAmbiente
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
     * @ORM\ManyToOne(targetEntity="Ambiente", inversedBy="periodosacademicos")
     * @ORM\JoinColumn(name="ambiente_id", referencedColumnName="id")
     */
    protected $ambiente;
    
    /**
     * @ORM\ManyToOne(targetEntity="PeriodoAcademico", inversedBy="periodosacademicosambiente")
     * @ORM\JoinColumn(name="periodoacademico_id", referencedColumnName="id")
     */
    protected $periodoacademico;
    
    /**
     * @var string
     *
     * @ORM\Column(name="trayecto", type="string", length=2,nullable=true)
     */
    
    private $trayecto;
    
    /**
     * @var string
     *
     * @ORM\Column(name="periodo", type="string", length=2,nullable=true)
     */
    
    private $periodo;
    
    
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
     * Set trayecto
     *
     * @param string $trayecto
     * @return PeriodoAcademicoAmbiente
     */
    public function setTrayecto($trayecto)
    {
        $this->trayecto = $trayecto;

        return $this;
    }

    /**
     * Get trayecto
     *
     * @return string 
     */
    public function getTrayecto()
    {
        return $this->trayecto;
    }

    /**
     * Set periodo
     *
     * @param string $periodo
     * @return PeriodoAcademicoAmbiente
     */
    public function setPeriodo($periodo)
    {
        $this->periodo = $periodo;

        return $this;
    }

    /**
     * Get periodo
     *
     * @return string 
     */
    public function getPeriodo()
    {
        return $this->periodo;
    }

    /**
     * Set ambiente
     *
     * @param \MisionSucre\RipesBundle\Entity\Ambiente $ambiente
     * @return PeriodoAcademicoAmbiente
     */
    public function setAmbiente(\MisionSucre\RipesBundle\Entity\Ambiente $ambiente = null)
    {
        $this->ambiente = $ambiente;

        return $this;
    }

    /**
     * Get ambiente
     *
     * @return \MisionSucre\RipesBundle\Entity\Ambiente 
     */
    public function getAmbiente()
    {
        return $this->ambiente;
    }

    /**
     * Set periodoacademico
     *
     * @param \MisionSucre\RipesBundle\Entity\PeriodoAcademico $periodoacademico
     * @return PeriodoAcademicoAmbiente
     */
    public function setPeriodoacademico(\MisionSucre\RipesBundle\Entity\PeriodoAcademico $periodoacademico = null)
    {
        $this->periodoacademico = $periodoacademico;

        return $this;
    }

    /**
     * Get periodoacademico
     *
     * @return \MisionSucre\RipesBundle\Entity\PeriodoAcademico 
     */
    public function getPeriodoacademico()
    {
        return $this->periodoacademico;
    }

    /**
     * Set actual
     *
     * @param string $actual
     * @return PeriodoAcademicoAmbiente
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
