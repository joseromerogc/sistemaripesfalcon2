<?php

namespace MisionSucre\RipesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ambiente
 *
 * @ORM\Table(name="unidad_curricular")
 * @ORM\Entity(repositoryClass="MisionSucre\RipesBundle\Entity\UnidadCurricularRepository")
 */
class UnidadCurricular
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
     * @ORM\ManyToOne(targetEntity="Pnf", inversedBy="ucs")
     * @ORM\JoinColumn(name="pnf_id", referencedColumnName="id")
     */
    protected $pnf;
    
    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=70)
     */
    
    private $nombre;
    
    /**
     * @var string
     *
     * @ORM\Column(name="codigo", type="string", length=12,unique=true)
     */
    
    private $codigo;
    
    /**
     * @var string
     *
     * @ORM\Column(name="area", type="string", length=70)
     */
    
    private $area;
    
    /**
     * @ORM\OneToMany(targetEntity="Malla", mappedBy="uc")
     */
    protected $mallas;
    
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
     * @return UnidadCurricular
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
     * Set codigo
     *
     * @param string $codigo
     * @return UnidadCurricular
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get codigo
     *
     * @return string 
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set area
     *
     * @param string $area
     * @return UnidadCurricular
     */
    public function setArea($area)
    {
        $this->area = $area;

        return $this;
    }

    /**
     * Get area
     *
     * @return string 
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * Set pnf
     *
     * @param \MisionSucre\RipesBundle\Entity\Pnf $pnf
     * @return UnidadCurricular
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
     * @return UnidadCurricular
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
}
