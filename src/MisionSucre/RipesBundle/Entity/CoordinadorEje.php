<?php

namespace MisionSucre\RipesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="coordinador_eje")
 * @ORM\Entity()
 * @ORM\Entity(repositoryClass="MisionSucre\RipesBundle\Entity\CoordinadorEjeRepository")
 */
class CoordinadorEje
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
     * @ORM\OneToOne(targetEntity="Eje")
     * @ORM\JoinColumn(name="eje_id", referencedColumnName="id")
     **/
    private $eje;


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
     * @return CoordinadorEje
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
     * Set eje
     *
     * @param \MisionSucre\RipesBundle\Entity\Eje $eje
     * @return CoordinadorEje
     */
    public function setEje(\MisionSucre\RipesBundle\Entity\Eje $eje = null)
    {
        $this->eje = $eje;

        return $this;
    }

    /**
     * Get eje
     *
     * @return \MisionSucre\RipesBundle\Entity\Eje 
     */
    public function getEje()
    {
        return $this->eje;
    }
}
