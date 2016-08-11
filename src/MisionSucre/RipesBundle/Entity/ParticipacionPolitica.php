<?php

namespace MisionSucre\RipesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="participacion_politica")
 * @ORM\Entity()
 * @ORM\Entity(repositoryClass="MisionSucre\RipesBundle\Entity\ParticipacionPoliticaRepository")
 */
class ParticipacionPolitica
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
    private $afiliacion;
    /**
     * @ORM\Column(type="string", length=40,nullable=true)
     */
    private $cargo;
    
    /**
     * @ORM\Column(type="integer", length=40,nullable=true)
     */
    private $mesa;
    
    /**
     * @ORM\ManyToOne(targetEntity="Ubch", inversedBy="personas")
     * @ORM\JoinColumn(name="ubch_id", referencedColumnName="id")
     */
    private $ubch;
    
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
     * Set afiliacion
     *
     * @param string $afiliacion
     * @return ParticipacionPolitica
     */
    public function setAfiliacion($afiliacion)
    {
        $this->afiliacion = $afiliacion;

        return $this;
    }

    /**
     * Get afiliacion
     *
     * @return string 
     */
    public function getAfiliacion()
    {
        return $this->afiliacion;
    }

    /**
     * Set cargo
     *
     * @param string $cargo
     * @return ParticipacionPolitica
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
     * Set mesa
     *
     * @param integer $mesa
     * @return ParticipacionPolitica
     */
    public function setMesa($mesa)
    {
        $this->mesa = $mesa;

        return $this;
    }

    /**
     * Get mesa
     *
     * @return integer 
     */
    public function getMesa()
    {
        return $this->mesa;
    }

    /**
     * Set ubch
     *
     * @param \MisionSucre\RipesBundle\Entity\Ubch $ubch
     * @return ParticipacionPolitica
     */
    public function setUbch(\MisionSucre\RipesBundle\Entity\Ubch $ubch = null)
    {
        $this->ubch = $ubch;

        return $this;
    }

    /**
     * Get ubch
     *
     * @return \MisionSucre\RipesBundle\Entity\Ubch 
     */
    public function getUbch()
    {
        return $this->ubch;
    }

    /**
     * Set user
     *
     * @param \MisionSucre\RipesBundle\Entity\User $user
     * @return ParticipacionPolitica
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
