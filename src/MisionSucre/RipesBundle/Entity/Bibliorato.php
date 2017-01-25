<?php

namespace MisionSucre\RipesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PeriodoAcademicoAmbiente
 *
 * @ORM\Table(name="bibliorato")
 * @ORM\Entity(repositoryClass="MisionSucre\RipesBundle\Entity\BiblioratoRepository")
 */
class Bibliorato
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
     * @ORM\ManyToOne(targetEntity="Ambiente", inversedBy="biblioratos")
     * @ORM\JoinColumn(name="ambiente_id", referencedColumnName="id")
     */
    protected $ambiente;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=20)
     */
    protected $vagon;
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=20)
     */
    protected $peldanyo;
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=20)
     */
    protected $bibliorato;
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=20)
     */
    protected $segmento;
    
    
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
     * Set vagon
     *
     * @param string $vagon
     * @return Bibliorato
     */
    public function setVagon($vagon)
    {
        $this->vagon = $vagon;

        return $this;
    }

    /**
     * Get vagon
     *
     * @return string 
     */
    public function getVagon()
    {
        return $this->vagon;
    }

    /**
     * Set peldanyo
     *
     * @param string $peldanyo
     * @return Bibliorato
     */
    public function setPeldanyo($peldanyo)
    {
        $this->peldanyo = $peldanyo;

        return $this;
    }

    /**
     * Get peldanyo
     *
     * @return string 
     */
    public function getPeldanyo()
    {
        return $this->peldanyo;
    }

    /**
     * Set bibliorato
     *
     * @param string $bibliorato
     * @return Bibliorato
     */
    public function setBibliorato($bibliorato)
    {
        $this->bibliorato = $bibliorato;

        return $this;
    }

    /**
     * Get bibliorato
     *
     * @return string 
     */
    public function getBibliorato()
    {
        return $this->bibliorato;
    }

    /**
     * Set segmento
     *
     * @param string $segmento
     * @return Bibliorato
     */
    public function setSegmento($segmento)
    {
        $this->segmento = $segmento;

        return $this;
    }

    /**
     * Get segmento
     *
     * @return string 
     */
    public function getSegmento()
    {
        return $this->segmento;
    }

    /**
     * Set ambiente
     *
     * @param \MisionSucre\RipesBundle\Entity\Ambiente $ambiente
     * @return Bibliorato
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
