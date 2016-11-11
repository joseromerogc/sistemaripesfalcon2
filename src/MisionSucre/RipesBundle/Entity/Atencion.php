<?php

namespace MisionSucre\RipesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Academico
 *
 * @ORM\Table(name="atencion")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="MisionSucre\RipesBundle\Entity\atencionRepository")
 */
class Atencion
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
     * @ORM\ManyToOne(targetEntity="User", inversedBy="atenciones")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;
    
    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=100)
     */
    
    private $tipo;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50)
     */
    
    private $estatus;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="date")
     */
    private $fecha;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100)
     */
    
    private $observacion;
   
    

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
     * Set tipo
     *
     * @param string $tipo
     * @return Atencion
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string 
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set estatus
     *
     * @param string $estatus
     * @return Atencion
     */
    public function setEstatus($estatus)
    {
        $this->estatus = $estatus;

        return $this;
    }

    /**
     * Get estatus
     *
     * @return string 
     */
    public function getEstatus()
    {
        return $this->estatus;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Atencion
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
     * Set observacion
     *
     * @param string $observacion
     * @return Atencion
     */
    public function setObservacion($observacion)
    {
        $this->observacion = $observacion;

        return $this;
    }

    /**
     * Get observacion
     *
     * @return string 
     */
    public function getObservacion()
    {
        return $this->observacion;
    }

    /**
     * Set user
     *
     * @param \MisionSucre\RipesBundle\Entity\User $user
     * @return Atencion
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
