<?php

namespace MisionSucre\RipesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Academico
 *
 * @ORM\Table(name="docente")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="MisionSucre\RipesBundle\Entity\DocenteRepository")
 */
class Docente
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
     * @ORM\ManyToOne(targetEntity="Aldea", inversedBy="docentes")
     * @ORM\JoinColumn(name="aldea_id", referencedColumnName="id")
     */
    protected $aldea;
    
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="docentes")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;
    
    /**
     * @var string
     *
     * @ORM\Column(name="unidades_curriculares", type="string", length=100)
     */
    
    private $unidadescurriculares;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100)
     */
    
    private $pnfs;
    
    /**
     * @var string
     *
     * @ORM\Column(name="horas", type="string", length=2)
     */
    
    private $horas;
    
    /**
     * @var string
     *
     * @ORM\Column( type="string", length=2)
     */
    
    private $componentedocente;
    
    /**
     * @var string
     *
     * @ORM\Column( name="periodo_ingreso",type="string", length=12)
     */
    
    private $periodoingreso;
    
    /**
     * @var string
     *
     * @ORM\Column( type="string", length=2)
     */
    
    private $exclusividad;
    
    /**
     * @ORM\OneToMany(targetEntity="ActaNota", mappedBy="docente")
     */
    protected $actasnotas;
    
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
     * Set unidadescurriculares
     *
     * @param string $unidadescurriculares
     * @return Docente
     */
    public function setUnidadescurriculares($unidadescurriculares)
    {
        $this->unidadescurriculares = $unidadescurriculares;

        return $this;
    }

    /**
     * Get unidadescurriculares
     *
     * @return string 
     */
    public function getUnidadescurriculares()
    {
        return $this->unidadescurriculares;
    }

    /**
     * Set horas
     *
     * @param string $horas
     * @return Docente
     */
    public function setHoras($horas)
    {
        $this->horas = $horas;

        return $this;
    }

    /**
     * Get horas
     *
     * @return string 
     */
    public function getHoras()
    {
        return $this->horas;
    }

    /**
     * Set aldea
     *
     * @param \MisionSucre\RipesBundle\Entity\Aldea $aldea
     * @return Docente
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
     * Set user
     *
     * @param \MisionSucre\RipesBundle\Entity\User $user
     * @return Docente
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
     * Set componentedocente
     *
     * @param string $componentedocente
     * @return Docente
     */
    public function setComponentedocente($componentedocente)
    {
        $this->componentedocente = $componentedocente;

        return $this;
    }

    /**
     * Get componentedocente
     *
     * @return string 
     */
    public function getComponentedocente()
    {
        return $this->componentedocente;
    }

    /**
     * Set pnfs
     *
     * @param string $pnfs
     * @return Docente
     */
    public function setPnfs($pnfs)
    {
        $this->pnfs = $pnfs;

        return $this;
    }

    /**
     * Get pnfs
     *
     * @return string 
     */
    public function getPnfs()
    {
        return $this->pnfs;
    }

    /**
     * Set periodoingreso
     *
     * @param string $periodoingreso
     * @return Docente
     */
    public function setPeriodoingreso($periodoingreso)
    {
        $this->periodoingreso = $periodoingreso;

        return $this;
    }

    /**
     * Get periodoingreso
     *
     * @return string 
     */
    public function getPeriodoingreso()
    {
        return $this->periodoingreso;
    }

    /**
     * Set exclusividad
     *
     * @param string $exclusividad
     * @return Docente
     */
    public function setExclusividad($exclusividad)
    {
        $this->exclusividad = $exclusividad;

        return $this;
    }

    /**
     * Get exclusividad
     *
     * @return string 
     */
    public function getExclusividad()
    {
        return $this->exclusividad;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->actasnotas = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add actasnotas
     *
     * @param \MisionSucre\RipesBundle\Entity\ActaNota $actasnotas
     * @return Docente
     */
    public function addActasnota(\MisionSucre\RipesBundle\Entity\ActaNota $actasnotas)
    {
        $this->actasnotas[] = $actasnotas;

        return $this;
    }

    /**
     * Remove actasnotas
     *
     * @param \MisionSucre\RipesBundle\Entity\ActaNota $actasnotas
     */
    public function removeActasnota(\MisionSucre\RipesBundle\Entity\ActaNota $actasnotas)
    {
        $this->actasnotas->removeElement($actasnotas);
    }

    /**
     * Get actasnotas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getActasnotas()
    {
        return $this->actasnotas;
    }
}
