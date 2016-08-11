<?php

namespace MisionSucre\RipesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Vocero
 *
 * @ORM\Table(name="vocero")
 * @ORM\Entity(repositoryClass="MisionSucre\RipesBundle\Entity\VoceroRepository")
 */
class Vocero
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
     * @ORM\OneToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     **/
    private $user;
    
    /**
     * @ORM\OneToOne(targetEntity="Ambiente")
     * @ORM\JoinColumn(name="ambiente_id", referencedColumnName="id")
     **/
    private $ambiente;

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
     * @return Vocero
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
     * Set ambiente
     *
     * @param \MisionSucre\RipesBundle\Entity\Ambiente $ambiente
     * @return Vocero
     */
    public function setAmbiente(\MisionSucre\RipesBundle\Entity\Ambiente $ambiente = null)
    {
        $this->ambiente = $ambiente;

        return $this;
    }

    /**
     * Get ambiente
     *
     * @return \MisionSucre\RipesBundle\Entity\Ambiente 
     */
    public function getAmbiente()
    {
        return $this->ambiente;
    }
}
