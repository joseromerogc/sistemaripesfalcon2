<?php

namespace MisionSucre\RipesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Academico
 *
 * @ORM\Table(name="registro_usuario")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="MisionSucre\RipesBundle\Entity\RegistrousuarioRepository")
 */
class RegistroUsuario
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
     * @ORM\ManyToOne(targetEntity="User", inversedBy="registradores")
     * @ORM\JoinColumn(name="registrador_id", referencedColumnName="id")
     */
    protected $registrador;
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="registrados")
     * @ORM\JoinColumn(name="registrado_id", referencedColumnName="id")
     */
    protected $registrado;
    
     /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="date", nullable=false)
     */
    private $fecha;
    

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
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return RegistroUsuario
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime 
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set registrador
     *
     * @param \MisionSucre\RipesBundle\Entity\User $registrador
     * @return RegistroUsuario
     */
    public function setRegistrador(\MisionSucre\RipesBundle\Entity\User $registrador = null)
    {
        $this->registrador = $registrador;

        return $this;
    }

    /**
     * Get registrador
     *
     * @return \MisionSucre\RipesBundle\Entity\User 
     */
    public function getRegistrador()
    {
        return $this->registrador;
    }

    /**
     * Set registrado
     *
     * @param \MisionSucre\RipesBundle\Entity\User $registrado
     * @return RegistroUsuario
     */
    public function setRegistrado(\MisionSucre\RipesBundle\Entity\User $registrado = null)
    {
        $this->registrado = $registrado;

        return $this;
    }

    /**
     * Get registrado
     *
     * @return \MisionSucre\RipesBundle\Entity\User 
     */
    public function getRegistrado()
    {
        return $this->registrado;
    }
    public function __construct()
    {
        $this->fecha = new \DateTime();
    }
}
