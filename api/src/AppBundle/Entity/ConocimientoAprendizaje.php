<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ConocimientoAprendizaje
 *
 * @ORM\Table(name="conocimientoAprendizaje")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ConocimientoAprendizajeRepository")
 */
class ConocimientoAprendizaje
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
     * @ORM\Column(name="conocimiento_id", type="integer")
     */
    private $conocimientoId;

    /**
     * @var int
     *
     * @ORM\Column(name="aprendizaje_id", type="integer")
     */
    private $aprendizajeId;


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
     * Set conocimientoId
     *
     * @param integer $conocimientoId
     *
     * @return ConocimientoAprendizaje
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

    /**
     * Set aprendizajeId
     *
     * @param integer $aprendizajeId
     *
     * @return ConocimientoAprendizaje
     */
    public function setAprendizajeId($aprendizajeId)
    {
        $this->aprendizajeId = $aprendizajeId;

        return $this;
    }

    /**
     * Get aprendizajeId
     *
     * @return int
     */
    public function getAprendizajeId()
    {
        return $this->aprendizajeId;
    }
}
