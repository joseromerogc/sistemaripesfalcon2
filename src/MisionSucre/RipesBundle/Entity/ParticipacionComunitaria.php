<?php

namespace MisionSucre\RipesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="participacion_comunitaria")
 * @ORM\Entity()
 * @ORM\Entity(repositoryClass="MisionSucre\RipesBundle\Entity\ParticipacionComunitariaRepository")
 */
class ParticipacionComunitaria
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=40,nullable=true)
     */
    private $voceria;
    /**
     * @ORM\Column(type="string", length=40,nullable=true)
     */
    private $organizacion_social;
    
    /**
     * @ORM\ManyToOne(targetEntity="Mision", inversedBy="personas")
     * @ORM\JoinColumn(name="mision_id", referencedColumnName="id")
     */
    private $mision;
    
    /**
     * @ORM\OneToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     **/
    private $user;
    
    /**
     * @ORM\ManyToOne(targetEntity="ConsejoComunal", inversedBy="personas")
     * @ORM\JoinColumn(name="cc_id", referencedColumnName="id")
     */
    protected $cc;
    
    
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
     * Set voceria
     *
     * @param string $voceria
     * @return ParticipacionComunitaria
     */
    public function setVoceria($voceria)
    {
        $this->voceria = $voceria;

        return $this;
    }

    /**
     * Get voceria
     *
     * @return string 
     */
    public function getVoceria()
    {
        return $this->voceria;
    }

    /**
     * Set organizacion_social
     *
     * @param string $organizacionSocial
     * @return ParticipacionComunitaria
     */
    public function setOrganizacionSocial($organizacionSocial)
    {
        $this->organizacion_social = $organizacionSocial;

        return $this;
    }

    /**
     * Get organizacion_social
     *
     * @return string 
     */
    public function getOrganizacionSocial()
    {
        return $this->organizacion_social;
    }

    /**
     * Set user
     *
     * @param \MisionSucre\RipesBundle\Entity\User $user
     * @return ParticipacionComunitaria
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
     * Set cc
     *
     * @param \MisionSucre\RipesBundle\Entity\ConsejoComunal $cc
     * @return ParticipacionComunitaria
     */
    public function setCc(\MisionSucre\RipesBundle\Entity\ConsejoComunal $cc = null)
    {
        $this->cc = $cc;

        return $this;
    }

    /**
     * Get cc
     *
     * @return \MisionSucre\RipesBundle\Entity\ConsejoComunal 
     */
    public function getCc()
    {
        return $this->cc;
    }

    /**
     * Set mision
     *
     * @param \MisionSucre\RipesBundle\Entity\Mision $mision
     * @return ParticipacionComunitaria
     */
    public function setMision(\MisionSucre\RipesBundle\Entity\Mision $mision = null)
    {
        $this->mision = $mision;

        return $this;
    }

    /**
     * Get mision
     *
     * @return \MisionSucre\RipesBundle\Entity\Mision 
     */
    public function getMision()
    {
        return $this->mision;
    }
}
