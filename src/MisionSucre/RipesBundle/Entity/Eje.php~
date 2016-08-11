<?php

namespace MisionSucre\RipesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="eje")
 * @ORM\Entity()
 * @ORM\Entity(repositoryClass="MisionSucre\RipesBundle\Entity\EjeRepository")
 */
class Eje
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=40, unique=true)
     */
    private $nombre;
    
    /**
     * @ORM\OneToMany(targetEntity="Parroquia", mappedBy="eje")
     */
    protected $parroquias;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->parroquias = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Eje
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
     * Add parroquias
     *
     * @param \MisionSucre\RipesBundle\Entity\Parroquia $parroquias
     * @return Eje
     */
    public function addParroquia(\MisionSucre\RipesBundle\Entity\Parroquia $parroquias)
    {
        $this->parroquias[] = $parroquias;

        return $this;
    }

    /**
     * Remove parroquias
     *
     * @param \MisionSucre\RipesBundle\Entity\Parroquia $parroquias
     */
    public function removeParroquia(\MisionSucre\RipesBundle\Entity\Parroquia $parroquias)
    {
        $this->parroquias->removeElement($parroquias);
    }

    /**
     * Get parroquias
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getParroquias()
    {
        return $this->parroquias;
    }
}
