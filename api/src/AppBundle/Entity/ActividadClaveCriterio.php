<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ActividadClaveCriterio
 *
 * @ORM\Table(name="actividadClaveCriterio")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ActividadClaveCriterioRepository")
 */
class ActividadClaveCriterio
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
     * @ORM\Column(name="criterio_id", type="integer")
     */
    private $criterioId;


    /**
     * @var int
     *
     * @ORM\Column(name="actividadClave_id", type="integer")
     */
    private $actividadClaveId;



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
     * Set criterioId
     *
     * @param integer $criterioId
     *
     * @return ActividadClaveCriterio
     */
    public function setCriterioId($criterioId)
    {
        $this->criterioId = $criterioId;

        return $this;
    }

    /**
     * Get criterioId
     *
     * @return int
     */
    public function getCriterioId()
    {
        return $this->criterioId;
    }

    /**
     * Set actividadClaveId
     *
     * @param integer $actividadClaveId
     *
     * @return ActividadClaveCriterio
     */
    public function setActividadClaveId($actividadClaveId)
    {
        $this->actividadClaveId = $actividadClaveId;

        return $this;
    }

    /**
     * Get actividadClaveId
     *
     * @return int
     */
    public function getActividadClaveId()
    {
        return $this->actividadClaveId;
    }

    public function __toString() {
        return $this->getNombre();
    }
}
