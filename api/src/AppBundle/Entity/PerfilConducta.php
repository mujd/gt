<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PerfilConducta
 *
 * @ORM\Table(name="perfilConducta")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PerfilConductaRepository")
 */
class PerfilConducta
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
     * @ORM\Column(name="conducta_id", type="integer")
     */
    private $conductaId;

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
     * @return PerfilConducta
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
     * Set conductaId
     *
     * @param integer $conductaId
     *
     * @return PerfilConducta
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
}
