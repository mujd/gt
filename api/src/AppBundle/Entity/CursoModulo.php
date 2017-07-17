<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CursoModulo
 *
 * @ORM\Table(name="cursoModulo")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CursoModuloRepository")
 */
class CursoModulo
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
     * @ORM\Column(name="modulo_id", type="integer")
     */
    private $moduloId;


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
     * @return CursoModulo
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
     * Set moduloId
     *
     * @param integer $moduloId
     *
     * @return CursoModulo
     */
    public function setModuloId($moduloId)
    {
        $this->moduloId = $moduloId;

        return $this;
    }

    /**
     * Get moduloId
     *
     * @return int
     */
    public function getModuloId()
    {
        return $this->moduloId;
    }
}
