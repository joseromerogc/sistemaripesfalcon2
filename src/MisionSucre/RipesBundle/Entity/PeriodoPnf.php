<?php

namespace MisionSucre\RipesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ambiente
 *
 * @ORM\Table(name="periodo_pnf")
 * @ORM\Entity(repositoryClass="MisionSucre\RipesBundle\Entity\PeriodoPndRepository")
 */
class PeriodoPnf
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
     * @ORM\ManyToOne(targetEntity="Pnf", inversedBy="periodos")
     * @ORM\JoinColumn(name="pnf_id", referencedColumnName="id")
     */
    protected $pnf;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="trayecto", type="integer", length=3)
     */
    
    private $trayecto;
    /**
     * @var integer
     *
     * @ORM\Column(name="periodo", type="integer", length=3)
     */
    
    private $periodo;
    
    /**
     * @ORM\OneToMany(targetEntity="Malla", mappedBy="periodopnf")
     */
    protected $mallas;
    /**
     * @ORM\OneToMany(targetEntity="PeriodoAcademicoAmbiente", mappedBy="periodopnf")
     */
    protected $periodosacademicosambientes;
    
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
     * @param integer $trayecto
     * @return PeriodoPnf
     */
    public function setTrayecto($trayecto)
    {
        $this->trayecto = $trayecto;

        return $this;
    }

    /**
     * Get trayecto
     *
     * @return integer 
     */
    public function getTrayecto()
    {
        return $this->trayecto;
    }

    /**
     * Set periodo
     *
     * @param integer $periodo
     * @return PeriodoPnf
     */
    public function setPeriodo($periodo)
    {
        $this->periodo = $periodo;

        return $this;
    }

    /**
     * Get periodo
     *
     * @return integer 
     */
    public function getPeriodo()
    {
        return $this->periodo;
    }

    /**
     * Set pnf
     *
     * @param \MisionSucre\RipesBundle\Entity\Pnf $pnf
     * @return PeriodoPnf
     */
    public function setPnf(\MisionSucre\RipesBundle\Entity\Pnf $pnf = null)
    {
        $this->pnf = $pnf;

        return $this;
    }

    /**
     * Get pnf
     *
     * @return \MisionSucre\RipesBundle\Entity\Pnf 
     */
    public function getPnf()
    {
        return $this->pnf;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->mallas = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add mallas
     *
     * @param \MisionSucre\RipesBundle\Entity\Malla $mallas
     * @return PeriodoPnf
     */
    public function addMalla(\MisionSucre\RipesBundle\Entity\Malla $mallas)
    {
        $this->mallas[] = $mallas;

        return $this;
    }

    /**
     * Remove mallas
     *
     * @param \MisionSucre\RipesBundle\Entity\Malla $mallas
     */
    public function removeMalla(\MisionSucre\RipesBundle\Entity\Malla $mallas)
    {
        $this->mallas->removeElement($mallas);
    }

    /**
     * Get mallas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMallas()
    {
        return $this->mallas;
    }

    /**
     * Add periodosacademicosambientes
     *
     * @param \MisionSucre\RipesBundle\Entity\PeriodoAcademicoAmbiente $periodosacademicosambientes
     * @return PeriodoPnf
     */
    public function addPeriodosacademicosambiente(\MisionSucre\RipesBundle\Entity\PeriodoAcademicoAmbiente $periodosacademicosambientes)
    {
        $this->periodosacademicosambientes[] = $periodosacademicosambientes;

        return $this;
    }

    /**
     * Remove periodosacademicosambientes
     *
     * @param \MisionSucre\RipesBundle\Entity\PeriodoAcademicoAmbiente $periodosacademicosambientes
     */
    public function removePeriodosacademicosambiente(\MisionSucre\RipesBundle\Entity\PeriodoAcademicoAmbiente $periodosacademicosambientes)
    {
        $this->periodosacademicosambientes->removeElement($periodosacademicosambientes);
    }

    /**
     * Get periodosacademicosambientes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPeriodosacademicosambientes()
    {
        return $this->periodosacademicosambientes;
    }
}
