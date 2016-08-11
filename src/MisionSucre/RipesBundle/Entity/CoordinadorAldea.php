<?php

namespace MisionSucre\RipesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="coordinador_aldea")
 * @ORM\Entity(repositoryClass="MisionSucre\RipesBundle\Entity\CoordinadoAldeaRepository")
 */
class CoordinadorAldea
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\OneToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     **/
    private $user;
    
    /**
     * @ORM\ManyToOne(targetEntity="Aldea", inversedBy="coordinadoresaldeas")
     * @ORM\JoinColumn(name="aldea_id", referencedColumnName="id")
     */
    protected $aldea;
    
     /**
     * @ORM\OneToMany(targetEntity="CoordinadorTurno", mappedBy="coordinadores")
     */
    protected $turnos;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->turnos = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set user
     *
     * @param \MisionSucre\RipesBundle\Entity\User $user
     * @return CoordinadorAldea
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
     * Set aldea
     *
     * @param \MisionSucre\RipesBundle\Entity\Aldea $aldea
     * @return CoordinadorAldea
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
     * Add turnos
     *
     * @param \MisionSucre\RipesBundle\Entity\CoordinadorTurno $turnos
     * @return CoordinadorAldea
     */
    public function addTurno(\MisionSucre\RipesBundle\Entity\CoordinadorTurno $turnos)
    {
        $this->turnos[] = $turnos;

        return $this;
    }

    /**
     * Remove turnos
     *
     * @param \MisionSucre\RipesBundle\Entity\CoordinadorTurno $turnos
     */
    public function removeTurno(\MisionSucre\RipesBundle\Entity\CoordinadorTurno $turnos)
    {
        $this->turnos->removeElement($turnos);
    }

    /**
     * Get turnos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTurnos()
    {
        return $this->turnos;
    }
}
