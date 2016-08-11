<?php

namespace MisionSucre\RipesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sector
 *
 * @ORM\Table(name="coordenadas_aldea")
 * @ORM\Entity()
 */
class CoordenadasAldea
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
     * @ORM\ManyToOne(targetEntity="Aldea", inversedBy="coordenadas")
     * @ORM\JoinColumn(name="aldea_id", referencedColumnName="id")
     */
    protected $aldea;
    
    
        
    /**
     * @var string
     *
     * @ORM\Column(name="norte", type="string", length=30)
     */
    
    private $norte;
    
    /**
     * @var string
     *
     * @ORM\Column(name="oeste", type="string", length=30)
     */
    
    private $oeste;
    
    
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
     * Set norte
     *
     * @param string $norte
     * @return CoordenadasAldea
     */
    public function setNorte($norte)
    {
        $this->norte = $norte;

        return $this;
    }

    /**
     * Get norte
     *
     * @return string 
     */
    public function getNorte()
    {
        return $this->norte;
    }

    /**
     * Set oeste
     *
     * @param string $oeste
     * @return CoordenadasAldea
     */
    public function setOeste($oeste)
    {
        $this->oeste = $oeste;

        return $this;
    }

    /**
     * Get oeste
     *
     * @return string 
     */
    public function getOeste()
    {
        return $this->oeste;
    }

    /**
     * Set aldea
     *
     * @param \MisionSucre\RipesBundle\Entity\Aldea $aldea
     * @return CoordenadasAldea
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
}
