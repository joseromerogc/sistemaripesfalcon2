<?php

namespace MisionSucre\RipesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Universidad
 *
 * @ORM\Table(name="universidad")
 * @ORM\Entity(repositoryClass="MisionSucre\RipesBundle\Entity\UniversidadRepository")
 */
class Universidad
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
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=70, unique=true)
     */
    
    private $nombre;
    
    /**
     * @var string
     *
     * @ORM\Column(name="direccion", type="string", length=70, nullable=true )
     */
    
    private $direccion;
    
    /**
     * @ORM\OneToMany(targetEntity="Pnf", mappedBy="universidad")
     */
    protected $pnfs;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pnfs = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Universidad
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
     * @return Universidad
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
     * Add pnfs
     *
     * @param \MisionSucre\RipesBundle\Entity\Pnf $pnfs
     * @return Universidad
     */
    public function addPnf(\MisionSucre\RipesBundle\Entity\Pnf $pnfs)
    {
        $this->pnfs[] = $pnfs;

        return $this;
    }

    /**
     * Remove pnfs
     *
     * @param \MisionSucre\RipesBundle\Entity\Pnf $pnfs
     */
    public function removePnf(\MisionSucre\RipesBundle\Entity\Pnf $pnfs)
    {
        $this->pnfs->removeElement($pnfs);
    }

    /**
     * Get pnfs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPnfs()
    {
        return $this->pnfs;
    }
    
            public function __toString()
{
    return $this->nombre;
}
}
