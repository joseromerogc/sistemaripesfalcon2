<?php

namespace MisionSucre\RipesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Parroquia
 *
 * @ORM\Table(name="parroquia")
 * @ORM\Entity(repositoryClass="MisionSucre\RipesBundle\Entity\ParroquiaRepository")
 */
class Parroquia
{
    /**
     * @ORM\ManyToOne(targetEntity="Municipio", inversedBy="parroquias")
     * @ORM\JoinColumn(name="municipio_id", referencedColumnName="id")
     */
    protected $municipio;
    
    /**
     * @ORM\ManyToOne(targetEntity="Eje", inversedBy="parroquias")
     * @ORM\JoinColumn(name="eje_id", referencedColumnName="id")
     */
    protected $eje;

    /**
     * @var string
     *
     * @ORM\Column(name="parroquia", type="string", length=250, nullable=false)
     */
    private $parroquia;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    
    /**
     * @ORM\OneToMany(targetEntity="Sector", mappedBy="parroquia")
     */
    protected $sectores;
    
    /**
     * @ORM\OneToMany(targetEntity="ConsejoComunal", mappedBy="parroquia")
     */
    protected $consejoscomunales;
    
    /**
     * @ORM\OneToMany(targetEntity="Ubch", mappedBy="parroquia")
     */
    protected $ubchs;
    
    /**
     * @ORM\OneToMany(targetEntity="Aldea", mappedBy="parroquia")
     */
    protected $aldeas;
    
    public function __construct()
    {
        $this->sectores = new ArrayCollection();
    }

    /**
     * Set parroquia
     *
     * @param string $parroquia
     * @return Parroquia
     */
    public function setParroquia($parroquia)
    {
        $this->parroquia = $parroquia;

        return $this;
    }

    /**
     * Get parroquia
     *
     * @return string 
     */
    public function getParroquia()
    {
        return $this->parroquia;
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
     * Set municipio
     *
     * @param \MisionSucre\RipesBundle\Entity\Municipio $municipio
     * @return Parroquia
     */
    public function setMunicipio(\MisionSucre\RipesBundle\Entity\Municipio $municipio = null)
    {
        $this->municipio = $municipio;

        return $this;
    }

    /**
     * Get municipio
     *
     * @return \MisionSucre\RipesBundle\Entity\Municipio 
     */
    public function getMunicipio()
    {
        return $this->municipio;
    }

    /**
     * Add sectores
     *
     * @param \MisionSucre\RipesBundle\Entity\Sector $sectores
     * @return Parroquia
     */
    public function addSectore(\MisionSucre\RipesBundle\Entity\Sector $sectores)
    {
        $this->sectores[] = $sectores;

        return $this;
    }

    /**
     * Remove sectores
     *
     * @param \MisionSucre\RipesBundle\Entity\Sector $sectores
     */
    public function removeSectore(\MisionSucre\RipesBundle\Entity\Sector $sectores)
    {
        $this->sectores->removeElement($sectores);
    }

    /**
     * Get sectores
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSectores()
    {
        return $this->sectores;
    }

    /**
     * Add consejoscomunales
     *
     * @param \MisionSucre\RipesBundle\Entity\ConsejoComunal $consejoscomunales
     * @return Parroquia
     */
    public function addConsejoscomunale(\MisionSucre\RipesBundle\Entity\ConsejoComunal $consejoscomunales)
    {
        $this->consejoscomunales[] = $consejoscomunales;

        return $this;
    }

    /**
     * Remove consejoscomunales
     *
     * @param \MisionSucre\RipesBundle\Entity\ConsejoComunal $consejoscomunales
     */
    public function removeConsejoscomunale(\MisionSucre\RipesBundle\Entity\ConsejoComunal $consejoscomunales)
    {
        $this->consejoscomunales->removeElement($consejoscomunales);
    }

    /**
     * Get consejoscomunales
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getConsejoscomunales()
    {
        return $this->consejoscomunales;
    }

    /**
     * Add ubchs
     *
     * @param \MisionSucre\RipesBundle\Entity\Ubch $ubchs
     * @return Parroquia
     */
    public function addUbch(\MisionSucre\RipesBundle\Entity\Ubch $ubchs)
    {
        $this->ubchs[] = $ubchs;

        return $this;
    }

    /**
     * Remove ubchs
     *
     * @param \MisionSucre\RipesBundle\Entity\Ubch $ubchs
     */
    public function removeUbch(\MisionSucre\RipesBundle\Entity\Ubch $ubchs)
    {
        $this->ubchs->removeElement($ubchs);
    }

    /**
     * Get ubchs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUbchs()
    {
        return $this->ubchs;
    }

    /**
     * Set eje
     *
     * @param \MisionSucre\RipesBundle\Entity\Eje $eje
     * @return Parroquia
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

    /**
     * Add aldeas
     *
     * @param \MisionSucre\RipesBundle\Entity\Aldea $aldeas
     * @return Parroquia
     */
    public function addAldea(\MisionSucre\RipesBundle\Entity\Aldea $aldeas)
    {
        $this->aldeas[] = $aldeas;

        return $this;
    }

    /**
     * Remove aldeas
     *
     * @param \MisionSucre\RipesBundle\Entity\Aldea $aldeas
     */
    public function removeAldea(\MisionSucre\RipesBundle\Entity\Aldea $aldeas)
    {
        $this->aldeas->removeElement($aldeas);
    }

    /**
     * Get aldeas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAldeas()
    {
        return $this->aldeas;
    }
}
