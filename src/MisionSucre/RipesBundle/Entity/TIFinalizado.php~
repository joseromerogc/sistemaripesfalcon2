<?php

namespace MisionSucre\RipesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PeriodoAcademicoAmbiente
 *
 * @ORM\Table(name="ti_finalizado")
 * @ORM\Entity(repositoryClass="MisionSucre\RipesBundle\Entity\TIFinalizadoRepository")
 */
class TIFinalizado
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
     * @ORM\ManyToOne(targetEntity="Ambiente", inversedBy="tifinalizados")
     * @ORM\JoinColumn(name="ambiente_id", referencedColumnName="id")
     */
    protected $ambiente;
    
    /**
     * @ORM\ManyToOne(targetEntity="PeriodoAcademico", inversedBy="periodosacademicostifinalizados")
     * @ORM\JoinColumn(name="periodoacademico_id", referencedColumnName="id")
     */
    protected $periodoacademico;
    
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
     * Set ambiente
     *
     * @param \MisionSucre\RipesBundle\Entity\Ambiente $ambiente
     * @return TIFinalizado
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

    /**
     * Set periodoacademico
     *
     * @param \MisionSucre\RipesBundle\Entity\PeriodoAcademico $periodoacademico
     * @return TIFinalizado
     */
    public function setPeriodoacademico(\MisionSucre\RipesBundle\Entity\PeriodoAcademico $periodoacademico = null)
    {
        $this->periodoacademico = $periodoacademico;

        return $this;
    }

    /**
     * Get periodoacademico
     *
     * @return \MisionSucre\RipesBundle\Entity\PeriodoAcademico 
     */
    public function getPeriodoacademico()
    {
        return $this->periodoacademico;
    }

    /**
     * Set user
     *
     * @param \MisionSucre\RipesBundle\Entity\User $user
     * @return TIFinalizado
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
}
