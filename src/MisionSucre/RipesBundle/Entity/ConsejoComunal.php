<?php

namespace MisionSucre\RipesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Parroquia
 *
 * @ORM\Table(name="consejo_comunal")
 * @ORM\Entity(repositoryClass="MisionSucre\RipesBundle\Entity\ConsejoComunalRepository")
 */
class ConsejoComunal
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\OneToMany(targetEntity="ParticipacionComunitaria", mappedBy="cc")
     */
    protected $personas;

    /**
     * @ORM\Column(type="string", length=70)
     */
    private $nombre;
    
    /**
     * @ORM\Column(type="string", length=60,nullable=true)
     */
    private $direccion;
    
    /**
     * @ORM\ManyToOne(targetEntity="Parroquia", inversedBy="consejoscomunales")
     * @ORM\JoinColumn(name="parroquia_id", referencedColumnName="id")
     */
    private $parroquia;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->personas = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return ConsejoComunal
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
     * @return ConsejoComunal
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
     * @param \MisionSucre\RipesBundle\Entity\ParticipacionComunitaria $personas
     * @return ConsejoComunal
     */
    public function addPersona(\MisionSucre\RipesBundle\Entity\ParticipacionComunitaria $personas)
    {
        $this->personas[] = $personas;

        return $this;
    }

    /**
     * Remove personas
     *
     * @param \MisionSucre\RipesBundle\Entity\ParticipacionComunitaria $personas
     */
    public function removePersona(\MisionSucre\RipesBundle\Entity\ParticipacionComunitaria $personas)
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set parroquia
     *
     * @param \MisionSucre\RipesBundle\Entity\Parroquia $parroquia
     * @return ConsejoComunal
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
}
