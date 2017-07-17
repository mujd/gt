<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PersonaEmpresa
 *
 * @ORM\Table(name="personaEmpresa")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PersonaEmpresaRepository")
 */
class PersonaEmpresa
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
     * @ORM\Column(name="empresa_id", type="integer")
     */
    private $empresaId;

    /**
     * @var int
     *
     * @ORM\Column(name="persona_id", type="integer")
     */
    private $personaId;


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
     * Set empresaId
     *
     * @param integer $empresaId
     *
     * @return PersonaEmpresa
     */
    public function setEmpresaId($empresaId)
    {
        $this->empresaId = $empresaId;

        return $this;
    }

    /**
     * Get empresaId
     *
     * @return int
     */
    public function getEmpresaId()
    {
        return $this->empresaId;
    }

    /**
     * Set personaId
     *
     * @param integer $personaId
     *
     * @return PersonaEmpresa
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
}
