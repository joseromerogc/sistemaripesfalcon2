<?php

namespace MisionSucre\RipesBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="enfermedad")
 * @ORM\Entity()
 * @ORM\Entity(repositoryClass="MisionSucre\RipesBundle\Entity\EnfermedadRepository")
 */
class Enfermedad
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=55, unique=true)
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=55)
     */
    private $tipo_enf;

     /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="enfermedades")
     */
    private $users;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Enfermedad
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
     * Set tipo_enf
     *
     * @param string $tipoEnf
     * @return Enfermedad
     */
    public function setTipoEnf($tipoEnf)
    {
        $this->tipo_enf = $tipoEnf;

        return $this;
    }

    /**
     * Get tipo_enf
     *
     * @return string 
     */
    public function getTipoEnf()
    {
        return $this->tipo_enf;
    }

    /**
     * Add users
     *
     * @param \MisionSucre\RipesBundle\Entity\User $users
     * @return Enfermedad
     */
    public function addUser(\MisionSucre\RipesBundle\Entity\User $users)
    {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove users
     *
     * @param \MisionSucre\RipesBundle\Entity\User $users
     */
    public function removeUser(\MisionSucre\RipesBundle\Entity\User $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsers()
    {
        return $this->users;
    }
}
