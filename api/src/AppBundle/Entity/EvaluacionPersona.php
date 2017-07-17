<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EvaluacionPersona
 *
 * @ORM\Table(name="evaluacionPersona")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EvaluacionPersonaRepository")
 */
class EvaluacionPersona
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
     * @ORM\Column(name="persona_id", type="integer")
     */
    private $personaId;

    /**
     * @var int
     *
     * @ORM\Column(name="evaluacion_id", type="integer")
     */
    private $evaluacionId;


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
     * Set personaId
     *
     * @param integer $personaId
     *
     * @return EvaluacionPersona
     */
    public function setPersonaId($personaId)
    {
        $this->personaId = $personaId;

        return $this;
    }

    /**
     * Get personaId
     *
     * @return int
     */
    public function getPersonaId()
    {
        return $this->personaId;
    }

    /**
     * Set evaluacionId
     *
     * @param integer $evaluacionId
     *
     * @return EvaluacionPersona
     */
    public function setEvaluacionId($evaluacionId)
    {
        $this->evaluacionId = $evaluacionId;

        return $this;
    }

    /**
     * Get evaluacionId
     *
     * @return int
     */
    public function getEvaluacionId()
    {
        return $this->evaluacionId;
    }
}
