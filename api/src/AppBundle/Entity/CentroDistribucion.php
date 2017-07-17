<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CentroDistribucion
 *
 * @ORM\Table(name="centroDistribucion")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CentroDistribucionRepository")
 */
class CentroDistribucion
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="direccion", type="string", length=255)
     */
    private $direccion;

    /**
     * @var int
     *
     * @ORM\Column(name="comuna_id", type="integer")
     */
    private $comunaId;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return CentroDistribucion
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
     * Set direccion
     *
     * @param string $direccion
     *
     * @return CentroDistribucion
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
     * Set comunaId
     *
     * @param integer $comunaId
     *
     * @return CentroDistribucion
     */
    public function setComunaId($comunaId)
    {
        $this->comunaId = $comunaId;

        return $this;
    }

    /**
     * Get comunaId
     *
     * @return int
     */
    public function getComunaId()
    {
        return $this->comunaId;
    }
}
