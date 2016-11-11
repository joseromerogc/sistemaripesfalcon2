<?php

namespace MisionSucre\RipesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Parroquia
 *
 * @ORM\Table(name="ubch")
 * @ORM\Entity(repositoryClass="MisionSucre\RipesBundle\Entity\UbchRepository")
 */
class Ubch
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\OneToMany(targetEntity="ParticipacionPolitica", mappedBy="ubch")
     */
    protected $personas;

    /**
     * @ORM\Column(type="string", length=80)
     */
    private $nombre;
    
    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     */
    private $direccion;
    
    /**
     * @ORM\ManyToOne(targetEntity="Parroquia", inversedBy="ubchs")
     * @ORM\JoinColumn(name="parroquia_id", referencedColumnName="id")
     */
    private $parroquia;
    
     /**
     * @ORM\Column(type="string", length=20, nullable=true,unique=true)
     */
    private $codigo;
    
        /**
     * Constructor
     */
    public function __construct()
    {
        $this->personas = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Ubch
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
     * Set direccion
     *
     * @param string $direccion
     * @return Ubch
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return string 
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Add personas
     *
     * @param \MisionSucre\RipesBundle\Entity\ParticipacionPolitica $personas
     * @return Ubch
     */
    public function addPersona(\MisionSucre\RipesBundle\Entity\ParticipacionPolitica $personas)
    {
        $this->personas[] = $personas;

        return $this;
    }

    /**
     * Remove personas
     *
     * @param \MisionSucre\RipesBundle\Entity\ParticipacionPolitica $personas
     */
    public function removePersona(\MisionSucre\RipesBundle\Entity\ParticipacionPolitica $personas)
    {
        $this->personas->removeElement($personas);
    }

    /**
     * Get personas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPersonas()
    {
        return $this->personas;
    }

    /**
     * Set parroquia
     *
     * @param \MisionSucre\RipesBundle\Entity\Parroquia $parroquia
     * @return Ubch
     */
    public function setParroquia(\MisionSucre\RipesBundle\Entity\Parroquia $parroquia = null)
    {
        $this->parroquia = $parroquia;

        return $this;
    }

    /**
     * Get parroquia
     *
     * @return \MisionSucre\RipesBundle\Entity\Parroquia 
     */
    public function getParroquia()
    {
        return $this->parroquia;
    }

    /**
     * Set codigo
     *
     * @param string $codigo
     * @return Ubch
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
}
