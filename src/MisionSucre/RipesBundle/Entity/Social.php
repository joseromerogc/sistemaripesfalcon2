<?php

namespace MisionSucre\RipesBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="social")
 * @ORM\Entity()
 * @ORM\Entity(repositoryClass="MisionSucre\RipesBundle\Entity\SocialRepository")
 */
class Social
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $cant_hij;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $tipo_viv;
    
    /**
     * @ORM\Column(type="string", length=30)
     */
    private $ing_fam;
    
    /**
     * @ORM\Column(type="string", length=40)
     */
    private $religion;
    
    /**
     * @ORM\Column(type="string", length=40)
     */
    private $ayuda;
    
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
     * Set cant_hij
     *
     * @param integer $cantHij
     * @return Social
     */
    public function setCantHij($cantHij)
    {
        $this->cant_hij = $cantHij;

        return $this;
    }

    /**
     * Get cant_hij
     *
     * @return integer 
     */
    public function getCantHij()
    {
        return $this->cant_hij;
    }

    /**
     * Set tipo_viv
     *
     * @param string $tipoViv
     * @return Social
     */
    public function setTipoViv($tipoViv)
    {
        $this->tipo_viv = $tipoViv;

        return $this;
    }

    /**
     * Get tipo_viv
     *
     * @return string 
     */
    public function getTipoViv()
    {
        return $this->tipo_viv;
    }

    /**
     * Set ing_fam
     *
     * @param integer $ingFam
     * @return Social
     */
    public function setIngFam($ingFam)
    {
        $this->ing_fam = $ingFam;

        return $this;
    }

    /**
     * Get ing_fam
     *
     * @return integer 
     */
    public function getIngFam()
    {
        return $this->ing_fam;
    }

    /**
     * Set religion
     *
     * @param string $religion
     * @return Social
     */
    public function setReligion($religion)
    {
        $this->religion = $religion;

        return $this;
    }

    /**
     * Get religion
     *
     * @return string 
     */
    public function getReligion()
    {
        return $this->religion;
    }

    /**
     * Set user
     *
     * @param \MisionSucre\RipesBundle\Entity\User $user
     * @return Social
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
     * Set ayuda
     *
     * @param string $ayuda
     * @return Social
     */
    public function setAyuda($ayuda)
    {
        $this->ayuda = $ayuda;

        return $this;
    }

    /**
     * Get ayuda
     *
     * @return string 
     */
    public function getAyuda()
    {
        return $this->ayuda;
    }
}
