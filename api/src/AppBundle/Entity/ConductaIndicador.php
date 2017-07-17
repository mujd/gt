<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ConductaIndicador
 *
 * @ORM\Table(name="conductaIndicador")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ConductaIndicadorRepository")
 */
class ConductaIndicador
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
     * @ORM\Column(name="conducta_id", type="integer")
     */
    private $conductaId;

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
     * Set conductaId
     *
     * @param integer $conductaId
     *
     * @return ConductaIndicador
     */
    public function setConductaId($conductaId)
    {
        $this->conductaId = $conductaId;

        return $this;
    }

    /**
     * Get conductaId
     *
     * @return int
     */
    public function getConductaId()
    {
        return $this->conductaId;
    }

    /**
     * Set indicadorId
     *
     * @param integer $indicadorId
     *
     * @return ConductaIndicador
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
