<?php

namespace MisionSucre\RipesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="trabajo")
 * @ORM\Entity()
 * @ORM\Entity(repositoryClass="MisionSucre\RipesBundle\Entity\TrabajoRepository")
 */
class Trabajo
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=40)
     */
    private $cargo;
    /**
     * @ORM\Column(type="string", length=40)
     */
    private $institucion;
    /**
     * @ORM\Column(type="string", length=10)
     */
    
    private $antiguedad;
    /**
     * @ORM\Column(type="string", length=2)
     */
   
    private $jubilacion;
    /**
     * @ORM\Column(type="string", length=20)
     */
    
    private $condicionlaboral;
    /**
     * @ORM\Column(type="string", length=40)
     */
    
    private $turno;
    
    /**
     * @ORM\OneToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     **/
    private $user;
    

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
     * @return Trabajo
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
     * Set institucion
     *
     * @param string $institucion
     * @return Trabajo
     */
    public function setInstitucion($institucion)
    {
        $this->institucion = $institucion;

        return $this;
    }

    /**
     * Get institucion
     *
     * @return string 
     */
    public function getInstitucion()
    {
        return $this->institucion;
    }

    /**
     * Set turno
     *
     * @param string $turno
     * @return Trabajo
     */
    public function setTurno($turno)
    {
        $this->turno = $turno;

        return $this;
    }

    /**
     * Get turno
     *
     * @return string 
     */
    public function getTurno()
    {
        return $this->turno;
    }

    /**
     * Set user
     *
     * @param \MisionSucre\RipesBundle\Entity\User $user
     * @return Trabajo
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

    /**
     * Set antiguedad
     *
     * @param string $antiguedad
     * @return Trabajo
     */
    public function setAntiguedad($antiguedad)
    {
        $this->antiguedad = $antiguedad;

        return $this;
    }

    /**
     * Get antiguedad
     *
     * @return string 
     */
    public function getAntiguedad()
    {
        return $this->antiguedad;
    }

    /**
     * Set jubilacion
     *
     * @param string $jubilacion
     * @return Trabajo
     */
    public function setJubilacion($jubilacion)
    {
        $this->jubilacion = $jubilacion;

        return $this;
    }

    /**
     * Get jubilacion
     *
     * @return string 
     */
    public function getJubilacion()
    {
        return $this->jubilacion;
    }

    /**
     * Set condicionlaboral
     *
     * @param string $condicionlaboral
     * @return Trabajo
     */
    public function setCondicionlaboral($condicionlaboral)
    {
        $this->condicionlaboral = $condicionlaboral;

        return $this;
    }

    /**
     * Get condicionlaboral
     *
     * @return string 
     */
    public function getCondicionlaboral()
    {
        return $this->condicionlaboral;
    }
}
