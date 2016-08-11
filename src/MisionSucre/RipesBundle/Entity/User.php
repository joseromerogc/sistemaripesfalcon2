<?php

namespace MisionSucre\RipesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * MisionSucre\RipesBundle\Entity\User
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="MisionSucre\RipesBundle\Entity\UserRepository")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=55, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
    * @ORM\Column(type="string", length=20)
    */

    private $tip_usr;
    
    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @inheritDoc
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    /**
     * @inheritDoc
     */
    public function getPassword()
    {
        return $this->password;
    }


    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password
        ) = unserialize($serialized);
    }
    
    /**
     * @ORM\ManyToMany(targetEntity="Role", inversedBy="users")
     *
     */
    private $roles;
    
    /**
     * @ORM\ManyToMany(targetEntity="Enfermedad", inversedBy="users")
     *
     */
    private $enfermedades;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->isActive = true;
    }

    public function getRoles()
    {
        return $this->roles->toArray();
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
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Set tip_usr
     *
     * @param string $tipUsr
     * @return User
     */
    public function setTipUsr($tipUsr)
    {
        $this->tip_usr = $tipUsr;

        return $this;
    }

    /**
     * Get tip_usr
     *
     * @return string 
     */
    public function getTipUsr()
    {
        return $this->tip_usr;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return User
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Add roles
     *
     * @param \MisionSucre\RipesBundle\Entity\Role $roles
     * @return User
     */
    public function addRole(\MisionSucre\RipesBundle\Entity\Role $roles)
    {
        $this->roles[] = $roles;

        return $this;
    }

    /**
     * Remove roles
     *
     * @param \MisionSucre\RipesBundle\Entity\Role $roles
     */
    public function removeRole(\MisionSucre\RipesBundle\Entity\Role $roles)
    {
        $this->roles->removeElement($roles);
    }

    /**
     * Add enfermedades
     *
     * @param \MisionSucre\RipesBundle\Entity\Enfermedad $enfermedades
     * @return User
     */
    public function addEnfermedade(\MisionSucre\RipesBundle\Entity\Enfermedad $enfermedades)
    {
        $this->enfermedades[] = $enfermedades;

        return $this;
    }

    /**
     * Remove enfermedades
     *
     * @param \MisionSucre\RipesBundle\Entity\Enfermedad $enfermedades
     */
    public function removeEnfermedade(\MisionSucre\RipesBundle\Entity\Enfermedad $enfermedades)
    {
        $this->enfermedades->removeElement($enfermedades);
    }

    /**
     * Get enfermedades
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEnfermedades()
    {
        return $this->enfermedades;
    }
    
    /**
     * @ORM\OneToMany(targetEntity="Docente", mappedBy="user")
     */
    protected $docentes;
    /**
     * @ORM\OneToMany(targetEntity="MiembroEstructura", mappedBy="user")
     */
    protected $miembros;
    /**
     * @ORM\OneToMany(targetEntity="EstructuraActividad", mappedBy="responsable")
     */
    protected $responsablesactividades;

    /**
     * Add docentes
     *
     * @param \MisionSucre\RipesBundle\Entity\Docente $docentes
     * @return User
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
    
    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->isActive;
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
     * Add miembros
     *
     * @param \MisionSucre\RipesBundle\Entity\MiembroEstructura $miembros
     * @return User
     */
    public function addMiembro(\MisionSucre\RipesBundle\Entity\MiembroEstructura $miembros)
    {
        $this->miembros[] = $miembros;

        return $this;
    }

    /**
     * Remove miembros
     *
     * @param \MisionSucre\RipesBundle\Entity\MiembroEstructura $miembros
     */
    public function removeMiembro(\MisionSucre\RipesBundle\Entity\MiembroEstructura $miembros)
    {
        $this->miembros->removeElement($miembros);
    }

    /**
     * Get miembros
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMiembros()
    {
        return $this->miembros;
    }
}
