<?php

namespace MisionSucre\RipesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PeriodoAcademicoAmbiente
 *
 * @ORM\Table(name="acta_nota")
 * @ORM\Entity(repositoryClass="MisionSucre\RipesBundle\Entity\ActaNotaRepository")
 */
class ActaNota
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
     * @ORM\ManyToOne(targetEntity="PeriodoAcademicoAmbiente", inversedBy="actasnotas")
     * @ORM\JoinColumn(name="periodoacademicoambiente_id", referencedColumnName="id")
     */
    protected $periodoacademicoambiente;
    
    /**
     * @ORM\ManyToOne(targetEntity="Docente", inversedBy="actasnotas")
     * @ORM\JoinColumn(name="docente_id", referencedColumnName="id")
     */
    protected $docente;
    /**
     * @ORM\ManyToOne(targetEntity="Malla", inversedBy="actasnotas")
     * @ORM\JoinColumn(name="malla_id", referencedColumnName="id")
     */
    protected $malla;
    
    /**
     * @var string
     *
     * @ORM\Column(name="validada", type="string", length=2,nullable=true)
     */
    
    private $validada;
    
    /**
     * @ORM\OneToMany(targetEntity="Nota", mappedBy="actanota")
     */
    protected $notas;
    /**
     * @ORM\OneToMany(targetEntity="ImpresionActaNota", mappedBy="actanota")
     */
    protected $impresiones;
    

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
     * Set periodoacademicoambiente
     *
     * @param \MisionSucre\RipesBundle\Entity\PeriodoAcademicoAmbiente $periodoacademicoambiente
     * @return ActaNota
     */
    public function setPeriodoacademicoambiente(\MisionSucre\RipesBundle\Entity\PeriodoAcademicoAmbiente $periodoacademicoambiente = null)
    {
        $this->periodoacademicoambiente = $periodoacademicoambiente;

        return $this;
    }

    /**
     * Get periodoacademicoambiente
     *
     * @return \MisionSucre\RipesBundle\Entity\PeriodoAcademicoAmbiente 
     */
    public function getPeriodoacademicoambiente()
    {
        return $this->periodoacademicoambiente;
    }

    /**
     * Set docente
     *
     * @param \MisionSucre\RipesBundle\Entity\Docente $docente
     * @return ActaNota
     */
    public function setDocente(\MisionSucre\RipesBundle\Entity\Docente $docente = null)
    {
        $this->docente = $docente;

        return $this;
    }

    /**
     * Get docente
     *
     * @return \MisionSucre\RipesBundle\Entity\Docente 
     */
    public function getDocente()
    {
        return $this->docente;
    }

    /**
     * Set malla
     *
     * @param \MisionSucre\RipesBundle\Entity\Malla $malla
     * @return ActaNota
     */
    public function setMalla(\MisionSucre\RipesBundle\Entity\Malla $malla = null)
    {
        $this->malla = $malla;

        return $this;
    }

    /**
     * Get malla
     *
     * @return \MisionSucre\RipesBundle\Entity\Malla 
     */
    public function getMalla()
    {
        return $this->malla;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->notas = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add notas
     *
     * @param \MisionSucre\RipesBundle\Entity\Nota $notas
     * @return ActaNota
     */
    public function addNota(\MisionSucre\RipesBundle\Entity\Nota $notas)
    {
        $this->notas[] = $notas;

        return $this;
    }

    /**
     * Remove notas
     *
     * @param \MisionSucre\RipesBundle\Entity\Nota $notas
     */
    public function removeNota(\MisionSucre\RipesBundle\Entity\Nota $notas)
    {
        $this->notas->removeElement($notas);
    }

    /**
     * Get notas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getNotas()
    {
        return $this->notas;
    }

    /**
     * Add impresiones
     *
     * @param \MisionSucre\RipesBundle\Entity\ImpresionActaNota $impresiones
     * @return ActaNota
     */
    public function addImpresione(\MisionSucre\RipesBundle\Entity\ImpresionActaNota $impresiones)
    {
        $this->impresiones[] = $impresiones;

        return $this;
    }

    /**
     * Remove impresiones
     *
     * @param \MisionSucre\RipesBundle\Entity\ImpresionActaNota $impresiones
     */
    public function removeImpresione(\MisionSucre\RipesBundle\Entity\ImpresionActaNota $impresiones)
    {
        $this->impresiones->removeElement($impresiones);
    }

    /**
     * Get impresiones
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getImpresiones()
    {
        return $this->impresiones;
    }

    /**
     * Set validada
     *
     * @param string $validada
     * @return ActaNota
     */
    public function setValidada($validada)
    {
        $this->validada = $validada;

        return $this;
    }

    /**
     * Get validada
     *
     * @return string 
     */
    public function getValidada()
    {
        return $this->validada;
    }
}
