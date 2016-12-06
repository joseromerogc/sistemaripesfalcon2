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
     * @ORM\ManyToOne(targetEntity="PeriodoPnf", inversedBy="periodosacademicosambientes")
     * @ORM\JoinColumn(name="pacadamb_id", referencedColumnName="id")
     */
    protected $periodopnf;
    
    /**
     * @ORM\OneToMany(targetEntity="PeriodoTriunfador", mappedBy="periodoacademicoambiente")
     */
    protected $periodostriunfadores;
    /**
     * @ORM\OneToMany(targetEntity="ActaNota", mappedBy="periodoacademicoambiente")
     */
    protected $actasnotas;
    
    
    
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
     * Set periodopnf
     *
     * @param \MisionSucre\RipesBundle\Entity\PeriodoPnf $periodopnf
     * @return PeriodoAcademicoAmbiente
     */
    public function setPeriodopnf(\MisionSucre\RipesBundle\Entity\PeriodoPnf $periodopnf = null)
    {
        $this->periodopnf = $periodopnf;

        return $this;
    }

    /**
     * Get periodopnf
     *
     * @return \MisionSucre\RipesBundle\Entity\PeriodoPnf 
     */
    public function getPeriodopnf()
    {
        return $this->periodopnf;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->periodostriunfadores = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add periodostriunfadores
     *
     * @param \MisionSucre\RipesBundle\Entity\PeriodoTriunfador $periodostriunfadores
     * @return PeriodoAcademicoAmbiente
     */
    public function addPeriodostriunfadore(\MisionSucre\RipesBundle\Entity\PeriodoTriunfador $periodostriunfadores)
    {
        $this->periodostriunfadores[] = $periodostriunfadores;

        return $this;
    }

    /**
     * Remove periodostriunfadores
     *
     * @param \MisionSucre\RipesBundle\Entity\PeriodoTriunfador $periodostriunfadores
     */
    public function removePeriodostriunfadore(\MisionSucre\RipesBundle\Entity\PeriodoTriunfador $periodostriunfadores)
    {
        $this->periodostriunfadores->removeElement($periodostriunfadores);
    }

    /**
     * Get periodostriunfadores
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPeriodostriunfadores()
    {
        return $this->periodostriunfadores;
    }

    /**
     * Add actasnotas
     *
     * @param \MisionSucre\RipesBundle\Entity\ActaNota $actasnotas
     * @return PeriodoAcademicoAmbiente
     */
    public function addActasnota(\MisionSucre\RipesBundle\Entity\ActaNota $actasnotas)
    {
        $this->actasnotas[] = $actasnotas;

        return $this;
    }

    /**
     * Remove actasnotas
     *
     * @param \MisionSucre\RipesBundle\Entity\ActaNota $actasnotas
     */
    public function removeActasnota(\MisionSucre\RipesBundle\Entity\ActaNota $actasnotas)
    {
        $this->actasnotas->removeElement($actasnotas);
    }

    /**
     * Get actasnotas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getActasnotas()
    {
        return $this->actasnotas;
    }
}
