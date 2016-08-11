<?php

namespace MisionSucre\RipesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ambiente
 *
 * @ORM\Table(name="miembro_estructura")
 * @ORM\Entity(repositoryClass="MisionSucre\RipesBundle\Entity\MiembroEstructuraRepository")
 */
class MiembroEstructura
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
     * @ORM\ManyToOne(targetEntity="Estructura", inversedBy="miembros")
     * @ORM\JoinColumn(name="estructura_id", referencedColumnName="id")
     */
    protected $estructura;
    
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="miembros")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;
        
    /**
     * @var string
     *
     * @ORM\Column(name="condicion", type="string", length=40)
     */
    
    private $cargo;
    
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
     * Set cargo
     *
     * @param string $cargo
     * @return MiembroEstructura
     */
    public function setCargo($cargo)
    {
        $this->cargo = $cargo;

        return $this;
    }

    /**
     * Get cargo
     *
     * @return string 
     */
    public function getCargo()
    {
        return $this->cargo;
    }

    /**
     * Set estructura
     *
     * @param \MisionSucre\RipesBundle\Entity\Estructura $estructura
     * @return MiembroEstructura
     */
    public function setEstructura(\MisionSucre\RipesBundle\Entity\Estructura $estructura = null)
    {
        $this->estructura = $estructura;

        return $this;
    }

    /**
     * Get estructura
     *
     * @return \MisionSucre\RipesBundle\Entity\Estructura 
     */
    public function getEstructura()
    {
        return $this->estructura;
    }

    /**
     * Set user
     *
     * @param \MisionSucre\RipesBundle\Entity\User $user
     * @return MiembroEstructura
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
