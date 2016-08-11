<?php

namespace MisionSucre\RipesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Academico
 *
 * @ORM\Table(name="academico")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="MisionSucre\RipesBundle\Entity\AcademicoRepository")
 */
class Academico
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
     * @ORM\OneToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     **/
    private $user;
    
    /**
     * @var string
     *
     * @ORM\Column(name="bachillerato", type="string", length=30)
     */
    private $bachillerato;
    
    /**
     * @var string
     *
     * @ORM\Column(name="tit_uni", type="string", length=50)
     */
    private $titulouniversitario;
    
    /**
     * @var string
     *
     * @ORM\Column(name="instituto", type="string", length=50, nullable=true)
     */
    private $instituto;
    /**
     * @var string
     *
     * @ORM\Column(name="post_grado", type="string", length=50)
     */
    private $postgrado;
    
    /**
     * @var string
     *
     * @ORM\Column(name="area_conoc", type="string", length=50, nullable=true)
     */
    private $areaconocimiento;
    
    /**
     * @var string
     *
     * @ORM\Column(name="cursos", type="string", length=100, nullable=true)
     */
    private $cursos;

    
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
     * Set bachillerato
     *
     * @param string $bachillerato
     * @return Academico
     */
    public function setBachillerato($bachillerato)
    {
        $this->bachillerato = $bachillerato;

        return $this;
    }

    /**
     * Get bachillerato
     *
     * @return string 
     */
    public function getBachillerato()
    {
        return $this->bachillerato;
    }

    /**
     * Set titulouniversitario
     *
     * @param string $titulouniversitario
     * @return Academico
     */
    public function setTitulouniversitario($titulouniversitario)
    {
        $this->titulouniversitario = $titulouniversitario;

        return $this;
    }

    /**
     * Get titulouniversitario
     *
     * @return string 
     */
    public function getTitulouniversitario()
    {
        return $this->titulouniversitario;
    }

    /**
     * Set postgrado
     *
     * @param string $postgrado
     * @return Academico
     */
    public function setPostgrado($postgrado)
    {
        $this->postgrado = $postgrado;

        return $this;
    }

    /**
     * Get postgrado
     *
     * @return string 
     */
    public function getPostgrado()
    {
        return $this->postgrado;
    }

    /**
     * Set areaconocimiento
     *
     * @param string $areaconocimiento
     * @return Academico
     */
    public function setAreaconocimiento($areaconocimiento)
    {
        $this->areaconocimiento = $areaconocimiento;

        return $this;
    }

    /**
     * Get areaconocimiento
     *
     * @return string 
     */
    public function getAreaconocimiento()
    {
        return $this->areaconocimiento;
    }

    /**
     * Set cursos
     *
     * @param string $cursos
     * @return Academico
     */
    public function setCursos($cursos)
    {
        $this->cursos = $cursos;

        return $this;
    }

    /**
     * Get cursos
     *
     * @return string 
     */
    public function getCursos()
    {
        return $this->cursos;
    }

    /**
     * Set user
     *
     * @param \MisionSucre\RipesBundle\Entity\User $user
     * @return Academico
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
     * Set instituto
     *
     * @param string $instituto
     * @return Academico
     */
    public function setInstituto($instituto)
    {
        $this->instituto = $instituto;

        return $this;
    }

    /**
     * Get instituto
     *
     * @return string 
     */
    public function getInstituto()
    {
        return $this->instituto;
    }
}
