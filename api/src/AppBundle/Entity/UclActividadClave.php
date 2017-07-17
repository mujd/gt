<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UclActividadClave
 *
 * @ORM\Table(name="uclActividadClave")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UclActividadClaveRepository")
 */
class UclActividadClave
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
     * @ORM\Column(name="ucl_id", type="integer")
     */
    private $uclId;

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
     * Set uclId
     *
     * @param integer $uclId
     *
     * @return UclActividadClave
     */
    public function setUclId($uclId)
    {
        $this->uclId = $uclId;

        return $this;
    }

    /**
     * Get uclId
     *
     * @return int
     */
    public function getUclId()
    {
        return $this->uclId;
    }

    /**
     * Set actividadClaveId
     *
     * @param integer $actividadClaveId
     *
     * @return UclActividadClave
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
