<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PerfilConocimiento
 *
 * @ORM\Table(name="perfilConocimiento")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PerfilConocimientoRepository")
 */
class PerfilConocimiento
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
     * @var int
     *
     * @ORM\Column(name="perfil_id", type="integer")
     */
    private $perfilId;

    /**
     * @var int
     *
     * @ORM\Column(name="conocimiento_id", type="integer")
     */
    private $conocimientoId;

    /**
     * @var int
     *
     * @ORM\Column(name="nivel", type="integer")
     */
    private $nivel;

    /**
     * Set nivel
     *
     * @param integer $nivel
     *
     * @return PerfilUcl
     */
    public function setNivel($nivel)
    {
        $this->nivel = $nivel;

        return $this;
    }

    /**
     * Get nivel
     *
     * @return int
     */
    public function getNivel()
    {
        return $this->nivel;
    }


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
     * Set perfilId
     *
     * @param integer $perfilId
     *
     * @return PerfilConocimiento
     */
    public function setPerfilId($perfilId)
    {
        $this->perfilId = $perfilId;

        return $this;
    }

    /**
     * Get perfilId
     *
     * @return int
     */
    public function getPerfilId()
    {
        return $this->perfilId;
    }

    /**
     * Set conocimientoId
     *
     * @param integer $conocimientoId
     *
     * @return PerfilConocimiento
     */
    public function setConocimientoId($conocimientoId)
    {
        $this->conocimientoId = $conocimientoId;

        return $this;
    }

    /**
     * Get conocimientoId
     *
     * @return int
     */
    public function getConocimientoId()
    {
        return $this->conocimientoId;
    }
}
