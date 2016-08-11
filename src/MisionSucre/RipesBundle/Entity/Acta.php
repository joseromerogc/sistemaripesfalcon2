<?php

namespace MisionSucre\RipesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Aldea
 *
 * @ORM\Table(name="acta")
 * @ORM\Entity(repositoryClass="MisionSucre\RipesBundle\Entity\ActaRepository")
 */
class Acta
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
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=70)
     */
    
    private $nombre;
    
    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255)
     */
    
    private $path;
    
    public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path
            ? null
            : $this->getUploadDir().'/'.$this->path;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/actas';
    }
    
}
