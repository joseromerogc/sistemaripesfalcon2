<?php

namespace MisionSucre\RipesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Parroquia
 *
 * @ORM\Table(name="data_cne")
 * @ORM\Entity
 */
class DataCne
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     */
    private $cedula;
    
    /**
     * @ORM\Column(type="string", length=80)
     */
    private $nombre_apellido;
    
    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $fechanacimiento;
    
    /**
     * @ORM\Column(type="string", length=3, nullable=true)
     */
    private $sexo;
    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $centroelectoral;

    /**
     * Set cedula
     *
     * @param integer $cedula
     * @return DataCne
     */
    public function setCedula($cedula)
    {
        $this->cedula = $cedula;

        return $this;
    }

    /**
     * Get cedula
     *
     * @return integer 
     */
    public function getCedula()
    {
        return $this->cedula;
    }

    /**
     * Set nombre_apellido
     *
     * @param string $nombreApellido
     * @return DataCne
     */
    public function setNombreApellido($nombreApellido)
    {
        $this->nombre_apellido = $nombreApellido;

        return $this;
    }

    /**
     * Get nombre_apellido
     *
     * @return string 
     */
    public function getNombreApellido()
    {
        return $this->nombre_apellido;
    }

    /**
     * Set fechanacimiento
     *
     * @param string $fechanacimiento
     * @return DataCne
     */
    public function setFechanacimiento($fechanacimiento)
    {
        $this->fechanacimiento = $fechanacimiento;

        return $this;
    }

    /**
     * Get fechanacimiento
     *
     * @return string 
     */
    public function getFechanacimiento()
    {
        return $this->fechanacimiento;
    }

    /**
     * Set sexo
     *
     * @param string $sexo
     * @return DataCne
     */
    public function setSexo($sexo)
    {
        $this->sexo = $sexo;

        return $this;
    }

    /**
     * Get sexo
     *
     * @return string 
     */
    public function getSexo()
    {
        return $this->sexo;
    }

    /**
     * Set centroelectoral
     *
     * @param string $centroelectoral
     * @return DataCne
     */
    public function setCentroelectoral($centroelectoral)
    {
        $this->centroelectoral = $centroelectoral;

        return $this;
    }

    /**
     * Get centroelectoral
     *
     * @return string 
     */
    public function getCentroelectoral()
    {
        return $this->centroelectoral;
    }
}
