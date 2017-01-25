<?php

namespace MisionSucre\RipesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Nota
 *
 * @ORM\Table(name="impresion_acta_nota")
 * @ORM\Entity(repositoryClass="MisionSucre\RipesBundle\Entity\ImpresionActaNotaRepository")
 */
class ImpresionActaNota
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
     * @ORM\ManyToOne(targetEntity="ActaNota", inversedBy="impresiones")
     * @ORM\JoinColumn(name="actanota_id", referencedColumnName="id")
     */
    protected $actanota;
    
    /**
     * @var string
     *
     * @ORM\Column(name="codigo", type="string",length=25)
     */
    private $codigo;
    /**
     * @var string
     *
     * @ORM\Column(name="url_local", type="string",length=45,nullable=true)
     */
    private $urlLocal;
    /**
     * @var string
     *
     * @ORM\Column(name="url_nube", type="string",length=45,nullable=true)
     */
    private $urlNube;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_creacion", type="date", nullable=false)
     */
    private $fechaCreacion;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_modificacion", type="date", nullable=true)
     */
    private $fechaModificacion;


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
     * Set codigo
     *
     * @param string $codigo
     * @return ImpresionActaNota
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
     * Set urlLocal
     *
     * @param string $urlLocal
     * @return ImpresionActaNota
     */
    public function setUrlLocal($urlLocal)
    {
        $this->urlLocal = $urlLocal;

        return $this;
    }

    /**
     * Get urlLocal
     *
     * @return string 
     */
    public function getUrlLocal()
    {
        return $this->urlLocal;
    }

    /**
     * Set urlNube
     *
     * @param string $urlNube
     * @return ImpresionActaNota
     */
    public function setUrlNube($urlNube)
    {
        $this->urlNube = $urlNube;

        return $this;
    }

    /**
     * Get urlNube
     *
     * @return string 
     */
    public function getUrlNube()
    {
        return $this->urlNube;
    }

    /**
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     * @return ImpresionActaNota
     */
    public function setFechaCreacion($fechaCreacion)
    {
        $this->fechaCreacion = $fechaCreacion;

        return $this;
    }

    /**
     * Get fechaCreacion
     *
     * @return \DateTime 
     */
    public function getFechaCreacion()
    {
        return $this->fechaCreacion;
    }

    /**
     * Set fechaModificacion
     *
     * @param \DateTime $fechaModificacion
     * @return ImpresionActaNota
     */
    public function setFechaModificacion($fechaModificacion)
    {
        $this->fechaModificacion = $fechaModificacion;

        return $this;
    }

    /**
     * Get fechaModificacion
     *
     * @return \DateTime 
     */
    public function getFechaModificacion()
    {
        return $this->fechaModificacion;
    }

    /**
     * Set actanota
     *
     * @param \MisionSucre\RipesBundle\Entity\ActaNota $actanota
     * @return ImpresionActaNota
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
}
