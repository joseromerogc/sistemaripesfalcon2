<?php

namespace MisionSucre\RipesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ambiente
 *
 * @ORM\Table(name="periodo_triunfador")
 * @ORM\Entity(repositoryClass="MisionSucre\RipesBundle\Entity\PeriodoTriunfadorRepository")
 */
class PeriodoTriunfador
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
     * @ORM\ManyToOne(targetEntity="PeriodoAcademicoAmbiente", inversedBy="periodostriunfadores")
     * @ORM\JoinColumn(name="periodoacademico_id", referencedColumnName="id")
     */
    protected $periodoacademicoambiente;
    
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="periodostriunfadores")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;
    
    /**
     * @ORM\OneToMany(targetEntity="Nota", mappedBy="triunfador")
     */
    protected $notas;
    

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
     * @return PeriodoTriunfador
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
     * Set user
     *
     * @param \MisionSucre\RipesBundle\Entity\User $user
     * @return PeriodoTriunfador
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
     * @return PeriodoTriunfador
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
}
