<?php

namespace MisionSucre\RipesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MigracionAldea
 *
 * @ORM\Table(name="migracion_aldea")
 * @ORM\Entity(repositoryClass="MisionSucre\RipesBundle\Entity\MigracionAldeaRepository")
 */
class MigracionAldea
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
     * @ORM\OneToMany(targetEntity="Triunfador", mappedBy="migraciones")
     */
    protected $triunfadores;
    
     /**
     * @ORM\ManyToOne(targetEntity="Aldea", inversedBy="ambientes")
     * @ORM\JoinColumn(name="aldea_id", referencedColumnName="id")
     */
    protected $aldea;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->triunfadores = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add triunfadores
     *
     * @param \MisionSucre\RipesBundle\Entity\Triunfador $triunfadores
     * @return MigracionAldea
     */
    public function addTriunfadore(\MisionSucre\RipesBundle\Entity\Triunfador $triunfadores)
    {
        $this->triunfadores[] = $triunfadores;

        return $this;
    }

    /**
     * Remove triunfadores
     *
     * @param \MisionSucre\RipesBundle\Entity\Triunfador $triunfadores
     */
    public function removeTriunfadore(\MisionSucre\RipesBundle\Entity\Triunfador $triunfadores)
    {
        $this->triunfadores->removeElement($triunfadores);
    }

    /**
     * Get triunfadores
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTriunfadores()
    {
        return $this->triunfadores;
    }

    /**
     * Set aldea
     *
     * @param \MisionSucre\RipesBundle\Entity\Aldea $aldea
     * @return MigracionAldea
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
}
