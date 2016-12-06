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
}
