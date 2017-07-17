<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CursoActividadClave
 *
 * @ORM\Table(name="cursoActividadClave")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CursoActividadClaveRepository")
 */
class CursoActividadClave
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
     * @ORM\Column(name="curso_id", type="integer")
     */
    private $cursoId;

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
     * Set cursoId
     *
     * @param integer $cursoId
     *
     * @return CursoActividadClave
     */
    public function setCursoId($cursoId)
    {
        $this->cursoId = $cursoId;

        return $this;
    }

    /**
     * Get cursoId
     *
     * @return int
     */
    public function getCursoId()
    {
        return $this->cursoId;
    }

    /**
     * Set actividadClaveId
     *
     * @param integer $actividadClaveId
     *
     * @return CursoActividadClave
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
}
