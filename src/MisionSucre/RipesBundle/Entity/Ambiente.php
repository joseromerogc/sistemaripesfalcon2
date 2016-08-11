<?php

namespace MisionSucre\RipesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ambiente
 *
 * @ORM\Table(name="ambiente")
 * @ORM\Entity(repositoryClass="MisionSucre\RipesBundle\Entity\AmbienteRepository")
 */
class Ambiente
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
     * @ORM\ManyToOne(targetEntity="Aldea", inversedBy="ambientes")
     * @ORM\JoinColumn(name="aldea_id", referencedColumnName="id")
     */
    protected $aldea;
    
    /**
     * @ORM\ManyToOne(targetEntity="AnexoAldea", inversedBy="ambientes")
     * @ORM\JoinColumn(name="anexo_id", referencedColumnName="id")
     */
    protected $anexo;
    
    /**
     * @ORM\Column(type="string", length=2)
     */
    protected $vinculadosistema;
    
    /**
     * @ORM\ManyToOne(targetEntity="Pnf", inversedBy="ambientes")
     * @ORM\JoinColumn(name="pnf_id", referencedColumnName="id")
     */
    protected $pnf;
    
    /**
     * @ORM\OneToMany(targetEntity="Triunfador", mappedBy="ambiente")
     */
    protected $triunfadores;
    
    /**
     * @ORM\OneToMany(targetEntity="PeriodoAcademicoAmbiente", mappedBy="ambiente")
     */
    protected $periodosacademicos;
        
    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=40, nullable=true)
     */
    
    private $nombre;
    
    /**
     * @var string
     *
     * @ORM\Column(name="promocion", type="string", length=2,nullable=true)
     */
    
    private $promocion;
    
    /**
     * @var string
     *
     * @ORM\Column(name="turno", type="string", length=20)
     */
    
    private $turno;
    
    /**
     * @var string
     *
     * @ORM\Column(name="condicion", type="string", length=20)
     */
    
    private $condicion;
    
    /**
     * @ORM\Column(type="string", length=10)
     */
    
    private $ingreso;
    
    /**
     * @ORM\Column(type="string", length=10,nullable=true)
     */
    
    private $egreso;

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
     * Set nombre
     *
     * @param string $nombre
     * @return Ambiente
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set promocion
     *
     * @param string $promocion
     * @return Ambiente
     */
    public function setPromocion($promocion)
    {
        $this->promocion = $promocion;

        return $this;
    }

    /**
     * Get promocion
     *
     * @return string 
     */
    public function getPromocion()
    {
        return $this->promocion;
    }

    /**
     * Set turno
     *
     * @param string $turno
     * @return Ambiente
     */
    public function setTurno($turno)
    {
        $this->turno = $turno;

        return $this;
    }

    /**
     * Get turno
     *
     * @return string 
     */
    public function getTurno()
    {
        return $this->turno;
    }

    /**
     * Set condicion
     *
     * @param string $condicion
     * @return Ambiente
     */
    public function setCondicion($condicion)
    {
        $this->condicion = $condicion;

        return $this;
    }

    /**
     * Get condicion
     *
     * @return string 
     */
    public function getCondicion()
    {
        return $this->condicion;
    }

    /**
     * Set aldea
     *
     * @param \MisionSucre\RipesBundle\Entity\Aldea $aldea
     * @return Ambiente
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
     * Set pnf
     *
     * @param \MisionSucre\RipesBundle\Entity\Pnf $pnf
     * @return Ambiente
     */
    public function setPnf(\MisionSucre\RipesBundle\Entity\Pnf $pnf = null)
    {
        $this->pnf = $pnf;

        return $this;
    }

    /**
     * Get pnf
     *
     * @return \MisionSucre\RipesBundle\Entity\Pnf 
     */
    public function getPnf()
    {
        return $this->pnf;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->triunfadores = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add triunfadores
     *
     * @param \MisionSucre\RipesBundle\Entity\Triunfador $triunfadores
     * @return Ambiente
     */
    public function addTriunfadore(\MisionSucre\RipesBundle\Entity\Triunfador $triunfadores)
    {
        $this->triunfadores[] = $triunfadores;

        return $this;
    }

    /**
     * Remove triunfadores
     *
     * @param \MisionSucre\RipesBundle\Entity\Triunfador $triunfadores
     */
    public function removeTriunfadore(\MisionSucre\RipesBundle\Entity\Triunfador $triunfadores)
    {
        $this->triunfadores->removeElement($triunfadores);
    }

    /**
     * Get triunfadores
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTriunfadores()
    {
        return $this->triunfadores;
    }

    /**
     * Set anexo
     *
     * @param \MisionSucre\RipesBundle\Entity\AnexoAldea $anexo
     * @return Ambiente
     */
    public function setAnexo(\MisionSucre\RipesBundle\Entity\AnexoAldea $anexo = null)
    {
        $this->anexo = $anexo;

        return $this;
    }

    /**
     * Get anexo
     *
     * @return \MisionSucre\RipesBundle\Entity\AnexoAldea 
     */
    public function getAnexo()
    {
        return $this->anexo;
    }

    /**
     * Set sistema
     *
     * @param string $sistema
     * @return Ambiente
     */
    public function setSistema($sistema)
    {
        $this->sistema = $sistema;

        return $this;
    }

    /**
     * Get sistema
     *
     * @return string 
     */
    public function getSistema()
    {
        return $this->sistema;
    }

    /**
     * Set ingreso
     *
     * @param string $ingreso
     * @return Ambiente
     */
    public function setIngreso($ingreso)
    {
        $this->ingreso = $ingreso;

        return $this;
    }

    /**
     * Get ingreso
     *
     * @return string 
     */
    public function getIngreso()
    {
        return $this->ingreso;
    }

    /**
     * Set egreso
     *
     * @param string $egreso
     * @return Ambiente
     */
    public function setEgreso($egreso)
    {
        $this->egreso = $egreso;

        return $this;
    }

    /**
     * Get egreso
     *
     * @return string 
     */
    public function getEgreso()
    {
        return $this->egreso;
    }

    /**
     * Set vinculadosistema
     *
     * @param string $vinculadosistema
     * @return Ambiente
     */
    public function setVinculadosistema($vinculadosistema)
    {
        $this->vinculadosistema = $vinculadosistema;

        return $this;
    }

    /**
     * Get vinculadosistema
     *
     * @return string 
     */
    public function getVinculadosistema()
    {
        return $this->vinculadosistema;
    }

    /**
     * Add periodosacademicos
     *
     * @param \MisionSucre\RipesBundle\Entity\PeriodoAcademicoAmbiente $periodosacademicos
     * @return Ambiente
     */
    public function addPeriodosacademico(\MisionSucre\RipesBundle\Entity\PeriodoAcademicoAmbiente $periodosacademicos)
    {
        $this->periodosacademicos[] = $periodosacademicos;

        return $this;
    }

    /**
     * Remove periodosacademicos
     *
     * @param \MisionSucre\RipesBundle\Entity\PeriodoAcademicoAmbiente $periodosacademicos
     */
    public function removePeriodosacademico(\MisionSucre\RipesBundle\Entity\PeriodoAcademicoAmbiente $periodosacademicos)
    {
        $this->periodosacademicos->removeElement($periodosacademicos);
    }

    /**
     * Get periodosacademicos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPeriodosacademicos()
    {
        return $this->periodosacademicos;
    }
}
