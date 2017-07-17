<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CursoIndicador
 *
 * @ORM\Table(name="cursoIndicador")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CursoIndicadorRepository")
 */
class CursoIndicador
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
     * @ORM\Column(name="indicador_id", type="integer")
     */
    private $indicadorId;


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
     * @return CursoIndicador
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
     * Set indicadorId
     *
     * @param integer $indicadorId
     *
     * @return CursoIndicador
     */
    public function setIndicadorId($indicadorId)
    {
        $this->indicadorId = $indicadorId;

        return $this;
    }

    /**
     * Get indicadorId
     *
     * @return int
     */
    public function getIndicadorId()
    {
        return $this->indicadorId;
    }
}
