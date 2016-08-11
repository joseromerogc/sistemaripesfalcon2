<?php

namespace MisionSucre\RipesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Parroquia
 *
 * @ORM\Table(name="mision")
 * @ORM\Entity
 */
class Mision
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\OneToMany(targetEntity="ParticipacionComunitaria", mappedBy="mision")
     */
    protected $personas;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $nombre;
    
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
     * @return Mision
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
     * Add personas
     *
     * @param \MisionSucre\RipesBundle\Entity\ParticipacionComunitaria $personas
     * @return Mision
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
}
