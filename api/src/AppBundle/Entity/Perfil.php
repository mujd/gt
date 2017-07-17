<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Perfil
 *
 * @ORM\Table(name="perfil")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PerfilRepository")
 */
class Perfil
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
     * @ORM\Column(name="objetivo", type="text")
     */
    private $objetivo;

    /**
     * @var string
     *
     * @ORM\Column(name="reporta", type="string", length=255)
     */
    private $reporta;

    /**
     * @var string
     *
     * @ORM\Column(name="tareas", type="string", length=255)
     */
    private $tareas;

    /**
     * @var int
     *
     * @ORM\Column(name="evaluacionTipo_id", type="integer")
     */
    private $evaluacionTipoId;

    /**
     * Set evaluacionTipoId
     *
     * @param integer $evaluacionTipoId
     *
     * @return Perfil
     */
    public function setEvaluacionTipoId($evaluacionTipoId)
    {
        $this->evaluacionTipoId = $evaluacionTipoId;

        return $this;
    }

    /**
     * Get evaluacionTipoId
     *
     * @return int
     */
    public function getEvaluacionTipoId()
    {
        return $this->evaluacionTipoId;
    }

    /**
     * @var int
     *
     * @ORM\Column(name="perfilFamilia_id", type="integer")
     */
    private $perfilFamilia;

    /**
     * Set perfilFamilia
     *
     * @param integer $perfilFamilia
     *
     * @return Perfil
     */
    public function setPerfilFamiliaId($perfilFamilia)
    {
        $this->perfilFamilia = $perfilFamilia;

        return $this;
    }

    /**
     * Get perfilFamilia
     *
     * @return int
     */
    public function getPerfilFamiliaId()
    {
        return $this->perfilFamilia;
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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Perfil
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
     * Set objetivo
     *
     * @param string $objetivo
     *
     * @return Perfil
     */
    public function setObjetivo($objetivo)
    {
        $this->objetivo = $objetivo;

        return $this;
    }

    /**
     * Get objetivo
     *
     * @return string
     */
    public function getObjetivo()
    {
        return $this->objetivo;
    }

    /**
     * Set reporta
     *
     * @param string $reporta
     *
     * @return Perfil
     */
    public function setReporta($reporta)
    {
        $this->reporta = $reporta;

        return $this;
    }

    /**
     * Get reporta
     *
     * @return string
     */
    public function getReporta()
    {
        return $this->reporta;
    }

    /**
     * Set tareas
     *
     * @param string $tareas
     *
     * @return Perfil
     */
    public function setTareas($tareas)
    {
        $this->tareas = $tareas;

        return $this;
    }

    /**
     * Get tareas
     *
     * @return string
     */
    public function getTareas()
    {
        return $this->tareas;
    }
}
