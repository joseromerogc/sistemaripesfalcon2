<?php

namespace MisionSucre\RipesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Persona
 *
 * @ORM\Table(name="estructura")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="MisionSucre\RipesBundle\Entity\EstructuraRepository")
 */
class Estructura
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
     * @ORM\ManyToOne(targetEntity="Aldea", inversedBy="estructuras")
     * @ORM\JoinColumn(name="aldea_id", referencedColumnName="id")
     */
    protected $aldea;

        
    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=70)
     */
    
    private $nombre;
    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=70)
     */
    
    private $tipo;

    /**
     * Get id
     *
     * @return integer 
     */
    
    /**
     * @ORM\OneToMany(targetEntity="MiembroEstructura", mappedBy="estructura")
     */
    protected $miembros;
    /**
     * @ORM\OneToMany(targetEntity="EstructuraActividad", mappedBy="estructura")
     */
    protected $actividades;
    
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Estructura
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
     * Set aldea
     *
     * @param \MisionSucre\RipesBundle\Entity\Aldea $aldea
     * @return Estructura
     */
    public function setAldea(\MisionSucre\RipesBundle\Entity\Aldea $aldea = null)
    {
        $this->aldea = $aldea;

        return $this;
    }

    /**
     * Get aldea
     *
     * @return \MisionSucre\RipesBundle\Entity\Aldea 
     */
    public function getAldea()
    {
        return $this->aldea;
    }

    /**
     * Set tipo
     *
     * @param string $tipo
     * @return Estructura
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string 
     */
    public function getTipo()
    {
        return $this->tipo;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->miembros = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add miembros
     *
     * @param \MisionSucre\RipesBundle\Entity\MiembroEstructura $miembros
     * @return Estructura
     */
    public function addMiembro(\MisionSucre\RipesBundle\Entity\MiembroEstructura $miembros)
    {
        $this->miembros[] = $miembros;

        return $this;
    }

    /**
     * Remove miembros
     *
     * @param \MisionSucre\RipesBundle\Entity\MiembroEstructura $miembros
     */
    public function removeMiembro(\MisionSucre\RipesBundle\Entity\MiembroEstructura $miembros)
    {
        $this->miembros->removeElement($miembros);
    }

    /**
     * Get miembros
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMiembros()
    {
        return $this->miembros;
    }
}
