<?php

namespace MisionSucre\RipesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pnf
 *
 * @ORM\Table(name="pnf")
 * @ORM\Entity(repositoryClass="MisionSucre\RipesBundle\Entity\PnfRepository")
 */
class Pnf
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
     * @ORM\ManyToOne(targetEntity="Universidad", inversedBy="pnfs")
     * @ORM\JoinColumn(name="universidad_id", referencedColumnName="id")
     */
    protected $universidad;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=70, unique=true)
     */
    
    private $nombre;
    
    /**
     * @var string
     *
     * @ORM\Column(name="modalidad", type="string", length=20)
     */
    
    private $modalidad;
    
    /**
     * @ORM\OneToMany(targetEntity="Ambiente", mappedBy="pnf")
     */
    protected $ambientes;
    
    /**
     * @ORM\OneToMany(targetEntity="Ambiente", mappedBy="pnf")
     */
    protected $ucs;
    
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
     * @return Pnf
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
     * Set modalidad
     *
     * @param string $modalidad
     * @return Pnf
     */
    public function setModalidad($modalidad)
    {
        $this->modalidad = $modalidad;

        return $this;
    }

    /**
     * Get modalidad
     *
     * @return string 
     */
    public function getModalidad()
    {
        return $this->modalidad;
    }

    /**
     * Set universidad
     *
     * @param \MisionSucre\RipesBundle\Entity\Universidad $universidad
     * @return Pnf
     */
    public function setUniversidad(\MisionSucre\RipesBundle\Entity\Universidad $universidad = null)
    {
        $this->universidad = $universidad;

        return $this;
    }

    /**
     * Get universidad
     *
     * @return \MisionSucre\RipesBundle\Entity\Universidad 
     */
    public function getUniversidad()
    {
        return $this->universidad;
    }

    /**
     * Add ambientes
     *
     * @param \MisionSucre\RipesBundle\Entity\Ambiente $ambientes
     * @return Pnf
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

    /**
     * Add ucs
     *
     * @param \MisionSucre\RipesBundle\Entity\Ambiente $ucs
     * @return Pnf
     */
    public function addUc(\MisionSucre\RipesBundle\Entity\Ambiente $ucs)
    {
        $this->ucs[] = $ucs;

        return $this;
    }

    /**
     * Remove ucs
     *
     * @param \MisionSucre\RipesBundle\Entity\Ambiente $ucs
     */
    public function removeUc(\MisionSucre\RipesBundle\Entity\Ambiente $ucs)
    {
        $this->ucs->removeElement($ucs);
    }

    /**
     * Get ucs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUcs()
    {
        return $this->ucs;
    }
}
