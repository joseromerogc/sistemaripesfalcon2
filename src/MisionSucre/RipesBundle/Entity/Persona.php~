<?php

namespace MisionSucre\RipesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Persona
 *
 * @ORM\Table(name="persona")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="MisionSucre\RipesBundle\Entity\PersonaRepository")
 */
class Persona
{
    /**
     * @var string
     *
     * @ORM\Column(name="pri_nom", type="string", length=30, nullable=false)
     */
    private $priNom;

    /**
     * @var string
     *
     * @ORM\Column(name="seg_nom", type="string", length=30, nullable=true)
     */
    private $segNom;

    /**
     * @var string
     *
     * @ORM\Column(name="pri_ape", type="string", length=30, nullable=false)
     */
    private $priApe;

    /**
     * @var string
     *
     * @ORM\Column(name="seg_ape", type="string", length=30, nullable=true)
     */
    private $segApe;

    /**
     * @var boolean
     *
     * @ORM\Column(name="cod_ced", type="boolean", nullable=false)
     */
    private $codCed;

    /**
     * @var integer
     *
     * @ORM\Column(name="ced_per", type="bigint", nullable=false)
     */
    private $cedPer;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fech_per", type="date", nullable=false)
     */
    private $fechPer;

    /**
     * @var string
     *
     * @ORM\Column(name="edo_civ", type="string", length=12, nullable=true)
     */
    private $edoCiv;

    /**
     * @var string
     *
     * @ORM\Column(name="tel_per", type="string", length=15, nullable=true)
     */
    private $telPer;

    /**
     * @var string
     *
     * @ORM\Column(name="cel_per", type="string", length=15, nullable=true)
     */
    private $celPer;

    /**
     * @var string
     *
     * @ORM\Column(name="sex_per", type="string", length=2, nullable=false)
     */
    private $sexPer;
    
    /**
     * @var string
     *
     * @ORM\Column(name="edad_per", type="integer", nullable=true)
     */
    private $edadPer;
    

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
     * Set priNom
     *
     * @param string $priNom
     * @return Persona
     */
    public function setPriNom($priNom)
    {
        $this->priNom = $priNom;

        return $this;
    }

    /**
     * Get priNom
     *
     * @return string 
     */
    public function getPriNom()
    {
        return $this->priNom;
    }

    /**
     * Set segNom
     *
     * @param string $segNom
     * @return Persona
     */
    public function setSegNom($segNom)
    {
        $this->segNom = $segNom;

        return $this;
    }

    /**
     * Get segNom
     *
     * @return string 
     */
    public function getSegNom()
    {
        return $this->segNom;
    }

    /**
     * Set priApe
     *
     * @param string $priApe
     * @return Persona
     */
    public function setPriApe($priApe)
    {
        $this->priApe = $priApe;

        return $this;
    }

    /**
     * Get priApe
     *
     * @return string 
     */
    public function getPriApe()
    {
        return $this->priApe;
    }

    /**
     * Set segApe
     *
     * @param string $segApe
     * @return Persona
     */
    public function setSegApe($segApe)
    {
        $this->segApe = $segApe;

        return $this;
    }

    /**
     * Get segApe
     *
     * @return string 
     */
    public function getSegApe()
    {
        return $this->segApe;
    }

    /**
     * Set codCed
     *
     * @param boolean $codCed
     * @return Persona
     */
    public function setCodCed($codCed)
    {
        $this->codCed = $codCed;

        return $this;
    }

    /**
     * Get codCed
     *
     * @return boolean 
     */
    public function getCodCed()
    {
        return $this->codCed;
    }

    /**
     * Set cedPer
     *
     * @param integer $cedPer
     * @return Persona
     */
    public function setCedPer($cedPer)
    {
        $this->cedPer = $cedPer;

        return $this;
    }

    /**
     * Get cedPer
     *
     * @return integer 
     */
    public function getCedPer()
    {
        return $this->cedPer;
    }

    /**
     * Set fechPer
     *
     * @param \DateTime $fechPer
     * @return Persona
     */
    public function setFechPer($fechPer)
    {
        $this->fechPer = $fechPer;

        return $this;
    }

    /**
     * Get fechPer
     *
     * @return \DateTime 
     */
    public function getFechPer()
    {
        return $this->fechPer;
    }

    /**
     * Set edoCiv
     *
     * @param string $edoCiv
     * @return Persona
     */
    public function setEdoCiv($edoCiv)
    {
        $this->edoCiv = $edoCiv;

        return $this;
    }

    /**
     * Get edoCiv
     *
     * @return string 
     */
    public function getEdoCiv()
    {
        return $this->edoCiv;
    }

    /**
     * Set telPer
     *
     * @param string $telPer
     * @return Persona
     */
    public function setTelPer($telPer)
    {
        $this->telPer = $telPer;

        return $this;
    }

    /**
     * Get telPer
     *
     * @return string 
     */
    public function getTelPer()
    {
        return $this->telPer;
    }

    /**
     * Set celPer
     *
     * @param string $celPer
     * @return Persona
     */
    public function setCelPer($celPer)
    {
        $this->celPer = $celPer;

        return $this;
    }

    /**
     * Get celPer
     *
     * @return string 
     */
    public function getCelPer()
    {
        return $this->celPer;
    }

    /**
     * Set sexPer
     *
     * @param string $sexPer
     * @return Persona
     */
    public function setSexPer($sexPer)
    {
        $this->sexPer = $sexPer;

        return $this;
    }

    /**
     * Get sexPer
     *
     * @return string 
     */
    public function getSexPer()
    {
        return $this->sexPer;
    }

    /**
     * Set edadPer
     *
     * @param string $edadPer
     * @return Persona
     */
    public function setEdadPer($edadPer)
    {
        $this->edadPer = $edadPer;

        return $this;
    }

    /**
     * Get edadPer
     *
     * @return string 
     */
    public function getEdadPer()
    {
        return $this->edadPer;
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
     * @return Persona
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
