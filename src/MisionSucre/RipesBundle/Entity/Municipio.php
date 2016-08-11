<?php

namespace MisionSucre\RipesBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\Mapping as ORM;

/**
 * Municipio
 *
 * @ORM\Table(name="municipio")
 * @ORM\Entity
 */
class Municipio
{
    
    /**
     * @var string
     *
     * @ORM\Column(name="municipio", type="string", length=100, nullable=false)
     */
    private $municipio;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    
     /**
     * @ORM\OneToMany(targetEntity="Parroquia", mappedBy="municipio")
     */
    protected $parroquias;
    
    public function __construct()
    {
        $this->parroquias = new ArrayCollection();
    }


    /**
     * Set municipio
     *
     * @param string $municipio
     * @return Municipio
     */
    public function setMunicipio($municipio)
    {
        $this->municipio = $municipio;

        return $this;
    }

    /**
     * Get municipio
     *
     * @return string 
     */
    public function getMunicipio()
    {
        return $this->municipio;
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
     * Add parroquias
     *
     * @param \MisionSucre\RipesBundle\Entity\Parroquia $parroquias
     * @return Municipio
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
