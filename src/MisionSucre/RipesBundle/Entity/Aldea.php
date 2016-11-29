<?php

namespace MisionSucre\RipesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Aldea
 *
 * @ORM\Table(name="aldea")
 * @ORM\Entity(repositoryClass="MisionSucre\RipesBundle\Entity\AldeaRepository")
 */
class Aldea
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
     * @ORM\ManyToOne(targetEntity="Parroquia", inversedBy="aldeas")
     * @ORM\JoinColumn(name="parroquia_id", referencedColumnName="id")
     */
    protected $parroquia;
//, unique=true
    /**
     * @var string
     *
     * @ORM\Column(name="codigo", type="string", length=12)
     */
    
    private $codigo;
    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=70)
     */
    
    private $nombre;
    
    /**
     * @var string
     *
     * @ORM\Column(name="direccion", type="string", length=150)
     */
    
    private $direccion;
    
    /**
     * @ORM\OneToMany(targetEntity="AldeaComunal", mappedBy="aldea")
     */
    protected $aldeascomunales;
    
    /**
     * @ORM\OneToMany(targetEntity="AnexoAldea", mappedBy="aldea")
     */
    protected $anexos;
    
    /**
     * @ORM\OneToMany(targetEntity="CoordenadasAldea", mappedBy="aldea")
     */
    protected $coordenadas;
    
    /**
     * @ORM\OneToMany(targetEntity="CoordinadorAldea", mappedBy="aldea")
     */
    protected $coordinadoresaldeas;
    
    /**
     * @ORM\OneToMany(targetEntity="Ambiente", mappedBy="aldea")
     */
    protected $ambientes;
    
    /**
     * @ORM\OneToMany(targetEntity="Operario", mappedBy="aldea")
     */
    protected $operarios;
    
    /**
     * @ORM\OneToMany(targetEntity="Docente", mappedBy="aldea")
     */
    protected $docentes;
    
    /**
     * @ORM\OneToMany(targetEntity="Estructura", mappedBy="aldea")
     */
    protected $estructuras;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->aldeascomunales = new \Doctrine\Common\Collections\ArrayCollection();
        $this->coordinadoresaldeas = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set nombre
     *
     * @param string $nombre
     * @return Aldea
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
     * Set parroquia
     *
     * @param \MisionSucre\RipesBundle\Entity\Parroquia $parroquia
     * @return Aldea
     */
    public function setParroquia(\MisionSucre\RipesBundle\Entity\Parroquia $parroquia = null)
    {
        $this->parroquia = $parroquia;

        return $this;
    }

    /**
     * Get parroquia
     *
     * @return \MisionSucre\RipesBundle\Entity\Parroquia 
     */
    public function getParroquia()
    {
        return $this->parroquia;
    }

    /**
     * Add aldeascomunales
     *
     * @param \MisionSucre\RipesBundle\Entity\AldeaComunal $aldeascomunales
     * @return Aldea
     */
    public function addAldeascomunale(\MisionSucre\RipesBundle\Entity\AldeaComunal $aldeascomunales)
    {
        $this->aldeascomunales[] = $aldeascomunales;

        return $this;
    }

    /**
     * Remove aldeascomunales
     *
     * @param \MisionSucre\RipesBundle\Entity\AldeaComunal $aldeascomunales
     */
    public function removeAldeascomunale(\MisionSucre\RipesBundle\Entity\AldeaComunal $aldeascomunales)
    {
        $this->aldeascomunales->removeElement($aldeascomunales);
    }

    /**
     * Get aldeascomunales
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAldeascomunales()
    {
        return $this->aldeascomunales;
    }

    /**
     * Add coordinadoresaldeas
     *
     * @param \MisionSucre\RipesBundle\Entity\CoordinadorAldea $coordinadoresaldeas
     * @return Aldea
     */
    public function addCoordinadoresaldea(\MisionSucre\RipesBundle\Entity\CoordinadorAldea $coordinadoresaldeas)
    {
        $this->coordinadoresaldeas[] = $coordinadoresaldeas;

        return $this;
    }

    /**
     * Remove coordinadoresaldeas
     *
     * @param \MisionSucre\RipesBundle\Entity\CoordinadorAldea $coordinadoresaldeas
     */
    public function removeCoordinadoresaldea(\MisionSucre\RipesBundle\Entity\CoordinadorAldea $coordinadoresaldeas)
    {
        $this->coordinadoresaldeas->removeElement($coordinadoresaldeas);
    }

    /**
     * Get coordinadoresaldeas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCoordinadoresaldeas()
    {
        return $this->coordinadoresaldeas;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     * @return Aldea
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return string 
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Add ambientes
     *
     * @param \MisionSucre\RipesBundle\Entity\Ambiente $ambientes
     * @return Aldea
     */
    public function addAmbiente(\MisionSucre\RipesBundle\Entity\Ambiente $ambientes)
    {
        $this->ambientes[] = $ambientes;

        return $this;
    }

    /**
     * Remove ambientes
     *
     * @param \MisionSucre\RipesBundle\Entity\Ambiente $ambientes
     */
    public function removeAmbiente(\MisionSucre\RipesBundle\Entity\Ambiente $ambientes)
    {
        $this->ambientes->removeElement($ambientes);
    }

    /**
     * Get ambientes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAmbientes()
    {
        return $this->ambientes;
    }

    /**
     * Add operarios
     *
     * @param \MisionSucre\RipesBundle\Entity\Operario $operarios
     * @return Aldea
     */
    public function addOperario(\MisionSucre\RipesBundle\Entity\Operario $operarios)
    {
        $this->operarios[] = $operarios;

        return $this;
    }

    /**
     * Remove operarios
     *
     * @param \MisionSucre\RipesBundle\Entity\Operario $operarios
     */
    public function removeOperario(\MisionSucre\RipesBundle\Entity\Operario $operarios)
    {
        $this->operarios->removeElement($operarios);
    }

    /**
     * Get operarios
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOperarios()
    {
        return $this->operarios;
    }
    
    
    /**
     * Add docentes
     *
     * @param \MisionSucre\RipesBundle\Entity\Docente $docentes
     * @return Aldea
     */
    public function addDocente(\MisionSucre\RipesBundle\Entity\Docente $docentes)
    {
        $this->docentes[] = $docentes;

        return $this;
    }

    /**
     * Remove docentes
     *
     * @param \MisionSucre\RipesBundle\Entity\Docente $docentes
     */
    public function removeDocente(\MisionSucre\RipesBundle\Entity\Docente $docentes)
    {
        $this->docentes->removeElement($docentes);
    }

    /**
     * Get docentes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDocentes()
    {
        return $this->docentes;
    }

    /**
     * Add coordenadas
     *
     * @param \MisionSucre\RipesBundle\Entity\CoordenadasAldea $coordenadas
     * @return Aldea
     */
    public function addCoordenada(\MisionSucre\RipesBundle\Entity\CoordenadasAldea $coordenadas)
    {
        $this->coordenadas[] = $coordenadas;

        return $this;
    }

    /**
     * Remove coordenadas
     *
     * @param \MisionSucre\RipesBundle\Entity\CoordenadasAldea $coordenadas
     */
    public function removeCoordenada(\MisionSucre\RipesBundle\Entity\CoordenadasAldea $coordenadas)
    {
        $this->coordenadas->removeElement($coordenadas);
    }

    /**
     * Get coordenadas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCoordenadas()
    {
        return $this->coordenadas;
    }

    /**
     * Add anexos
     *
     * @param \MisionSucre\RipesBundle\Entity\AnexoAldea $anexos
     * @return Aldea
     */
    public function addAnexo(\MisionSucre\RipesBundle\Entity\AnexoAldea $anexos)
    {
        $this->anexos[] = $anexos;

        return $this;
    }

    /**
     * Remove anexos
     *
     * @param \MisionSucre\RipesBundle\Entity\AnexoAldea $anexos
     */
    public function removeAnexo(\MisionSucre\RipesBundle\Entity\AnexoAldea $anexos)
    {
        $this->anexos->removeElement($anexos);
    }

    /**
     * Get anexos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAnexos()
    {
        return $this->anexos;
    }

    /**
     * Add estructuras
     *
     * @param \MisionSucre\RipesBundle\Entity\Estructura $estructuras
     * @return Aldea
     */
    public function addEstructura(\MisionSucre\RipesBundle\Entity\Estructura $estructuras)
    {
        $this->estructuras[] = $estructuras;

        return $this;
    }

    /**
     * Remove estructuras
     *
     * @param \MisionSucre\RipesBundle\Entity\Estructura $estructuras
     */
    public function removeEstructura(\MisionSucre\RipesBundle\Entity\Estructura $estructuras)
    {
        $this->estructuras->removeElement($estructuras);
    }

    /**
     * Get estructuras
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEstructuras()
    {
        return $this->estructuras;
    }

    /**
     * Set codigo
     *
     * @param string $codigo
     * @return Aldea
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get codigo
     *
     * @return string 
     */
    public function getCodigo()
    {
        return $this->codigo;
    }
}
