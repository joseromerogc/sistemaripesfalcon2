<?php

namespace MisionSucre\RipesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ambiente
 *
 * @ORM\Table(name="malla")
 * @ORM\Entity(repositoryClass="MisionSucre\RipesBundle\Entity\MallaRepository")
 */
class Malla
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
     * @ORM\ManyToOne(targetEntity="UnidadCurricular", inversedBy="mallas")
     * @ORM\JoinColumn(name="uc_id", referencedColumnName="id")
     */
    protected $uc;
    /**
     * @ORM\ManyToOne(targetEntity="PeriodoPnf", inversedBy="mallas")
     * @ORM\JoinColumn(name="pp_id", referencedColumnName="id")
     */
    protected $periodopnf;
    
    /**
     * @var string
     *
     * @ORM\Column(name="es_electiva", type="string", length=2)
     */
    
    private $eselectiva;
    
    /**
     * @var string
     *
     * @ORM\Column(name="es_taller", type="string", length=2)
     */
    
    private $estaller;

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
     * Set eselectiva
     *
     * @param string $eselectiva
     * @return Malla
     */
    public function setEselectiva($eselectiva)
    {
        $this->eselectiva = $eselectiva;

        return $this;
    }

    /**
     * Get eselectiva
     *
     * @return string 
     */
    public function getEselectiva()
    {
        return $this->eselectiva;
    }

    /**
     * Set estaller
     *
     * @param string $estaller
     * @return Malla
     */
    public function setEstaller($estaller)
    {
        $this->estaller = $estaller;

        return $this;
    }

    /**
     * Get estaller
     *
     * @return string 
     */
    public function getEstaller()
    {
        return $this->estaller;
    }

    /**
     * Set uc
     *
     * @param \MisionSucre\RipesBundle\Entity\UnidadCurricular $uc
     * @return Malla
     */
    public function setUc(\MisionSucre\RipesBundle\Entity\UnidadCurricular $uc = null)
    {
        $this->uc = $uc;

        return $this;
    }

    /**
     * Get uc
     *
     * @return \MisionSucre\RipesBundle\Entity\UnidadCurricular 
     */
    public function getUc()
    {
        return $this->uc;
    }

    /**
     * Set periodopnf
     *
     * @param \MisionSucre\RipesBundle\Entity\PeriodoPnf $periodopnf
     * @return Malla
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
}
