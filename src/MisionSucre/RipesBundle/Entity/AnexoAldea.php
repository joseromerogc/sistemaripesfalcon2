<?php

namespace MisionSucre\RipesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AnexoAldea
 *
 * @ORM\Table(name="anexo_aldea")
 * @ORM\Entity(repositoryClass="MisionSucre\RipesBundle\Entity\AnexoAldeaRepository")
 */
class AnexoAldea
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
     * @ORM\ManyToOne(targetEntity="Aldea", inversedBy="anexos")
     * @ORM\JoinColumn(name="aldea_id", referencedColumnName="id")
     */
    protected $aldea;
    
    /**
     * @ORM\ManyToOne(targetEntity="Sector", inversedBy="anexos")
     * @ORM\JoinColumn(name="sector_id", referencedColumnName="id")
     */
    protected $sector;
    
    /**
     * @ORM\OneToMany(targetEntity="Ambiente", mappedBy="anexo")
     */
    protected $ambientes;
        
    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=70)
     */
    
    private $nombre;
    
    /**
     * @var string
     *
     * @ORM\Column(name="direccion", type="string", length=80)
     */
    
    private $direccion;

        /**
     * Constructor
     */
    public function __construct()
    {
        $this->ambientes = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return AnexoAldea
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
     * @return AnexoAldea
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
     * Set aldea
     *
     * @param \MisionSucre\RipesBundle\Entity\Aldea $aldea
     * @return AnexoAldea
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
     * Set sector
     *
     * @param \MisionSucre\RipesBundle\Entity\Sector $sector
     * @return AnexoAldea
     */
    public function setSector(\MisionSucre\RipesBundle\Entity\Sector $sector = null)
    {
        $this->sector = $sector;

        return $this;
    }

    /**
     * Get sector
     *
     * @return \MisionSucre\RipesBundle\Entity\Sector 
     */
    public function getSector()
    {
        return $this->sector;
    }

    /**
     * Add ambientes
     *
     * @param \MisionSucre\RipesBundle\Entity\Ambiente $ambientes
     * @return AnexoAldea
     */
    public function addAmbiente(\MisionSucre\RipesBundle\Entity\Ambiente $ambientes)
    {
        $this->ambientes[] = $ambientes;

        return $this;
    }

    /**
     * Remove ambientes
     *
     * @param \MisionSucre\RipesBundle\Entity\Ambiente $ambientes
     */
    public function removeAmbiente(\MisionSucre\RipesBundle\Entity\Ambiente $ambientes)
    {
        $this->ambientes->removeElement($ambientes);
    }

    /**
     * Get ambientes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAmbientes()
    {
        return $this->ambientes;
    }
}
