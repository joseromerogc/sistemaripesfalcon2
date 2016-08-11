<?php

namespace MisionSucre\RipesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Persona
 *
 * @ORM\Table(name="ubicacion_vivienda")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="MisionSucre\RipesBundle\Entity\UbicacionViviendaRepository")
 */
class UbicacionVivienda
{
    /**
     * @ORM\ManyToOne(targetEntity="Sector", inversedBy="ubicaciones")
     * @ORM\JoinColumn(name="sector_id", referencedColumnName="id")
     */
    private $sector;
    
    /**
     * @var string
     *
     * @ORM\Column(name="dirreccion", type="string", length=80, nullable=true)
     */
    private $direccion;

    /**
     * @ORM\OneToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     **/
    private $user;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * Set direccion
     *
     * @param string $direccion
     * @return UbicacionVivienda
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set sector
     *
     * @param \MisionSucre\RipesBundle\Entity\Sector $sector
     * @return UbicacionVivienda
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
     * Set user
     *
     * @param \MisionSucre\RipesBundle\Entity\User $user
     * @return UbicacionVivienda
     */
    public function setUser(\MisionSucre\RipesBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \MisionSucre\RipesBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}
