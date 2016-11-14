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
     * @ORM\Column(name="area", type="string", length=70,unique=true)
     */
    
    private $area;
    
    
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
}
